#!/bin/bash
  cd ..
  composer install --no-dev --no-progress --prefer-dist
  php artisan cache:clear
  php artisan route:clear
  php artisan config:clear
  php artisan view:clear
  php artisan migrate --force --no-interaction
  php artisan get:fx-rate
  php artisan flush:redis-dbs
  php artisan index:cars
  php artisan process:queue
  cd bin || exit
  ./opcacheReset.sh
  cd ..
  varnishadm -T 127.0.0.1:6082 -S /etc/varnish/secret 'ban req.http.host ~ (^accept.carranker.com$)'
  varnishadm -T 127.0.0.1:6082 -S /etc/varnish/secret 'ban req.http.host ~ (^carranker.com$)'
  echo "Varnish cache cleared!"
