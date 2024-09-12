#!/bin/bash

if [ -f .env ]; then
    export $(cat .env | grep -v '^#' | xargs)
fi

if [ ! -d "vendor" ] || [ -z "$(ls -A vendor)" ]; then
    if [ "$APP_ENV" == "local" ]; then
        composer install --no-interaction
        composer dump-autoload
        npm install
    else
        composer install --no-interaction --optimize-autoloader --no-dev      
    fi
fi

case "$RUN_MODE" in
    octane)
        if [ "$APP_ENV" == "local" ]; then
            php artisan octane:swoole --host=0.0.0.0 --watch 
        else
            php artisan octane:swoole --host=0.0.0.0 --workers=4 --task-workers=4
        fi
        ;;
    notif)
        php artisan app:dispatch-notifications
        ;;
    *)
        echo "Invalid RUN_MODE. Please set it to 'octane' or 'notif'."
        exit 1
        ;;
esac