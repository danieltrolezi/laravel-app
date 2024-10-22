FROM php:8.3-fpm

ARG APP_ENV=production
ENV APP_ENV=${APP_ENV}

WORKDIR /var/www/laravel-app

RUN apt-get update && apt-get install -y \
        apt-utils \
        curl \
        wget \
        zip \
        git \
        nano \
        supervisor

RUN docker-php-ext-configure pcntl --enable-pcntl
RUN docker-php-ext-install pdo pdo_mysql pcntl opcache

RUN pecl install xdebug \
        redis \
    && docker-php-ext-enable xdebug \
        redis

RUN mkdir -p /var/run/php && \
        chown -R www-data:www-data /var/run/php && \
        chmod 755 /var/run/php

COPY . /var/www/laravel-app

COPY ./docker/php "${PHP_INI_DIR}/conf.d/"
COPY ./docker/php-fpm/www.conf /usr/local/etc/php-fpm.d/www.conf
COPY ./docker/supervisor /etc/supervisor/

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN if [ "$APP_ENV" = "production" ]; then \
        composer install --no-interaction --optimize-autoloader --no-dev --prefer-dist \
        && find /var/www/laravel-app -not -path "/var/www/laravel-app/vendor/*" -type f -exec chmod 644 {} \; \
        && find /var/www/laravel-app -type d -exec chmod 755 {} \; \
        && chown -R www-data:www-data /var/www/laravel-app \
        && chmod -R ug+rwx storage bootstrap/cache; \
    fi

COPY ./docker/entrypoint.app.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh
ENTRYPOINT ["entrypoint.sh"]