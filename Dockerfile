FROM php:8.2-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libonig-dev \
    libxml2-dev \
    zip unzip \
    curl git \
    libzip-dev \
    libpq-dev \
    && docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd zip

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy files
COPY . .

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Laravel stuff
RUN php artisan config:cache \
 && php artisan route:cache \
 && php artisan view:cache

# Expose port
EXPOSE 8000

# Start app with Laravel's built-in web server
CMD php artisan serve --host=0.0.0.0 --port=${PORT}
