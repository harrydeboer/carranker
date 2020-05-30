#!/bin/bash
if [[ ${OSTYPE} == 'msys' ]]; then
  winpty docker exec -it carranker php artisan migrate --database='test_mysql'
  winpty docker exec -it carranker ./wordpress/phpunit-7.5.20.phar --configuration ./public/wp-content/themes/carranker-theme/phpunit.xml.dist
else
  docker exec -it carranker php artisan migrate --database='test_mysql'
  docker exec -it carranker ./wordpress/phpunit-7.5.20.phar --configuration ./public/wp-content/themes/carranker-theme/phpunit.xml.dist
fi
