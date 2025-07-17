#!/bin/bash

composer install --no-interaction

supervisord -n -c /etc/supervisor/supervisord.conf
