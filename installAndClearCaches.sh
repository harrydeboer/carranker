#!/bin/bash
docker-compose build --no-cache
docker-compose up --scale selenium=0 -d
sudo docker cp /etc/letsencrypt carranker:/etc/letsencrypt
docker-compose up --scale selenium=0 -d
PREFIX="docker exec -it carranker"
$PREFIX mkdir vendor
$PREFIX chown devuser:devuser -R vendor
docker exec -it --user=devuser carranker composer install --no-dev --no-progress --prefer-dist
$PREFIX php artisan cache:clear
$PREFIX php artisan route:clear
$PREFIX php artisan config:clear
$PREFIX php artisan view:clear
./opcacheReset.sh
$PREFIX php artisan migrate --force --no-interaction
$PREFIX chown www-data:www-data -R storage
$PREFIX php artisan migrate --force --no-interaction
$PREFIX php artisan get:fx-rate
$PREFIX php artisan flush:redis-dbs
$PREFIX php artisan process:queue --truncate
$PREFIX php artisan index:cars
./opcacheReset.sh
docker-compose restart varnish

echo "Varnish cache cleared!"
