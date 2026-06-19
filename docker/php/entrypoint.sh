#!/bin/sh
set -e

cd /var/www/html

mkdir -p storage/framework/cache/data \
         storage/framework/sessions \
         storage/framework/views \
         storage/logs \
         bootstrap/cache

if [ ! -f .env ]; then
    if [ -f .env.docker ]; then
        cp .env.docker .env
    elif [ -f .env.example ]; then
        cp .env.example .env
    fi
fi

if [ ! -f vendor/autoload.php ]; then
    composer install --no-interaction --prefer-dist
fi

chmod -R 777 storage bootstrap/cache || true

if [ -f .env ] && ! grep -q "^APP_KEY=base64:" .env; then
    php artisan key:generate --force || true
fi

php artisan config:clear || true
php artisan cache:clear || true
php artisan view:clear || true
php artisan storage:link || true

if [ "$RUN_MIGRATIONS" = "true" ]; then
    php artisan migrate --force || true
fi

if [ "$RUN_SEEDERS" = "true" ]; then
    php artisan db:seed --force || true
fi

chmod -R 777 storage bootstrap/cache || true
chown -R www-data:www-data storage bootstrap/cache || true

exec "$@"