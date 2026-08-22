#!/bin/bash
# Deployment script for Material & Inventory Management System

echo "=========================================="
echo "DEPLOYMENT SCRIPT"
echo "=========================================="

# Database credentials
DB_HOST="127.0.0.1"
DB_PORT="3306"
DB_NAME="material_inventory"
DB_USER="root"
DB_PASSWORD="kasu@11@22"

# 1. Create database
echo "1. Creating database..."
mysql -u $DB_USER -p"$DB_PASSWORD" -e "CREATE DATABASE IF NOT EXISTS $DB_NAME;"

# 2. Import database
echo "2. Importing database..."
mysql -u $DB_USER -p"$DB_PASSWORD" $DB_NAME < material_inventory_full.sql

# 3. Update .env file
echo "3. Updating .env file..."
cd ..
sed -i "s/DB_DATABASE=.*/DB_DATABASE=$DB_NAME/" .env
sed -i "s/DB_USERNAME=.*/DB_USERNAME=$DB_USER/" .env
sed -i "s/DB_PASSWORD=.*/DB_PASSWORD=$DB_PASSWORD/" .env

# 4. Clear cache
echo "4. Clearing cache..."
php artisan optimize:clear

# 5. Set permissions
echo "5. Setting permissions..."
chmod -R 775 storage bootstrap/cache

echo ""
echo "=========================================="
echo "DEPLOYMENT COMPLETE!"
echo "=========================================="
echo ""
echo "Start server: php artisan serve --host=0.0.0.0 --port=8000"
echo ""
