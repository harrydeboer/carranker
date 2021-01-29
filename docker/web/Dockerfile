FROM ubuntu:20.04

ARG PHP_VERSION
ARG APP_ENV

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
    apache2 \
    php${PHP_VERSION} \
    php${PHP_VERSION}-mysql \
    libxml2 \
    libxslt1.1 \
    libc6 \
    libonig5 \
    libgd3 \
    libapache2-mod-php${PHP_VERSION}

RUN a2enmod php${PHP_VERSION}

RUN a2enmod headers proxy rewrite proxy_balancer proxy_http slotmem_shm ssl

# Expose apache.
EXPOSE 80
EXPOSE 443
EXPOSE 8181

RUN DEBIAN_FRONTEND=noninteractive apt-get install \
    php${PHP_VERSION}-sqlite php${PHP_VERSION}-xml php${PHP_VERSION}-bcmath php${PHP_VERSION}-curl php${PHP_VERSION}-gd \
    php${PHP_VERSION}-mbstring php${PHP_VERSION}-igbinary php${PHP_VERSION}-redis php${PHP_VERSION}-zip \
    && phpenmod xml bcmath curl gd mbstring igbinary redis zip \
    && phpdismod ffi gettext shmop xsl

RUN if [ "$APP_ENV" = "local" ] ; then apt-get install php${PHP_VERSION}-xdebug && phpenmod xdebug; fi

# By default start up apache in the foreground, override with /bin/bash for interative.
CMD /usr/sbin/apache2ctl -D FOREGROUND

# 5. composer
COPY --from=composer:2.0.7 /usr/bin/composer /usr/bin/composer

COPY . /var/www/html
COPY ./config/apache2.conf /etc/apache2/apache2.conf
COPY ./config/ports.conf /etc/apache2/ports.conf
COPY ./config/000-default.${APP_ENV}.conf /etc/apache2/sites-available/000-default.conf
COPY ./config/php-apache2.${APP_ENV}.ini /etc/php/${PHP_VERSION}/apache2/conf.d/php-apache2.ini
COPY ./config/php-cli.${APP_ENV}.ini /etc/php/${PHP_VERSION}/cli/conf.d/php-cli.ini

RUN chown www-data:www-data -R /var/www/html/
