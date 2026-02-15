#!/bin/bash

# ==========================================
# 1. SETUP FOLDER & FILE WAJIB
# ==========================================
echo "🚀 Starting Deployment Script..."

# Pastikan folder documents ada
mkdir -p /app/documents
chmod -R 777 /app/documents

# Buat folder session
mkdir -p /tmp/sessions
chown -R nobody:nogroup /tmp/sessions
chmod -R 777 /tmp/sessions

# Pastikan conf.php ada
if [ ! -f "/app/htdocs/conf/conf.php" ]; then
    echo "⚠️ conf.php not found! Creating dummy..."
    mkdir -p /app/htdocs/conf
    touch /app/htdocs/conf/conf.php
fi

# ==========================================
# 2. SECURITY FIXES
# ==========================================
echo "🔒 Applying security fixes..."
touch /app/documents/install.lock
chown root:root /app/htdocs/conf/conf.php
chmod 0444 /app/htdocs/conf/conf.php

# ==========================================
# 3. GENERATE CONFIGURATION FILES
# ==========================================
echo "⚙️ Generating config files..."

# A. Buat Config PHP-FPM (User nobody + Session Fix)
cat > /app/php-fpm.conf <<EOF
[global]
error_log = /proc/self/fd/2
daemonize = yes
[www]
user = nobody
group = nogroup
listen = 127.0.0.1:9000
pm = dynamic
pm.max_children = 5
pm.start_servers = 2
pm.min_spare_servers = 1
pm.max_spare_servers = 3
clear_env = no
catch_workers_output = yes
php_admin_value[session.save_path] = /tmp/sessions
EOF

# B. Buat MIME TYPES
cat > /app/mime.types <<EOF
types {
    text/html                             html htm shtml;
    text/css                              css;
    text/xml                              xml;
    image/gif                             gif;
    image/jpeg                            jpeg jpg;
    application/javascript                js;
    application/atom+xml                  atom;
    application/rss+xml                   rss;
    text/plain                            txt;
    image/png                             png;
    image/svg+xml                         svg svgz;
    image/webp                            webp;
    application/font-woff                 woff;
    application/json                      json;
    application/pdf                       pdf;
    application/zip                       zip;
    application/octet-stream              bin exe dll;
}
EOF

# C. Buat FASTCGI PARAMS (HTTPS Fix)
cat > /app/fastcgi_params <<EOF
fastcgi_param  QUERY_STRING       \$query_string;
fastcgi_param  REQUEST_METHOD     \$request_method;
fastcgi_param  CONTENT_TYPE       \$content_type;
fastcgi_param  CONTENT_LENGTH     \$content_length;
fastcgi_param  SCRIPT_NAME        \$fastcgi_script_name;
fastcgi_param  REQUEST_URI        \$request_uri;
fastcgi_param  DOCUMENT_URI       \$document_uri;
fastcgi_param  DOCUMENT_ROOT      \$document_root;
fastcgi_param  SERVER_PROTOCOL    \$server_protocol;
fastcgi_param  REQUEST_SCHEME     https;
fastcgi_param  HTTPS              on;
fastcgi_param  GATEWAY_INTERFACE  CGI/1.1;
fastcgi_param  SERVER_SOFTWARE    nginx;
fastcgi_param  REMOTE_ADDR        \$remote_addr;
fastcgi_param  REMOTE_PORT        \$remote_port;
fastcgi_param  SERVER_ADDR        \$server_addr;
fastcgi_param  SERVER_PORT        \$server_port;
fastcgi_param  SERVER_NAME        \$server_name;
fastcgi_param  REDIRECT_STATUS    200;
EOF

# D. Buat Config NGINX Utama (REVISI BLOKIR)
cat > /app/nginx.conf <<EOF
worker_processes 1;
events { worker_connections 1024; }
http {
    include /app/mime.types;
    default_type application/octet-stream;
    sendfile on;
    keepalive_timeout 65;
    access_log /dev/stdout;
    error_log /dev/stderr;
    client_max_body_size 20M;

    server {
        listen ${PORT:-80};
        server_name _;
        root /app/htdocs;
        index index.php index.html;

        # --- [REVISI PENTING DISINI] ---
        # Hanya blokir conf dan documents (upload rahasia).
        # SAYA HAPUS 'includes', 'custom', dan 'install' dari daftar blokir.
        location ~ ^/(conf|documents)/ {
            deny all;
        }

        location / {
            try_files \$uri \$uri/ /index.php?\$args;
        }

        location ~ \.php$ {
            include /app/fastcgi_params;
            fastcgi_pass 127.0.0.1:9000;
            fastcgi_param SCRIPT_FILENAME \$document_root\$fastcgi_script_name;
        }
    }
}
EOF

# ==========================================
# 4. JALANKAN SERVICE
# ==========================================
echo "✅ Starting Services..."
php-fpm -y /app/php-fpm.conf
nginx -c /app/nginx.conf -g "daemon off;"