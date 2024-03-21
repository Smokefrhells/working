FROM php:8.3.4-fpm

WORKDIR /var/www/html

RUN apt-get update \
     && docker-php-ext-install mysqli pdo pdo_mysql \
     && docker-php-ext-enable pdo_mysql

EXPOSE 9000