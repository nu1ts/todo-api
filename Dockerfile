FROM php:8.3-cli AS build

COPY . /var/www/html

FROM php:8.3-apache

RUN apt-get update && apt-get install -y \
    libpq-dev \
    nano \
    && docker-php-ext-install pdo pdo_pgsql

COPY --from=build /var/www/html /var/www/html

RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

RUN a2enmod rewrite

COPY ./apache.conf /etc/apache2/sites-available/000-default.conf

CMD ["apache2-foreground"]