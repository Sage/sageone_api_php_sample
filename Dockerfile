FROM php:7.3-apache

RUN apt-get update \
    && \
    apt-get install -y --no-install-recommends --no-install-suggests \
        libyaml-dev \
    && \
    rm -rf /var/lib/apt/lists/*

RUN pecl install yaml \
    && docker-php-ext-enable yaml

COPY . /var/www/html/
