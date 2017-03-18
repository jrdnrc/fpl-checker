FROM php:7.1-fpm

RUN apt-get update && apt-get install -y libmcrypt-dev libxml2-dev mysql-client \
    && docker-php-ext-install mcrypt pdo_mysql soap

WORKDIR /var/www
VOLUME ["/var/www"]