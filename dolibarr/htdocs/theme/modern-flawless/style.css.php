<?php
/* Copyright (C) 2004-2017	Laurent Destailleur		<eldy@users.sourceforge.net>
 * Copyright (C) 2006		Rodolphe Quiedeville	<rodolphe@quiedeville.org>
 * Copyright (C) 2007-2017	Regis Houssin			<regis.houssin@inodbox.com>
 * Copyright (C) 2011		Philippe Grand			<philippe.grand@atoo-net.com>
 * Copyright (C) 2012		Juanjo Menent			<jmenent@2byte.es>
 * Copyright (C) 2018       Ferran Marcet           <fmarcet@2byte.es>
 * Copyright (C) 2021-2023  Anthony Berton          <anthony.berton@bb2a.fr>
 * Copyright (C) 2024		MDW							<mdeweerd@users.noreply.github.com>
 * Copyright (C) 2024       Frédéric France         <frederic.france@free.fr>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 */

/**
 *		\file       htdocs/theme/eldy/style.css.php
 *		\brief      File for CSS style sheet Eldy (MODERNIZED)
 */

if (!defined('NOREQUIRESOC')) {
	define('NOREQUIRESOC', '1');
}
if (!defined('NOCSRFCHECK')) {
	define('NOCSRFCHECK', 1);
}
if (!defined('NOTOKENRENEWAL')) {
	define('NOTOKENRENEWAL', 1);
}
if (!defined('NOLOGIN')) {
	define('NOLOGIN', 1); 
}
if (!defined('NOREQUIREHTML')) {
	define('NOREQUIREHTML', 1);
}
if (!defined('NOREQUIREAJAX')) {
	define('NOREQUIREAJAX', '1');
}

define('ISLOADEDBYSTEELSHEET', '1');

require __DIR__.'/theme_vars.inc.php';
if (defined('THEME_ONLY_CONSTANT')) {
	return;
}

// --- MODERNIZATION: DEFINE TYPES ---
'
@phan-var-force string $badgeWarning
@phan-var-force string $butactionbg
@phan-var-force string $colorbackbody
@phan-var-force string $colorbackhmenu1
@phan-var-force string $colorbacklinebreak
@phan-var-force string $colorbacklineimpair1
@phan-var-force string $colorbacklineimpair2
@phan-var-force string $colorbacklinepair1
@phan-var-force string $colorbacklinepair2
@phan-var-force string $colorbacklinepairchecked
@phan-var-force string $colorbacklinepairhover
@phan-var-force string $colorbacktabactive
@phan-var-force string $colorbacktabcard1
@phan-var-force string $colorbacktitle1
@phan-var-force string $colorbackvmenu1
@phan-var-force string $colorblind_deuteranopes_textSuccess
@phan-var-force string $colorblind_deuteranopes_textWarning
@phan-var-force string $colortext
@phan-var-force string $colortextlink
@phan-var-force string $colortexttitle
@phan-var-force string $colortexttitlelink
@phan-var-force string $colortexttitlenotab
@phan-var-force string $colortexttitlenotab2
@phan-var-force string $colortopbordertitle1
@phan-var-force string $fontsize
@phan-var-force string $textDanger
@phan-var-force string $textSuccess
@phan-var-force string $textWarning
@phan-var-force string $textbutaction
@phan-var-force string $toolTipBgColor
@phan-var-force string $toolTipFontColor
@phan-var-force string $topMenuFontSize
';

session_cache_limiter('public');

require_once __DIR__.'/../../main.inc.php'; 
require_once DOL_DOCUMENT_ROOT.'/core/lib/functions2.lib.php';

// Load user to have $user->conf loaded
if (empty($user->id) && !empty($_SESSION['dol_login'])) {
	$user->fetch(0, $_SESSION['dol_login'], '', 1);
	$user->loadRights();
	// @phan-suppress-next-line PhanRedefinedClassReference
	$menumanager = new MenuManager($db, empty($user->socid) ? 0 : 1);
	// @phan-suppress-next-line PhanRedefinedClassReference
	$menumanager->loadMenu();
}

// Define css type
top_httphead('text/css');

if (empty($dolibarr_nocache)) {
	header('Cache-Control: max-age=10800, public, must-revalidate');
} else {
	header('Cache-Control: no-cache');
}

// Handling Params
if (GETPOST('theme', 'aZ09')) {
	$conf->theme = GETPOST('theme', 'aZ09');
}
if (GETPOST('lang', 'aZ09')) {
	$langs->setDefaultLang(GETPOST('lang', 'aZ09'));
}
if (GETPOSTISSET('THEME_DARKMODEENABLED')) {
	$conf->global->THEME_DARKMODEENABLED = GETPOSTINT('THEME_DARKMODEENABLED');
}

$langs->load("main", 0, 1);
$right = ($langs->trans("DIRECTION") == 'rtl' ? 'left' : 'right');
$left = ($langs->trans("DIRECTION") == 'rtl' ? 'right' : 'left');

$path = ''; 
$theme = 'eldy'; 
if (getDolGlobalString('MAIN_OVERWRITE_THEME_RES')) {
	$path = '/' . getDolGlobalString('MAIN_OVERWRITE_THEME_RES');
	$theme = getDolGlobalString('MAIN_OVERWRITE_THEME_RES');
}

// --- MODERNIZATION: FONT ---
// We force Inter font for a modern look
$fontlist = '"Inter", "Segoe UI", Roboto, "Helvetica Neue", Helvetica, Arial, sans-serif';

$img_head = '';
$img_button = dol_buildpath($path.'/theme/'.$theme.'/img/button_bg.png', 1);
$dol_hide_topmenu = $conf->dol_hide_topmenu;
$dol_hide_leftmenu = $conf->dol_hide_leftmenu;
$dol_optimize_smallscreen = $conf->dol_optimize_smallscreen;
$dol_no_mouse_hover = $conf->dol_no_mouse_hover;

// --- VARIABLES SETUP (Keeping logic but preparing for override) ---

$useboldtitle = getDolGlobalInt('THEME_ELDY_USEBOLDTITLE');
$userborderontable = getDolGlobalInt('THEME_ELDY_USEBORDERONTABLE');
$borderwidth = 1;

// Setting default fallbacks
if (!isset($conf->global->THEME_ELDY_BACKBODY)) $conf->global->THEME_ELDY_BACKBODY = $colorbackbody;
if (!isset($conf->global->THEME_ELDY_TOPMENU_BACK1)) $conf->global->THEME_ELDY_TOPMENU_BACK1 = $colorbackhmenu1;
if (!isset($conf->global->THEME_ELDY_VERMENU_BACK1)) $conf->global->THEME_ELDY_VERMENU_BACK1 = $colorbackvmenu1;
if (!isset($conf->global->THEME_ELDY_BACKTITLE1)) $conf->global->THEME_ELDY_BACKTITLE1 = $colorbacktitle1;
if (!isset($conf->global->THEME_ELDY_USE_HOVER)) $conf->global->THEME_ELDY_USE_HOVER = $colorbacklinepairhover;
if (!isset($conf->global->THEME_ELDY_USE_CHECKED)) $conf->global->THEME_ELDY_USE_CHECKED = $colorbacklinepairchecked;
if (!isset($conf->global->THEME_ELDY_LINEBREAK)) $conf->global->THEME_ELDY_LINEBREAK = $colorbacklinebreak;
if (!isset($conf->global->THEME_ELDY_TEXTTITLENOTAB)) $conf->global->THEME_ELDY_TEXTTITLENOTAB = $colortexttitlenotab;
if (!isset($conf->global->THEME_ELDY_TEXTLINK)) $conf->global->THEME_ELDY_TEXTLINK = $colortextlink;
if (!isset($conf->global->THEME_ELDY_BTNACTION)) $conf->global->THEME_ELDY_BTNACTION = $butactionbg;
if (!isset($conf->global->THEME_ELDY_TEXTBTNACTION)) $conf->global->THEME_ELDY_TEXTBTNACTION = $textbutaction;

// Calculate Colors
$colorbackbody        = empty($user->conf->THEME_ELDY_ENABLE_PERSONALIZED) ? (!getDolGlobalString('THEME_ELDY_BACKBODY') ? $colorbackbody : $conf->global->THEME_ELDY_BACKBODY) : (empty($user->conf->THEME_ELDY_BACKBODY) ? $colorbackbody : $user->conf->THEME_ELDY_BACKBODY);
$colorbackhmenu1      = empty($user->conf->THEME_ELDY_ENABLE_PERSONALIZED) ? (!getDolGlobalString('THEME_ELDY_TOPMENU_BACK1') ? $colorbackhmenu1 : $conf->global->THEME_ELDY_TOPMENU_BACK1) : (empty($user->conf->THEME_ELDY_TOPMENU_BACK1) ? $colorbackhmenu1 : $user->conf->THEME_ELDY_TOPMENU_BACK1);
$colorbackvmenu1      = empty($user->conf->THEME_ELDY_ENABLE_PERSONALIZED) ? (!getDolGlobalString('THEME_ELDY_VERMENU_BACK1') ? $colorbackvmenu1 : $conf->global->THEME_ELDY_VERMENU_BACK1) : (empty($user->conf->THEME_ELDY_VERMENU_BACK1) ? $colorbackvmenu1 : $user->conf->THEME_ELDY_VERMENU_BACK1);
$colortopbordertitle1 = empty($user->conf->THEME_ELDY_ENABLE_PERSONALIZED) ? (!getDolGlobalString('THEME_ELDY_TOPBORDER_TITLE1') ? $colortopbordertitle1 : $conf->global->THEME_ELDY_TOPBORDER_TITLE1) : (empty($user->conf->THEME_ELDY_TOPBORDER_TITLE1) ? $colortopbordertitle1 : $user->conf->THEME_ELDY_TOPBORDER_TITLE1);
$colorbacktitle1      = empty($user->conf->THEME_ELDY_ENABLE_PERSONALIZED) ? (!getDolGlobalString('THEME_ELDY_BACKTITLE1') ? $colorbacktitle1 : $conf->global->THEME_ELDY_BACKTITLE1) : (empty($user->conf->THEME_ELDY_BACKTITLE1) ? $colorbacktitle1 : $user->conf->THEME_ELDY_BACKTITLE1);
$colorbacktabcard1    = empty($user->conf->THEME_ELDY_ENABLE_PERSONALIZED) ? (!getDolGlobalString('THEME_ELDY_BACKTABCARD1') ? $colorbacktabcard1 : $conf->global->THEME_ELDY_BACKTABCARD1) : (empty($user->conf->THEME_ELDY_BACKTABCARD1) ? $colorbacktabcard1 : $user->conf->THEME_ELDY_BACKTABCARD1);
$colorbacktabactive   = empty($user->conf->THEME_ELDY_ENABLE_PERSONALIZED) ? (!getDolGlobalString('THEME_ELDY_BACKTABACTIVE') ? $colorbacktabactive : $conf->global->THEME_ELDY_BACKTABACTIVE) : (empty($user->conf->THEME_ELDY_BACKTABACTIVE) ? $colorbacktabactive : $user->conf->THEME_ELDY_BACKTABACTIVE);
$colorbacklineimpair1 = empty($user->conf->THEME_ELDY_ENABLE_PERSONALIZED) ? (!getDolGlobalString('THEME_ELDY_LINEIMPAIR1') ? $colorbacklineimpair1 : $conf->global->THEME_ELDY_LINEIMPAIR1) : (empty($user->conf->THEME_ELDY_LINEIMPAIR1) ? $colorbacklineimpair1 : $user->conf->THEME_ELDY_LINEIMPAIR1);
$colorbacklineimpair2 = empty($user->conf->THEME_ELDY_ENABLE_PERSONALIZED) ? (!getDolGlobalString('THEME_ELDY_LINEIMPAIR2') ? $colorbacklineimpair2 : $conf->global->THEME_ELDY_LINEIMPAIR2) : (empty($user->conf->THEME_ELDY_LINEIMPAIR2) ? $colorbacklineimpair2 : $user->conf->THEME_ELDY_LINEIMPAIR2);
$colorbacklinepair1   = empty($user->conf->THEME_ELDY_ENABLE_PERSONALIZED) ? (!getDolGlobalString('THEME_ELDY_LINEPAIR1') ? $colorbacklinepair1 : $conf->global->THEME_ELDY_LINEPAIR1) : (empty($user->conf->THEME_ELDY_LINEPAIR1) ? $colorbacklinepair1 : $user->conf->THEME_ELDY_LINEPAIR1);
$colorbacklinepair2   = empty($user->conf->THEME_ELDY_ENABLE_PERSONALIZED) ? (!getDolGlobalString('THEME_ELDY_LINEPAIR2') ? $colorbacklinepair2 : $conf->global->THEME_ELDY_LINEPAIR2) : (empty($user->conf->THEME_ELDY_LINEPAIR2) ? $colorbacklinepair2 : $user->conf->THEME_ELDY_LINEPAIR2);
$colorbacklinebreak   = empty($user->conf->THEME_ELDY_ENABLE_PERSONALIZED) ? (!getDolGlobalString('THEME_ELDY_LINEBREAK') ? $colorbacklinebreak : $conf->global->THEME_ELDY_LINEBREAK) : (empty($user->conf->THEME_ELDY_LINEBREAK) ? $colorbacklinebreak : $user->conf->THEME_ELDY_LINEBREAK);
$colortexttitlenotab  = empty($user->conf->THEME_ELDY_ENABLE_PERSONALIZED) ? (!getDolGlobalString('THEME_ELDY_TEXTTITLENOTAB') ? $colortexttitlenotab : $conf->global->THEME_ELDY_TEXTTITLENOTAB) : (empty($user->conf->THEME_ELDY_TEXTTITLENOTAB) ? $colortexttitlenotab : $user->conf->THEME_ELDY_TEXTTITLENOTAB);
$colortexttitle       = empty($user->conf->THEME_ELDY_ENABLE_PERSONALIZED) ? (!getDolGlobalString('THEME_ELDY_TEXTTITLE') ? $colortexttitle : $conf->global->THEME_ELDY_TEXTTITLE) : (empty($user->conf->THEME_ELDY_TEXTTITLE) ? $colortexttitle : $user->conf->THEME_ELDY_TEXTTITLE);
$colortexttitlelink   = empty($user->conf->THEME_ELDY_ENABLE_PERSONALIZED) ? (!getDolGlobalString('THEME_ELDY_TEXTTITLELINK') ? $colortexttitlelink : $conf->global->THEME_ELDY_TEXTTITLELINK) : (empty($user->conf->THEME_ELDY_TEXTTITLELINK) ? $colortexttitlelink : $user->conf->THEME_ELDY_TEXTTITLELINK);
$colortext            = empty($user->conf->THEME_ELDY_ENABLE_PERSONALIZED) ? (!getDolGlobalString('THEME_ELDY_TEXT') ? $colortext : $conf->global->THEME_ELDY_TEXT) : (empty($user->conf->THEME_ELDY_TEXT) ? $colortext : $user->conf->THEME_ELDY_TEXT);
$colortextlink        = empty($user->conf->THEME_ELDY_ENABLE_PERSONALIZED) ? (!getDolGlobalString('THEME_ELDY_TEXTLINK') ? $colortextlink : $conf->global->THEME_ELDY_TEXTLINK) : (empty($user->conf->THEME_ELDY_TEXTLINK) ? $colortextlink : $user->conf->THEME_ELDY_TEXTLINK);
$colortextlinkHsla    = colorHexToHsl($colortextlink, false, true);

$butactionbg       	  = empty($user->conf->THEME_ELDY_ENABLE_PERSONALIZED) ? (!getDolGlobalString('THEME_ELDY_BTNACTION') ? $butactionbg : $conf->global->THEME_ELDY_BTNACTION) : (empty($user->conf->THEME_ELDY_BTNACTION) ? $butactionbg : $user->conf->THEME_ELDY_BTNACTION);
$textbutaction        = empty($user->conf->THEME_ELDY_ENABLE_PERSONALIZED) ? (!getDolGlobalString('THEME_ELDY_TEXTBTNACTION') ? $textbutaction : $conf->global->THEME_ELDY_TEXTBTNACTION) : (empty($user->conf->THEME_ELDY_TEXTBTNACTION) ? $textbutaction : $user->conf->THEME_ELDY_TEXTBTNACTION);
$fontsize             = empty($user->conf->THEME_ELDY_ENABLE_PERSONALIZED) ? (!getDolGlobalString('THEME_ELDY_FONT_SIZE1') ? $fontsize : $conf->global->THEME_ELDY_FONT_SIZE1) : (empty($user->conf->THEME_ELDY_FONT_SIZE1) ? $fontsize : $user->conf->THEME_ELDY_FONT_SIZE1);
$fontsizesmaller      = empty($user->conf->THEME_ELDY_ENABLE_PERSONALIZED) ? (!getDolGlobalString('THEME_ELDY_FONT_SIZE2') ? $fontsize : $conf->global->THEME_ELDY_FONT_SIZE2) : (empty($user->conf->THEME_ELDY_FONT_SIZE2) ? $fontsize : $user->conf->THEME_ELDY_FONT_SIZE2);
$heightrow			  = empty($user->conf->THEME_ELDY_ENABLE_PERSONALIZED) ? (!getDolGlobalString('THEME_ELDY_USECOMOACTROW') ? '155%' : '300%') : (empty($user->conf->THEME_ELDY_USECOMOACTROW) ? '155%' : '300%');

// Hover & Checked
$colorbacklinepairhover = ((!isset($conf->global->THEME_ELDY_USE_HOVER) || (string) $conf->global->THEME_ELDY_USE_HOVER === '255,255,255') ? '' : ($conf->global->THEME_ELDY_USE_HOVER === '1' ? 'e6edf0' : $conf->global->THEME_ELDY_USE_HOVER));
$colorbacklinepairchecked = ((!isset($conf->global->THEME_ELDY_USE_CHECKED) || (string) $conf->global->THEME_ELDY_USE_CHECKED === '255,255,255') ? '' : ($conf->global->THEME_ELDY_USE_CHECKED === '1' ? 'e6edf0' : $conf->global->THEME_ELDY_USE_CHECKED));
if (!empty($user->conf->THEME_ELDY_ENABLE_PERSONALIZED)) {
	$colorbacklinepairhover = ((!isset($user->conf->THEME_ELDY_USE_HOVER) || $user->conf->THEME_ELDY_USE_HOVER === '0') ? '' : ($user->conf->THEME_ELDY_USE_HOVER === '1' ? 'e6edf0' : $user->conf->THEME_ELDY_USE_HOVER));
	$colorbacklinepairchecked = ((!isset($user->conf->THEME_ELDY_USE_CHECKED) || $user->conf->THEME_ELDY_USE_CHECKED === '0') ? '' : ($user->conf->THEME_ELDY_USE_CHECKED === '1' ? 'e6edf0' : $user->conf->THEME_ELDY_USE_CHECKED));
}

// Normalize Text Colors based on background brightness
$colorbackhmenu1 = implode(',', colorStringToArray($colorbackhmenu1));
$tmppart = explode(',', $colorbackhmenu1);
$tmpval = (!empty($tmppart[0]) ? $tmppart[0] : 0) + (!empty($tmppart[1]) ? $tmppart[1] : 0) + (!empty($tmppart[2]) ? $tmppart[2] : 0);
$colortextbackhmenu = ($tmpval <= 460) ? 'FFFFFF' : '000000';

$colorbackvmenu1 = implode(',', colorStringToArray($colorbackvmenu1));
$tmppart = explode(',', $colorbackvmenu1);
$tmpval = (!empty($tmppart[0]) ? $tmppart[0] : 0) + (!empty($tmppart[1]) ? $tmppart[1] : 0) + (!empty($tmppart[2]) ? $tmppart[2] : 0);
$colortextbackvmenu = ($tmpval <= 460) ? 'FFFFFF' : '222222';

$colortopbordertitle1 = implode(',', colorStringToArray($colortopbordertitle1)); 
$colorbacktitle1 = implode(',', colorStringToArray($colorbacktitle1));
$tmppart = explode(',', $colorbacktitle1);
if ($colortexttitle == '') {
	$tmpval = (!empty($tmppart[0]) ? $tmppart[0] : 0) + (!empty($tmppart[1]) ? $tmppart[1] : 0) + (!empty($tmppart[2]) ? $tmppart[2] : 0);
	if ($tmpval <= 460) {
		$colortexttitle = 'FFFFFF';
		$colorshadowtitle = '888888';
	} else {
		$colortexttitle = '000000';
		$colorshadowtitle = 'FFFFFF';
	}
} else {
	$colorshadowtitle = '888888';
}

$colorbacktabcard1 = implode(',', colorStringToArray($colorbacktabcard1));
$tmppart = explode(',', $colorbacktabcard1);
$tmpval = (!empty($tmppart[0]) ? $tmppart[0] : 0) + (!empty($tmppart[1]) ? $tmppart[1] : 0) + (!empty($tmppart[2]) ? $tmppart[2] : 0);
$colortextbacktab = ($tmpval <= 460) ? 'FFFFFF' : '000000';

// Formatting
$colorbackhmenu1 = implode(',', colorStringToArray($colorbackhmenu1));
$colorbackvmenu1 = implode(',', colorStringToArray($colorbackvmenu1));
$colorbacktitle1 = implode(',', colorStringToArray($colorbacktitle1));
$colorbacktabcard1 = implode(',', colorStringToArray($colorbacktabcard1));
$colorbacktabactive = implode(',', colorStringToArray($colorbacktabactive));
$colorbacklineimpair1 = implode(',', colorStringToArray($colorbacklineimpair1));
$colorbacklineimpair2 = implode(',', colorStringToArray($colorbacklineimpair2));
$colorbacklinepair1 = implode(',', colorStringToArray($colorbacklinepair1));
$colorbacklinepair2 = implode(',', colorStringToArray($colorbacklinepair2));
if ($colorbacklinepairhover != '') $colorbacklinepairhover = implode(',', colorStringToArray($colorbacklinepairhover));
if ($colorbacklinepairchecked != '') $colorbacklinepairchecked = implode(',', colorStringToArray($colorbacklinepairchecked));
$colorbackbody = implode(',', colorStringToArray($colorbackbody));
$colortexttitlenotab = implode(',', colorStringToArray($colortexttitlenotab));
$colortexttitle = implode(',', colorStringToArray($colortexttitle));
$colortext = implode(',', colorStringToArray($colortext));
$colortextlink = implode(',', colorStringToArray($colortextlink));

// Menu counts
$nbtopmenuentries = $menumanager->showmenu('topnb');
$nbtopmenuentriesreal = $nbtopmenuentries;
if ($conf->browser->layout == 'phone') {
	$nbtopmenuentries = max($nbtopmenuentries, 10);
}

$minwidthtmenu = 66; 
$heightmenu = 50; 
$heightmenu2 = 49; 
$disableimages = 0;
$maxwidthloginblock = 180;
if (getDolGlobalInt('THEME_TOPMENU_DISABLE_IMAGE') == 1 || !empty($user->conf->MAIN_OPTIMIZEFORTEXTBROWSER)) {
	$disableimages = 1;
	$maxwidthloginblock += 50;
	$minwidthtmenu = 0;
}
if (getDolGlobalString('MAIN_USE_TOP_MENU_QUICKADD_DROPDOWN')) $maxwidthloginblock += 55;
if (getDolGlobalString('MAIN_USE_TOP_MENU_SEARCH_DROPDOWN')) $maxwidthloginblock += 55;
if (isModEnabled('bookmark')) $maxwidthloginblock += 55;

// Printing CSS variables for PHP Logic
print '/*'."\n";
print 'colorbackbody='.$colorbackbody."\n";
print 'colorbackvmenu1='.$colorbackvmenu1."\n";
print 'colorbackhmenu1='.$colorbackhmenu1."\n";
print 'colorbacktitle1='.$colorbacktitle1."\n";
print 'colorbacklineimpair1='.$colorbacklineimpair1."\n";
print 'colorbacklineimpair2='.$colorbacklineimpair2."\n";
print 'colorbacklinepair1='.$colorbacklinepair1."\n";
print 'colorbacklinepair2='.$colorbacklinepair2."\n";
print 'colorbacklinepairhover='.$colorbacklinepairhover."\n";
print 'colorbacklinepairchecked='.$colorbacklinepairchecked."\n";
print '$colortexttitlenotab='.$colortexttitlenotab."\n";
print '$colortexttitle='.$colortexttitle."\n";
print '$colortext='.$colortext."\n";
print '$colortextlink='.$colortextlink."\n";
print '$colortextbackhmenu='.$colortextbackhmenu."\n";
print '$colortextbackvmenu='.$colortextbackvmenu."\n";
print 'dol_hide_topmenu='.$dol_hide_topmenu."\n";
print 'dol_hide_leftmenu='.$dol_hide_leftmenu."\n";
print 'dol_optimize_smallscreen='.$dol_optimize_smallscreen."\n";
print 'dol_no_mouse_hover='.$dol_no_mouse_hover."\n";
print 'dol_screenwidth='.(empty($_SESSION['dol_screenwidth']) ? '' : $_SESSION['dol_screenwidth'])."\n";
print 'dol_screenheight='.(empty($_SESSION['dol_screenheight']) ? '' : $_SESSION['dol_screenheight'])."\n";
print 'fontsize='.$fontsize."\n";
print 'nbtopmenuentries='.$nbtopmenuentries."\n";
print 'fontsizesmaller='.$fontsizesmaller."\n";
print 'topMenuFontSize='.$topMenuFontSize."\n";
print 'toolTipBgColor='.$toolTipBgColor."\n";
print 'toolTipFontColor='.$toolTipFontColor."\n";
print 'getDolGlobalString("THEME_SATURATE_RATIO")='.getDolGlobalString('THEME_SATURATE_RATIO')." (must be between 0 and 1)\n";
print '*/'."\n";

// Include the standard generator (this creates the base CSS)
require __DIR__.'/global.inc.php';

// ==============================================================================
// === MODERN OVERRIDES (This section overrides the standard Dolibarr CSS) ===
// ==============================================================================
print '
/* IMPORT INTER FONT */
@import url("https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap");

:root {
    /* Modern Palette Variables */
    --font-primary: "Inter", -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
    --color-bg-page: #f8fafc;
    --color-surface: #ffffff;
    --color-primary: #3b82f6; /* Modern Blue */
    --color-primary-dark: #2563eb;
    --color-text-main: #1e293b;
    --color-text-light: #64748b;
    --color-border: #e2e8f0;
    --color-hover: #f1f5f9;
    --radius-sm: 0.375rem;
    --radius-md: 0.5rem;
    --radius-lg: 0.75rem;
    --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
    --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
}

/* --- GLOBAL RESETS & TYPOGRAPHY --- */
body {
    background-color: var(--color-bg-page) !important;
    font-family: var(--font-primary) !important;
    color: var(--color-text-main);
    -webkit-font-smoothing: antialiased;
    line-height: 1.5;
}

a { text-decoration: none; color: var(--color-primary); transition: all 0.2s; }
a:hover { color: var(--color-primary-dark); }

/* --- HEADER / TOP MENU --- */
.side-nav-vert, div.tmenu, .login_block {
    background: #ffffff !important; /* Force white header */
    background-image: none !important;
    border-bottom: 1px solid var(--color-border);
}

div.tmenu {
    box-shadow: var(--shadow-sm);
}

/* Menu Items */
div.tmenudiv {
    background: transparent !important;
}
a.tmenu, a.tmenu:link, a.tmenu:visited, a.tmenu:hover, a.tmenu:active {
    color: var(--color-text-main) !important;
    font-weight: 500;
    font-size: 0.9rem;
    text-transform: none !important;
}
li.tmenudiv:hover, li.tmenudiv.tmenudivsel {
    background: var(--color-hover) !important;
    border-bottom: 2px solid var(--color-primary);
}
.mainmenuaspan {
    color: var(--color-text-main) !important;
}

/* User Login Block */
.login_block {
    background: transparent !important;
    border: none !important;
}
.login_block a {
    color: var(--color-text-main) !important;
    font-weight: 600;
}

/* --- SIDEBAR / LEFT MENU --- */
div.vmenu, .vmenu {
    background: #ffffff !important;
    border-right: 1px solid var(--color-border);
    border-bottom: none !important;
}

.vmenu .blockvmenu .menu_titre {
    color: var(--color-text-light) !important;
    text-transform: uppercase;
    font-size: 0.75rem;
    font-weight: 700;
    letter-spacing: 0.05em;
    padding: 15px 10px 5px 15px !important;
    background: transparent !important;
    border-bottom: none !important;
    background-image: none !important;
}

.vmenu .blockvmenu .menu_top {
    border-bottom: none !important;
}

a.vmenu, a.vmenu:link, a.vmenu:visited {
    color: var(--color-text-main) !important;
    padding: 8px 15px !important;
    font-size: 0.9rem;
    border-radius: var(--radius-sm);
    margin: 0 8px;
    display: block;
    width: auto !important;
}

a.vmenu:hover, .vmenu .menu_contenu .menu_top:hover {
    background-color: var(--color-hover) !important;
    color: var(--color-primary) !important;
    text-decoration: none;
    box-shadow: none !important;
}

/* --- CARDS & CONTAINERS (The Modern ERP Look) --- */
/* Transform standard boxes into modern cards */
div.fiche, form.fiche, .tabBar, .div-table-responsive, .div-table-responsive-no-min {
    background: var(--color-surface);
    border: 1px solid var(--color-border);
    border-radius: var(--radius-md);
    box-shadow: var(--shadow-sm);
    margin-bottom: 20px;
    padding: 20px;
    margin-top: 10px;
}
.tabBar { padding: 0; border: none; box-shadow: none; background: transparent; }
.tabBar .fiche { margin-top: 0; }

/* Remove old borders */
table.border, table.bordernobottom, table.border > tbody > tr > td {
    border: none !important;
}

/* --- TABLES (Data Grid) --- */
table.liste, .div-table-responsive table {
    border-collapse: separate;
    border-spacing: 0;
    width: 100%;
    margin-bottom: 0 !important;
}

/* Header */
.div-table-responsive .liste_titre, table.liste tr.liste_titre, tr.liste_titre th, tr.liste_titre td {
    background: #f1f5f9 !important;
    color: var(--color-text-light) !important;
    font-weight: 600;
    text-transform: uppercase;
    font-size: 0.75rem;
    letter-spacing: 0.05em;
    padding: 12px 16px !important;
    border-bottom: 1px solid var(--color-border) !important;
    height: auto !important;
}

/* Rows */
.div-table-responsive .liste_titre_filter, table.liste tr.liste_titre_filter {
    background: #ffffff !important;
}

table.liste tr.pair, table.liste tr.impair, .div-table-responsive .tr_line {
    background: #ffffff !important;
    transition: background-color 0.15s ease;
}

table.liste td, .div-table-responsive .td_line {
    border-bottom: 1px solid var(--color-border) !important;
    padding: 12px 16px !important;
    color: var(--color-text-main);
    font-size: 0.9rem;
    vertical-align: middle;
}

/* Hover Effect */
table.liste tr:hover td, .div-table-responsive .tr_line:hover {
    background-color: var(--color-hover) !important;
}

/* Links in tables */
table.liste td a, .div-table-responsive .td_line a {
    color: var(--color-text-main);
    font-weight: 500;
}
table.liste td a:hover {
    color: var(--color-primary);
}

/* --- BUTTONS & ACTIONS --- */
.butAction, .butActionDelete, .button, .button-reset {
    background: white;
    border: 1px solid var(--color-border) !important;
    color: var(--color-text-main) !important;
    padding: 8px 16px !important;
    border-radius: var(--radius-sm);
    font-size: 0.85rem;
    font-weight: 500;
    box-shadow: var(--shadow-sm);
    transition: all 0.2s;
    text-transform: none;
    background-image: none !important;
    text-decoration: none !important;
}

.butAction:hover, .button:hover {
    border-color: var(--color-primary) !important;
    color: var(--color-primary) !important;
    background: white !important;
    box-shadow: var(--shadow-md);
}

/* Primary Action Button Override */
.butActionRef {
    background-color: var(--color-primary) !important;
    color: white !important;
    border: 1px solid var(--color-primary) !important;
}
.butActionRef:hover {
    background-color: var(--color-primary-dark) !important;
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
}

.butActionDelete {
    color: #ef4444 !important;
    border-color: #fecaca !important;
}
.butActionDelete:hover {
    background-color: #fef2f2 !important;
}

/* --- FORMS & INPUTS --- */
input[type="text"], input[type="password"], input[type="date"], select, textarea {
    background-color: #fff;
    border: 1px solid var(--color-border) !important;
    border-radius: var(--radius-sm);
    padding: 8px 12px !important;
    font-size: 0.9rem;
    color: var(--color-text-main);
    transition: border-color 0.15s, box-shadow 0.15s;
    min-height: 38px;
}

input:focus, select:focus, textarea:focus {
    outline: none;
    border-color: var(--color-primary) !important;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

/* Select2 Overrides (Common in Dolibarr) */
.select2-container--default .select2-selection--single {
    border: 1px solid var(--color-border) !important;
    border-radius: var(--radius-sm) !important;
    height: 38px !important;
    padding-top: 5px;
}

/* --- MISC UI ELEMENTS --- */
/* Title area */
.titre {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--color-text-main);
    margin-bottom: 20px;
    padding-left: 0;
    text-shadow: none !important;
}

/* Tabs */
div.tabs {
    border-bottom: 1px solid var(--color-border);
    margin-bottom: 20px;
}
div.tab {
    background: transparent;
    border: none;
    border-bottom: 2px solid transparent;
    color: var(--color-text-light);
    margin-right: 20px;
    padding: 10px 5px;
    font-weight: 500;
}
div.tab_active, div.tab:hover {
    background: transparent !important;
    border-bottom: 2px solid var(--color-primary);
    color: var(--color-primary);
}

/* Badges / Status */
.badge {
    border-radius: 9999px;
    padding: 4px 10px;
    font-weight: 600;
    font-size: 0.75rem;
    text-transform: uppercase;
}

/* Footer */
.footer {
    background: transparent;
    border-top: 1px solid var(--color-border);
    color: var(--color-text-light);
}
';

if (is_object($db)) {
	$db->close();
}
?>