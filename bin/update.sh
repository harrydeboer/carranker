#!/bin/bash
  cd ..
  if [[ $EUID -eq 0 ]]; then
    echo "This script must NOT be run as root" 1>&2
    exit 1
  fi
  prev=$(git rev-parse HEAD)
  git pull origin master
  test "$prev" == "$(git rev-parse HEAD)" && exit 1
  cd bin
  ./installAndClearCaches.sh
