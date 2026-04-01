#!/bin/bash

# CVBoost.id Deployment Script for Alwaysdata
# Usage: ./deploy.sh [environment]

set -e

ENVIRONMENT=${1:-production}
PROJECT_NAME="cvboost-id"
DOMAIN="yourdomain.alwaysdata.net"

echo "🚀 Deploying CVBoost.id to Alwaysdata..."
echo "Environment: $ENVIRONMENT"
echo "Domain: $DOMAIN"

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Function to print colored output
print_status() {
    echo -e "${GREEN}[INFO]${NC} $1"
}

print_warning() {
    echo -e "${YELLOW}[WARNING]${NC} $1"
}

print_error() {
    echo -e "${RED}[ERROR]${NC} $1"
}

# Check if we're in the right directory
if [ ! -f "artisan" ]; then
    print_error "Not in Laravel project root directory!"
    exit 1
fi

print_status "Step 1: Installing dependencies..."
composer install --no-dev --optimize-autoloader --no-interaction

print_status "Step 2: Caching configuration..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

print_status "Step 3: Running database migrations..."
php artisan migrate --force --no-interaction

print_status "Step 4: Seeding templates..."
php artisan db:seed --class=TemplateSeeder --no-interaction

print_status "Step 5: Setting up storage..."
php artisan storage:link
mkdir -p storage/app/public/pdfs
chmod -R 755 storage

print_status "Step 6: Optimizing application..."
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear

print_status "Step 7: Setting file permissions..."
chmod -R 755 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

print_status "Step 8: Generating sitemap..."
php artisan sitemap:generate

if [ "$ENVIRONMENT" = "production" ]; then
    print_status "Step 9: Production optimizations..."
    php artisan config:cache
    php artisan route:cache
    php artisan view:cache
    php artisan optimize:clear
fi

print_status "Step 10: Testing application..."
php artisan about --no-interaction

print_status "✅ Deployment completed successfully!"
echo ""
echo "📋 Next steps:"
echo "1. Update your .env file with production values"
echo "2. Set up cron jobs for scheduled tasks"
echo "3. Configure SSL certificate"
echo "4. Test the application at https://$DOMAIN"
echo ""
echo "🔧 Cron job to set up (in Alwaysdata Task Scheduler):"
echo "* * * * * cd /home/youraccount/www/$PROJECT_NAME && php artisan schedule:run >> /dev/null 2>&1"
echo ""
echo "🌐 Application URLs:"
echo "Main: https://$DOMAIN"
echo "Admin: https://$DOMAIN/admin"
echo "API: https://$DOMAIN/api"
echo ""
echo "📊 Monitoring:"
echo "Logs: /home/youraccount/logs/$PROJECT_NAME/"
echo "Queue: php artisan queue:failed"
echo ""
