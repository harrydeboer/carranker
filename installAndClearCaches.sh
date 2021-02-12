#!/bin/bash
docker-compose build --no-cache
docker-compose -f docker-compose.yml -f docker-compose.prod.yml up -d --remove-orphans
docker cp /home/letsencrypt carranker:/etc/letsencrypt
docker-compose -f docker-compose.yml -f docker-compose.prod.yml up -d --remove-orphans
PREFIX="docker exec -t carranker"
docker exec -t --user=www-data carranker composer install --no-dev --no-progress --prefer-dist
$PREFIX php artisan cache:clear
$PREFIX php artisan route:clear
$PREFIX php artisan config:clear
$PREFIX php artisan view:clear
./opcacheReset.sh
while ! $PREFIX php artisan migrate --force --no-interaction
do
  echo "Try again"
  sleep 1
done
$PREFIX php artisan get:fx-rate
$PREFIX php artisan flush:redis-dbs
while ! $PREFIX php artisan index:cars
do
  echo "Try again"
  sleep 1
done
$PREFIX php artisan process:queue --truncate
./opcacheReset.sh
docker-compose restart varnish

echo "Varnish cache cleared!"
