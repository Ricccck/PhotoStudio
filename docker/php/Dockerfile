FROM php:8.2.12-fpm

RUN apt-get update && apt-get install -y \
	git \
	curl \
	zip \
	unzip \
	libfreetype6-dev \
	libjpeg62-turbo-dev \
	libpng-dev \
	&& docker-php-ext-configure gd --with-freetype --with-jpeg \
	&& docker-php-ext-install -j$(nproc) gd \
	&& docker-php-ext-install pdo_mysql

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

COPY ./src /var/www/html/
COPY ./composer.json /var/www/html/
COPY ./composer.lock /var/www/html/

WORKDIR /var/www/html
RUN composer install

COPY ./docker/nginx/default.conf /etc/nginx/conf.d/default.conf

RUN chown -R www-data:www-data /var/www/html \
	&& chmod -R 755 /var/www/html

EXPOSE 80

CMD [ "php-fpm" ]
