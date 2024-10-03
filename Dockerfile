FROM php:8.3-apache

RUN docker-php-ext-install pdo pdo_pgsql

COPY . /var/www/html

RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

RUN a2enmod rewrite

COPY ./apache.conf /etc/apache2/sites-available/000-default.conf