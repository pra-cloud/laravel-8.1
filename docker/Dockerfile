FROM php:8.1-alpine

ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/

RUN apk add --no-cache bash curl freetype-dev g++ gcc git icu-dev icu-libs imagemagick  \
    libc-dev libjpeg-turbo-dev libpng-dev libzip-dev make mysql-client oniguruma-dev \
    postgresql-libs supervisor zlib-dev


RUN pecl install igbinary redis imagick openswoole

RUN docker-php-ext-enable igbinary redis imagick openswoole

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN composer --version




