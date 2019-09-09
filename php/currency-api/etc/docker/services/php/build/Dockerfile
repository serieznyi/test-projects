FROM php:7.2.22-fpm-alpine3.10

RUN apk add --no-cache --virtual .build-deps $PHPIZE_DEPS

RUN set -ex \
 && apk --no-cache add \
   git \
   zlib-dev \
   postgresql-dev \
   libzip-dev \
   icu-dev

RUN docker-php-ext-configure zip --with-libzip

RUN docker-php-ext-install \
   mbstring \
   zip \
   intl \
   pdo_pgsql \
   bcmath

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && composer global require hirak/prestissimo

RUN pecl install xdebug

# Удаляем все вспомогательные библиотеки, что бы облегчить образ
RUN apk del .build-deps

ENV PATH "$PATH:/var/www/vendor/bin"

CMD ["php-fpm"]
