
# Install PHP dependencies
composer install --no-dev --optimize-autoloader

# Copy environment file
cp .env.example .env and connect your db

# Generate application key
php artisan key:generate

php artisan migrate:fresh --seed

php artisan jwt:secret



