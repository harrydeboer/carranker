#!/bin/bash
PARENT_PATH=$( cd "$(dirname "${BASH_SOURCE[0]}")" || exit ; pwd -P )
PARENT_DIR="$(basename "$PARENT_PATH")"
PUBLIC_DIR=${PARENT_PATH}/public/
RANDOM_NAME=$(head /dev/urandom | tr -dc A-Za-z0-9 | head -c 100)

echo "<?php opcache_reset(); ?>" > "${PUBLIC_DIR}""${RANDOM_NAME}".php

if [[ $PARENT_DIR = "accept.carranker.com" ]]; then
  curl https://accept.carranker.com/"${RANDOM_NAME}".php
elif [[ $PARENT_DIR = "carranker.com" ]]; then
  curl https://carranker.com/"${RANDOM_NAME}".php
elif [[ $PARENT_DIR = "carranker" ]]; then
  curl http://carranker/"${RANDOM_NAME}".php
fi
rm "${PUBLIC_DIR}""${RANDOM_NAME}".php

echo "OPcache has been reset!"
