# Stage 1: Build dependencies
FROM php:8.2-fpm AS build

RUN apt-get update && apt-get install -y \
    unzip zip curl git libzip-dev \
    libpng-dev libjpeg-dev libfreetype6-dev \
    libonig-dev libxml2-dev \
    libpq-dev \
    && docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd zip

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www
COPY . .

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Stage 2: Runtime container
FROM php:8.2-fpm

# Copy PHP extensions from build container
COPY --from=build /usr/local/lib/php/extensions /usr/local/lib/php/extensions
COPY --from=build /usr/local/etc/php/conf.d /usr/local/etc/php/conf.d
COPY --from=build /usr/bin/composer /usr/bin/composer

# Copy app code
WORKDIR /var/www
COPY . .

# Copy Nginx config and startup
RUN apt-get update && apt-get install -y nginx
COPY nginx/default.conf /etc/nginx/conf.d/default.conf
COPY start-container.sh /start-container.sh
RUN chmod +x /start-container.sh

# Give permissions
RUN chmod -R 775 storage bootstrap/cache

EXPOSE 80
CMD ["/start-container.sh"]
