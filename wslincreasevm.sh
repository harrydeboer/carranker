#!/bin/bash
wsl -d docker-desktop
echo 262144 >> /proc/sys/vm/max_map_count
echo "vm.max_map_count = 262144" > /etc/sysctl.d/99-docker-desktop.conf
