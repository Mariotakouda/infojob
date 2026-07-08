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

exec apache2-foreground