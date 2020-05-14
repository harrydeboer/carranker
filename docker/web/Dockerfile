FROM php:7.4-apache

RUN apt-get update

RUN apt-get install -y \
    git \
    zip \
    curl \
    nano \
    sudo \
    unzip \
    libzip-dev

# 2. apache configs + document root
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

RUN pecl install igbinary \
    && docker-php-ext-enable igbinary

RUN pecl install -o -f redis \
    &&  rm -rf /tmp/pear \
    &&  docker-php-ext-enable redis

RUN yes | pecl install xdebug \
    && docker-php-ext-install pdo_mysql mysqli bcmath opcache calendar zip pcntl sysvmsg sysvsem sysvshm \
    && docker-php-ext-enable xdebug \
    && echo "xdebug.remote_enable=1" >> /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.remote_autostart=1" >> /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.remote_port=9777" >> /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.remote_timeout=1000" >> /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.profiler_enable=0" >> /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.profiler_output_dir=/var/www/html/tmp" >> /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.profiler_output_name=cachegrind.out.%t-%s" >> /usr/local/etc/php/conf.d/xdebug.ini

RUN a2enmod headers proxy rewrite proxy_balancer proxy_http slotmem_shm ssl

RUN echo "upload_max_filesize=1024M" >> /usr/local/etc/php/conf.d/php.ini \
   && echo "post_max_size=1024M" >> /usr/local/etc/php/conf.d/php.ini \
   && echo "post_max_size=1024M" >> /usr/local/etc/php/conf.d/php.ini \
   && echo "memory_limit=1024M" >> /usr/local/etc/php/conf.d/php.ini \
   && echo "max_input_time=3600" >> /usr/local/etc/php/conf.d/php.ini \
   && echo "max_execution_time=3600" >> /usr/local/etc/php/conf.d/php.ini \
   && echo "openssl.cafile=/var/www/html/config/curl-ca-bundle.crt" >> /usr/local/etc/php/conf.d/php.ini \
   && echo "curl.cainfo=/var/www/html/config/curl-ca-bundle.crt" >> /usr/local/etc/php/conf.d/php.ini \
   && echo "realpath_cache_size=4096K" >> /usr/local/etc/php/conf.d/php.ini \
   && echo "realpath_cache_ttl=600" >> /usr/local/etc/php/conf.d/php.ini

# 5. composer
COPY --from=composer:1.10.6 /usr/bin/composer /usr/bin/composer

ARG uid
RUN useradd -G www-data,root -u $uid -d /home/devuser devuser
RUN mkdir -p /home/devuser/.composer && \
    chown -R devuser:devuser /home/devuser
