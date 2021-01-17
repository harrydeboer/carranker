#!/bin/bash
  if [[ ${OSTYPE} == 'msys' ]]; then
    winpty docker exec -it carranker php artisan migrate --database='mysql_testing'
    winpty docker exec -it carranker php artisan index:cars --testing
    winpty docker exec -it carranker ./vendor/bin/phpunit --configuration phpunitFeature.xml
    winpty docker exec -it carranker php artisan db:wipe --database='mysql_testing'
    winpty docker exec -it carranker php artisan index:cars --testing
  else
    docker exec -it carranker php artisan migrate --database='mysql_testing'
    docker exec -it carranker php artisan index:cars --testing
    docker exec -it carranker ./vendor/bin/phpunit --configuration phpunitFeature.xml
    docker exec -it carranker php artisan db:wipe --database='mysql_testing'
    docker exec -it carranker php artisan index:cars --testing
  fi
