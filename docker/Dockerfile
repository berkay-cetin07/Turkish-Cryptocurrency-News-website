FROM php:8.2-apache

# Install system dependencies
RUN apt-get update && apt-get install -y \
    libxml2-dev \
    libonig-dev \
    iputils-ping \
    git \
    unzip \
    libgmp-dev \
    && rm -rf /var/lib/apt/lists/*

# Configure GMP
RUN ln -s /usr/include/x86_64-linux-gnu/gmp.h /usr/include/gmp.h

# Install PHP extensions
RUN docker-php-ext-install \
    pdo \
    pdo_mysql \
    xml \
    mbstring \
    bcmath \
    gmp

# Enable PHP extensions
RUN docker-php-ext-enable \
    pdo \
    pdo_mysql \
    mbstring \
    bcmath \
    gmp

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Set the working directory for the application
WORKDIR /var/www

# Copy composer.json only
COPY composer.json .

# Install dependencies and generate lock file
RUN composer install --no-scripts --no-autoloader

# Copy the rest of the application
COPY ./public/ /var/www/html/
COPY ./src/ /var/www/src/

# Generate autoloader
RUN composer dump-autoload --optimize

# Configure PHP
RUN echo "file_uploads = On\n" \
         "memory_limit = 2048M\n" \
         "upload_max_filesize = 64M\n" \
         "post_max_size = 64M\n" \
         "max_execution_time = 600\n" \
         > /usr/local/etc/php/conf.d/uploads.ini

# Enable Apache modules
RUN a2enmod rewrite

# Adjust Apache configuration
COPY ./docker/apache-php.conf /etc/apache2/sites-available/000-default.conf

# Set permissions
RUN chown -R www-data:www-data /var/www && \
    chmod -R 755 /var/www

# Make sure the uploads directory exists
RUN mkdir -p /var/www/html/uploads && \
    chmod 777 /var/www/html/uploads

# Configure Apache
RUN sed -i '/<Directory \/var\/www\/>/,/<\/Directory>/ s/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf

EXPOSE 80

CMD ["apache2-foreground"]