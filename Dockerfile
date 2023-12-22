# Usa una imagen de PHP con Apache
FROM php:8.0.30-apache-buster


# Configura el servidor Apache
RUN a2enmod rewrite
COPY ./apache-config.conf /etc/apache2/sites-available/000-default.conf

# Instala Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Configura el directorio de trabajo
WORKDIR /var/www/html

# Copia los archivos de la aplicación al contenedor
COPY . .

# Instala las dependencias utilizando Composer
RUN composer install --no-scripts --no-autoloader && \
    composer clear-cache && \
    rm -rf /var/www/html/vendor /var/www/html/composer.lock && \
    composer install --no-scripts


# Configura las variables de entorno para Laravel
COPY .env.example .env

# Expone el puerto 80
EXPOSE 80

# Inicia el servidor Apache
CMD ["apache2-foreground"]
