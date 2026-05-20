#!/bin/bash
set -e

echo "========================================"
echo "  Starting Laravel ERP Prototype"
echo "========================================"

# Safety: remove Vite hot file if it exists
# If present, Laravel loads assets from dev server instead of public/build
rm -f public/hot

# Ensure correct permissions
echo "[1/4] Setting permissions..."
chmod -R 775 storage bootstrap/cache 2>/dev/null || true
chown -R www-data:www-data storage bootstrap/cache 2>/dev/null || true

# Clear ALL old caches to prevent stale config/session conflicts
# IMPORTANT: Do NOT run config:cache / route:cache / view:cache here!
# Render injects ENV vars at runtime, NOT via .env file.
# Running config:cache would bake empty/wrong values and cause 419 errors.
echo "[2/4] Clearing all caches..."
php artisan optimize:clear

# Run migrations
echo "[3/4] Running database migrations..."
php artisan migrate --force 2>/dev/null || echo "  ⚠ Migration skipped (check DB connection)"

echo "========================================"
echo "  ✅ Application ready on port ${PORT:-10000}"
echo "========================================"

# Start Laravel server
echo "[4/4] Starting server..."
exec php artisan serve --host=0.0.0.0 --port=${PORT:-10000}
