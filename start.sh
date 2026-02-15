#!/bin/bash

# ==========================================
# 1. SETUP FOLDER & FILE WAJIB
# ==========================================
echo "🚀 Starting Deployment Script..."

# Pastikan folder documents ada
mkdir -p /app/documents
chmod -R 777 /app/documents

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

# Install Lock & Permission
touch /app/documents/install.lock
chmod 0444 /app/htdocs/conf/conf.php

# ==========================================
# 3. GENERATE CONFIGURATION FILES
# ==========================================
echo "⚙️ Generating config files..."

# A. Buat Config PHP-FPM
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

# B. Buat MIME TYPES Manual (Solusi Error Anda)
# Kita buat file ini agar Nginx tahu cara membaca CSS/JS/Gambar
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
    text/mathml                           mml;
    text/plain                            txt;
    text/vnd.sun.j2me.app-descriptor      jad;
    text/vnd.wap.wml                      wml;
    text/x-component                      htc;
    image/png                             png;
    image/tiff                            tif tiff;
    image/vnd.wap.wbmp                    wbmp;
    image/x-icon                          ico;
    image/x-jng                           jng;
    image/bmp                             bmp;
    image/svg+xml                         svg svgz;
    image/webp                            webp;
    application/font-woff                 woff;
    application/java-archive              jar war ear;
    application/json                      json;
    application/mac-binhex40              hqx;
    application/msword                    doc;
    application/pdf                       pdf;
    application/postscript                ps eps ai;
    application/rtf                       rtf;
    application/vnd.apple.mpegurl         m3u8;
    application/vnd.ms-excel              xls;
    application/vnd.ms-fontobject         eot;
    application/vnd.ms-powerpoint         ppt;
    application/vnd.wap.wmlc              wmlc;
    application/vnd.google-earth.kml+xml  kml;
    application/vnd.google-earth.kmz      kmz;
    application/x-7z-compressed           7z;
    application/x-cocoa                   cco;
    application/x-java-archive-diff       jardiff;
    application/x-java-jnlp-file          jnlp;
    application/x-makeself                run;
    application/x-perl                    pl pm;
    application/x-pilot                   prc pdb;
    application/x-rar-compressed          rar;
    application/x-redhat-package-manager  rpm;
    application/x-sea                     sea;
    application/x-shockwave-flash         swf;
    application/x-stuffit                 sit;
    application/x-tcl                     tcl tk;
    application/x-x509-ca-cert            der pem crt;
    application/x-xpinstall               xpi;
    application/xhtml+xml                 xhtml;
    application/xspf+xml                  xspf;
    application/zip                       zip;
    application/octet-stream              bin exe dll;
    application/octet-stream              deb;
    application/octet-stream              dmg;
    application/octet-stream              iso img;
    application/octet-stream              msi msp msm;
    audio/midi                            mid midi kar;
    audio/mpeg                            mp3;
    audio/ogg                             ogg;
    audio/x-m4a                           m4a;
    audio/x-realaudio                     ra;
    video/3gpp                            3gpp 3gp;
    video/mp2t                            ts;
    video/mp4                             mp4;
    video/mpeg                            mpeg mpg;
    video/quicktime                       mov;
    video/webm                            webm;
    video/x-flv                           flv;
    video/x-m4v                           m4v;
    video/x-mng                           mng;
    video/x-ms-asf                        asx asf;
    video/x-ms-wmv                        wmv;
    video/x-msvideo                       avi;
}
EOF

# C. Buat Config NGINX (Arahkan ke MIME yg baru dibuat)
cat > /app/nginx.conf <<EOF
worker_processes 1;
events { worker_connections 1024; }
http {
    # Arahkan ke file mime.types yang baru kita buat di /app
    include /app/mime.types;
    
    default_type application/octet-stream;
    sendfile on;
    keepalive_timeout 65;
    
    access_log /dev/stdout;
    error_log /dev/stderr;

    server {
        listen ${PORT:-80};
        server_name _;
        root /app/htdocs;
        index index.php index.html;

        # Blokir akses folder sensitif
        location ~ ^/(conf|includes|install|custom|documents)/ {
            deny all;
        }

        location / {
            try_files \$uri \$uri/ /index.php?\$args;
        }

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
php-fpm -y /app/php-fpm.conf -R
nginx -c /app/nginx.conf -g "daemon off;"