#!/bin/bash

# Production Deployment Script for ftwodev.id/mart
# Run this script before uploading to hosting

echo "🚀 Starting Production Deployment Preparation..."

# Step 1: Clear all caches
echo "📦 Clearing caches..."
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear

# Step 2: Optimize for production
echo "⚡ Optimizing application..."
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize

# Step 3: Build frontend assets
echo "🎨 Building frontend assets..."
npm install
npm run build

# Step 4: Create storage link if not exists
echo "🔗 Creating storage link..."
php artisan storage:link

# Step 5: Set proper permissions
echo "🔒 Setting file permissions..."
chmod -R 755 storage
chmod -R 755 bootstrap/cache

# Step 6: Export database for migration
echo "🗄️ Exporting database..."
mysqldump -u root -p piciabakery > database_backup.sql

echo "✅ Deployment preparation complete!"
echo ""
echo "📋 Next steps:"
echo "1. Upload all files to hosting (except node_modules, .env, tests)"
echo "2. Create .env file on hosting using .env.production.example"
echo "3. Import database_backup.sql to hosting database"
echo "4. Run 'php artisan key:generate' on hosting"
echo "5. Run 'php artisan migrate --force' on hosting"
echo "6. Run 'php artisan db:seed --force' on hosting (if needed)"
echo "7. Set proper permissions on hosting"
echo ""
echo "📖 See DEPLOYMENT_GUIDE.md for detailed instructions"
