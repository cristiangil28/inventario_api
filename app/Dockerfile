# Imagen base oficial de PHP con Apache
FROM php:8.2-apache

# Instala dependencias del sistema
RUN apt-get update && apt-get install -y \
    git unzip zip curl libzip-dev libpng-dev libonig-dev libxml2-dev \
    && docker-php-ext-install pdo pdo_mysql zip mbstring exif pcntl bcmath

# Instala Composer
COPY --from=composer:2.6 /usr/bin/composer /usr/bin/composer

# Copia el código al contenedor
COPY . /var/www/html

# Establece directorio de trabajo
WORKDIR /var/www/html

# Copia archivo personalizado de configuración de Apache si se desea (opcional)
# COPY ./docker/apache/vhost.conf /etc/apache2/sites-available/000-default.conf

# Da permisos y limpia
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage

# Activa mod_rewrite de Apache
RUN a2enmod rewrite

# Instala dependencias PHP
RUN composer install --optimize-autoloader --no-dev

# Genera la clave (opcional, puede hacerse vía build.sh si se desea)
# RUN php artisan key:generate

# Expone el puerto 80
EXPOSE 80
