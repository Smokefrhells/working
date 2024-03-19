FROM php:7.3-apache

WORKDIR /var/www/html

RUN apt-get update \
     && docker-php-ext-install mysqli pdo pdo_mysql \
     && docker-php-ext-enable pdo_mysql

RUN a2enmod rewrite