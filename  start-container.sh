#!/bin/bash

# Start PHP-FPM in the background
php-fpm &

# Start nginx in the foreground
nginx -g "daemon off;"
