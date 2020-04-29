#!/bin/bash

WEBDIR=/var/www/carranker.com/public/
RANDOM_NAME=$(head /dev/urandom | tr -dc A-Za-z0-9 | head -c 100)
echo "<?php opcache_reset(); ?>" > ${WEBDIR}${RANDOM_NAME}.php
curl https://carranker.com/${RANDOM_NAME}.php
rm ${WEBDIR}${RANDOM_NAME}.php

echo "Opcache has been reset!"