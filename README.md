
# Install PHP dependencies
composer install

# Copy environment file
cp .env.example .env 

# Generate application key
php artisan key:generate

php artisan migrate:fresh --seed

php artisan jwt:secret



