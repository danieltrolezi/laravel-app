#!/bin/sh

envsubst '$PHP_UPSTREAM_HOST' < /etc/nginx/conf.d/default.conf.template > /etc/nginx/conf.d/default.conf

exec nginx -g 'daemon off;'
