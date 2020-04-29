#!/bin/bash
  if [[ $EUID -eq 0 ]]; then
    echo "This script must NOT be run as root" 1>&2
    exit 1
  fi
  prev=$(git rev-parse HEAD)
  git pull origin master
  test "$prev" == "$(git rev-parse HEAD)" && exit 1
  curl https://carranker.com/opcache_reset.php
  composer install --no-dev --no-progress --prefer-dist
  php artisan cache:clear
  php artisan route:clear
  php artisan config:clear
  php artisan view:clear
  curl https://carranker.com/opcache_reset.php
  php artisan migrate --force --no-interaction
  php artisan getcmsdata
  php artisan getfxrate
  php artisan flushredisdb
  varnishadm -T 127.0.0.1:6082 -S /etc/varnish/secret 'ban req.http.host ~ (^carranker.com$)'