FROM php:7.3-apache

RUN apt-get update \
    && \
    apt-get install -y --no-install-recommends --no-install-suggests \
        libyaml-dev \
        git \
        zip \
        unzip \
    && \
    rm -rf /var/lib/apt/lists/*

RUN pecl install yaml \
    && docker-php-ext-enable yaml

COPY . /var/www/html
COPY --from=composer /usr/bin/composer /usr/bin/composer

RUN mkdir -p /var/php/vendor
RUN composer install
