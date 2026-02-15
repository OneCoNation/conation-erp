#!/bin/bash

# 1. Pastikan folder documents ada (untuk Volume)
echo "Checking documents directory..."
mkdir -p /app/documents

# 2. Sinkronisasi File Konfigurasi
# Kita pastikan conf.php berada di tempat yang benar (htdocs/conf/conf.php)
if [ -f "/app/htdocs/conf/conf.php" ]; then
    echo "Using existing htdocs/conf/conf.php"
else
    echo "Warning: htdocs/conf/conf.php not found! Creating from environment..."
    # Jika file tidak ada, kita buat folder dan file kosong agar tidak error
    mkdir -p /app/htdocs/conf
    touch /app/htdocs/conf/conf.php
fi

# 3. Auto-Heal Security (Fix Permission & Install Lock)
echo "Applying security fixes..."
touch /app/documents/install.lock
chmod -R 777 /app/documents
chmod 444 /app/htdocs/conf/conf.php || true

# 4. Jalankan Service Utama (Nginx & PHP-FPM)
# Nixpacks biasanya menggunakan perintah start bawaan, 
# tapi jika Anda menggunakan start.sh manual, jalankan perintah ini:

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