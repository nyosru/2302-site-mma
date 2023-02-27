FROM php:8.1.1-fpm-buster
ENV TZ=UTC
WORKDIR /www

# Install system dependencies
RUN apt-get update && apt-get install -y git

RUN apt-get update && apt-get install -y \
        openssl \
        curl \
        wget \
        git \
        unzip \
        libpq-dev \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libmcrypt-dev \
        libsodium-dev \
        libicu-dev \
        --no-install-recommends

RUN  apt-get install -y \
             libzip-dev \
             && docker-php-ext-install zip  && docker-php-ext-enable zip

RUN docker-php-ext-install bcmath \
    && docker-php-ext-enable bcmath

RUN docker-php-ext-install pdo \
    && docker-php-ext-enable pdo

RUN docker-php-ext-install pdo_mysql \
    && docker-php-ext-enable pdo_mysql

RUN docker-php-ext-configure pcntl --enable-pcntl \
  && docker-php-ext-install \
    pcntl \
    sockets
RUN docker-php-ext-enable sockets
RUN apt-get update && apt-get install -y libbz2-dev
# Install PHP extensions

RUN docker-php-ext-install pdo_pgsql

RUN apt-get install -y --no-install-recommends \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
&& docker-php-ext-configure gd \
    --with-freetype=/usr/include/ \
    --with-jpeg=/usr/include/ \
&& docker-php-ext-install -j$(nproc) gd

RUN pecl install redis \
    && docker-php-ext-enable redis

RUN pecl install msgpack \
    && docker-php-ext-enable msgpack
RUN docker-php-ext-enable sodium
RUN apt-get install mc -y
RUN apt-get install firefox-esr -y
RUN apt-get install graphicsmagick-imagemagick-compat -y
RUN apt-get install libpci-dev -y
RUN apt-get install libegl1 -y
RUN apt-get install xvfb -y
RUN apt-get install rsync -y

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# RUN php artisan migrate

# COPY . .

