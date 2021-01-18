#!/bin/bash
if [[ ${OSTYPE} == 'msys' ]]; then
  PREFIX=winpty
else
  PREFIX=""
fi
$PREFIX docker exec -it carranker ./vendor/bin/phpunit --configuration phpunitUnit.xml
