#!/bin/bash
  cd ..
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
  cd bin
  ./opcache_reset.sh
  cd ..
  varnishadm -T 127.0.0.1:6082 -S /etc/varnish/secret 'ban req.http.host ~ (^accept.carranker.com$)'
  varnishadm -T 127.0.0.1:6082 -S /etc/varnish/secret 'ban req.http.host ~ (^carranker.com$)'
  echo "Varnish cache cleared!"
