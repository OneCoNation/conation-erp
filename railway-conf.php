<?php
//
// Dolibarr configuration file for Railway deployment
// Auto-configured using environment variables
//

// Main URL - auto-detect from Railway
$dolibarr_main_url_root='https://'.getenv('RAILWAY_PUBLIC_DOMAIN');
$dolibarr_main_document_root='/app/htdocs';
$dolibarr_main_url_root_alt='/custom';
$dolibarr_main_document_root_alt='/app/htdocs/custom';
$dolibarr_main_data_root='/app/documents';

// Database configuration from Railway env vars
$dolibarr_main_db_host=getenv('DOLI_DB_HOST') ?: 'mysql.railway.internal';
$dolibarr_main_db_port=getenv('DOLI_DB_PORT') ?: '3306';
$dolibarr_main_db_name=getenv('DOLI_DB_NAME') ?: 'railway';
$dolibarr_main_db_prefix='llx_';
$dolibarr_main_db_user=getenv('DOLI_DB_USER') ?: 'root';
$dolibarr_main_db_pass=getenv('DOLI_DB_PASSWORD') ?: '';
$dolibarr_main_db_type='mysqli';
$dolibarr_main_db_character_set='utf8';
$dolibarr_main_db_collation='utf8_unicode_ci';

// Authentication settings
$dolibarr_main_authentication='dolibarr';

// Security settings
$dolibarr_main_prod='1';
$dolibarr_main_force_https='1';
$dolibarr_main_restrict_os_commands='mariadb-dump, mariadb, mysqldump, mysql, pg_dump, pg_restore, clamdscan, clamdscan.exe';
$dolibarr_nocsrfcheck='0';
$dolibarr_main_instance_unique_id='6b2272db4b7b63d14b38169ddbe8ba63';
$dolibarr_mailing_limit_sendbyweb='0';
$dolibarr_mailing_limit_sendbycli='0';
$dolibarr_main_distrib='standard';
