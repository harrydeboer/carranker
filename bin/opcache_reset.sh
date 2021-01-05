#!/bin/bash
cd ..
parent_path=$( cd "$(dirname "${BASH_SOURCE[0]}")" ; pwd -P )
parentname="$(basename "$parent_path")"
WEBDIR=${parent_path}/public/
RANDOM_NAME=$(head /dev/urandom | tr -dc A-Za-z0-9 | head -c 100)
echo "<?php opcache_reset(); ?>" > ${WEBDIR}${RANDOM_NAME}.php
if [[ $parentname = "accept.carranker.com" ]]
then
  curl https://accept.carranker.com/${RANDOM_NAME}.php
elif [[ $parentname = "carranker.com" ]]
then
  curl https://carranker.com/${RANDOM_NAME}.php
elif [[ $parentname = "carranker" ]]
then
  curl http://carranker/${RANDOM_NAME}.php
fi
rm ${WEBDIR}${RANDOM_NAME}.php

echo "Opcache has been reset!"
