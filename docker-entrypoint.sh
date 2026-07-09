#!/bin/bash
set -e

cd /var/www/html

if [ -z "$APP_KEY" ]; then
    php artisan key:generate --force
fi

php artisan config:cache
php artisan route:cache
php artisan view:cache

php artisan storage:link || true

php artisan migrate --force

if [ "$RUN_ADMIN_SEED" = "true" ]; then
    echo ">>> RUN_ADMIN_SEED=true : lancement du seeder admin"
    php artisan db:seed --class=AdministrationSeeder --force || true
fi

if [ -n "$NEW_ADMIN_EMAIL" ] && [ -n "$NEW_ADMIN_PASSWORD" ]; then
    echo ">>> NEW_ADMIN_EMAIL défini : création d'un admin réel"
    php artisan app:create-admin \
        --name="${NEW_ADMIN_NAME:-Administrateur}" \
        --email="$NEW_ADMIN_EMAIL" \
        --password="$NEW_ADMIN_PASSWORD" \
        --no-interaction || true
fi

exec apache2-foreground