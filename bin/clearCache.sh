#!/bin/bash
cd ..
if [[ ${OSTYPE} == 'msys' ]]; then
  winpty docker exec -it carranker php artisan cache:clear
  winpty docker exec -it carranker php artisan route:clear
  winpty docker exec -it carranker php artisan config:clear
  winpty docker exec -it carranker php artisan view:clear
else
  docker exec -it carranker php artisan cache:clear
  docker exec -it carranker php artisan route:clear
  docker exec -it carranker php artisan config:clear
  docker exec -it carranker php artisan view:clear
fi
