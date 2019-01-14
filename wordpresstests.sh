#!/bin/bash
php artisan migrate --database='test_mysql'
cd public/wp-content/themes/carranker-theme
phpunit