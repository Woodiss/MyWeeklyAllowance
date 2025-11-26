FROM php:8.2-apache

# Installation des extensions PHP nécessaires
RUN apt-get update && apt-get install -y \
    libzip-dev \
    zip \
    unzip \
    git \
    && docker-php-ext-install pdo pdo_mysql mysqli zip

# Installation de Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Installation de PHPUnit (pour TDD)
RUN curl -L https://phar.phpunit.de/phpunit-10.phar -o /usr/local/bin/phpunit \
    && chmod +x /usr/local/bin/phpunit

# Activation du module Apache rewrite
RUN a2enmod rewrite

# Configuration du répertoire de travail
WORKDIR /var/www/html

# Copie des fichiers du projet
COPY . /var/www/html

# Permissions
RUN chown -R www-data:www-data /var/www/html

EXPOSE 80
