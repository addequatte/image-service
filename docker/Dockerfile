FROM php:8.1-cli
USER root

# Set working directory
WORKDIR /var/www/gd_service

RUN  rm /etc/apt/preferences.d/no-debian-php

# Install dependencies
RUN apt-get update -y \
  && apt-get install -y \
        ssh \
        zip \
        php-zip \
        php-dev \
        libpq-dev \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libpng-dev \
        zlib1g-dev \
        supervisor \
  && apt-get clean -y \
  && docker-php-ext-install pcntl sockets \
  && docker-php-ext-configure gd --with-freetype --with-jpeg \
  && docker-php-ext-install -j$(nproc) gd

RUN pecl install xdebug \
 && docker-php-ext-enable xdebug

COPY supervisor /etc/supervisor

COPY ./xdebug.ini /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

ADD ./php.ini /usr/local/etc/php/php.ini

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

CMD ["/usr/bin/supervisord","-n"]
