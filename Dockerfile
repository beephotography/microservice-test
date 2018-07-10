FROM php:7.2-apache

RUN apt-get update && apt-get install -y \
    mysql-client \
    && docker-php-ext-install \
    pdo_mysql \
    && rm -rf /var/lib/apt/lists/*


COPY ./www /var/www/html
WORKDIR /var/www/html