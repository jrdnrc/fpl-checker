FROM php:7.1-fpm

RUN apt-get update && apt-get install -y libmcrypt-dev libxml2-dev mysql-client libc-client-dev libkrb5-dev \
    && rm -r /var/lib/apt/lists/* \
    && docker-php-ext-configure imap --with-kerberos --with-imap-ssl --with-imap \
    && docker-php-ext-install imap mcrypt pdo_mysql soap \
    && pecl install mailparse \
    && docker-php-ext-enable mailparse

WORKDIR /var/www
VOLUME ["/var/www"]