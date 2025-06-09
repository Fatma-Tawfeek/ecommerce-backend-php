# use an official PHP runtime as a parent image
FROM php:8.2-apache

# Set working directory
WORKDIR /var/www/html

# Install dependencies
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    zip \
    libzip-dev \
    && docker-php-ext-install zip \
    && docker-php-ext-install pdo pdo_mysql

# enable mod_rewrite
RUN a2enmod rewrite

# set document root
RUN sed -i 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/000-default.conf

# copy source files
COPY . /var/www/html

# copy .env
COPY .env /var/www/html/.env

# copy composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# install dependencies
RUN composer install --no-dev --optimize-autoloader && composer dump-autoload --optimize

# expose port
EXPOSE 80

# run apache
CMD ["apache2-foreground"]
