FROM ubuntu:20.04

ARG php=7.4

RUN apt-get update

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
    php-pear \
    libc6 \
    libonig5 \
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

RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Expose apache.
EXPOSE 80
EXPOSE 8080

RUN DEBIAN_FRONTEND=noninteractive apt-get install \
    php${php}-sqlite php${php}-bcmath php${php}-curl php${php}-mbstring php${php}-igbinary php${php}-redis php${php}-xdebug php${php}-zip \
    && phpenmod bcmath curl mbstring igbinary redis xdebug zip \
    && phpdismod ffi exif ftp gettext shmop sockets xsl

# By default start up apache in the foreground, override with /bin/bash for interative.
CMD /usr/sbin/apache2ctl -D FOREGROUND

RUN echo "xdebug.remote_enable=1" >> /etc/php/7.4/apache2/conf.d/20-xdebug.ini \
    && echo "xdebug.remote_autostart=1" >> /etc/php/7.4/apache2/conf.d/20-xdebug.ini \
    && echo "xdebug.remote_port=9777" >> /etc/php/7.4/apache2/conf.d/20-xdebug.ini \
    && echo "xdebug.remote_timeout=1000" >> /etc/php/7.4/apache2/conf.d/20-xdebug.ini \
    && echo "xdebug.profiler_enable=0" >> /etc/php/7.4/apache2/conf.d/20-xdebug.ini \
    && echo "xdebug.profiler_output_dir=/var/www/html/tmp" >> /etc/php/7.4/apache2/conf.d/20-xdebug.ini \
    && echo "xdebug.profiler_output_name=cachegrind.out.%t-%s" >> /etc/php/7.4/apache2/conf.d/20-xdebug.ini

RUN echo "upload_max_filesize=1024M" >> /etc/php/7.4/apache2/php.ini \
   && echo "post_max_size=1024M" >> /etc/php/7.4/apache2/php.ini \
   && echo "post_max_size=1024M" >> /etc/php/7.4/apache2/php.ini \
   && echo "memory_limit=1024M" >> /etc/php/7.4/apache2/php.ini \
   && echo "max_input_time=3600" >> /etc/php/7.4/apache2/php.ini \
   && echo "max_execution_time=3600" >> /etc/php/7.4/apache2/php.ini \
   && echo "openssl.cafile=/var/www/html/config/curl-ca-bundle.crt" >> /etc/php/7.4/apache2/php.ini \
   && echo "curl.cainfo=/var/www/html/config/curl-ca-bundle.crt" >> /etc/php/7.4/apache2/php.ini \
   && echo "realpath_cache_size=4096K" >> /etc/php/7.4/apache2/php.ini \
   && echo "realpath_cache_ttl=600" >> /etc/php/7.4/apache2/php.ini

# 5. composer
COPY --from=composer:1.10.6 /usr/bin/composer /usr/bin/composer

ARG uid
RUN useradd -G www-data,root -u ${uid} -d /home/devuser devuser
RUN mkdir -p /home/devuser/.composer && \
    chown -R devuser:devuser /home/devuser

ARG upass
RUN echo devuser:${upass} | chpasswd && echo root:${upass} | chpasswd && usermod -aG sudo devuser
