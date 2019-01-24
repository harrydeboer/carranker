#!/bin/bash
  prev=$(git rev-parse HEAD)
  git pull origin master
  test "$prev" == "$(git rev-parse HEAD)" && exit 1
  curl https://carranker.com/opcache_reset.php
  composer install --no-dev --no-progress --prefer-dist
  php artisan cache:clear
  php artisan route:cache
  php artisan config:cache
  php artisan view:clear
  php artisan view:cache
  curl https://carranker.com/opcache_reset.php
  php artisan migrate --force --no-interaction
  php artisan getcmsdata