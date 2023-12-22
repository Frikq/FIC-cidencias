FROM php:8.0.11-apache

RUN apk --no-cache update && \
    apk --no-cache add \
        libzip-dev \
        libonig-dev \
        libxml2-dev \
        unixODBC-dev \
        unzip \
        freetype-dev \
        libjpeg-turbo-dev \
        libpng-dev


# Enable necessary Apache modules
RUN a2enmod rewrite

# Install required PHP extensions
RUN docker-php-ext-install pdo pdo_mysql mbstring zip pcntl soap

# Install SQL Server PDO Driver
RUN set -eux; \
    docker-php-ext-configure pdo_sqlsrv --with-pdo-sqlsrv=unixODBC,/usr; \
    docker-php-ext-install pdo_sqlsrv

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www/html

COPY . .

# Install dependencies
RUN composer install

# Set appropriate permissions
RUN chown -R www-data:www-data storage bootstrap/cache

# Copy Apache virtual host configuration
COPY apache.conf /etc/apache2/sites-available/000-default.conf

# Expose port 80
EXPOSE 80

CMD ["apache2-foreground"]
