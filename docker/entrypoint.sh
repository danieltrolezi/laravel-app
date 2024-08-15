#!/bin/bash

if [ ! -f ./composer.lock ]; then
    composer install --no-interaction
    composer dump-autoload
fi

/usr/bin/supervisord -n
