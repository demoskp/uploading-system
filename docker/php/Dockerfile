FROM php:7.4-fpm

# Install PHP Extensions using apt-get
RUN DEBIAN_FRONTEND=noninteractive apt-get update -q \
    && DEBIAN_FRONTEND=noninteractive apt-get install -qy \
      curl \
      git \
      zip unzip \
      libonig-dev

# Install PHP Extensions within docker
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl

# Install Composer
RUN curl -sS https://getcomposer.org/installer -o setup-composer.php && php setup-composer.php --install-dir=/usr/local/bin --filename=composer


RUN cd /usr/local/etc/php/ && touch php.ini && echo "upload_max_filesize=20M" >> php.ini