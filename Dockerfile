# Usa una imagen de PHP con Apache
FROM php:8.0.30-apache

# Instala las dependencias necesarias
RUN apt-get update && \
    apt-get install -y \
        libzip-dev \
        unzip \
        libicu-dev \
        freetds-dev \
        && docker-php-ext-configure pdo_dblib --with-libdir=/lib/x86_64-linux-gnu \
        && docker-php-ext-install pdo_dblib \
        && docker-php-ext-install zip \
        && docker-php-ext-install pdo pdo_mysql intl

# Instala las extensiones necesarias para SQL Server
RUN pecl install sqlsrv pdo_sqlsrv \
    && docker-php-ext-enable sqlsrv pdo_sqlsrv

# Configura el servidor Apache
RUN a2enmod rewrite
COPY ./apache-config.conf /etc/apache2/sites-available/000-default.conf

# Instala Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Configura el directorio de trabajo
WORKDIR /var/www/html

# Copia los archivos de la aplicación al contenedor
COPY . .

# Instala las dependencias de Composer
RUN composer install

# Configura las variables de entorno para Laravel
COPY .env.example .env
RUN php artisan key:generate

# Expone el puerto 80
EXPOSE 80

# Inicia el servidor Apache
CMD ["apache2-foreground"]
