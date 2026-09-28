# syntax=docker/dockerfile:1
#
# Sabonea: production images, built on the server by docker-compose.yml (see DEPLOIEMENT.md).
#   - app: PHP-FPM with the code and its dependencies;
#   - web: Caddy, which serves the static files, passes PHP requests to "app" and handles HTTPS;
#   - test: the test suite, run by the GitHub check (.github/workflows/docker.yml).

# ---------------------------------------------------------------------------- PHP and the extensions of the site
FROM php:8.4-fpm-bookworm AS base

COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/

# pgsql: database; gd: images of the PDF profile; intl: numbers and dates; zip: Excel exports.
RUN install-php-extensions pdo_pgsql pgsql gd intl zip bcmath opcache

COPY --from=composer:2 /usr/bin/composer /usr/local/bin/composer
COPY docker/php/php.ini /usr/local/etc/php/conf.d/zz-sabonea.ini
COPY docker/php/www.conf /usr/local/etc/php-fpm.d/zz-sabonea.conf

WORKDIR /var/www/html

# ---------------------------------------------------------------------------- Code and production dependencies
FROM base AS build

COPY composer.json composer.lock ./
RUN composer install --no-dev --no-interaction --prefer-dist --no-progress --no-scripts --no-autoloader

COPY . .

# Storage folders first (excluded from the build context): Laravel needs them to boot.
# Filament's scripts and styles are not in Git: they are published here.
RUN mkdir -p storage/app/public storage/app/private storage/fonts storage/logs \
        storage/framework/cache/data storage/framework/sessions storage/framework/views bootstrap/cache \
    && composer dump-autoload --optimize --no-dev --no-scripts \
    && php artisan package:discover --ansi \
    && php artisan filament:assets \
    && rm -rf tests docker .github \
    && chown -R www-data:www-data storage bootstrap/cache

# ---------------------------------------------------------------------------- Test suite (GitHub check)
FROM base AS test

COPY composer.json composer.lock ./
RUN composer install --no-interaction --prefer-dist --no-progress --no-scripts --no-autoloader

COPY . .

RUN mkdir -p storage/app/public storage/app/private storage/fonts storage/logs \
        storage/framework/cache/data storage/framework/sessions storage/framework/views bootstrap/cache \
    && composer dump-autoload --no-scripts \
    && php artisan package:discover --ansi \
    && cp .env.example .env \
    && php artisan key:generate --force

CMD ["php", "vendor/bin/phpunit"]

# ---------------------------------------------------------------------------- Application: PHP-FPM
FROM base AS app

COPY --from=build /var/www/html /var/www/html
COPY docker/entrypoint.sh /usr/local/bin/sabonea-entrypoint
RUN sed -i 's/\r$//' /usr/local/bin/sabonea-entrypoint && chmod +x /usr/local/bin/sabonea-entrypoint

ENTRYPOINT ["sabonea-entrypoint"]
CMD ["php-fpm"]

# ---------------------------------------------------------------------------- Web server: Caddy (HTTPS included)
FROM caddy:2-alpine AS web

COPY docker/caddy/Caddyfile /etc/caddy/Caddyfile
COPY --from=build /var/www/html/public /var/www/html/public
