FROM php:8.2-cli

WORKDIR /var/www/html

# تثبيت الأدوات اللي محتاجاها
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    zip \
    libzip-dev \
    && docker-php-ext-install zip

# نسخة Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# نسخ ملفات المشروع
COPY . .

# تثبيت الـ dependencies
RUN composer install --no-dev --optimize-autoloader

# CMD ["php", "public/index.php"]
CMD ["php", "-S", "0.0.0.0:8000", "-t", "public"]
