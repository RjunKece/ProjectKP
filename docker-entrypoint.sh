#!/bin/bash
set -e

echo "========================================"
echo "  Starting Laravel ERP Prototype"
echo "========================================"

# Copy .env from example if not exists
if [ ! -f .env ]; then
    echo "[1/7] Creating .env from .env.example..."
    cp .env.example .env
fi

# Generate app key if not set
if [ -z "$APP_KEY" ]; then
    echo "[2/7] Generating application key..."
    php artisan key:generate --force
else
    echo "[2/7] APP_KEY already set via environment"
fi

# Ensure correct permissions
echo "[3/7] Setting permissions..."
chmod -R 775 storage bootstrap/cache 2>/dev/null || true
chown -R www-data:www-data storage bootstrap/cache 2>/dev/null || true

# Clear ALL old caches first to prevent stale config conflicts
echo "[4/7] Clearing old caches..."
php artisan optimize:clear

# Rebuild caches for production performance
echo "[5/7] Caching configuration for production..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Run migrations
echo "[6/7] Running database migrations..."
php artisan migrate --force 2>/dev/null || echo "  ⚠ Migration skipped (check DB connection)"

# Seed if needed (only if users table is empty)
echo "[7/7] Checking database seeding..."
php artisan db:seed --force 2>/dev/null || echo "  ⚠ Seeding skipped"

echo "========================================"
echo "  ✅ Application ready on port ${PORT:-10000}"
echo "========================================"

# Start Laravel server
exec php artisan serve --host=0.0.0.0 --port=${PORT:-10000}
