
# Install PHP dependencies
composer install --no-dev --optimize-autoloader

# Copy environment file
cp .env.example .env


# Generate application key
RUN php artisan migrate

RUN php artisan migrate:fresh --seed

RUN php artisan jwt:secret



