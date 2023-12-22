FROM php:8.0.11-fpm-alpine

# Install system dependencies
RUN apk --no-cache update && \
    apk --no-cache add \
        libzip-dev \
        libonig-dev \
        libxml2-dev \
        unixodbc-dev \
        unzip \
    && rm -rf /var/cache/apk/*

# Install additional dependencies for PHP extensions
RUN apk --no-cache add \
        freetype-dev \
        libjpeg-turbo-dev \
        libpng-dev \
    && rm -rf /var/cache/apk/*

# Enable necessary PHP extensions
RUN docker-php-ext-install pdo pdo_mysql mbstring zip pcntl soap

# Install SQL Server PDO Driver
RUN set -eux; \
    apk --no-cache add --virtual .build-deps unixodbc-dev; \
    docker-php-ext-configure pdo_sqlsrv --with-pdo-sqlsrv=unixODBC,/usr; \
    docker-php-ext-install pdo_sqlsrv; \
    apk del .build-deps

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www/html

COPY . .

# Install dependencies
RUN composer install

# Set appropriate permissions
RUN chown -R www-data:www-data storage bootstrap/cache

# Expose port 9000 for php-fpm
EXPOSE 9000

CMD ["php-fpm"]
