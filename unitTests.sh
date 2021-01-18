#!/bin/bash
  if [[ ${OSTYPE} == 'msys' ]]; then
    winpty docker exec -it carranker ./vendor/bin/phpunit --configuration phpunitUnit.xml
  else
    docker exec -it carranker ./vendor/bin/phpunit --configuration phpunitUnit.xml
  fi
