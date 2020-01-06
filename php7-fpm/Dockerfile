FROM php:7.4.1-fpm

RUN apt-get update && apt-get install -y \
    openssl \
    git \
    unzip \
    wget

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
&& composer --version

# Install DB driver
RUN apt-get install --yes --no-install-recommends libpq-dev \
      && docker-php-ext-install pdo_pgsql

# install xdebug
RUN pecl install xdebug \
&& docker-php-ext-enable xdebug
COPY xdebug.ini  /etc/php7/conf.d/

# config
COPY symfony.ini /etc/php7/conf.d/
COPY symfony.ini /etc/php7/cli/conf.d/
COPY symfony.pool.conf /etc/php7/php-fpm.d/

WORKDIR /var/www/symfony

EXPOSE 9000