FROM php:8.3-cli

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
        supervisor \
        npm

RUN docker-php-ext-configure pcntl --enable-pcntl
RUN docker-php-ext-install pdo pdo_mysql pcntl

RUN pecl install xdebug \
        redis

RUN pecl install -D 'enable-sockets="no" enable-openssl="yes" enable-http2="yes" enable-mysqlnd="yes" enable-swoole-json="no" enable-swoole-curl="yes" enable-cares="yes"' swoole

RUN docker-php-ext-enable xdebug \
        redis \
        swoole

RUN mkdir -p /var/log/supervisor

COPY . /var/www/laravel-app
COPY ./docker/supervisord /etc/supervisor/conf.d/
COPY ./docker/php "${PHP_INI_DIR}/conf.d/"

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN find /var/www/laravel-app -not -path "/var/www/laravel-app/vendor/*" -type f -exec chmod 644 {} \;
RUN find /var/www/laravel-app -type d -exec chmod 755 {} \;
RUN chown -R $USER:www-data /var/www/laravel-app
RUN chgrp -R www-data storage bootstrap/cache
RUN chmod -R ug+rwx storage bootstrap/cache

COPY ./docker/entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh
ENTRYPOINT ["/entrypoint.sh"]