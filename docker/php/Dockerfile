FROM php:8.1-fpm

# Copy composer.lock and composer.json
COPY composer.lock composer.json /var/www/

# Set working directory
WORKDIR /var/www

# Install dependencies
RUN apt-get update && apt-get install -y \
    build-essential \
    libonig-dev \
    libzip-dev \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libaio1 \
    libaio-dev \
    locales \
    zip \
    jpegoptim optipng pngquant gifsicle \
    vim \
    unzip \
    git \
    curl

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install extensions
RUN docker-php-ext-install pdo_mysql mbstring zip exif pcntl
# RUN docker-php-ext-configure gd --with-gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ --with-png-dir=/usr/include/
RUN docker-php-ext-install gd

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install JDBC
RUN mkdir /opt/oracle

# Copy DB drivers
COPY docker/php/instantclient-basic-linux.x64-21.4.0.0.0dbru.zip \
    docker/php/instantclient-sdk-linux.x64-21.4.0.0.0dbru.zip \
    /var/www/

RUN unzip ./instantclient-basic-linux.x64-21.4.0.0.0dbru.zip
RUN unzip ./instantclient-sdk-linux.x64-21.4.0.0.0dbru.zip

RUN mv ./instantclient_21_4 /opt/oracle/instantclient_21_4

ENV LD_LIBRARY_PATH  /opt/oracle/instantclient_21_4:${LD_LIBRARY_PATH}

RUN echo /opt/oracle/instantclient_21_4 > /etc/ld.so.conf.d/oracle-instantclient

# Install Oracle extensions
RUN echo 'instantclient,/opt/oracle/instantclient_21_4/' | pecl install oci8 \ 
      && docker-php-ext-enable \
               oci8 \ 
       && docker-php-ext-configure pdo_oci --with-pdo-oci=instantclient,/opt/oracle/instantclient_21_4,11.1 \
       && docker-php-ext-install \
               pdo_oci 

# Add user for laravel application
RUN groupadd -g 1000 www
RUN useradd -u 1000 -ms /bin/bash -g www www

# Copy existing application directory contents
COPY . /var/www

# Copy existing application directory permissions
COPY --chown=www:www . /var/www

# Change current user to www
USER www

# Expose port 9000 and start php-fpm server
EXPOSE 9000
CMD ["php-fpm"]