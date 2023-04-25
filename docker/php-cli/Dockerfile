FROM php:8.2-cli

RUN apt-get update && apt-get install -y zip unzip git curl
RUN docker-php-ext-install mysqli pdo pdo_mysql sockets pcntl

#RUN pecl install xdebug \
#    && docker-php-ext-enable xdebug

COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

#COPY ./xdebug.ini "${PHP_INI_DIR}/conf.d"