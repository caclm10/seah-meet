FROM php:8.2-apache

# Install dependencies
RUN apt-get update

RUN apt-get install curl
RUN apt-get install sudo -y
RUN apt-get install nano -y
RUN apt-get install zip unzip -y

RUN docker-php-ext-install mysqli pdo pdo_mysql && docker-php-ext-enable pdo_mysql

RUN mkdir /var/www/app

COPY ./.conf/000-default.conf /etc/apache2/sites-available/000-default.conf

COPY ./ /var/www/app

RUN a2enmod rewrite

RUN rmdir /var/www/html

RUN useradd -ms /bin/bash app && \
    usermod -a -G app app

RUN chown -R app:www-data /var/www/app && \
    chmod -R 775 /var/www/app

USER app
WORKDIR /var/www/app

COPY --from=composer /usr/bin/composer /usr/bin/composer
RUN composer update

RUN cp ./.env.example ./.env

RUN php artisan key:generate