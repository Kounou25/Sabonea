# Mettre Sabonea en ligne avec Docker (serveur Ubuntu)

Le site tourne dans trois conteneurs, décrits dans `docker-compose.yml` :

| Conteneur | Rôle |
| --- | --- |
| `app` | PHP 8.4 (PHP-FPM) avec le code du site. Au démarrage : migrations de la base, puis mise en cache de la configuration. |
| `web` | Caddy, le serveur web : fichiers statiques, transmission des pages à `app`, et **HTTPS automatique** dès qu'un domaine est configuré. |
| `db` | PostgreSQL 16, accessible uniquement par `app` (jamais depuis Internet). |

Les données sont dans des **volumes Docker**, conservés quand on met le code à jour :
`db-data` (la base), `storage` (documents des fournisseurs, images du site, journaux), `caddy-data` (certificats HTTPS).

> ⚠️ Ne jamais lancer `docker compose down -v` : l'option `-v` supprime les volumes, donc la base et les documents.

---

## 1. Préparer le serveur (une seule fois)

Ubuntu 22.04 ou 24.04, 2 Go de mémoire au minimum (4 Go conseillés), ports **80** et **443** ouverts.

```bash
sudo apt update && sudo apt upgrade -y
curl -fsSL https://get.docker.com | sudo sh     # Docker et « docker compose »
sudo usermod -aG docker $USER                   # puis se déconnecter / reconnecter
```

Si le pare-feu `ufw` est activé :

```bash
sudo ufw allow OpenSSH && sudo ufw allow 80/tcp && sudo ufw allow 443
```

## 2. Récupérer le code

```bash
sudo mkdir -p /opt/sabonea && sudo chown $USER /opt/sabonea
git clone https://github.com/Kounou25/Sabonea.git /opt/sabonea
cd /opt/sabonea
```

Le dépôt étant privé, GitHub demande un identifiant : utilisez un *personal access token* (GitHub › Settings › Developer settings) en guise de mot de passe.

## 3. Configurer le fichier `.env`

**À faire avant toute commande `docker compose`** (sinon Docker crée un dossier `.env` à la place du fichier).

```bash
cp .env.production.example .env
nano .env
```

À remplir :

- `APP_URL` : `http://ADRESSE_IP_DU_SERVEUR` tant qu'il n'y a pas de domaine.
- `DB_PASSWORD` : un mot de passe aléatoire, par exemple le résultat de `openssl rand -hex 24`.
- `MAIL_PASSWORD` : le mot de passe de la boîte `contact@sabonea.com`.
- `APP_KEY` : une fois `DB_PASSWORD` rempli et le fichier enregistré, générez-la, puis collez la valeur affichée après `APP_KEY=` :

  ```bash
  docker compose build app
  docker compose run --rm --no-deps app php artisan key:generate --show
  ```

N'utilisez pas le caractère `$` dans les valeurs du `.env` (Docker l'interprète).

## 4. Premier lancement

### Avec vos données (recommandé)

**Sur votre PC**, dans le dossier du projet, exportez la base et les fichiers :

```powershell
powershell -ExecutionPolicy Bypass -File docker\exporter-donnees-locales.ps1
scp "$HOME\Desktop\sabonea-export\*" utilisateur@ADRESSE_IP_DU_SERVEUR:/opt/sabonea/
```

**Sur le serveur** :

```bash
cd /opt/sabonea

# 1. La base seule, puis l'import de vos données
docker compose up -d --wait db
docker compose exec -T db sh -c 'psql -v ON_ERROR_STOP=1 -U "$POSTGRES_USER" -d "$POSTGRES_DB"' < sabonea-base.sql

# 2. Tout le site
docker compose up -d --build

# 3. Les fichiers (images du site, documents des fournisseurs)
docker compose cp sabonea-fichiers.tar.gz app:/tmp/
docker compose exec app sh -c 'tar -xzf /tmp/sabonea-fichiers.tar.gz -C storage/app && chown -R www-data:www-data storage && rm /tmp/sabonea-fichiers.tar.gz'

# 4. Ces fichiers contiennent des données personnelles : on les supprime
rm sabonea-base.sql sabonea-fichiers.tar.gz
```

Vos comptes du back-office sont repris : connectez-vous avec les mêmes identifiants qu'en local.

### Ou : installation neuve (sans vos données)

```bash
docker compose up -d --build
docker compose exec app php artisan db:seed --force
```

La dernière commande crée les pages, textes et listes de départ, ainsi que le compte administrateur `ADMIN_EMAIL` du `.env` (le mot de passe s'affiche s'il n'est pas renseigné dans `ADMIN_PASSWORD`).

### Vérifier

- `http://ADRESSE_IP_DU_SERVEUR/fr` : le site ;
- `http://ADRESSE_IP_DU_SERVEUR/admin` : le back-office ;
- envoyez un message depuis la page Contact et vérifiez sa réception.

## 5. Activer le HTTPS (quand le domaine est prêt)

1. Chez le registraire du domaine, créez deux enregistrements DNS de type **A** : `sabonea.com` et `www.sabonea.com` → adresse IP du serveur.
2. Dans `.env` :

   ```dotenv
   SITE_ADDRESS="sabonea.com, www.sabonea.com"
   APP_URL=https://sabonea.com
   SESSION_SECURE_COOKIE=true
   ```

3. Relancez :

   ```bash
   docker compose up -d --force-recreate
   ```

Caddy obtient alors les certificats tout seul (Let's Encrypt), les renouvelle, et redirige HTTP vers HTTPS.

## 6. Mettre à jour le site

```bash
cd /opt/sabonea
git pull
docker compose up -d --build
```

Les migrations de la base s'appliquent automatiquement au démarrage ; les données et documents sont conservés.

Après une modification du `.env` seul : `docker compose up -d --force-recreate`.

## 7. Sauvegardes

```bash
cd /opt/sabonea
# La base
docker compose exec -T db sh -c 'pg_dump -U "$POSTGRES_USER" "$POSTGRES_DB"' | gzip > sauvegarde-base-$(date +%F).sql.gz
# Les fichiers (documents des fournisseurs, images du site)
docker compose run --rm --no-deps -v "$PWD":/sauvegarde app tar -czf /sauvegarde/sauvegarde-fichiers-$(date +%F).tar.gz -C /var/www/html/storage app
```

À copier régulièrement hors du serveur (ou à programmer avec `crontab -e`).

## 8. Commandes utiles

| Besoin | Commande |
| --- | --- |
| État des conteneurs | `docker compose ps` |
| Journaux en direct | `docker compose logs -f app web` |
| Journal de Laravel | `docker compose exec app sh -c 'tail -n 100 storage/logs/laravel-*.log'` |
| Commande Laravel | `docker compose exec app php artisan …` |
| Redémarrer | `docker compose restart` |
| Arrêter (données conservées) | `docker compose down` |

## Vérification automatique sur GitHub

À chaque `git push`, GitHub (onglet **Actions**, fichier `.github/workflows/docker.yml`) construit les images, lance les tests dans l'image PHP du serveur, puis démarre le site complet et vérifie les pages principales. Une coche verte indique que la version Docker est prête à être déployée.
