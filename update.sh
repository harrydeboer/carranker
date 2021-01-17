#!/bin/bash
  cd ..
  if [[ $EUID -eq 0 ]]; then
    echo "This script must NOT be run as root" 1>&2
    exit 1
  fi
  previous=$(git rev-parse HEAD)
  git pull origin master
  test "$previous" == "$(git rev-parse HEAD)" && exit 1
  cd scripts || exit
  ./installAndClearCaches.sh
