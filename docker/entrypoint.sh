#!/bin/bash

if [ ! -f ./composer.lock ]; then
    composer install --no-interaction
    composer dump-autoload
fi

if [ ! -f ./package-lock.json ]; then
    npm install
    npm run build
fi

/usr/bin/supervisord -n
