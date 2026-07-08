#!/bin/bash
set -e

cd /var/www/html

# Genere APP_KEY si absente (normalement definie sur Render, securite en plus)
if [ -z "$APP_KEY" ]; then
    php artisan key:generate --force
fi

# Cache la config maintenant que les variables d'env de Render sont disponibles
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Lien symbolique storage -> public/storage (utile pour les fichiers uploades)
php artisan storage:link || true

# Applique les migrations a chaque deploiement
php artisan migrate --force

# Demarre Apache au premier plan (process principal du conteneur)
exec apache2-foreground