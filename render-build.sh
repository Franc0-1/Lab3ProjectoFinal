#!/usr/bin/env bash

# Exit on error
set -e

# Install dependencies
composer install --no-dev --optimize-autoloader

# Install NPM dependencies
npm install

# Build assets
npm run build

# Create storage link
php artisan storage:link

# Cache config
php artisan config:cache

# Cache routes
php artisan route:cache

# Cache views
php artisan view:cache

# Run migrations
php artisan migrate --force

# Clear any cached views that might be invalid
php artisan view:clear
