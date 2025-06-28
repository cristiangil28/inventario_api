# Imagen base oficial de PHP con Apache
FROM php:8.2-apache

# Asegura que todos los paquetes estén actualizados
RUN apt-get update && apt-get upgrade -y

# Instala dependencias del sistema
RUN apt-get install -y \
    git unzip zip curl libzip-dev libpng-dev libonig-dev libxml2-dev \
    && docker-php-ext-install pdo pdo_mysql zip mbstring exif pcntl bcmath

# Instala Composer
COPY --from=composer:2.6 /usr/bin/composer /usr/bin/composer

# Copia el código del proyecto Laravel
COPY . /var/www/html

# Establece el directorio de trabajo
WORKDIR /var/www/html

# Configura Apache para que sirva desde /public
RUN echo '<VirtualHost *:80>\n\
    DocumentRoot /var/www/html/public\n\
    <Directory /var/www/html/public>\n\
        Options Indexes FollowSymLinks\n\
        AllowOverride All\n\
        Require all granted\n\
    </Directory>\n\
</VirtualHost>' > /etc/apache2/sites-available/000-default.conf

# Activa mod_rewrite de Apache
RUN a2enmod rewrite

# Asigna permisos correctos
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage

# Instala dependencias PHP
RUN composer install --optimize-autoloader --no-dev

# Expone el puerto 80
EXPOSE 80

# Comando de arranque por defecto
CMD ["apache2-foreground"]
