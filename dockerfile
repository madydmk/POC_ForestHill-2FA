# Utiliser l'image officielle PHP avec Apache
FROM php:8.2-apache

# Installer les extensions PHP nécessaires
RUN apt-get update && apt-get install -y \
    libicu-dev \
    libonig-dev \
    libzip-dev \
    unzip \
    && docker-php-ext-install intl mbstring opcache pdo pdo_mysql zip \
    && a2enmod rewrite

# Installer Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copier les fichiers de l'application dans le conteneur
COPY . /var/www/html

# Définir le répertoire de travail
WORKDIR /var/www/html

# Installer les dépendances de Symfony
RUN composer install --prefer-dist --no-scripts --no-dev --no-autoloader

# Configurer les permissions
RUN chown -R www-data:www-data /var/www/html/var
RUN chmod -R 775 /var/www/html/var

# Exposer le port 80
EXPOSE 80
