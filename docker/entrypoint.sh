#!/bin/bash

if [ -f .env ]; then
    export $(cat .env | grep -v '^#' | xargs)
fi

if [ ! -f ./composer.lock ]; then
    composer install --no-interaction
    composer dump-autoload
fi

if [ "$RUN_MODE" == "php-fpm" ]; then
    /usr/local/sbin/php-fpm -F
elif [ "$RUN_MODE" == "notification" ]; then
    php /var/www/laravel-app/artisan app:dispatch-notifications
else
    echo "Invalid RUN_MODE. Please set it to 'php-fpm' or 'artisan'."
    exit 1
fi