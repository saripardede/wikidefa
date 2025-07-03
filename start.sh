#!/bin/bash

cp .env.production .env

php artisan config:clear
php artisan key:generate
php -S 0.0.0.0:$PORT -t public
