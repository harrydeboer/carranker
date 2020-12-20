#!/bin/bash
  if [[ $EUID -eq 0 ]]; then
    echo "This script must NOT be run as root" 1>&2
    exit 1
  fi
  prev=$(git rev-parse HEAD)
  git pull origin master
  test "$prev" == "$(git rev-parse HEAD)" && exit 1
  composer install --no-dev --no-progress --prefer-dist
  php artisan cache:clear
  php artisan route:clear
  php artisan config:clear
  php artisan view:clear
  php artisan migrate --force --no-interaction
  php artisan getcmsdata
  php artisan getfxrate
  php artisan flushredisdb
  php artisan indexcars
  php artisan processqueue
  ./opcache_reset.sh
  varnishadm -T 127.0.0.1:6082 -S /etc/varnish/secret 'ban req.http.host ~ (^accept.carranker.com$)'
  varnishadm -T 127.0.0.1:6082 -S /etc/varnish/secret 'ban req.http.host ~ (^carranker.com$)'
  echo "Varnish cache cleared!"
