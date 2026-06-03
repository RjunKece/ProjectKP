#!/bin/bash
set -e

echo "========================================"
echo "  Starting Laravel ERP Prototype"
echo "========================================"

# Safety: remove Vite hot file if it exists
# If present, Laravel loads assets from dev server instead of public/build
rm -f public/hot

# Ensure correct permissions
echo "[1/6] Setting permissions..."
chmod -R 775 storage bootstrap/cache 2>/dev/null || true
chown -R www-data:www-data storage bootstrap/cache 2>/dev/null || true

# Create storage link for file uploads
echo "[2/6] Creating storage link..."
php artisan storage:link --force 2>/dev/null || true

# Clear ALL old caches to prevent stale config/session conflicts
# IMPORTANT: Do NOT run config:cache / route:cache / view:cache here!
# Render injects ENV vars at runtime, NOT via .env file.
echo "[3/6] Clearing all caches..."
php artisan optimize:clear
php artisan view:clear
php artisan config:clear
php artisan cache:clear

# Clear stale session files to prevent 419 Page Expired errors
echo "  Clearing stale sessions..."
rm -rf storage/framework/sessions/* 2>/dev/null || true
mkdir -p storage/framework/sessions

if [ "${APP_ENV}" = "production" ]; then
    echo "  Caching routes (production)..."
    php artisan route:cache 2>/dev/null || true
fi

# Ensure APP_KEY exists — generate one if missing
if [ -z "$APP_KEY" ]; then
    echo "  ⚠ APP_KEY not set, generating..."
    php artisan key:generate --force 2>/dev/null || true
fi

# Run migrations (fail loudly in production so deploy issues are visible)
echo "[4/6] Running database migrations..."
if ! php artisan migrate --force; then
    echo "  ✗ Migration failed — check DB_HOST, DB_PASSWORD, DB_SSLMODE"
    exit 1
fi

# Optional one-time cleanup of dummy operational data (set ERP_CLEAN_OPERATIONAL_ON_BOOT=true)
if [ "$ERP_CLEAN_OPERATIONAL_ON_BOOT" = "true" ]; then
    echo "[4b/6] Cleaning operational data (activities, reports)..."
    php artisan erp:clean-operational-data --force --seed-demo || true
fi

# Seed base data if tables are empty (safe for first deploy)
echo "[5/6] Checking seeder..."
php artisan db:seed --force 2>/dev/null || true

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
echo "[6/6] Starting server..."
exec php artisan serve --host=0.0.0.0 --port=${PORT:-10000}
