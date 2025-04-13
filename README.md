
# Install PHP dependencies
composer install

# Copy environment file
cp .env.example .env and connect your db

# Generate application key
php artisan key:generate

php artisan migrate:fresh --seed

php artisan jwt:secret



