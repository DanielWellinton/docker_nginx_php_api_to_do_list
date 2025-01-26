FROM php:8.2-fpm
RUN apt-get update
RUN apt-get install -y libpq-dev
RUN docker-php-ext-install pdo_pgsql
WORKDIR /var/www/html