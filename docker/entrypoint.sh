#!/bin/sh
# Start of the application container: storage folders, database migrations, caches, then PHP-FPM.
set -e

cd /var/www/html

# Folders of the persistent "storage" volume (they may be missing in a volume created by an older version).
mkdir -p storage/app/public storage/app/private storage/fonts storage/logs \
    storage/framework/cache/data storage/framework/sessions storage/framework/views bootstrap/cache

if [ "$1" = "php-fpm" ]; then
    if [ ! -f .env ]; then
        echo "Fichier .env introuvable : copiez .env.production.example en .env et complétez-le (voir DEPLOIEMENT.md)." >&2
        exit 1
    fi

    # The database may still be starting: a few attempts before giving up.
    attempt=1
    until php artisan migrate --force; do
        if [ "$attempt" -ge 20 ]; then
            echo "Base de données injoignable : vérifiez DB_HOST, DB_DATABASE, DB_USERNAME et DB_PASSWORD dans .env." >&2
            exit 1
        fi
        echo "Base de données pas encore prête, nouvel essai dans 3 s (essai $attempt)..."
        attempt=$((attempt + 1))
        sleep 3
    done

    # Interface strings added by a new version: only missing keys are created, back-office edits are kept.
    php artisan db:seed --class=UiTranslationSeeder --force

    # Configuration, routes, views and Filament components cached for speed (rebuilt at every start).
    php artisan optimize
    php artisan filament:optimize
fi

# Files written above belong to root: PHP-FPM runs as www-data.
chown -R www-data:www-data storage bootstrap/cache

exec "$@"
