FROM php:7.2-apache

RUN apt-get update && apt-get install -y \
    mysql-client \
    zlib1g-dev \
    libmemcached-dev \
    libpq-dev \
    && docker-php-ext-install \
    pdo_mysql \
    && rm -rf /var/lib/apt/lists/*

# Install Memcached for php 7
RUN curl -L -o /tmp/memcached.tar.gz "https://github.com/php-memcached-dev/php-memcached/archive/php7.tar.gz" \
    && mkdir -p /usr/src/php/ext/memcached \
    && tar -C /usr/src/php/ext/memcached -zxvf /tmp/memcached.tar.gz --strip 1 \
    && docker-php-ext-configure memcached \
    && docker-php-ext-install memcached \
    && rm /tmp/memcached.tar.gz

COPY ./www /var/www/html
WORKDIR /var/www/html