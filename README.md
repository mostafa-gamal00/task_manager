git clone <repository-url>
cd task

# Install PHP dependencies
composer install

# Copy environment file
cp .env.example .env and connect your db

# Generate application key
php artisan key:generate

php artisan migrate:fresh --seed

php artisan jwt:secret

JWT_SECRET=your_generated_secret
JWT_TTL=60
JWT_REFRESH_TTL=20160

php artisan serve

