#!/bin/bash

composer install --no-interaction
composer dump-autoload

supervisord -n -c /etc/supervisor/supervisord.conf
