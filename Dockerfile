# استخدمي نسخة PHP مدمجة مع Apache
FROM php:8.2-apache

# إعداد المسار الرئيسي داخل الكونتينر
WORKDIR /var/www/html

# تثبيت الأدوات المطلوبة
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    zip \
    libzip-dev \
    && docker-php-ext-install zip \
    && docker-php-ext-install pdo pdo_mysql

# تفعيل mod_rewrite (مهم عشان .htaccess)
RUN a2enmod rewrite

# تعديل ملف إعدادات Apache عشان يوجه لـ public
RUN sed -i 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/000-default.conf

# نسخ مشروعك للكونتينر
COPY . /var/www/html

# نسخ Composer من صورة composer الرسمية
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# تثبيت الباكدجات بدون dev
RUN composer install --no-dev --optimize-autoloader && composer dump-autoload --optimize

# فتح البورت 80
EXPOSE 80

# بدء Apache
CMD ["apache2-foreground"]
