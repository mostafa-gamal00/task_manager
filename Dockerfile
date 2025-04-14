FROM php:8.2-fpm

# Install dependencies
RUN apt-get update && apt-get install -y \
    nginx \
    zip unzip curl git \
    libzip-dev \
    libpng-dev libjpeg-dev libfreetype6-dev \
    libonig-dev libxml2-dev \
    && docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd zip

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy app code
COPY . .

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Copy nginx config
COPY nginx/default.conf /etc/nginx/conf.d/default.conf

# Give permissions to Laravel folders
RUN chmod -R 775 storage bootstrap/cache

# Expose port
EXPOSE 80

# Start both nginx and php-fpm
COPY start-container.sh /start-container.sh
RUN chmod +x /start-container.sh
CMD ["/start-container.sh"]
