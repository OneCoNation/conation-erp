#!/bin/bash
set -e

echo "=== Dolibarr Railway Startup Script ==="

# Create documents directory if it doesn't exist
mkdir -p /app/documents
chmod 777 /app/documents

# Copy Railway-specific conf.php
echo "Setting up conf.php for Railway..."
cp /app/railway-conf.php /app/htdocs/conf/conf.php
chmod 644 /app/htdocs/conf/conf.php

echo "conf.php configured successfully!"
echo "Document root: /app/htdocs"
echo "Data root: /app/documents"
echo "DB Host: ${DOLI_DB_HOST}"
echo "DB Name: ${DOLI_DB_NAME}"

# Check if Dolibarr install lock exists
if [ -f "/app/documents/install.lock" ]; then
    echo "Dolibarr is already installed (install.lock found)"
else
    echo "WARNING: Dolibarr installation not complete yet."
    echo "Please visit https://${RAILWAY_PUBLIC_DOMAIN}/install/ to complete setup."
    echo "After installation, an install.lock file will be created."
fi

# Start PHP built-in server
echo "Starting PHP server on port ${PORT:-8080}..."
exec php -S 0.0.0.0:${PORT:-8080} -t /app/htdocs
