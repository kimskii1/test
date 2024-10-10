FROM php:8.3-fpm

WORKDIR /app
CMD ["php-fpm"]

RUN rm -Rf /var/www/html \
    && apt-get update && apt-get install -y libpq-dev librabbitmq-dev libzip-dev zip libpng-dev libicu-dev libxml2-dev \
    && pecl install amqp xdebug \
    && docker-php-ext-configure intl \
    && docker-php-ext-install mysqli pgsql pdo pdo_mysql pdo_pgsql zip gd intl pcntl bcmath soap \
    && docker-php-ext-enable amqp

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php composer-setup.php \
    && php -r "unlink('composer-setup.php');" \
    && mv composer.phar /usr/local/bin/composer

COPY ./ /app

RUN composer install

EXPOSE 9000
