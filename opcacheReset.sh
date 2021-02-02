#!/bin/bash
PARENT_PATH=$( cd "$(dirname "${BASH_SOURCE[0]}")" || exit ; pwd -P )
PARENT_DIR="$(basename "$PARENT_PATH")"
PUBLIC_DIR=${PARENT_PATH}/public/
RANDOM_NAME=$(head /dev/urandom | tr -dc A-Za-z0-9 | head -c 100).php
echo "<?php opcache_reset(); echo 'OPcache reset!'?>" > "${PUBLIC_DIR}""${RANDOM_NAME}"
docker cp "${PUBLIC_DIR}""${RANDOM_NAME}" carranker:/var/www/html/public/"${RANDOM_NAME}"

sleep 1

if [[ $PARENT_DIR = "accept.carranker.com" ]]; then
  curl https://accept.carranker.com/"${RANDOM_NAME}"
elif [[ $PARENT_DIR = "carranker.com" ]]; then
  curl https://carranker.com/"${RANDOM_NAME}"
elif [[ $PARENT_DIR = "carranker" ]]; then
  curl http://carranker/"${RANDOM_NAME}"
fi
rm "${PUBLIC_DIR}""${RANDOM_NAME}"
docker exec -it carranker rm /var/www/html/public/"${RANDOM_NAME}"

echo "OPcache has been reset!"
