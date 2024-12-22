FROM php:8.2-apache

# Install system dependencies
RUN apt-get update && apt-get install -y \
    libxml2-dev libonig-dev iputils-ping git unzip libgmp-dev && \
    rm -rf /var/lib/apt/lists/*

# Configure GMP and PHP extensions
RUN ln -s /usr/include/x86_64-linux-gnu/gmp.h /usr/include/gmp.h
RUN docker-php-ext-install pdo pdo_mysql mbstring bcmath gmp opcache

# Copy application files
WORKDIR /var/www
COPY ./public /var/www/html
COPY ./src /var/www/src

# Configure PHP and OPCache
COPY ./docker/opcache.ini /usr/local/etc/php/conf.d/opcache.ini
RUN echo "file_uploads = On\n" \
         "memory_limit = 2048M\n" \
         "upload_max_filesize = 64M\n" \
         "post_max_size = 64M\n" \
         "max_execution_time = 600\n" \
         > /usr/local/etc/php/conf.d/uploads.ini

# Enable Apache modules
RUN a2enmod rewrite

# Configure Apache
COPY ./docker/apache-php.conf /etc/apache2/sites-available/000-default.conf

# Ensure uploads directory exists with proper permissions
RUN mkdir -p /var/www/html/uploads && \
    chmod 777 /var/www/html/uploads

# Expose port 80 for Apache
EXPOSE 80

# Start Apache
CMD ["apache2-foreground"]
