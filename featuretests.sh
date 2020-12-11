#!/bin/bash
if [[ ${OSTYPE} == 'msys' ]]; then
  winpty docker exec -it carranker php artisan migrate --database='mysql_testing'
  winpty docker exec -it carranker ./vendor/bin/phpunit --configuration phpunitfeature.xml
else
  docker exec -it carranker php artisan migrate --database='mysql_testing'
  docker exec -it carranker ./vendor/bin/phpunit --configuration phpunitfeature.xml
fi
