#!/bin/bash
set -e

# Copy .env if not exists
if [ ! -f .env ]; then
    cp .env.example .env
    php artisan key:generate
fi

# Wait for database
echo "Waiting for database..."
while ! php artisan db:monitor --databases=mysql 2>/dev/null; do
    sleep 2
done
echo "Database is ready!"

# Run migrations
php artisan migrate --force --seed

# Create storage link
php artisan storage:link 2>/dev/null || true

# Clear and cache config
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Set permissions
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache

echo "========================================="
echo "  CloudDrive is running on port 8000"
echo "  phpMyAdmin on port 8080"
echo "========================================="

# Start supervisor (nginx + php-fpm)
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
