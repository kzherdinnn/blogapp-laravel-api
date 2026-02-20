#!/bin/bash

# Run database migrations
php artisan migrate --force

# Create storage symlink (required for /storage/... URLs to work)
rm -rf public/storage
ln -s /app/storage/app/public public/storage
echo "Storage link created."

# Start the Laravel server
php artisan serve --host=0.0.0.0 --port=$PORT
