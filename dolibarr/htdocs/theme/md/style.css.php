<?php
/*
 * MODERN ERP THEME FOR DOLIBARR - REFINED ELEGANCE EDITION
 * Fixes: Button Contrast, Icon Colors, Professional Typography
 */

// -------------------------------------------------------------------
// 1. PHP DOLIBARR LOGIC PRESERVATION
// -------------------------------------------------------------------
if (!defined('NOREQUIRESOC'))    define('NOREQUIRESOC', '1');
if (!defined('NOCSRFCHECK'))     define('NOCSRFCHECK', 1);
if (!defined('NOTOKENRENEWAL'))  define('NOTOKENRENEWAL', 1);
if (!defined('NOLOGIN'))         define('NOLOGIN', 1);
if (!defined('NOREQUIREHTML'))   define('NOREQUIREHTML', 1);
if (!defined('NOREQUIREAJAX'))   define('NOREQUIREAJAX', '1');

define('ISLOADEDBYSTEELSHEET', '1');

require __DIR__.'/theme_vars.inc.php';
if (defined('THEME_ONLY_CONSTANT')) return;

session_cache_limiter('public');
require_once __DIR__.'/../../main.inc.php';
require_once DOL_DOCUMENT_ROOT.'/core/lib/functions2.lib.php';

if (empty($user->id) && !empty($_SESSION['dol_login'])) {
	$user->fetch(0, $_SESSION['dol_login'], '', 1);
	$user->loadRights();
	$menumanager = new MenuManager($db, empty($user->socid) ? 0 : 1);
	$menumanager->loadMenu();
}

top_httphead('text/css');
if (empty($dol_nocache)) {
	header('Cache-Control: max-age=10800, public, must-revalidate');
} else {
	header('Cache-Control: no-cache');
}

$langs->load("main", 0, 1);
$right = ($langs->trans("DIRECTION") == 'rtl' ? 'left' : 'right');
$left  = ($langs->trans("DIRECTION") == 'rtl' ? 'right' : 'left');

// -------------------------------------------------------------------
// 2. CONFIGURATION & VARIABLES
// -------------------------------------------------------------------

// Typography - Inter for Professional Look
$font_primary = "'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif";

// PHP Variables for Legacy Support
$colortext          = "15, 23, 42";      // Slate 900
$colorbackhmenu1    = "255, 255, 255";
$colorbackvmenu1    = "255, 255, 255";
$colorbacktitle1    = "255, 255, 255";
$colorbacktabcard1  = "255, 255, 255";

?>

/* ============================================================================== */
/* CSS STARTS HERE                                                               */
/* ============================================================================== */

/* Import Inter Font */
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

:root {
    /* --- COLOR PALETTE (Professional & Elegant) --- */
    --bg-app: #f1f5f9;           /* Light Grey/Blue Tint */
    --bg-surface: #ffffff;
    
    /* Primary Action Color (Deep Indigo/Sapphire) */
    --primary: #4338ca;          /* Indigo 700 - Lebih gelap/elegan dari standar */
    --primary-hover: #3730a3;    /* Indigo 800 */
    --primary-text: #ffffff;
    
    /* Secondary/Cancel Actions */
    --secondary-bg: #ffffff;
    --secondary-text: #64748b;   /* Slate 500 */
    --secondary-border: #cbd5e1; /* Slate 300 */
    
    /* Text Colors */
    --text-main: #0f172a;        /* Slate 900 - Hampir Hitam */
    --text-muted: #64748b;       /* Slate 500 */
    --text-light: #94a3b8;       /* Slate 400 */
    
    /* Icon Colors */
    --icon-default: #475569;     /* Slate 600 - Elegan, tidak terlalu hitam */
    --icon-active: #4338ca;      /* Primary color */
    
    /* Status Colors */
    --danger: #ef4444;
    --success: #10b981;
    --warning: #f59e0b;

    /* Layout & Effects */
    --header-height: 64px;
    --sidebar-width: 250px;
    --radius-card: 12px;
    --radius-btn: 6px;
    
    /* Shadows - Soft & Modern */
    --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
    --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
}

/* ------------------------------------------------------------------- */
/* 1. GLOBAL RESET & TYPOGRAPHY                                        */
/* ------------------------------------------------------------------- */

body {
    font-family: <?php print $font_primary; ?>;
    font-size: 13.5px; /* Sedikit lebih kecil agar lebih padat informasinya seperti SAP */
    line-height: 1.5;
    color: var(--text-main);
    background-color: var(--bg-app);
    margin: 0;
    -webkit-font-smoothing: antialiased;
}

/* Links */
a, a:link, a:visited {
    color: var(--primary);
    text-decoration: none;
    font-weight: 500;
    transition: color 0.2s;
}
a:hover {
    color: var(--primary-hover);
}

/* ------------------------------------------------------------------- */
/* 2. BUTTONS - FIXING THE WHITE TEXT ISSUE                            */
/* ------------------------------------------------------------------- */

/* Base Button Style */
.button, .butAction, .butActionDelete, .buttonDelete {
    display: inline-flex !important;
    align-items: center;
    justify-content: center;
    padding: 8px 20px !important;
    font-size: 13px !important;
    font-weight: 600 !important;
    border-radius: var(--radius-btn) !important;
    cursor: pointer;
    transition: all 0.2s ease-in-out !important;
    box-shadow: var(--shadow-sm);
    text-transform: uppercase;
    letter-spacing: 0.5px;
    border: 1px solid transparent !important;
    text-decoration: none !important;
    line-height: 1.4 !important;
}

/* --- PRIMARY BUTTONS (Save, Create, Validate) --- */
/* Pastikan teks putih di atas background gelap */
.button, .butAction {
    background-color: var(--primary) !important;
    color: #ffffff !important;
    border-color: var(--primary) !important;
}

/* Paksa warna teks tetap putih saat hover/visited */
.button:hover, .butAction:hover, 
.button:visited, .butAction:visited,
.button:link, .butAction:link {
    background-color: var(--primary-hover) !important;
    color: #ffffff !important;
    box-shadow: var(--shadow-md);
    transform: translateY(-1px);
}

/* --- SECONDARY/DELETE BUTTONS (Cancel, Delete) --- */
/* Background putih, Teks Gelap/Merah */
.buttonDelete, .butActionDelete, .buttonRefused {
    background-color: #ffffff !important;
    color: var(--text-muted) !important; 
    border: 1px solid var(--secondary-border) !important;
    box-shadow: none !important;
}

/* Hover state untuk tombol delete - Teks menjadi merah */
.buttonDelete:hover, .butActionDelete:hover {
    background-color: #fff1f2 !important; /* Merah sangat muda */
    color: var(--danger) !important;
    border-color: var(--danger) !important;
    box-shadow: var(--shadow-sm);
}

/* Tombol khusus 'Link' */
.buttonlink {
    color: var(--primary) !important;
    background: transparent !important;
    border: none !important;
    box-shadow: none !important;
}
.buttonlink:hover {
    text-decoration: underline !important;
}

/* ------------------------------------------------------------------- */
/* 3. NAVIGATION & ICONS (ELEGANT STYLE)                               */
/* ------------------------------------------------------------------- */

/* Top Navigation Bar */
#id-top {
    background: #ffffff !important;
    border-bottom: 1px solid var(--border-light);
    height: var(--header-height);
    box-shadow: 0 1px 2px rgba(0,0,0,0.03);
    position: sticky;
    top: 0;
    z-index: 1050;
}

/* Menu Containers */
div.tmenudiv {
    height: var(--header-height);
    display: flex;
    align-items: center;
}

/* Menu Items Styles */
li.tmenu, li.tmenusel {
    height: var(--header-height);
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 0 15px !important;
    position: relative;
    background: transparent !important; /* Hapus background default */
}

/* --- ICON COLOR REFINEMENT --- */
/* Ikon FontAwesome (.fa) dan Image Icons */
li.tmenu .fa, li.tmenu .mainmenuaspan, 
div.tmenuimage, a.tmenuimage {
    color: var(--icon-default) !important; /* Abu-abu tua elegan */
    font-size: 1.1em;
    transition: color 0.3s ease;
}

/* Text Label Menu */
.mainmenuaspan {
    font-weight: 600 !important;
    font-size: 12px !important;
    margin-top: 4px;
    display: block;
    color: var(--icon-default) !important;
}

/* Hover & Active States */
li.tmenu:hover .fa, li.tmenu:hover .mainmenuaspan,
li.tmenusel .fa, li.tmenusel .mainmenuaspan {
    color: var(--primary) !important; /* Berubah jadi Indigo saat hover */
}

/* Garis Indikator Aktif di Bawah */
li.tmenusel::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    width: 100%;
    height: 3px;
    background-color: var(--primary);
}

/* Sidebar (Left Menu) */
.side-nav {
    background: #ffffff !important;
    border-right: 1px solid var(--border-light);
    box-shadow: none !important;
    padding-top: 20px;
    width: var(--sidebar-width);
}

/* Sidebar Links */
a.vmenu, a.vsmenu {
    color: var(--text-main) !important;
    padding: 10px 18px !important;
    margin: 4px 12px !important;
    border-radius: 6px;
    display: block;
    font-weight: 500 !important;
    border-left: 3px solid transparent;
}

/* Sidebar Icons */
a.vmenu .fa, a.vsmenu .fa {
    width: 20px;
    text-align: center;
    color: var(--icon-default); /* Warna ikon sidebar */
    margin-right: 8px;
}

/* Sidebar Hover/Active */
a.vmenu:hover, a.vsmenu:hover,
.blockvmenuimpair a:hover {
    background-color: #f8fafc !important;
    color: var(--primary) !important;
    border-left-color: var(--primary);
}

a.vmenu:hover .fa, a.vsmenu:hover .fa {
    color: var(--primary); /* Ikon ikut berubah warna */
}

/* ------------------------------------------------------------------- */
/* 4. CARDS & CONTENT (THE "PAPER" LOOK)                               */
/* ------------------------------------------------------------------- */

div.fiche {
    background: var(--bg-surface);
    border-radius: var(--radius-card);
    box-shadow: var(--shadow-sm); /* Bayangan halus */
    border: 1px solid var(--border-light);
    padding: 30px !important;
    margin: 20px !important;
}

/* Judul Halaman (Elegant Header) */
div.titre {
    font-size: 20px !important;
    font-weight: 700 !important;
    color: var(--text-main) !important;
    padding-bottom: 15px;
    margin-bottom: 25px;
    border-bottom: 1px solid var(--border-light);
    display: flex;
    align-items: center;
}

/* Tabs Navigation (Modern Underline Style) */
div.tabs {
    margin-bottom: 0 !important;
    padding-left: 20px;
    border-bottom: 1px solid var(--border-light);
    background: #fff;
}

div.tabBar {
    background: transparent !important;
    border: none !important;
    padding: 0 !important;
    margin: 0 !important;
}

a.tab {
    background: transparent !important;
    border: none !important;
    color: var(--text-muted) !important;
    padding: 12px 20px !important;
    font-weight: 600 !important;
    border-bottom: 2px solid transparent !important;
    margin-right: 2px;
}

a.tab:hover {
    color: var(--primary) !important;
    background: #f8fafc !important;
}

a.tab#active {
    color: var(--primary) !important;
    border-bottom: 2px solid var(--primary) !important;
    background: transparent !important;
}

/* ------------------------------------------------------------------- */
/* 5. TABLES (CLEAN DATA PRESENTATION)                                 */
/* ------------------------------------------------------------------- */

table.liste {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
    border: 1px solid var(--border-light);
    border-radius: 8px;
    overflow: hidden;
    margin-bottom: 20px;
}

/* Header Tabel */
tr.liste_titre {
    background: #f8fafc !important;
    height: 45px !important;
}

tr.liste_titre th, tr.liste_titre td {
    border-bottom: 1px solid var(--border-light) !important;
    border-top: none !important;
    border-left: none !important;
    border-right: none !important;
    color: var(--text-muted) !important;
    font-weight: 600 !important;
    text-transform: uppercase;
    font-size: 11px !important;
    letter-spacing: 0.05em;
    padding: 10px 15px !important;
}

/* Body Tabel */
table.liste tr {
    background: #ffffff !important;
    transition: background-color 0.1s;
}

table.liste td {
    padding: 12px 15px !important;
    border-bottom: 1px solid var(--border-light);
    border-right: none !important;
    color: var(--text-main);
    font-size: 13px;
}

/* Hover Effect pada Baris */
table.liste tr:hover td {
    background-color: #f1f5f9 !important; /* Highlight biru/abu sangat muda */
}

/* Hapus garis vertikal yang mengganggu */
.liste td, .liste th {
    border-left: none !important;
    border-right: none !important;
}

/* ------------------------------------------------------------------- */
/* 6. INPUT FORMS                                                      */
/* ------------------------------------------------------------------- */

input[type="text"], input[type="password"], select, textarea {
    background: #fff !important;
    border: 1px solid var(--border-light) !important;
    border-radius: 6px !important;
    padding: 8px 12px !important;
    color: var(--text-main) !important;
    box-shadow: var(--shadow-sm);
    transition: all 0.2s;
}

input:focus, select:focus, textarea:focus {
    border-color: var(--primary) !important;
    box-shadow: 0 0 0 3px rgba(67, 56, 202, 0.1) !important; /* Glow Effect */
    outline: none;
}

/* Select2 Customization */
.select2-container .select2-selection--single {
    height: 38px !important;
    border: 1px solid var(--border-light) !important;
    border-radius: 6px !important;
    display: flex;
    align-items: center;
}
.select2-container--default .select2-selection--single .select2-selection__rendered {
    line-height: 38px !important;
    padding-left: 12px !important;
}

/* ------------------------------------------------------------------- */
/* 7. LOGIN PAGE (PROFESSIONAL BRANDING)                               */
/* ------------------------------------------------------------------- */

.bodylogin {
    background: #f1f5f9 !important; /* Background polos bersih */
    display: flex;
    align-items: center;
    justify-content: center;
}

.login_table {
    background: #ffffff !important;
    border: 1px solid var(--border-light) !important;
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04) !important;
    border-radius: 16px !important;
    padding: 40px !important;
    max-width: 400px !important;
}

.login_table_title {
    font-size: 1.25rem !important;
    font-weight: 700 !important;
    color: var(--text-main) !important;
    text-align: center;
    margin-bottom: 20px;
}

/* ------------------------------------------------------------------- */
/* 8. MISC / UTILITIES                                                 */
/* ------------------------------------------------------------------- */

/* Badges */
.badge {
    border-radius: 4px !important;
    padding: 2px 8px !important;
    font-size: 11px !important;
    font-weight: 600 !important;
    text-transform: uppercase;
}

/* Scrollbar Modern */
::-webkit-scrollbar {
    width: 8px;
    height: 8px;
}
::-webkit-scrollbar-track {
    background: #f1f5f9; 
}
::-webkit-scrollbar-thumb {
    background: #cbd5e1; 
    border-radius: 4px;
}
::-webkit-scrollbar-thumb:hover {
    background: #94a3b8; 
}

/* Mobile Fixes */
@media only screen and (max-width: 768px) {
    #id-right { padding: 10px !important; }
    div.fiche { padding: 15px !important; margin: 10px !important; }
    .side-nav { display: none; }
}