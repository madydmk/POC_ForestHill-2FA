# Utiliser une image de base officielle PHP avec Apache
FROM php:8.2-apache

# Installer les dépendances nécessaires
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libicu-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    && docker-php-ext-install \
    pdo \
    pdo_mysql \
    intl \
    mbstring \
    xml \
    opcache

# Installer Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Configurer Apache
RUN a2enmod rewrite

# Copier les fichiers du projet
COPY . /var/www/html

# Installer les dépendances du projet
RUN composer install --prefer-dist --no-scripts --no-dev --no-autoloader && composer dump-autoload --optimize

# Configurer les permissions
RUN chown -R www-data:www-data /var/www/html/var
RUN chmod -R 775 /var/www/html/var

# Exposer le port 80
EXPOSE 80

# Démarrer Apache
CMD ["apache2-foreground"]
