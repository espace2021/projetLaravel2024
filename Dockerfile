# Utiliser l'image officielle PHP avec Apache
FROM php:8.2-apache

# Installer les dépendances requises
RUN docker-php-ext-install pdo pdo_mysql

# Installer Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Définir le répertoire de travail
WORKDIR /var/www/html

# Copier le code de l'application
COPY . .

# Installer les dépendances npm
RUN apt-get update && apt-get install -y npm && npm install && npm run build

# Exposer le port 80
EXPOSE 80
