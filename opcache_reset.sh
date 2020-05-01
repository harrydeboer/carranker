#!/bin/bash

parent_path=$( cd "$(dirname "${BASH_SOURCE[0]}")" ; pwd -P )
parentname="$(basename "$parent_path")"

WEBDIR=${parent_path}/public/
RANDOM_NAME=$(head /dev/urandom | tr -dc A-Za-z0-9 | head -c 100)
echo "<?php opcache_reset(); ?>" > ${WEBDIR}${RANDOM_NAME}.php
curl https://${parentname}/${RANDOM_NAME}.php
rm ${WEBDIR}${RANDOM_NAME}.php

WEBDIRWP=${parent_path}/wordpress/wp/
RANDOM_NAME=$(head /dev/urandom | tr -dc A-Za-z0-9 | head -c 100)
echo "<?php opcache_reset(); ?>" > ${WEBDIRWP}${RANDOM_NAME}.php
curl https://${parentname}/${RANDOM_NAME}.php
rm ${WEBDIRWP}${RANDOM_NAME}.php

echo "Opcache has been reset!"