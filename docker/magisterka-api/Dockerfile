FROM php:8.2-fpm

RUN apt -y update && apt -y upgrade && apt -y install git bash libpq-dev libzip-dev unzip curl

RUN docker-php-ext-install opcache zip pdo pdo_pgsql

 RUN curl -sL https://deb.nodesource.com/setup_18.x | bash ;\
     apt-get update; \
     apt-get install -y nodejs

WORKDIR /var/www/symfony/

COPY ./docker/magisterka-api/php.ini /usr/local/etc/php/conf.d/docker-php-config.ini
COPY --from=composer:2.3.5 /usr/bin/composer /usr/bin/composer

ADD . .

RUN pecl install xdebug && docker-php-ext-enable xdebug

