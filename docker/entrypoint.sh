#!/bin/bash

composer install --no-interaction --dump-autoload

supervisord -n -c /etc/supervisor/supervisord.conf
