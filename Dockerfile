FROM php:8.3-fpm

WORKDIR /var/www/laravel-app

RUN apt-get update && apt-get install -y \
        apt-utils \
        curl \
        wget \
        zip \
        git \
        nano \
        supervisor

RUN docker-php-ext-install pdo pdo_mysql

RUN mkdir -p /var/log/supervisor && \
    mkdir -p /var/log/php-fpm

COPY . /var/www/laravel-app
COPY ./docker/supervisord /etc/supervisor/conf.d/

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer install --no-interaction
RUN composer dump-autoload

RUN find . -type f -exec chmod 644 {} \;
RUN find . -type d -exec chmod 755 {} \;
RUN chown -R $USER:www-data .

RUN chgrp -R www-data storage bootstrap/cache
RUN chmod -R ug+rwx storage bootstrap/cache

CMD ["/usr/bin/supervisord", "-n"]