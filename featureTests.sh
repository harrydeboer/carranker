#!/bin/bash
if [[ ${OSTYPE} == 'msys' ]]; then
  PREFIX=winpty
else
  PREFIX=""
fi
$PREFIX docker exec -it carranker php artisan migrate:refresh --database='mysql_testing'
$PREFIX docker exec -it carranker php artisan index:cars --testing
$PREFIX docker exec -it carranker php artisan flush:redis-dbs --testing
$PREFIX docker exec -it carranker ./vendor/bin/phpunit --configuration phpunitFeature.xml
