FROM php:5.4.45-fpm

RUN apt update --force-yes -y \
  && apt upgrade --force-yes -y \
  && apt install --force-yes -y \
  git \
  mercurial \
  zip \
  nano \
  php5\
  php5-fpm \
  php5-cli \
  php5-readline \
  php5 libmcrypt-dev php-pear \
  php5-mysql \
  php5-mcrypt \
  php5-curl \
  php5-gd \
  php5-intl \
  libfreetype6-dev \
  libjpeg62-turbo-dev \
  mysql-client\
  libzip-dev \
  zlib1g-dev \
  libpng-dev \
  pkg-config \
  patch

# Clear cache
RUN apt-get clean -y \
  && rm -rf /var/lib/apt/lists/* \
  /tmp/* \
  /var/tmp/*

RUN php5enmod mcrypt
RUN docker-php-ext-install mcrypt mbstring zip exif gd mysqli pdo pdo_mysql pcntl

ADD https://git.archlinux.org/svntogit/packages.git/plain/trunk/freetype.patch?h=packages/php /tmp/freetype.patch
RUN docker-php-source extract; \
  cd /usr/src/php; \
  patch -p1 -i /tmp/freetype.patch; \
  rm /tmp/freetype.patch

RUN docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/

#RUN yes | pecl install xdebug-2.4.1 \
#    && echo "zend_extension=$(find /usr/local/lib/php/extensions/ -name xdebug.so)" > /usr/local/etc/php/conf.d/xdebug.ini \
#    && echo "xdebug.remote_port=9001" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
#    && echo "xdebug.remote_enable=1" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
#    && echo "xdebug.remote_connect_back=1" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
#    && echo "xdebug.idekey=\"PHPSTORM\"" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
#    && echo "xdebug.remote_autostart=1" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
#    && echo "xdebug.remote_log=/tmp/xdebug.log" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
#    && echo "xdebug.profiler_enable_trigger=1" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
#    && echo "xdebug.profiler_output_dir=/var/www/.docker/xdebug_profiler/" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
#    && echo "xdebug.var_display_max_depth = -1" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
#    && echo "xdebug.var_display_max_children = -1" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
#    && echo "xdebug.var_display_max_data = -1" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini


RUN curl -sS https://getcomposer.org/installer | \
    php -- --install-dir=/usr/bin/ --filename=composer

ENV COMPOSER_ALLOW_SUPERUSER=1
#RUN composer selfupdate --1

#ADD ./.docker/php-fpm/php.ini /usr/local/etc/php/conf.d/php.ini

ENV USER=test-task
ENV UID=1000
ENV GID=1000

RUN groupadd -g "$GID" "$USER"
RUN useradd -u "$UID" -ms /bin/bash -g "$USER" "$USER"

USER "$USER"

WORKDIR /var/www