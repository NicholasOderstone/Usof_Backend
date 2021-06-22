#!/bin/bash
php composer.phar update
php composer.phar install
# php create_db.php
cp .env.example .env
php artisan migrate:install
php artisan migrate --seed
php artisan key:generate