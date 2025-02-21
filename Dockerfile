FROM php:8.2-apache

# Install required packages
RUN apt-get update && apt-get install -y \
    git unzip libpq-dev libonig-dev libzip-dev \
    && docker-php-ext-install pdo pdo_mysql zip

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Enable mod_rewrite
RUN a2enmod rewrite

# Set DocumentRoot to Symfony's public directory
COPY ./apache.conf /etc/apache2/sites-available/000-default.conf