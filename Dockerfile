############################################
# STAGE 1 — Build Frontend (Vite + Tailwind)
############################################
FROM node:20 AS node_builder

WORKDIR /app

COPY package.json package-lock.json* ./
RUN npm install

COPY resources ./resources
COPY vite.config.js ./

RUN npm run build


############################################
# STAGE 2 — Build Backend (Laravel + Apache)
############################################
FROM php:8.2-apache

RUN apt-get update && apt-get install -y \
    git unzip libpng-dev libzip-dev zip

RUN docker-php-ext-install pdo pdo_mysql zip

RUN a2enmod rewrite

WORKDIR /var/www/html

COPY . .

COPY --from=node_builder /app/public/build ./public/build

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Create required Laravel directories
RUN mkdir -p storage/framework/cache/data \
    storage/framework/sessions \
    storage/framework/views \
    storage/logs \
    bootstrap/cache

# Temp env for build
RUN cp .env.example .env

RUN composer install --no-dev --optimize-autoloader

# Set proper permissions
RUN chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

RUN sed -i 's!/var/www/html!/var/www/html/public!g' \
    /etc/apache2/sites-available/000-default.conf

EXPOSE 80

CMD ["apache2-foreground"]