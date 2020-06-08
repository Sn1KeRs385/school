FROM php:7.3.9-fpm-alpine3.9

RUN apk update && apk add alpine-sdk \
    zip \
    ffmpeg \
    curl \
    libmcrypt-dev \
    mysql-client \
    imagemagick \
    exiftool \
    freetype \
    libpng \
    libjpeg-turbo \
    freetype-dev \
    libpng-dev \
    libjpeg-turbo-dev \
    libzip-dev\
    zlib-dev \
    && docker-php-ext-configure bcmath \
    && docker-php-ext-configure exif \
    && docker-php-ext-install zip \
    && docker-php-ext-configure zip --with-libzip \
    && docker-php-ext-install bcmath \
    && docker-php-ext-install exif \
    && docker-php-ext-install mysqli \
    && docker-php-ext-install pdo_mysql \
    && docker-php-ext-install pcntl \
    && docker-php-ext-configure gd \
    --with-gd \
    --with-freetype-dir=/usr/include/ \
    --with-png-dir=/usr/include/ \
    --with-jpeg-dir=/usr/include/  \
    && NPROC=$(grep -c ^processor /proc/cpuinfo 2>/dev/null || 1)  \
    && docker-php-ext-install -j${NPROC} gd  \
    && apk del --no-cache freetype-dev libpng-dev libjpeg-turbo-dev \
    && apk --no-cache add procps

RUN set -ex \
    && apk add --no-cache --virtual .phpize-deps $PHPIZE_DEPS imagemagick-dev libtool \
    && export CFLAGS="$PHP_CFLAGS" CPPFLAGS="$PHP_CPPFLAGS" LDFLAGS="$PHP_LDFLAGS" \
    && pecl install imagick-3.4.3 \
    && docker-php-ext-enable imagick \
    && apk add --no-cache --virtual .imagick-runtime-deps imagemagick \
    && apk del .phpize-deps

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www/
