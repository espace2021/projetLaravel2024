# Utiliser l'image officielle PHP avec Apache
FROM php:8.2-apache

# Installer les dépendances système requises
RUN apt-get update && apt-get install -y \
    libonig-dev \
    libzip-dev \
    libicu-dev \
    libxml2-dev \
    libpq-dev \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    libssl-dev \
    zip \
    unzip

# Installer les extensions PHP requises
RUN docker-php-ext-install pdo pdo_mysql mbstring tokenizer bcmath opcache

# Installer Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Définir le répertoire de travail
WORKDIR /var/www/html

# Copier les fichiers du projet Laravel
COPY . /var/www/laravel

# Installer les dépendances PHP (sans scripts pour alléger)
RUN composer install --no-dev --optimize-autoloader --no-scripts --working-dir=/var/www/laravel

# Configurer Apache pour pointer vers le répertoire public de Laravel
RUN echo "DocumentRoot /var/www/laravel/public" >> /etc/apache2/sites-available/000-default.conf

# Activer le module Rewrite pour gérer les routes Laravel
RUN a2enmod rewrite

# Exposer le port 80
EXPOSE 80

# Vérification des permissions
RUN chown -R www-data:www-data /var/www/laravel/storage /var/www/laravel/bootstrap/cache
