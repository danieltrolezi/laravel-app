#!/bin/bash

if [ "$APP_ENV" == "local" ]; then
    if [ ! -d "vendor" ] || [ -z "$(ls -A vendor)" ]; then
        composer install --no-interaction
        composer dump-autoload

        php artisan key:generate --ansi
        php artisan migrate --seed
    fi
fi

supervisord -n -c /etc/supervisor/supervisord.conf
