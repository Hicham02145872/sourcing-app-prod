#!/bin/bash
# =============================================================================
# test-seed.sh — Seed the testing database with test data
# =============================================================================
set -e

echo "=== SmartSourcing E2E Test Seed ==="

# Wait for DB to be ready
echo "Waiting for MySQL..."
until php artisan db:monitor --timeout=1 2>/dev/null; do
  sleep 2
done
echo "MySQL is ready."

# Run migrations
echo "Running migrations..."
php artisan migrate:fresh --force

# Seed using the testing seeder
echo "Seeding database..."
php artisan db:seed --class=TestingSeeder --force 2>/dev/null || \
php artisan db:seed --force

# Cache config
echo "Caching configuration..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Generate application key if not set
php artisan key:generate --force 2>/dev/null || true

# Set permissions
chown -R www-data:www-data storage bootstrap/cache 2>/dev/null || true
chmod -R 775 storage bootstrap/cache 2>/dev/null || true

echo "=== Seed complete ==="
echo ""
echo "Test users created:"
echo "  Client:     test-client@example.com / password"
echo "  Admin:      test-admin@example.com / password"
echo "  Super Admin: test-superadmin@example.com / password"
