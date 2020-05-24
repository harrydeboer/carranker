FROM ubuntu:20.04

ARG php=7.4

RUN apt-get update

RUN DEBIAN_FRONTEND=noninteractive apt-get -y install software-properties-common
RUN DEBIAN_FRONTEND=noninteractive add-apt-repository ppa:ondrej/php

RUN DEBIAN_FRONTEND=noninteractive apt-get -y install \
    git \
    zip \
    curl \
    nano \
    sudo \
    unzip \
    libzip-dev \
    apache2=2.4.41-4ubuntu3 \
    php${php} \
    php${php}-mysql \
    libxml2 \
    libxslt1.1 \
    libc6 \
    libonig5 \
    libgd3 \
    php-imagick=3.4.4-4 \
    libapache2-mod-php${php}

RUN a2enmod php${php}

RUN a2enmod headers proxy rewrite proxy_balancer proxy_http slotmem_shm ssl

# Manually set up the apache environment variables
ENV APACHE_LOG_DIR /var/log/apache2
ENV APACHE_LOCK_DIR /var/lock/apache2
ENV APACHE_PID_FILE /var/run/apache2.pid
ENV APACHE_RUN_USER=www-data
ENV APACHE_RUN_GROUP=www-data
ENV PHP_XDEBUG_ENABLED=1
ENV PHP_INI_XDEBUG__REMOTE_PORT=9777
ENV XDEBUG_CONFIG="remote_host=host.docker.internal"

RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Expose apache.
EXPOSE 80
EXPOSE 8080

RUN DEBIAN_FRONTEND=noninteractive apt-get install \
    php${php}-sqlite php${php}-xml php${php}-bcmath php${php}-curl php${php}-gd \
    php${php}-mbstring php${php}-igbinary php${php}-redis php${php}-xdebug php${php}-zip \
    && phpenmod xml imagick bcmath curl gd mbstring igbinary redis xdebug zip \
    && phpdismod ffi gettext shmop xsl

# By default start up apache in the foreground, override with /bin/bash for interative.
CMD /usr/sbin/apache2ctl -D FOREGROUND

# 5. composer
COPY --from=composer:1.10.6 /usr/bin/composer /usr/bin/composer

ARG uid
RUN useradd -G www-data,root -u ${uid} -d /home/devuser devuser
RUN mkdir -p /home/devuser/.composer && \
    chown -R devuser:devuser /home/devuser

ARG upass
RUN echo devuser:${upass} | chpasswd && echo root:${upass} | chpasswd && usermod -aG sudo devuser
