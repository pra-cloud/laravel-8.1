FROM php:8.0-alpine

LABEL maintainer="HyperZod"

ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/

# Install persistent / runtime deps
RUN set -eux; \ 
    apk --update add --no-cache tzdata nodejs npm gnupg su-exec zip unzip git supervisor sqlite libcap openssl

# Install PHP extensions (HEAVY)
RUN chmod +x /usr/local/bin/install-php-extensions \
    && install-php-extensions sockets mongodb amqp opcache pcntl swoole \
    && php -r "readfile('http://getcomposer.org/installer');" | php -- --install-dir=/usr/bin/ --filename=composer

# Install PHP extensions (LIGHT)
RUN install-php-extensions xml readline ldap intl pdo_mysql pcov msgpack bcmath igbinary zip 

ENV TZ=UTC
ENV DEBIAN_FRONTEND noninteractive

COPY /docker/php.ini /usr/local/etc/php/conf.d/99-sail.ini
COPY /docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY /docker/start-container /usr/local/bin/start-container

# Clean up
RUN rm -rf /usr/share/php /usr/share/php8 /usr/src/* /usr/local/bin/phpdbg \
    /usr/local/bin/install-php-extensions /var/lib/apt/lists/* /tmp/* /var/tmp/* \
    # Miscellany
    && setcap "cap_net_bind_service=+ep" /usr/local/bin/php \
    && adduser --disabled-password --gecos "" -u 1337 -s /bin/sh -G www-data sail \
    && ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone \
    && chmod +x /usr/local/bin/start-container \
    && echo http://dl-2.alpinelinux.org/alpine/edge/community/ >> /etc/apk/repositories \
    && apk --no-cache add shadow && usermod -u 1000 www-data

# ADD bash aliases
RUN echo 'alias serve="php artisan serve --host=0.0.0.0 --port=8000"' >> ~/.ashrc

WORKDIR /var/www/html

# RUN echo $ENV
# #ENTRYPOINT ["start-container"]
# ENTRYPOINT if [ "$APP_ENV" = "production" ]; then "start-container"; else "bash"; fi