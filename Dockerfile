FROM php:8.0.11-fpm-alpine

RUN apk --no-cache upgrade && \
    apk --no-cache add bash git sudo openssh libxml2-dev oniguruma-dev autoconf gcc g++ make npm freetype-dev libjpeg-turbo-dev libpng-dev libzip-dev unixODBC-dev

# Install SQL Server PDO Driver
RUN set -eux; \
    apk add --no-cache --virtual .build-deps $PHPIZE_DEPS; \
    pecl install pdo_sqlsrv; \
    docker-php-ext-enable pdo_sqlsrv; \
    apk del .build-deps

# PHP: Install php extensions
RUN pecl channel-update pecl.php.net
RUN pecl install pcov ssh2 swoole
RUN docker-php-ext-configure gd --with-freetype --with-jpeg
RUN docker-php-ext-install mbstring xml iconv pcntl gd zip sockets bcmath soap pdo pdo_mysql pdo_sqlsrv
RUN docker-php-ext-enable mbstring xml gd iconv zip pcov pcntl sockets bcmath pdo pdo_mysql pdo_sqlsrv soap swoole

RUN curl -sS https://getcomposer.org/installer | php -- \
     --install-dir=/usr/local/bin --filename=composer

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
COPY --from=spiralscout/roadrunner:2.4.2 /usr/bin/rr /usr/bin/rr

WORKDIR /app
COPY . .
RUN rm -rf /app/vendor
RUN rm -rf /app/composer.lock
RUN composer install
RUN composer require laravel/octane spiral/roadrunner
COPY .env.example .env
RUN mkdir -p /app/storage/logs
RUN php artisan cache:clear
RUN php artisan view:clear
RUN php artisan config:clear
RUN php artisan octane:install --server="swoole"
CMD php artisan octane:start --server="swoole" --host="0.0.0.0"

EXPOSE 8000
