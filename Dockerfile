# Utiliser l'image officielle PHP avec Apache
FROM php:8.2-apache

# Installer les dépendances requises pour Laravel
RUN docker-php-ext-install pdo pdo_mysql

# Installer Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Définir le répertoire de travail sur /var/www/html
WORKDIR /var/www/html

# Copier uniquement le contenu du répertoire public dans le répertoire racine de Apache
COPY ./public /var/www/html

# Copier les autres fichiers Laravel (sauf le répertoire public)
COPY . /var/www/laravel

# Installer les dépendances PHP
RUN composer install --no-dev --optimize-autoloader --working-dir=/var/www/laravel

# Configurer Apache pour pointer vers le répertoire Laravel
RUN echo "DocumentRoot /var/www/laravel/public" >> /etc/apache2/sites-available/000-default.conf

# Activer les modules Apache nécessaires (Rewrite)
RUN a2enmod rewrite

# Exposer le port 80
EXPOSE 80
