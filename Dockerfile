# Use the official PHP-FPM image as the base image
FROM php:8.1-fpm

# Set working directory
WORKDIR /var/www

# Install dependencies
RUN apt-get update && apt-get install -y \
    git \
    zip \
    unzip \
    && docker-php-ext-install pdo pdo_mysql

# Copy Laravel files to the container
COPY . /var/www

# Install Composer (a PHP package manager)
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install Laravel dependencies
RUN composer install

# Expose port 9000 (PHP-FPM port)
EXPOSE 9000

# Start PHP-FPM server
CMD ["php-fpm"]
