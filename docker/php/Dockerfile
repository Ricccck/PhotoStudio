FROM php:8.1-fpm


# Composerをインストール
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN apt-get update && apt-get install -y \
  git \
  curl \
  zip \
  unzip \
  && docker-php-ext-install pdo_mysql 

RUN composer require "twig/twig:^1.0"

COPY ./docker/php/php.ini /usr/local/etc/php/

COPY composer.json /var/www/html/

RUN composer install

WORKDIR /var/www/html
