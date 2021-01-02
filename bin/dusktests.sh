#!/bin/bash
cd ..
if [[ ${OSTYPE} == 'msys' ]]; then
  winpty docker exec -it carranker php artisan dusk
else
  docker exec -it carranker php artisan dusk
fi
