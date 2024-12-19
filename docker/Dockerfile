FROM php:8.2-apache

# Enable needed PHP extensions
RUN docker-php-ext-install pdo pdo_mysql && docker-php-ext-enable pdo pdo_mysql
RUN apt-get update && apt-get install -y libxml2-dev && docker-php-ext-install xml

# Increase upload limits in PHP
RUN echo "file_uploads = On\n" \
         "memory_limit = 256M\n" \
         "upload_max_filesize = 64M\n" \
         "post_max_size = 64M\n" \
         "max_execution_time = 600\n" \
         > /usr/local/etc/php/conf.d/uploads.ini

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

# Make sure the uploads directory exists and has proper permissions
RUN mkdir -p /var/www/html/uploads && \
    chown -R www-data:www-data /var/www/html/uploads && \
    chmod 777 /var/www/html/uploads

# Add this to your Dockerfile
RUN a2enmod rewrite

# Make sure .htaccess files are allowed
RUN sed -i '/<Directory \/var\/www\/>/,/<\/Directory>/ s/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf
EXPOSE 80
CMD ["apache2-foreground"]
