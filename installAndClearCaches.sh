#!/bin/bash
docker-compose build --no-cache
docker-compose up -d
docker cp /etc/letsencrypt carranker:/etc/letsencrypt
docker-compose up -d
PREFIX="docker exec -t carranker"
docker exec -t --user=www-data carranker composer install --no-dev --no-progress --prefer-dist
$PREFIX php artisan cache:clear
$PREFIX php artisan route:clear
$PREFIX php artisan config:clear
$PREFIX php artisan view:clear
./opcacheReset.sh
$PREFIX php artisan migrate --force --no-interaction
$PREFIX php artisan get:fx-rate
$PREFIX php artisan flush:redis-dbs
$PREFIX php artisan process:queue --truncate
$PREFIX php artisan index:cars
./opcacheReset.sh
docker-compose restart varnish

echo "Varnish cache cleared!"
