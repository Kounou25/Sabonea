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

Tout se fait avec le script **`deploy.sh`** : il installe Docker si besoin, crée et remplit le fichier `.env`, construit et démarre le site, importe vos données. Il peut être relancé sans risque : il ne remplace jamais une valeur déjà remplie ni une base existante.

---

## 1. Récupérer le code sur le serveur

Ubuntu 22.04 ou 24.04, 2 Go de mémoire au minimum (4 Go conseillés).

Depuis votre PC (PowerShell), connectez-vous au serveur :

```powershell
ssh utilisateur@ADRESSE_IP_DU_SERVEUR
```

Puis, **sur le serveur** :

```bash
sudo apt update && sudo apt install -y git
sudo mkdir -p /opt/sabonea && sudo chown $USER /opt/sabonea
git clone https://github.com/Kounou25/Sabonea.git /opt/sabonea
```

Le dépôt étant privé, GitHub demande un identifiant : votre nom d'utilisateur GitHub, et comme mot de passe un *personal access token* (GitHub › Settings › Developer settings › Personal access tokens, droit « Contents : read » sur le dépôt). Pour ne pas le retaper à chaque mise à jour : `git -C /opt/sabonea config credential.helper store` (le jeton est alors conservé sur le serveur).

## 2. Copier vos données sur le serveur (recommandé)

À faire **avant** l'étape 3 pour retrouver en ligne vos pages, textes, comptes, candidatures et documents.

**Sur votre PC**, dans le dossier du projet (PowerShell) :

```powershell
powershell -ExecutionPolicy Bypass -File docker\exporter-donnees-locales.ps1
scp "$HOME\Desktop\sabonea-export\sabonea-base.sql" "$HOME\Desktop\sabonea-export\sabonea-fichiers.tar.gz" utilisateur@ADRESSE_IP_DU_SERVEUR:/opt/sabonea/
```

Sans ces fichiers, le site démarre avec son contenu de départ et un compte administrateur neuf.

## 3. Installer et démarrer le site

**Sur le serveur** :

```bash
cd /opt/sabonea
bash deploy.sh
```

Le script :

1. crée le fichier `.env` et le remplit tout seul : clé de l'application, mot de passe de la base, adresse du site (l'IP du serveur, détectée), mot de passe administrateur ;
2. **demande le mot de passe de la boîte `contact@sabonea.com`** (saisie masquée ; Entrée pour le donner plus tard) ;
3. installe Docker s'il est absent et ouvre les ports 80 et 443 du pare-feu `ufw` s'il est actif ;
4. construit les images (5 à 10 minutes la première fois) ;
5. importe `sabonea-base.sql` et `sabonea-fichiers.tar.gz` s'ils sont présents, sinon installe le contenu de départ ;
6. démarre le site et affiche son adresse et les identifiants de connexion.

Ensuite :

- supprimez les fichiers importés, qui contiennent des données personnelles : `rm sabonea-base.sql sabonea-fichiers.tar.gz` ;
- ouvrez `http://ADRESSE_IP_DU_SERVEUR/fr` (le site) et `/admin` (le back-office) ;
- envoyez un message depuis la page Contact et vérifiez sa réception.

Données copiées après coup ? `bash deploy.sh --import` remplace la base du serveur par `sabonea-base.sql` (et importe les fichiers).

## 4. Activer le HTTPS (quand le domaine est prêt)

1. Chez le registraire du domaine, créez deux enregistrements DNS de type **A** : `sabonea.com` et `www.sabonea.com` → adresse IP du serveur.
2. Sur le serveur :

   ```bash
   cd /opt/sabonea
   bash deploy.sh --domain sabonea.com
   ```

Le script met à jour le `.env` (adresse `https://sabonea.com`, cookies sécurisés) et redémarre le site. Caddy obtient alors les certificats tout seul (Let's Encrypt), les renouvelle, et redirige HTTP vers HTTPS. Le script prévient si le domaine ne pointe pas encore vers le serveur.

## 5. Mettre à jour le site

Après avoir poussé les modifications sur GitHub depuis votre PC :

```bash
cd /opt/sabonea
bash deploy.sh --update
```

Le code est récupéré (`git pull`), les images reconstruites et le site redémarré ; les migrations de la base s'appliquent automatiquement, et les nouveaux textes d'interface sont ajoutés (ceux modifiés dans le back-office ne sont jamais écrasés). Les données et documents sont conservés.

**Changer un réglage** : modifiez `.env` (`nano .env`), puis `bash deploy.sh`.
Pour changer le mot de passe mail, videz la ligne (`MAIL_PASSWORD=`) et relancez `bash deploy.sh` : il le redemande.

## 6. Le fichier `.env`

| Valeur | Remplie par |
| --- | --- |
| `APP_KEY` | `deploy.sh`, une fois pour toutes. |
| `DB_PASSWORD` | `deploy.sh`, une fois pour toutes : **ne plus la changer**, la base a été créée avec. |
| `APP_URL`, `SITE_ADDRESS` | `deploy.sh` : IP du serveur, puis domaine avec `--domain`. |
| `MAIL_PASSWORD` | Vous, à la demande de `deploy.sh`. |
| `ADMIN_PASSWORD` | `deploy.sh` ; ne sert que pour une installation neuve (sans vos données). |

Gardez une copie du `.env` hors du serveur (dans un gestionnaire de mots de passe par exemple) : sans `APP_KEY` et `DB_PASSWORD`, une sauvegarde ne peut pas être restaurée telle quelle.

## 7. Sauvegardes

```bash
cd /opt/sabonea
# La base
docker compose exec -T db sh -c 'pg_dump -U "$POSTGRES_USER" "$POSTGRES_DB"' | gzip > sauvegarde-base-$(date +%F).sql.gz
# Les fichiers (documents des fournisseurs, images du site)
docker compose run --rm --no-deps -v "$PWD":/sauvegarde app tar -czf /sauvegarde/sauvegarde-fichiers-$(date +%F).tar.gz -C /var/www/html/storage app
```

À copier régulièrement hors du serveur (ou à programmer avec `crontab -e`). Ces fichiers ne vont ni dans Git ni dans les images Docker.

## 8. Commandes utiles

Depuis `/opt/sabonea`. Si Docker vient d'être installé, reconnectez-vous en SSH pour utiliser `docker` sans `sudo`.

| Besoin | Commande |
| --- | --- |
| Aide du script | `bash deploy.sh --help` |
| État des conteneurs | `docker compose ps` |
| Journaux en direct | `docker compose logs -f app web` |
| Journal de Laravel | `docker compose exec app sh -c 'tail -n 100 storage/logs/laravel-*.log'` |
| Commande Laravel | `docker compose exec -u www-data app php artisan …` |
| Redémarrer | `docker compose restart` |
| Arrêter (données conservées) | `docker compose down` |

## Vérification automatique sur GitHub

À chaque `git push`, GitHub (onglet **Actions**, fichier `.github/workflows/docker.yml`) lance les tests dans l'image PHP du serveur, puis déploie le site complet avec `deploy.sh` comme sur le serveur, vérifie les pages principales, et relance `deploy.sh` pour s'assurer que les données sont conservées. Une coche verte indique que la version Docker est prête à être déployée.
