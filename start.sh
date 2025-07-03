#!/bin/bash

# Laravel preparation
php artisan config:clear
php artisan migrate --force
php artisan key:generate

# Jalankan Laravel di Railway
php artisan serve --host=0.0.0.0 --port=3000
