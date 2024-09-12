FROM php:8.3-cli

WORKDIR /srv/gamewatch

RUN apt-get update && apt-get install -y \
        libcurl4-openssl-dev \
        libbrotli-dev \
        libc-ares-dev \
        libssl-dev \
        apt-utils \
        curl \
        wget \
        zip \
        git \
        nano \
        npm

RUN docker-php-ext-configure pcntl --enable-pcntl
RUN docker-php-ext-install pdo pdo_mysql pcntl opcache

RUN pecl install xdebug \
        redis

RUN printf "\n" | pecl install swoole

RUN docker-php-ext-enable xdebug \
        redis \ 
        swoole

COPY . .
COPY ./docker/php "${PHP_INI_DIR}/conf.d/"

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

COPY ./docker/entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

ENTRYPOINT ["/entrypoint.sh"]