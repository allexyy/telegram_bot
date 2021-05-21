ARG PHP_VERSION=8.0
ARG NGINX_VERSION=1.19.3
ARG COMPOSER_VERSION=2.0.6

FROM composer:${COMPOSER_VERSION} AS composer_stage

# "php" stage
FROM php:${PHP_VERSION}-fpm-alpine AS php

# Add AMQP
ENV EXT_AMQP_VERSION=master

RUN docker-php-source extract \
    && apk -Uu add git rabbitmq-c-dev \
    && git clone --branch $EXT_AMQP_VERSION --depth 1 https://github.com/php-amqp/php-amqp.git /usr/src/php/ext/amqp \
    && cd /usr/src/php/ext/amqp && git submodule update --init \
    && docker-php-ext-install amqp

## Composer
COPY --from=composer_stage /usr/bin/composer /usr/bin/composer
### https://getcomposer.org/doc/03-cli.md#composer-allow-superuser
ENV COMPOSER_ALLOW_SUPERUSER=1

## Install application
WORKDIR /srv

# build for production
#ARG APP_ENV=production
### prevent the reinstallation of vendors at every changes in the source code
COPY composer.json composer.lock ./

RUN set -eux; \
    composer check-platform-reqs; \
    composer install; \
    composer clear-cache

### copy source files
COPY bin bin/
COPY config config/
COPY public public/
COPY src src/

### Finish composer install
RUN set -eux; \
    composer dump-autoload --classmap-authoritative
### Setup entrypoint
COPY docker/php/docker-entrypoint.sh /usr/local/bin/docker-entrypoint
RUN chmod +x /usr/local/bin/docker-entrypoint
ENTRYPOINT ["docker-entrypoint"]
CMD ["php-fpm"]

FROM nginx:${NGINX_VERSION}-alpine AS nginx

COPY docker/nginx/conf.d/upstream.nginx /etc/nginx/conf.d/upstream.conf
COPY docker/nginx/conf.d/default.nginx /etc/nginx/conf.d/default.conf
COPY docker/nginx/conf.d/cors.inc /etc/nginx/conf.d/cors.inc

WORKDIR /srv/public

COPY --from=php /srv/public ./
COPY --from=php /srv/public/images images/