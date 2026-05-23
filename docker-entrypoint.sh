#!/bin/bash
set -e

echo "========================================"
echo "  Starting Laravel ERP Prototype"
echo "========================================"

# Safety: remove Vite hot file if it exists
# If present, Laravel loads assets from dev server instead of public/build
rm -f public/hot

# Ensure correct permissions
echo "[1/5] Setting permissions..."
chmod -R 775 storage bootstrap/cache 2>/dev/null || true
chown -R www-data:www-data storage bootstrap/cache 2>/dev/null || true

# Create storage link for file uploads
echo "[2/5] Creating storage link..."
php artisan storage:link --force 2>/dev/null || true

# Clear ALL old caches to prevent stale config/session conflicts
# IMPORTANT: Do NOT run config:cache / route:cache / view:cache here!
# Render injects ENV vars at runtime, NOT via .env file.
# Running config:cache would bake empty/wrong values and cause 419 errors.
echo "[3/5] Clearing all caches..."
php artisan optimize:clear 2>/dev/null || true

# Ensure APP_KEY exists — generate one if missing
if [ -z "$APP_KEY" ]; then
    echo "  ⚠ APP_KEY not set, generating..."
    php artisan key:generate --force 2>/dev/null || true
fi

# Run migrations
echo "[4/5] Running database migrations..."
php artisan migrate --force 2>/dev/null || echo "  ⚠ Migration skipped (check DB connection)"

# Verify build assets exist
if [ -d "public/build" ]; then
    echo "  ✅ Vite assets found in public/build"
else
    echo "  ⚠ WARNING: public/build not found — CSS/JS may not load!"
fi

echo "========================================"
echo "  ✅ Application ready on port ${PORT:-10000}"
echo "  Environment: ${APP_ENV:-production}"
echo "  URL: ${APP_URL:-http://localhost}"
echo "========================================"

# Start Laravel server
echo "[5/5] Starting server..."
exec php artisan serve --host=0.0.0.0 --port=${PORT:-10000}
