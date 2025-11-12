FROM php:8.1-apache

# Instala extensiones necesarias y utilidades
RUN apt-get update && apt-get install -y \
    zip unzip git libzip-dev libonig-dev libpng-dev libjpeg-dev libicu-dev libxml2-dev \
    && docker-php-ext-install mysqli pdo pdo_mysql mbstring exif intl gd zip \
    && a2enmod rewrite

# Instala composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Configura DocumentRoot si quieres exponer / (opcional)
# WORKDIR /var/www/html

# Copiar composer.json si existe y hacer install (opcional en build)
# COPY composer.json composer.lock /var/www/html/
# RUN composer install --no-dev --no-scripts --no-interaction --prefer-dist

# Permisos (ajusta si es necesario)
RUN chown -R www-data:www-data /var/www/html