# ---- Stage 1 : build des assets front (Vite / Tailwind) ----
FROM node:20-alpine AS assets
WORKDIR /app
COPY package.json package-lock.json* ./
RUN npm install
COPY . .
RUN npm run build

# ---- Stage 2 : image PHP + Apache pour servir Laravel ----
FROM php:8.2-apache

# Extensions PHP nécessaires à Laravel (+ pgsql pour la base de données Render)
RUN apt-get update && apt-get install -y \
    git unzip libzip-dev libpq-dev libonig-dev \
    && docker-php-ext-install pdo pdo_pgsql pgsql zip mbstring \
    && a2enmod rewrite

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# On copie d'abord composer.json pour profiter du cache Docker
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-scripts --no-interaction

# Reste du code
COPY . .

# Assets compilés par le stage précédent
COPY --from=assets /app/public/build ./public/build

RUN composer dump-autoload --optimize

# Apache doit servir le dossier public/ de Laravel
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf \
    && sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Autoriser .htaccess (nécessaire pour les routes Laravel)
RUN printf '<Directory /var/www/html/public>\n\tAllowOverride All\n</Directory>\n' > /etc/apache2/conf-available/laravel.conf \
    && a2enconf laravel

# Permissions pour que Laravel puisse écrire dans storage/ et bootstrap/cache/
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

COPY docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN sed -i 's/\r$//' /usr/local/bin/docker-entrypoint.sh \
    && chmod +x /usr/local/bin/docker-entrypoint.sh

EXPOSE 80
ENTRYPOINT ["docker-entrypoint.sh"]