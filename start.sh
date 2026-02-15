#!/bin/bash

# ==========================================
# 1. SETUP FOLDER & FILE WAJIB
# ==========================================
echo "🚀 Starting Deployment Script..."

# Pastikan folder documents ada (untuk Volume Railway)
mkdir -p /app/documents
chmod -R 777 /app/documents

# Pastikan conf.php ada
if [ ! -f "/app/htdocs/conf/conf.php" ]; then
    echo "⚠️ conf.php not found! Creating dummy..."
    mkdir -p /app/htdocs/conf
    touch /app/htdocs/conf/conf.php
fi

# ==========================================
# 2. SECURITY FIXES (Jurus Anti Warning)
# ==========================================
echo "🔒 Applying security fixes..."

# Buat install.lock agar tidak minta install ulang
touch /app/documents/install.lock

# KUNCI conf.php menjadi Read-Only (0444)
# Ini yang membuat warning kuning di dashboard HILANG
chmod 0444 /app/htdocs/conf/conf.php

# ==========================================
# 3. GENERATE CONFIGURATION (Solusi Error 502)
# ==========================================
echo "⚙️ Generating config files..."

# A. Buat Config PHP-FPM (Agar tidak error "No config file")
cat > /app/php-fpm.conf <<EOF
[global]
error_log = /proc/self/fd/2
daemonize = yes
[www]
user = root
group = root
listen = 127.0.0.1:9000
pm = dynamic
pm.max_children = 5
pm.start_servers = 2
pm.min_spare_servers = 1
pm.max_spare_servers = 3
clear_env = no
catch_workers_output = yes
EOF

# B. Buat Config NGINX (Agar Port $PORT terbaca benar)
# Kita buat server block yang aman dan efisien
cat > /app/nginx.conf <<EOF
worker_processes 1;
events { worker_connections 1024; }
http {
    include /etc/nginx/mime.types;
    default_type application/octet-stream;
    sendfile on;
    keepalive_timeout 65;
    
    # Log ke stdout agar muncul di Railway Logs
    access_log /dev/stdout;
    error_log /dev/stderr;

    server {
        listen ${PORT:-80};
        server_name _;
        root /app/htdocs;
        index index.php index.html;

        # Blokir akses ke folder sensitif
        location ~ ^/(conf|includes|install|custom|documents)/ {
            deny all;
        }

        location / {
            try_files \$uri \$uri/ /index.php?\$args;
        }

        # Handler PHP
        location ~ \.php$ {
            include fastcgi_params;
            fastcgi_pass 127.0.0.1:9000;
            fastcgi_param SCRIPT_FILENAME \$document_root\$fastcgi_script_name;
            fastcgi_param HTTPS 'on'; 
        }
    }
}
EOF

# ==========================================
# 4. JALANKAN SERVICE
# ==========================================
echo "✅ Starting Services..."

# Jalankan PHP-FPM dengan config buatan kita (-y) dan izinkan root (-R)
php-fpm -y /app/php-fpm.conf -R

# Jalankan Nginx dengan config buatan kita (-c)
nginx -c /app/nginx.conf -g "daemon off;"