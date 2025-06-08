# استخدم PHP 8.2 مع Apache
FROM php:8.2-apache

# فعل mod_rewrite
RUN a2enmod rewrite

# نسخ Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# نسخ ملفات المشروع
COPY . /var/www/html/

# إعداد مجلد العمل
WORKDIR /var/www/html

# ثبّت الـ dependencies
RUN composer install

# إعداد الصلاحيات
RUN chown -R www-data:www-data /var/www/html && chmod -R 755 /var/www/html

# إعداد الـ Apache ليشتغل من public/
RUN sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/html/public|' /etc/apache2/sites-available/000-default.conf

# ✨ أهم تعديل هنا:
# السماح بقراءة .htaccess من الجذر
RUN echo '<Directory /var/www/html/public>\n\
    AllowOverride All\n\
</Directory>' >> /etc/apache2/apache2.conf

# تشغيل Apache
CMD ["apache2-foreground"]
