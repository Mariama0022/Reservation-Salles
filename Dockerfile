FROM php:8.3-apache

RUN apt-get update \
	&& apt-get install -y --no-install-recommends libzip-dev unzip \
	&& docker-php-ext-install pdo pdo_mysql zip \
	&& rm -rf /var/lib/apt/lists/*

RUN a2enmod rewrite

WORKDIR /var/www/html

COPY . .

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

RUN composer install --no-dev --optimize-autoloader

RUN chown -R www-data:www-data /var/www/html

EXPOSE 80