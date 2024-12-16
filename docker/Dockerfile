FROM php:8.2-apache

# Enable needed PHP extensions
RUN docker-php-ext-install pdo pdo_mysql && docker-php-ext-enable pdo pdo_mysql
RUN apt-get update && apt-get install -y libxml2-dev && docker-php-ext-install xml

# Enable Apache rewrite module
RUN a2enmod rewrite

# Copy application into webroot
COPY ./public/ /var/www/html/
COPY ./src/ /var/www/src/

# Set the working directory
WORKDIR /var/www/html

# Adjust Apache configuration
COPY ./docker/apache-php.conf /etc/apache2/sites-available/000-default.conf

# Set permissions
RUN chown -R www-data:www-data /var/www/html /var/www/src && chmod -R 755 /var/www/html /var/www/src

EXPOSE 80
CMD ["apache2-foreground"]
