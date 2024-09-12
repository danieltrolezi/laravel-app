#!/bin/bash

if [ ! -d "vendor" ] || [ -z "$(ls -A vendor)" ]; then
    if [ "$APP_ENV" == "local" ]; then
        composer install --no-interaction
        composer dump-autoload
    else
        composer install --no-interaction --optimize-autoloader --no-dev      
    fi
fi

/usr/bin/supervisord -n -c /etc/supervisor/supervisord.conf
