#!/bin/bash

# Clear config dan generate key
php artisan config:clear
php artisan key:generate

# Jalankan Laravel di PORT Railway
php -S 0.0.0.0:$PORT -t public
