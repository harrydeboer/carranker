FROM ubuntu:20.04

ARG PHP_VERSION
ARG APP_ENV
ARG VARNISH_HOST
ARG APACHE_HOST

RUN apt-get update

RUN DEBIAN_FRONTEND=noninteractive apt-get -y install software-properties-common
RUN DEBIAN_FRONTEND=noninteractive add-apt-repository ppa:ondrej/php
RUN DEBIAN_FRONTEND=noninteractive add-apt-repository ppa:ondrej/apache2

RUN DEBIAN_FRONTEND=noninteractive apt-get -y install \
    git \
    zip \
    curl \
    nano \
    sudo \
    unzip \
    libzip-dev \
    apache2=2.4.46-2+ubuntu20.04.1+deb.sury.org+1 \
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

RUN DEBIAN_FRONTEND=noninteractive apt-get install \
    php${PHP_VERSION}-sqlite php${PHP_VERSION}-xml php${PHP_VERSION}-bcmath php${PHP_VERSION}-curl php${PHP_VERSION}-gd \
    php${PHP_VERSION}-mbstring php${PHP_VERSION}-igbinary php${PHP_VERSION}-redis php${PHP_VERSION}-zip \
    && phpenmod xml bcmath curl gd mbstring igbinary redis zip \
    && phpdismod ffi gettext shmop xsl

RUN if [ "$APP_ENV" = "local" ] ; then apt-get install php${PHP_VERSION}-xdebug && phpenmod xdebug; fi

CMD /usr/sbin/apache2ctl -D FOREGROUND

COPY --from=composer:2.0.7 /usr/bin/composer /usr/bin/composer

COPY . /var/www/html
COPY ./config/web/apache2.conf /etc/apache2/apache2.conf
COPY ./config/web/ports.conf /etc/apache2/ports.conf
COPY ./config/web/000-default.${APP_ENV}.conf /etc/apache2/sites-available/000-default.conf
COPY ./config/web/php-apache2.ini /etc/php/${PHP_VERSION}/apache2/conf.d/php-apache2.ini
COPY ./config/web/php-cli.ini /etc/php/${PHP_VERSION}/cli/conf.d/php-cli.ini

RUN if [ "$APP_ENV" = "local" ] ; then cp /var/www/html/config/web/php-cli.xdebug.ini /etc/php/${PHP_VERSION}/cli/conf.d/xdebug-cli.ini; fi
RUN if [ "$APP_ENV" = "local" ] ; then cp /var/www/html/config/web/php-apache2.xdebug.ini /etc/php/${PHP_VERSION}/apache2/conf.d/xdebug-apache2.ini; fi
RUN sed -i -r "s/VARNISH_HOST/${VARNISH_HOST}/g" /etc/apache2/sites-available/000-default.conf
RUN sed -i -r "s/APACHE_HOST/${APACHE_HOST}/g" /etc/apache2/apache2.conf

RUN chown www-data:www-data -R /var/www/html/
