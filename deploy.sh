#!/usr/bin/env bash
#
# Sabonea : installation et mises à jour sur un serveur Ubuntu avec Docker (voir DEPLOIEMENT.md).
#
#   bash deploy.sh                        installe le site, ou le redémarre avec la configuration actuelle
#   bash deploy.sh --update               récupère la dernière version du code (git pull), puis met le site à jour
#   bash deploy.sh --domain sabonea.com   passe le site sur sabonea.com et www.sabonea.com, en HTTPS
#   bash deploy.sh --import               remplace la base du serveur par sabonea-base.sql (et les fichiers)
#   bash deploy.sh --config-only          crée / complète seulement le fichier .env
#
# Le fichier .env est créé et complété automatiquement : clé de l'application, mot de passe de la base,
# adresse du serveur, mot de passe administrateur. Seul le mot de passe de la boîte mail est demandé
# (ou donné ainsi : MAIL_PASSWORD='...' bash deploy.sh). Les valeurs déjà remplies ne sont jamais changées.
#
# Première installation : si sabonea-base.sql (et sabonea-fichiers.tar.gz) sont à côté de ce script,
# vos données sont importées ; sinon le site démarre avec son contenu de départ.

set -euo pipefail

SCRIPT="$(cd "$(dirname "$0")" && pwd)/$(basename "$0")"
cd "$(dirname "$SCRIPT")"

DOMAIN=""
UPDATE=false
UPDATED=false
IMPORT=false
CONFIG_ONLY=false
ARGS=()

usage() { awk 'NR > 2 && /^#/ { sub(/^# ?/, ""); print; next } NR > 2 { exit }' "$SCRIPT"; }

while [ $# -gt 0 ]; do
    case "$1" in
        --domain)
            if [ $# -lt 2 ]; then echo "--domain : indiquez le domaine, par exemple --domain sabonea.com" >&2; exit 1; fi
            DOMAIN="$2"; ARGS+=("$1" "$2"); shift 2 ;;
        --domain=*) DOMAIN="${1#*=}"; ARGS+=("$1"); shift ;;
        --update) UPDATE=true; shift ;;
        --updated) UPDATED=true; shift ;;
        --import) IMPORT=true; ARGS+=("$1"); shift ;;
        --config-only) CONFIG_ONLY=true; ARGS+=("$1"); shift ;;
        -h | --help) usage; exit 0 ;;
        *) echo "Option inconnue : $1" >&2; echo >&2; usage >&2; exit 1 ;;
    esac
done

step() { printf '\n\033[1;35m==> %s\033[0m\n' "$*"; }
info() { printf '    %s\n' "$*"; }
warn() { printf '\033[1;33m    ! %s\033[0m\n' "$*"; }
fail() { printf '\033[1;31m    %s\033[0m\n' "$*" >&2; exit 1; }

SUDO=""
if [ "$(id -u)" -ne 0 ]; then SUDO="sudo"; fi

random_hex() { openssl rand -hex "$1" 2>/dev/null || head -c "$1" /dev/urandom | od -An -tx1 | tr -d ' \n'; }
random_base64() { openssl rand -base64 "$1" 2>/dev/null || head -c "$1" /dev/urandom | base64 | tr -d '\n'; }

if $UPDATE; then
    step "Récupération de la dernière version du code"
    git pull --ff-only
    # The rest is done by the new version of this script.
    exec bash "$SCRIPT" --updated ${ARGS[@]+"${ARGS[@]}"}
fi

# ------------------------------------------------------------------------------------------------ .env

env_get() {
    { grep -E "^$1=" .env 2>/dev/null || true; } | tail -n 1 | cut -d= -f2- | sed -e 's/^"\(.*\)"$/\1/' -e "s/^'\(.*\)'$/\1/"
}

env_set() {
    local key="$1" value="$2" tmp

    if grep -qE "^$key=" .env; then
        tmp="$(mktemp)"
        KEY="$key" VALUE="$value" awk 'index($0, ENVIRON["KEY"] "=") == 1 { print ENVIRON["KEY"] "=" ENVIRON["VALUE"]; next } { print }' .env > "$tmp"
        # Rewritten in place: the file mounted in the "app" container stays the same file.
        cat "$tmp" > .env
        rm -f "$tmp"
    else
        if [ -n "$(tail -c 1 .env)" ]; then echo >> .env; fi
        printf '%s=%s\n' "$key" "$value" >> .env
    fi
}

# Between quotes, read literally by Docker and by Laravel: a password may contain $, #, spaces or quotes.
env_quote() {
    local value="$1"

    case "$value" in
        *"'"*)
            value="${value//\\/\\\\}"
            value="${value//\"/\\\"}"
            value="${value//\$/\\\$}"
            printf '"%s"' "$value" ;;
        *)
            printf "'%s'" "$value" ;;
    esac
}

public_ip() {
    curl -fsS --max-time 5 https://api.ipify.org 2>/dev/null \
        || { hostname -I 2>/dev/null | awk '{ print $1 }'; } \
        || true
}

configure_env() {
    step "Configuration (.env)"

    local before=""

    if [ -f .env ] && { [ ! -r .env ] || [ ! -w .env ]; }; then
        fail ".env appartient à un autre compte (script lancé avec sudo ?) : « sudo chown $(id -un) .env », puis relancez sans sudo."
    elif [ -f .env ]; then
        before="$(md5sum .env | cut -d' ' -f1)"
    elif [ -e .env ]; then
        fail ".env existe mais n'est pas un fichier (dossier créé par Docker ?) : supprimez-le avec « rm -r .env » puis relancez."
    else
        cp .env.production.example .env
        info "Fichier .env créé à partir de .env.production.example"
    fi

    if [ -z "$(env_get APP_KEY)" ]; then
        env_set APP_KEY "base64:$(random_base64 32)"
        info "Clé de l'application générée"
    fi

    if [ -z "$(env_get DB_PASSWORD)" ]; then
        env_set DB_PASSWORD "$(random_hex 24)"
        info "Mot de passe de la base généré"
    fi

    if [ -z "$(env_get ADMIN_PASSWORD)" ]; then
        env_set ADMIN_PASSWORD "$(random_hex 8)"
        info "Mot de passe administrateur généré (utilisé seulement pour une installation neuve)"
    fi

    if [ -n "$DOMAIN" ]; then
        DOMAIN="$(printf '%s' "$DOMAIN" | tr '[:upper:]' '[:lower:]' | sed -e 's#^[a-z]*://##' -e 's#/.*$##' -e 's#^www\.##')"

        if ! printf '%s' "$DOMAIN" | grep -Eq '^[a-z0-9]([a-z0-9-]*[a-z0-9])?(\.[a-z0-9]([a-z0-9-]*[a-z0-9])?)+$'; then
            fail "Domaine invalide : « $DOMAIN » (exemple : --domain sabonea.com)"
        fi

        # sabonea.com is also served as www.sabonea.com; a subdomain (shop.sabonea.com) alone.
        local hosts="$DOMAIN"
        if [ "$(printf '%s' "$DOMAIN" | tr -cd '.' | wc -c)" -eq 1 ]; then hosts="$DOMAIN, www.$DOMAIN"; fi

        env_set SITE_ADDRESS "\"$hosts\""
        env_set APP_URL "https://$DOMAIN"
        env_set SESSION_SECURE_COOKIE true
        info "Adresse du site : https://$DOMAIN (certificat HTTPS automatique pour $hosts)"

        local ip resolved
        ip="$(public_ip)"
        resolved="$(getent ahostsv4 "$DOMAIN" 2>/dev/null | awk '{ print $1; exit }' || true)"
        if [ -n "$ip" ] && [ "$resolved" != "$ip" ]; then
            warn "$DOMAIN pointe vers « ${resolved:-aucune adresse} » et non vers ce serveur ($ip) :"
            warn "le certificat HTTPS sera obtenu dès que l'enregistrement DNS A sera à jour."
        fi
    elif [ -z "$(env_get APP_URL)" ] || env_get APP_URL | grep -q "ADRESSE_IP_DU_SERVEUR"; then
        local ip
        ip="$(public_ip)"
        env_set APP_URL "http://${ip:-localhost}"
        env_set SITE_ADDRESS ":80"
        info "Adresse du site : http://${ip:-localhost} (sans domaine pour l'instant)"
    fi

    if [ -z "$(env_get MAIL_PASSWORD)" ]; then
        local password="${MAIL_PASSWORD:-}"

        if [ -z "$password" ] && [ -t 0 ]; then
            read -rsp "    Mot de passe de la boîte $(env_get MAIL_USERNAME) (Entrée pour le donner plus tard) : " password
            echo
        fi

        if [ -n "$password" ]; then
            env_set MAIL_PASSWORD "$(env_quote "$password")"
            info "Mot de passe de la boîte mail enregistré"
        else
            warn "Pas de mot de passe mail : les e-mails ne partiront pas. Relancez « bash deploy.sh » pour le saisir."
        fi
    fi

    chmod 600 .env

    ENV_CHANGED=false
    if [ "$before" != "$(md5sum .env | cut -d' ' -f1)" ]; then ENV_CHANGED=true; fi
}

# ------------------------------------------------------------------------------------------------ Docker

install_docker() {
    step "Docker"

    if ! command -v docker >/dev/null 2>&1; then
        info "Installation de Docker (quelques minutes)..."
        curl -fsSL https://get.docker.com | $SUDO sh
    fi

    DOCKER="docker"
    if ! docker info >/dev/null 2>&1; then
        DOCKER="$SUDO docker"

        # For the commands of DEPLOIEMENT.md without sudo, after the next login.
        if [ -n "$SUDO" ] && ! id -nG | grep -qw docker; then
            $SUDO usermod -aG docker "$(id -un)"
            info "Compte $(id -un) ajouté au groupe docker (effectif à la prochaine connexion SSH)"
        fi
    fi

    $DOCKER compose version >/dev/null 2>&1 || fail "« docker compose » est introuvable : installez le paquet docker-compose-plugin."
    info "$($DOCKER --version)"

    if command -v ufw >/dev/null 2>&1 && $SUDO ufw status 2>/dev/null | grep -q "Status: active"; then
        $SUDO ufw allow 80/tcp >/dev/null
        $SUDO ufw allow 443 >/dev/null
        info "Pare-feu : ports 80 et 443 ouverts"
    fi
}

compose() { $DOCKER compose "$@"; }

# psql in the "db" container, over the network with the password of .env: the same connection as the site.
db_psql() {
    compose exec -T -e PGOPTIONS="-c client_min_messages=warning" db \
        sh -c 'PGPASSWORD="$POSTGRES_PASSWORD" psql -h 127.0.0.1 -v ON_ERROR_STOP=1 -U "$POSTGRES_USER" -d "$POSTGRES_DB" "$@"' psql "$@"
}

database_is_empty() {
    local output=""

    for _ in $(seq 1 20); do
        if output="$(db_psql -tAc "SELECT to_regclass('public.migrations') IS NULL" 2>&1)"; then
            [ "$output" = "t" ]
            return
        fi

        if printf '%s' "$output" | grep -q "password authentication failed"; then
            fail "DB_PASSWORD du .env ne correspond pas à la base existante (le .env a-t-il été recréé ?). Remettez l'ancien mot de passe dans .env."
        fi

        sleep 3
    done

    printf '%s\n' "$output" >&2
    fail "La base de données ne répond pas."
}

wait_for_app() {
    # PHP-FPM replaces the start script once the migrations and caches are done.
    for _ in $(seq 1 100); do
        if compose exec -T app sh -c 'grep -q php-fpm /proc/1/comm' 2>/dev/null; then
            return 0
        fi
        sleep 3
    done

    compose logs --tail 60 app >&2
    fail "Le site n'a pas démarré (messages ci-dessus)."
}

# ------------------------------------------------------------------------------------------------ Steps

configure_env

if $CONFIG_ONLY; then
    info "Fichier .env prêt."
    exit 0
fi

if $IMPORT && [ ! -f sabonea-base.sql ]; then
    fail "--import : sabonea-base.sql est introuvable dans $(pwd)."
fi

install_docker

step "Construction des images (quelques minutes la première fois)"
if $UPDATED; then
    compose pull --quiet db
    compose build --pull
else
    compose build
fi

step "Base de données"
compose up -d --wait db

FRESH=false
IMPORTED=false
SEEDED=false

if database_is_empty; then
    FRESH=true
else
    info "Base existante : conservée"
fi

if [ -f sabonea-base.sql ] && { $FRESH || $IMPORT; }; then
    if ! $FRESH; then
        info "Arrêt du site pendant le remplacement de la base..."
        compose stop app
    fi

    info "Import de vos données (sabonea-base.sql)..."
    db_psql -q < sabonea-base.sql > /dev/null
    IMPORTED=true
    info "Base importée"
fi

step "Démarrage du site"
if $ENV_CHANGED; then
    # The configuration is read when the containers start.
    compose up -d --remove-orphans --force-recreate app web
else
    compose up -d --remove-orphans
fi
wait_for_app
info "Site démarré"

if $FRESH && ! $IMPORTED; then
    step "Contenu de départ"
    compose exec -T -u www-data app php artisan db:seed --force
    SEEDED=true
fi

if $IMPORTED && [ -f sabonea-fichiers.tar.gz ]; then
    step "Import de vos fichiers (sabonea-fichiers.tar.gz)"
    compose cp sabonea-fichiers.tar.gz app:/tmp/sabonea-fichiers.tar.gz
    compose exec -T app sh -c 'tar -xzf /tmp/sabonea-fichiers.tar.gz -C storage/app && chown -R www-data:www-data storage && rm -f /tmp/sabonea-fichiers.tar.gz'
    info "Fichiers importés"
fi

URL="$(env_get APP_URL)"

step "Terminé"
info "Site        : $URL/fr"
info "Back-office : $URL/admin"

if $SEEDED; then
    info "Connexion   : $(env_get ADMIN_EMAIL) / $(env_get ADMIN_PASSWORD)  (changez ce mot de passe après la première connexion)"
fi

if $IMPORTED; then
    info "Connexion   : les mêmes identifiants que sur votre PC."
    warn "Supprimez les fichiers importés (données personnelles) : rm sabonea-base.sql sabonea-fichiers.tar.gz"
fi

if [ -z "$(env_get MAIL_PASSWORD)" ]; then
    warn "E-mails désactivés tant que le mot de passe mail manque : relancez « bash deploy.sh » pour le saisir."
fi
