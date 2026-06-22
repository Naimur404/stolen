#!/usr/bin/env sh
set -e

cd /var/www/html

# Discover packages now that the full runtime (with DB driver + env) is ready.
php artisan package:discover --ansi || true

# Make sure storage symlink exists (ignore if already linked)
php artisan storage:link 2>/dev/null || true

# Run database migrations on boot. Remove this if you prefer to migrate manually.
php artisan migrate --force || true

# Cache configuration, routes and views for production performance.
# These are rebuilt on every container start so new env values are picked up.
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Fix permissions on writable dirs (Coolify may mount volumes here)
chown -R www-data:www-data storage bootstrap/cache || true

exec "$@"
