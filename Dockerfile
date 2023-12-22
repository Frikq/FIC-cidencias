# Usa una imagen de PHP con Apache
FROM php:8.0.30-apache-buster


# Configura el servidor Apache
RUN a2enmod rewrite
COPY ./apache-config.conf /etc/apache2/sites-available/000-default.conf

# Instala Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Configura el directorio de trabajo
WORKDIR /var/www/html

# Copia los archivos de la aplicación al contenedor
COPY . .


# Configura las variables de entorno para Laravel
COPY .env.example .env
RUN php artisan key:generate

# Expone el puerto 80
EXPOSE 80

# Inicia el servidor Apache
CMD ["apache2-foreground"]
