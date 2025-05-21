# Utiliser une image officielle PHP comme base
FROM php:8.2-apache

# Installer les dépendances nécessaires
RUN apt-get update && apt-get install -y \
    libzip-dev \
    zip \
    unzip \
    && docker-php-ext-install pdo pdo_mysql zip

# Activer le module Apache rewrite
RUN a2enmod rewrite

# Installer Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copier le projet Symfony dans le conteneur
COPY . /var/www/html/

# Définir le répertoire de travail
WORKDIR /var/www/html

ENV APP_ENV=prod

# Installer les dépendances PHP via Composer
RUN composer install --no-dev --optimize-autoloader \
    && php bin/console cache:clear --env=prod

# Définir les permissions
RUN chown -R www-data:www-data /var/www/html/var

RUN sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/html/public|g' /etc/apache2/sites-available/000-default.conf \
    && echo "ServerName localhost" >> /etc/apache2/apache2.conf

# Exposer le port 80
EXPOSE 80