#!/bin/bash
cd ..
if [[ ${OSTYPE} == 'msys' ]]; then
  winpty docker exec -it carranker php artisan migrate --database='mysql_testing'
  winpty docker exec -it carranker ./vendor/bin/phpunit --configuration phpunitfeature.xml
  winpty docker exec -it carranker php artisan db:wipe --database='mysql_testing'
else
  docker exec -it carranker php artisan migrate --database='mysql_testing'
  docker exec -it carranker ./vendor/bin/phpunit --configuration phpunitfeature.xml
  docker exec -it carranker php artisan db:wipe --database='mysql_testing'
fi
