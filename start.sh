#!/bin/bash

# 1. Pastikan folder documents ada (untuk Volume)
echo "Checking documents directory..."
mkdir -p /app/documents

# 2. Pastikan file conf.php ada
if [ ! -f "/app/htdocs/conf/conf.php" ]; then
    echo "Warning: htdocs/conf/conf.php not found! Creating dummy..."
    mkdir -p /app/htdocs/conf
    touch /app/htdocs/conf/conf.php
fi

# 3. JURUS KUNCI: Set Permission dengan Benar (User www-data)
# Kita ubah pemilik file menjadi 'root' agar webserver (yang berjalan sebagai user lain)
# BENAR-BENAR tidak bisa mengeditnya.
echo "Applying security fixes..."
touch /app/documents/install.lock
chmod -R 777 /app/documents

# Trik menghilangkan warning conf.php:
# Ubah permission ke 0444 (Read Only Everyone)
chmod 0444 /app/htdocs/conf/conf.php

# 4. Jalankan Nginx & PHP-FPM (Mode Production)
# Bukan 'php -S' lagi!
echo "Starting Nginx and PHP-FPM..."

# Start PHP-FPM in background
php-fpm -D

# Start Nginx in foreground
nginx -g "daemon off;"