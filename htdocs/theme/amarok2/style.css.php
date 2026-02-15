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
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <https://www.gnu.org/licenses/>.
 */

/**
 *		\file       htdocs/theme/eldy/style.css.php
 *		\brief      Modern Enterprise ERP Theme - Flawless & Fascinating Edition
 */

//if (! defined('NOREQUIREUSER')) define('NOREQUIREUSER','1');	// Not disabled because need to load personalized language
//if (! defined('NOREQUIREDB'))   define('NOREQUIREDB','1');	// Not disabled to increase speed. Language code is found on url.
if (!defined('NOREQUIRESOC')) {
	define('NOREQUIRESOC', '1');
}
//if (! defined('NOREQUIRETRAN')) define('NOREQUIRETRAN','1');	// Not disabled because need to do translations
if (!defined('NOCSRFCHECK')) {
	define('NOCSRFCHECK', 1);
}
if (!defined('NOTOKENRENEWAL')) {
	define('NOTOKENRENEWAL', 1);
}
if (!defined('NOLOGIN')) {
	define('NOLOGIN', 1); // File must be accessed by logon page so without login.
}
//if (!defined('NOREQUIREMENU'))   define('NOREQUIREMENU',1);  	// We load menu manager class (note that object loaded may have wrong content because NOLOGIN is set and some values depends on login)
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
// Types from theme_vars
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

require_once __DIR__.'/../../main.inc.php'; // __DIR__ allow this script to be included in custom themes
require_once DOL_DOCUMENT_ROOT.'/core/lib/functions2.lib.php';
/**
 * @var Conf $conf
 * @var DoliDB $db
 * @var Translate $langs
 * @var User $user
 *
 * @var string $badgeWarning
 * @var string $butactionbg
 * @var string $colorbackbody
 * @var string $colorbackhmenu1
 * @var string $colorbacklinebreak
 * @var string $colorbacklineimpair1
 * @var string $colorbacklineimpair2
 * @var string $colorbacklinepair1
 * @var string $colorbacklinepair2
 * @var string $colorbacklinepairchecked
 * @var string $colorbacklinepairhover
 * @var string $colorbacktabactive
 * @var string $colorbacktabcard1
 * @var string $colorbacktitle1
 * @var string $colorbackvmenu1
 * @var string $colorblind_deuteranopes_textSuccess
 * @var string $colorblind_deuteranopes_textWarning
 * @var string $colortext
 * @var string $colortextlink
 * @var string $colortexttitle
 * @var string $colortexttitlelink
 * @var string $colortexttitlenotab
 * @var string $colortexttitlenotab2
 * @var string $colortopbordertitle1
 * @var string $fontsize
 * @var string $textDanger
 * @var string $textSuccess
 * @var string $textWarning
 * @var string $textbutaction
 * @var string $toolTipBgColor
 * @var string $toolTipFontColor
 * @var string $topMenuFontSize
 */
// Load user to have $user->conf loaded (not done into main because of NOLOGIN constant defined)
// and permission, so we can later calculate number of top menu ($nbtopmenuentries) according to user profile.
if (empty($user->id) && !empty($_SESSION['dol_login'])) {
	$user->fetch(0, $_SESSION['dol_login'], '', 1);
	$user->loadRights();

	// Reload menu now we have the good user (and we need the good menu to have ->showmenu('topnb') correct.
	// @phan-suppress-next-line PhanRedefinedClassReference
	$menumanager = new MenuManager($db, empty($user->socid) ? 0 : 1);
	// @phan-suppress-next-line PhanRedefinedClassReference
	$menumanager->loadMenu();
}


// Define css type
top_httphead('text/css');
// Important: Following code is to avoid page request by browser and PHP CPU at each Dolibarr page access.
if (empty($dolibarr_nocache)) {
	header('Cache-Control: max-age=10800, public, must-revalidate');
} else {
	header('Cache-Control: no-cache');
}

if (GETPOST('theme', 'aZ09')) {
	$conf->theme = GETPOST('theme', 'aZ09'); // If theme was forced on URL
}
if (GETPOST('lang', 'aZ09')) {
	$langs->setDefaultLang(GETPOST('lang', 'aZ09')); // If language was forced on URL
}
if (GETPOSTISSET('THEME_DARKMODEENABLED')) {
	$conf->global->THEME_DARKMODEENABLED = GETPOSTINT('THEME_DARKMODEENABLED'); // If darkmode was forced on URL
}

$langs->load("main", 0, 1);
$right = ($langs->trans("DIRECTION") == 'rtl' ? 'left' : 'right');
$left = ($langs->trans("DIRECTION") == 'rtl' ? 'right' : 'left');

$path = ''; // This value may be used in future for external module to overwrite theme
$theme = 'eldy'; // Value of theme
if (getDolGlobalString('MAIN_OVERWRITE_THEME_RES')) {
	$path = '/' . getDolGlobalString('MAIN_OVERWRITE_THEME_RES');
	$theme = getDolGlobalString('MAIN_OVERWRITE_THEME_RES');
}

// Define image path files and other constants

//$fontlist='helvetica, verdana, arial, sans-serif';
//$fontlist='"open sans", "Helvetica Neue", Helvetica, Arial, sans-serif';

// MODERN FONT SYSTEM - Enterprise Grade Typography
$fontlist = '"Plus Jakarta Sans", "Inter", -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif, "Apple Color Emoji", "Segoe UI Emoji"';
if (getDolGlobalString('THEME_FONT_FAMILY')) {
	$fontlist = getDolGlobalString('THEME_FONT_FAMILY').', '.$fontlist;
}

$img_head = '';
$img_button = dol_buildpath($path.'/theme/'.$theme.'/img/button_bg.png', 1);
$dol_hide_topmenu = $conf->dol_hide_topmenu;
$dol_hide_leftmenu = $conf->dol_hide_leftmenu;
$dol_optimize_smallscreen = $conf->dol_optimize_smallscreen;
$dol_no_mouse_hover = $conf->dol_no_mouse_hover;

//$conf->global->THEME_ELDY_ENABLE_PERSONALIZED=0;
//$user->conf->THEME_ELDY_ENABLE_PERSONALIZED=0;
//var_dump($user->conf->THEME_ELDY_RGB);

$useboldtitle = getDolGlobalInt('THEME_ELDY_USEBOLDTITLE');
$userborderontable = getDolGlobalInt('THEME_ELDY_USEBORDERONTABLE');
$borderwidth = 1;

// Case of option always editable
if (!isset($conf->global->THEME_ELDY_BACKBODY)) {
	$conf->global->THEME_ELDY_BACKBODY = $colorbackbody;
}
if (!isset($conf->global->THEME_ELDY_TOPMENU_BACK1)) {
	$conf->global->THEME_ELDY_TOPMENU_BACK1 = $colorbackhmenu1;
}
if (!isset($conf->global->THEME_ELDY_VERMENU_BACK1)) {
	$conf->global->THEME_ELDY_VERMENU_BACK1 = $colorbackvmenu1;
}
if (!isset($conf->global->THEME_ELDY_BACKTITLE1)) {
	$conf->global->THEME_ELDY_BACKTITLE1 = $colorbacktitle1;
}
if (!isset($conf->global->THEME_ELDY_USE_HOVER)) {
	$conf->global->THEME_ELDY_USE_HOVER = $colorbacklinepairhover;
}
if (!isset($conf->global->THEME_ELDY_USE_CHECKED)) {
	$conf->global->THEME_ELDY_USE_CHECKED = $colorbacklinepairchecked;
}
if (!isset($conf->global->THEME_ELDY_LINEBREAK)) {
	$conf->global->THEME_ELDY_LINEBREAK = $colorbacklinebreak;
}
if (!isset($conf->global->THEME_ELDY_TEXTTITLENOTAB)) {
	$conf->global->THEME_ELDY_TEXTTITLENOTAB = $colortexttitlenotab;
}
if (!isset($conf->global->THEME_ELDY_TEXTLINK)) {
	$conf->global->THEME_ELDY_TEXTLINK = $colortextlink;
}
if (!isset($conf->global->THEME_ELDY_BTNACTION)) {
	$conf->global->THEME_ELDY_BTNACTION = $butactionbg;
}
if (!isset($conf->global->THEME_ELDY_TEXTBTNACTION)) {
	$conf->global->THEME_ELDY_TEXTBTNACTION = $textbutaction;
}
// Case of option editable only if option THEME_ELDY_ENABLE_PERSONALIZED is on
if (!getDolGlobalString('THEME_ELDY_ENABLE_PERSONALIZED')) {
	$conf->global->THEME_ELDY_BACKTABCARD1 = '255,255,255'; // card
	$conf->global->THEME_ELDY_BACKTABACTIVE = '234,234,234';
	$conf->global->THEME_ELDY_TEXT = '0,0,0';
	$conf->global->THEME_ELDY_FONT_SIZE1 = $fontsize;
	$conf->global->THEME_ELDY_FONT_SIZE2 = '0.75em';
}

// Case of option availables only if THEME_ELDY_ENABLE_PERSONALIZED is on
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
// Hover color
$colorbacklinepairhover = ((!isset($conf->global->THEME_ELDY_USE_HOVER) || (string) $conf->global->THEME_ELDY_USE_HOVER === '255,255,255') ? '' : ($conf->global->THEME_ELDY_USE_HOVER === '1' ? 'e6edf0' : $conf->global->THEME_ELDY_USE_HOVER));
$colorbacklinepairchecked = ((!isset($conf->global->THEME_ELDY_USE_CHECKED) || (string) $conf->global->THEME_ELDY_USE_CHECKED === '255,255,255') ? '' : ($conf->global->THEME_ELDY_USE_CHECKED === '1' ? 'e6edf0' : $conf->global->THEME_ELDY_USE_CHECKED));
if (!empty($user->conf->THEME_ELDY_ENABLE_PERSONALIZED)) {
	$colorbacklinepairhover = ((!isset($user->conf->THEME_ELDY_USE_HOVER) || $user->conf->THEME_ELDY_USE_HOVER === '0') ? '' : ($user->conf->THEME_ELDY_USE_HOVER === '1' ? 'e6edf0' : $user->conf->THEME_ELDY_USE_HOVER));
	$colorbacklinepairchecked = ((!isset($user->conf->THEME_ELDY_USE_CHECKED) || $user->conf->THEME_ELDY_USE_CHECKED === '0') ? '' : ($user->conf->THEME_ELDY_USE_CHECKED === '1' ? 'e6edf0' : $user->conf->THEME_ELDY_USE_CHECKED));
}


// Set text color to black or white
$colorbackhmenu1 = implode(',', colorStringToArray($colorbackhmenu1)); // Normalize value to 'x,y,z'
$tmppart = explode(',', $colorbackhmenu1);
$tmpval = (!empty($tmppart[0]) ? $tmppart[0] : 0) + (!empty($tmppart[1]) ? $tmppart[1] : 0) + (!empty($tmppart[2]) ? $tmppart[2] : 0);
if ($tmpval <= 460) {
	$colortextbackhmenu = 'FFFFFF';
} else {
	$colortextbackhmenu = '000000';
}

$colorbackvmenu1 = implode(',', colorStringToArray($colorbackvmenu1)); // Normalize value to 'x,y,z'
$tmppart = explode(',', $colorbackvmenu1);
$tmpval = (!empty($tmppart[0]) ? $tmppart[0] : 0) + (!empty($tmppart[1]) ? $tmppart[1] : 0) + (!empty($tmppart[2]) ? $tmppart[2] : 0);
if ($tmpval <= 460) {
	$colortextbackvmenu = 'FFFFFF';
} else {
	$colortextbackvmenu = '222222';
}

$colortopbordertitle1 = implode(',', colorStringToArray($colortopbordertitle1)); // Normalize value to 'x,y,z'

$colorbacktitle1 = implode(',', colorStringToArray($colorbacktitle1)); // Normalize value to 'x,y,z'
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

$colorbacktabcard1 = implode(',', colorStringToArray($colorbacktabcard1)); // Normalize value to 'x,y,z'
$tmppart = explode(',', $colorbacktabcard1);
$tmpval = (!empty($tmppart[0]) ? $tmppart[0] : 0) + (!empty($tmppart[1]) ? $tmppart[1] : 0) + (!empty($tmppart[2]) ? $tmppart[2] : 0);
if ($tmpval <= 460) {
	$colortextbacktab = 'FFFFFF';
} else {
	$colortextbacktab = '000000';
}


// Format color value to match expected format (may be 'FFFFFF' or '255,255,255')
$colorbackhmenu1 = implode(',', colorStringToArray($colorbackhmenu1));
$colorbackvmenu1 = implode(',', colorStringToArray($colorbackvmenu1));
$colorbacktitle1 = implode(',', colorStringToArray($colorbacktitle1));
$colorbacktabcard1 = implode(',', colorStringToArray($colorbacktabcard1));
$colorbacktabactive = implode(',', colorStringToArray($colorbacktabactive));
$colorbacklineimpair1 = implode(',', colorStringToArray($colorbacklineimpair1));
$colorbacklineimpair2 = implode(',', colorStringToArray($colorbacklineimpair2));
$colorbacklinepair1 = implode(',', colorStringToArray($colorbacklinepair1));
$colorbacklinepair2 = implode(',', colorStringToArray($colorbacklinepair2));
if ($colorbacklinepairhover != '') {
	$colorbacklinepairhover = implode(',', colorStringToArray($colorbacklinepairhover));
}
if ($colorbacklinepairchecked != '') {
	$colorbacklinepairchecked = implode(',', colorStringToArray($colorbacklinepairchecked));
}
$colorbackbody = implode(',', colorStringToArray($colorbackbody));
$colortexttitlenotab = implode(',', colorStringToArray($colortexttitlenotab));
$colortexttitle = implode(',', colorStringToArray($colortexttitle));
$colortext = implode(',', colorStringToArray($colortext));
$colortextlink = implode(',', colorStringToArray($colortextlink));

// @phan-suppress-next-line PhanRedefinedClassReference
$nbtopmenuentries = $menumanager->showmenu('topnb');
$nbtopmenuentriesreal = $nbtopmenuentries;
if ($conf->browser->layout == 'phone') {
	$nbtopmenuentries = max($nbtopmenuentries, 10);
}

$minwidthtmenu = 66; /* minimum width for one top menu entry */
$heightmenu = 50; /* height of top menu, part with image */
$heightmenu2 = 49; /* height of top menu, part with login  */
$disableimages = 0;
$maxwidthloginblock = 180;
if (getDolGlobalInt('THEME_TOPMENU_DISABLE_IMAGE') == 1 || !empty($user->conf->MAIN_OPTIMIZEFORTEXTBROWSER)) {
	$disableimages = 1;
	$maxwidthloginblock += 50;
	$minwidthtmenu = 0;
}

if (getDolGlobalString('MAIN_USE_TOP_MENU_QUICKADD_DROPDOWN')) {
	$maxwidthloginblock += 55;
}
if (getDolGlobalString('MAIN_USE_TOP_MENU_SEARCH_DROPDOWN')) {
	$maxwidthloginblock += 55;
}
if (isModEnabled('bookmark')) {
	$maxwidthloginblock += 55;
}


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

?>

/* ============================================================================= */
/* MODERN ENTERPRISE ERP THEME - FLAWLESS & FASCINATING EDITION                  */
/* Inspired by: Odoo, SAP Fiori, Salesforce Lightning, Monday.com               */
/* ============================================================================= */

/* ===========================
   GOOGLE FONTS IMPORT
   =========================== */
@import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Inter:wght@300;400;500;600;700;800&display=swap');

/* ===========================
   CSS CUSTOM PROPERTIES (VARIABLES)
   Modern Design System
   =========================== */
:root {
	/* === Typography Scale === */
	--font-family-base: <?php echo $fontlist; ?>;
	--font-size-xs: 0.75rem;      /* 12px */
	--font-size-sm: 0.875rem;     /* 14px */
	--font-size-base: <?php echo $fontsize; ?>;
	--font-size-lg: 1.125rem;     /* 18px */
	--font-size-xl: 1.25rem;      /* 20px */
	--font-size-2xl: 1.5rem;      /* 24px */
	--font-size-3xl: 1.875rem;    /* 30px */
	
	--font-weight-light: 300;
	--font-weight-normal: 400;
	--font-weight-medium: 500;
	--font-weight-semibold: 600;
	--font-weight-bold: 700;
	--font-weight-extrabold: 800;
	
	--line-height-tight: 1.25;
	--line-height-normal: 1.5;
	--line-height-relaxed: 1.625;
	--line-height-loose: 2;
	
	--letter-spacing-tight: -0.025em;
	--letter-spacing-normal: 0em;
	--letter-spacing-wide: 0.025em;
	
	/* === Spacing System (8px base) === */
	--spacing-1: 0.25rem;   /* 4px */
	--spacing-2: 0.5rem;    /* 8px */
	--spacing-3: 0.75rem;   /* 12px */
	--spacing-4: 1rem;      /* 16px */
	--spacing-5: 1.25rem;   /* 20px */
	--spacing-6: 1.5rem;    /* 24px */
	--spacing-8: 2rem;      /* 32px */
	--spacing-10: 2.5rem;   /* 40px */
	--spacing-12: 3rem;     /* 48px */
	--spacing-16: 4rem;     /* 64px */
	
	/* === Border Radius === */
	--radius-sm: 8px;
	--radius-md: 12px;
	--radius-lg: 16px;
	--radius-xl: 20px;
	--radius-2xl: 24px;
	--radius-full: 9999px;
	
	/* === Layered Shadow System (Elevation) === */
	--shadow-xs: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
	--shadow-sm: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px -1px rgba(0, 0, 0, 0.1);
	--shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -2px rgba(0, 0, 0, 0.1);
	--shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -4px rgba(0, 0, 0, 0.1);
	--shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
	--shadow-2xl: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
	--shadow-inner: inset 0 2px 4px 0 rgba(0, 0, 0, 0.05);
	
	/* Modern Hover Shadow */
	--shadow-hover: 0 12px 24px -4px rgba(0, 0, 0, 0.12), 0 6px 8px -6px rgba(0, 0, 0, 0.08);
	
	/* === Transitions (Cubic Bezier for Smoothness) === */
	--transition-base: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
	--transition-smooth: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
	--transition-bounce: all 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55);
	
	/* === Colors (RGB values for transparency) === */
	--color-body-bg: rgb(<?php echo $colorbackbody; ?>);
	--color-primary: rgb(<?php echo $colorbackhmenu1; ?>);
	--color-secondary: rgb(<?php echo $colorbackvmenu1; ?>);
	--color-text: rgb(<?php echo $colortext; ?>);
	--color-text-light: rgba(<?php echo $colortext; ?>, 0.6);
	--color-text-lighter: rgba(<?php echo $colortext; ?>, 0.4);
	--color-link: rgb(<?php echo $colortextlink; ?>);
	--color-link-hover: rgba(<?php echo $colortextlink; ?>, 0.8);
	
	/* Card & Surface Colors */
	--color-card-bg: rgb(<?php echo $colorbacktabcard1; ?>);
	--color-card-border: rgba(0, 0, 0, 0.06);
	
	/* Table Colors */
	--color-table-header: rgb(<?php echo $colorbacktitle1; ?>);
	--color-table-odd: rgb(<?php echo $colorbacklineimpair1; ?>);
	--color-table-even: rgb(<?php echo $colorbacklinepair1; ?>);
	<?php if ($colorbacklinepairhover != '') { ?>
	--color-table-hover: rgba(<?php echo $colorbacklinepairhover; ?>, 0.5);
	<?php } else { ?>
	--color-table-hover: rgba(59, 130, 246, 0.08);
	<?php } ?>
	<?php if ($colorbacklinepairchecked != '') { ?>
	--color-table-checked: rgba(<?php echo $colorbacklinepairchecked; ?>, 0.7);
	<?php } else { ?>
	--color-table-checked: rgba(59, 130, 246, 0.15);
	<?php } ?>
	
	/* Button Colors */
	--color-button-primary: rgb(<?php echo $butactionbg; ?>);
	--color-button-text: rgb(<?php echo $textbutaction; ?>);
	--color-button-hover: rgba(<?php echo $butactionbg; ?>, 0.9);
	
	/* Status Colors */
	--color-success: rgb(<?php echo $textSuccess; ?>);
	--color-warning: rgb(<?php echo $textWarning; ?>);
	--color-danger: rgb(<?php echo $textDanger; ?>);
	
	/* Neutral Palette (Modern Grays) */
	--gray-50: #f8fafc;
	--gray-100: #f1f5f9;
	--gray-200: #e2e8f0;
	--gray-300: #cbd5e1;
	--gray-400: #94a3b8;
	--gray-500: #64748b;
	--gray-600: #475569;
	--gray-700: #334155;
	--gray-800: #1e293b;
	--gray-900: #0f172a;
}

/* ===========================
   GLOBAL RESET & BASE STYLES
   =========================== */
* {
	box-sizing: border-box;
	margin: 0;
	padding: 0;
}

html {
	font-size: 16px;
	-webkit-font-smoothing: antialiased;
	-moz-osx-font-smoothing: grayscale;
	text-rendering: optimizeLegibility;
}

body {
	font-family: var(--font-family-base);
	font-size: var(--font-size-base);
	font-weight: var(--font-weight-normal);
	line-height: var(--line-height-normal);
	color: var(--color-text);
	background-color: var(--gray-50) !important; /* Modern off-white background */
	letter-spacing: var(--letter-spacing-normal);
	min-height: 100vh;
}

/* Better Text Rendering */
h1, h2, h3, h4, h5, h6 {
	font-weight: var(--font-weight-semibold);
	line-height: var(--line-height-tight);
	letter-spacing: var(--letter-spacing-tight);
	margin-bottom: var(--spacing-4);
	color: var(--gray-900);
}

h1 { font-size: var(--font-size-3xl); }
h2 { font-size: var(--font-size-2xl); }
h3 { font-size: var(--font-size-xl); }
h4 { font-size: var(--font-size-lg); }
h5 { font-size: var(--font-size-base); }
h6 { font-size: var(--font-size-sm); }

p {
	margin-bottom: var(--spacing-4);
	line-height: var(--line-height-relaxed);
}

a {
	color: var(--color-link);
	text-decoration: none;
	transition: var(--transition-base);
	font-weight: var(--font-weight-medium);
}

a:hover {
	color: var(--color-link-hover);
	text-decoration: none;
}

/* ===========================
   MODERN CARD SYSTEM
   =========================== */
.fiche,
.card,
div.tabBar,
div.tabsAction,
.fichehalfright,
.fichehalfleft,
.fichecenter,
.fichethirdleft,
.fichethirdright,
.refidno,
.pagination,
.titre {
	background: var(--color-card-bg) !important;
	border-radius: var(--radius-lg) !important;
	box-shadow: var(--shadow-md) !important;
	border: 1px solid var(--color-card-border) !important;
	padding: var(--spacing-6) !important;
	margin-bottom: var(--spacing-5) !important;
	transition: var(--transition-smooth) !important;
}

.fiche:hover,
.card:hover {
	box-shadow: var(--shadow-hover) !important;
	transform: translateY(-2px);
}

/* Subtle Card Variants */
.info-box,
.box {
	background: var(--gray-50) !important;
	border-radius: var(--radius-md) !important;
	padding: var(--spacing-5) !important;
	border: 1px solid var(--gray-200) !important;
	box-shadow: var(--shadow-sm) !important;
}

/* ===========================
   MODERN TABLE STYLES (Odoo-inspired)
   =========================== */
table.liste,
table.noborder,
table.tagtable,
.table,
table.centpercent {
	width: 100% !important;
	background: white !important;
	border-radius: var(--radius-lg) !important;
	overflow: hidden !important;
	box-shadow: var(--shadow-md) !important;
	border-collapse: separate !important;
	border-spacing: 0 !important;
	border: none !important;
	margin-bottom: var(--spacing-6) !important;
}

/* Remove Vertical Borders - Odoo Style */
table.liste td,
table.liste th,
table.noborder td,
table.noborder th {
	border-left: none !important;
	border-right: none !important;
	border-top: none !important;
	border-bottom: 1px solid var(--gray-200) !important;
	padding: var(--spacing-4) var(--spacing-5) !important;
	vertical-align: middle !important;
	line-height: var(--line-height-relaxed) !important;
}

/* Modern Table Header */
table.liste thead tr,
table.liste tr.liste_titre,
.liste_titre {
	background: var(--gray-100) !important;
	border-bottom: 2px solid var(--gray-300) !important;
}

table.liste thead th,
table.liste tr.liste_titre td,
table.liste tr.liste_titre th {
	font-weight: var(--font-weight-semibold) !important;
	font-size: var(--font-size-sm) !important;
	text-transform: uppercase !important;
	letter-spacing: var(--letter-spacing-wide) !important;
	color: var(--gray-700) !important;
	padding: var(--spacing-4) var(--spacing-5) !important;
	border-bottom: 2px solid var(--gray-300) !important;
}

/* Alternating Row Colors (Subtle) */
table.liste tbody tr:nth-child(odd),
tr.oddeven.impair,
tr.impair {
	background: white !important;
}

table.liste tbody tr:nth-child(even),
tr.oddeven.pair,
tr.pair {
	background: var(--gray-50) !important;
}

/* Modern Row Hover Effect */
table.liste tbody tr:hover,
tr.oddeven:hover,
tr.liste_titre:hover {
	background: var(--color-table-hover) !important;
	transition: var(--transition-base) !important;
	cursor: pointer;
}

/* Selected/Checked Row */
table.liste tbody tr.selected,
tr.oddeven.selected,
tr.trforbreak {
	background: var(--color-table-checked) !important;
	border-left: 3px solid var(--color-link) !important;
}

/* Remove Last Row Border */
table.liste tbody tr:last-child td {
	border-bottom: none !important;
}

/* ===========================
   MODERN BUTTON SYSTEM
   =========================== */
.button,
.butAction,
.butActionNew,
input[type="submit"],
input[type="button"],
button,
a.button {
	display: inline-flex !important;
	align-items: center !important;
	justify-content: center !important;
	gap: var(--spacing-2) !important;
	padding: var(--spacing-3) var(--spacing-6) !important;
	font-family: var(--font-family-base) !important;
	font-size: var(--font-size-sm) !important;
	font-weight: var(--font-weight-medium) !important;
	line-height: 1.5 !important;
	text-align: center !important;
	text-decoration: none !important;
	border-radius: var(--radius-md) !important;
	border: none !important;
	cursor: pointer !important;
	transition: var(--transition-smooth) !important;
	box-shadow: var(--shadow-sm) !important;
	white-space: nowrap !important;
	background: var(--color-button-primary) !important;
	color: var(--color-button-text) !important;
}

.button:hover,
.butAction:hover,
.butActionNew:hover,
input[type="submit"]:hover,
input[type="button"]:hover,
button:hover,
a.button:hover {
	background: var(--color-button-hover) !important;
	box-shadow: var(--shadow-lg) !important;
	transform: translateY(-2px) !important;
	color: white !important;
}

.button:active,
.butAction:active {
	transform: translateY(0px) !important;
	box-shadow: var(--shadow-sm) !important;
}

/* Secondary Button */
.butActionRefused,
.butActionDelete,
.button.secondary {
	background: white !important;
	color: var(--gray-700) !important;
	border: 1px solid var(--gray-300) !important;
	box-shadow: var(--shadow-xs) !important;
}

.butActionRefused:hover,
.button.secondary:hover {
	background: var(--gray-50) !important;
	border-color: var(--gray-400) !important;
	color: var(--gray-900) !important;
}

/* Danger Button */
.butActionDelete:hover {
	background: var(--color-danger) !important;
	color: white !important;
	border-color: var(--color-danger) !important;
}

/* ===========================
   MODERN FORM INPUTS
   =========================== */
input[type="text"],
input[type="email"],
input[type="password"],
input[type="number"],
input[type="date"],
input[type="tel"],
select,
textarea,
.select2-container .select2-selection {
	width: 100% !important;
	padding: var(--spacing-3) var(--spacing-4) !important;
	font-family: var(--font-family-base) !important;
	font-size: var(--font-size-base) !important;
	font-weight: var(--font-weight-normal) !important;
	line-height: var(--line-height-normal) !important;
	color: var(--gray-900) !important;
	background: white !important;
	border: 1px solid var(--gray-300) !important;
	border-radius: var(--radius-md) !important;
	box-shadow: var(--shadow-xs) !important;
	transition: var(--transition-base) !important;
}

input:focus,
select:focus,
textarea:focus {
	outline: none !important;
	border-color: var(--color-link) !important;
	box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1), var(--shadow-sm) !important;
}

/* ===========================
   SIDEBAR (LEFT MENU)
   =========================== */
#id-left,
.side-nav,
.vmenu {
	background: white !important;
	border-right: 1px solid var(--gray-200) !important;
	box-shadow: var(--shadow-md) !important;
	padding: var(--spacing-4) 0 !important;
	overflow-y: auto !important;
}

.menu_titre,
.vmenu .menu {
	padding: var(--spacing-3) var(--spacing-5) !important;
	font-weight: var(--font-weight-medium) !important;
	font-size: var(--font-size-sm) !important;
	color: var(--gray-700) !important;
	border-radius: var(--radius-sm) !important;
	margin: var(--spacing-1) var(--spacing-3) !important;
	transition: var(--transition-base) !important;
}

.menu_titre:hover,
.vmenu .menu:hover,
.vmenu a:hover {
	background: var(--gray-100) !important;
	color: var(--color-link) !important;
	transform: translateX(4px) !important;
}

.vmenu a.vsmenu,
.vmenu .vsmenu {
	padding: var(--spacing-2) var(--spacing-5) var(--spacing-2) var(--spacing-8) !important;
	font-size: var(--font-size-sm) !important;
	color: var(--gray-600) !important;
}

/* Active Menu Item */
.vmenu .tmenusel,
.vmenu a.vmenu:hover {
	background: linear-gradient(90deg, var(--color-link) 3px, var(--gray-100) 3px) !important;
	color: var(--color-link) !important;
	font-weight: var(--font-weight-semibold) !important;
}

/* ===========================
   TOP MENU BAR
   =========================== */
#id-top,
.tmenu {
	background: white !important;
	border-bottom: 1px solid var(--gray-200) !important;
	box-shadow: var(--shadow-sm) !important;
	padding: var(--spacing-3) var(--spacing-6) !important;
	height: auto !important;
}

.tmenu a,
.topmenu a {
	padding: var(--spacing-3) var(--spacing-4) !important;
	margin: 0 var(--spacing-1) !important;
	border-radius: var(--radius-md) !important;
	font-weight: var(--font-weight-medium) !important;
	font-size: var(--font-size-sm) !important;
	color: var(--gray-700) !important;
	transition: var(--transition-base) !important;
}

.tmenu a:hover,
.topmenu a:hover {
	background: var(--gray-100) !important;
	color: var(--color-link) !important;
}

.tmenusel,
.topmenusel {
	background: var(--gray-100) !important;
	color: var(--color-link) !important;
	font-weight: var(--font-weight-semibold) !important;
	border-bottom: 2px solid var(--color-link) !important;
}

/* ===========================
   BREADCRUMB
   =========================== */
.pagination,
.breadcrumb {
	display: flex !important;
	align-items: center !important;
	gap: var(--spacing-2) !important;
	background: white !important;
	padding: var(--spacing-3) var(--spacing-5) !important;
	border-radius: var(--radius-lg) !important;
	box-shadow: var(--shadow-sm) !important;
	font-size: var(--font-size-sm) !important;
	margin-bottom: var(--spacing-5) !important;
}

.pagination a,
.breadcrumb a {
	color: var(--color-link) !important;
	font-weight: var(--font-weight-medium) !important;
}

.pagination span,
.breadcrumb span {
	color: var(--gray-500) !important;
}

/* ===========================
   TABS
   =========================== */
.tabs,
.tabBar,
ul.tabs {
	display: flex !important;
	gap: var(--spacing-2) !important;
	background: white !important;
	padding: var(--spacing-2) var(--spacing-5) !important;
	border-radius: var(--radius-lg) var(--radius-lg) 0 0 !important;
	border-bottom: 1px solid var(--gray-200) !important;
	box-shadow: var(--shadow-xs) !important;
}

.tabBar a,
.tabs a,
ul.tabs li a {
	padding: var(--spacing-3) var(--spacing-5) !important;
	border-radius: var(--radius-md) !important;
	font-weight: var(--font-weight-medium) !important;
	font-size: var(--font-size-sm) !important;
	color: var(--gray-600) !important;
	transition: var(--transition-base) !important;
	border: none !important;
}

.tabBar a:hover,
.tabs a:hover {
	background: var(--gray-100) !important;
	color: var(--gray-900) !important;
}

.tabBar a.tabactive,
.tabs a.active,
ul.tabs li.active a {
	background: var(--color-link) !important;
	color: white !important;
	box-shadow: var(--shadow-sm) !important;
}

/* ===========================
   BADGES & LABELS
   =========================== */
.badge,
.classfortooltip,
span.badge {
	display: inline-flex !important;
	align-items: center !important;
	padding: var(--spacing-1) var(--spacing-3) !important;
	font-size: var(--font-size-xs) !important;
	font-weight: var(--font-weight-semibold) !important;
	line-height: 1 !important;
	border-radius: var(--radius-full) !important;
	background: var(--gray-100) !important;
	color: var(--gray-700) !important;
}

.badge.badge-status-success {
	background: rgba(34, 197, 94, 0.1) !important;
	color: rgb(22, 163, 74) !important;
}

.badge.badge-status-warning {
	background: rgba(251, 191, 36, 0.1) !important;
	color: rgb(217, 119, 6) !important;
}

.badge.badge-status-danger {
	background: rgba(239, 68, 68, 0.1) !important;
	color: rgb(220, 38, 38) !important;
}

/* ===========================
   TOOLTIPS
   =========================== */
.tooltip,
.classfortooltip {
	position: relative !important;
	cursor: help !important;
}

.tooltip::after {
	content: attr(title) !important;
	position: absolute !important;
	bottom: 125% !important;
	left: 50% !important;
	transform: translateX(-50%) !important;
	padding: var(--spacing-2) var(--spacing-3) !important;
	background: var(--gray-900) !important;
	color: white !important;
	font-size: var(--font-size-xs) !important;
	font-weight: var(--font-weight-medium) !important;
	border-radius: var(--radius-md) !important;
	white-space: nowrap !important;
	opacity: 0 !important;
	pointer-events: none !important;
	transition: var(--transition-base) !important;
	box-shadow: var(--shadow-lg) !important;
}

.tooltip:hover::after {
	opacity: 1 !important;
}

/* ===========================
   MODALS & DIALOGS
   =========================== */
.ui-dialog,
.modal {
	border-radius: var(--radius-xl) !important;
	box-shadow: var(--shadow-2xl) !important;
	border: none !important;
	overflow: hidden !important;
}

.ui-dialog-titlebar,
.modal-header {
	background: white !important;
	padding: var(--spacing-5) var(--spacing-6) !important;
	border-bottom: 1px solid var(--gray-200) !important;
}

.ui-dialog-title,
.modal-title {
	font-size: var(--font-size-xl) !important;
	font-weight: var(--font-weight-semibold) !important;
	color: var(--gray-900) !important;
}

.ui-dialog-content,
.modal-body {
	padding: var(--spacing-6) !important;
}

/* ===========================
   ALERTS & NOTIFICATIONS
   =========================== */
.info,
.ok,
.warning,
.error,
.alert {
	padding: var(--spacing-4) var(--spacing-5) !important;
	border-radius: var(--radius-lg) !important;
	border-left: 4px solid !important;
	margin-bottom: var(--spacing-4) !important;
	font-weight: var(--font-weight-medium) !important;
	box-shadow: var(--shadow-sm) !important;
}

.info,
.alert-info {
	background: rgba(59, 130, 246, 0.1) !important;
	border-color: rgb(59, 130, 246) !important;
	color: rgb(30, 64, 175) !important;
}

.ok,
.alert-success {
	background: rgba(34, 197, 94, 0.1) !important;
	border-color: rgb(34, 197, 94) !important;
	color: rgb(22, 101, 52) !important;
}

.warning,
.alert-warning {
	background: rgba(251, 191, 36, 0.1) !important;
	border-color: rgb(251, 191, 36) !important;
	color: rgb(146, 64, 14) !important;
}

.error,
.alert-danger {
	background: rgba(239, 68, 68, 0.1) !important;
	border-color: rgb(239, 68, 68) !important;
	color: rgb(153, 27, 27) !important;
}

/* ===========================
   LOADING SPINNERS
   =========================== */
.loading,
.spinner {
	display: inline-block !important;
	width: 20px !important;
	height: 20px !important;
	border: 2px solid var(--gray-200) !important;
	border-top-color: var(--color-link) !important;
	border-radius: var(--radius-full) !important;
	animation: spin 0.8s linear infinite !important;
}

@keyframes spin {
	to { transform: rotate(360deg); }
}

/* ===========================
   PROGRESS BARS
   =========================== */
.progress {
	width: 100% !important;
	height: 8px !important;
	background: var(--gray-200) !important;
	border-radius: var(--radius-full) !important;
	overflow: hidden !important;
	box-shadow: var(--shadow-inner) !important;
}

.progress-bar {
	height: 100% !important;
	background: linear-gradient(90deg, var(--color-link), var(--color-link-hover)) !important;
	border-radius: var(--radius-full) !important;
	transition: width 0.3s ease !important;
}

/* ===========================
   UTILITY CLASSES
   =========================== */
.text-center { text-align: center !important; }
.text-right { text-align: right !important; }
.text-left { text-align: left !important; }

.font-bold { font-weight: var(--font-weight-bold) !important; }
.font-semibold { font-weight: var(--font-weight-semibold) !important; }
.font-medium { font-weight: var(--font-weight-medium) !important; }
.font-normal { font-weight: var(--font-weight-normal) !important; }

.text-xs { font-size: var(--font-size-xs) !important; }
.text-sm { font-size: var(--font-size-sm) !important; }
.text-base { font-size: var(--font-size-base) !important; }
.text-lg { font-size: var(--font-size-lg) !important; }
.text-xl { font-size: var(--font-size-xl) !important; }

.mb-0 { margin-bottom: 0 !important; }
.mb-2 { margin-bottom: var(--spacing-2) !important; }
.mb-4 { margin-bottom: var(--spacing-4) !important; }
.mb-6 { margin-bottom: var(--spacing-6) !important; }

.mt-0 { margin-top: 0 !important; }
.mt-2 { margin-top: var(--spacing-2) !important; }
.mt-4 { margin-top: var(--spacing-4) !important; }
.mt-6 { margin-top: var(--spacing-6) !important; }

.p-0 { padding: 0 !important; }
.p-2 { padding: var(--spacing-2) !important; }
.p-4 { padding: var(--spacing-4) !important; }
.p-6 { padding: var(--spacing-6) !important; }

/* ===========================
   RESPONSIVE DESIGN
   =========================== */
@media (max-width: 768px) {
	:root {
		--font-size-base: 14px;
		--spacing-4: 0.875rem;
		--spacing-6: 1.25rem;
	}
	
	.fiche,
	.card,
	table.liste {
		border-radius: var(--radius-md) !important;
		padding: var(--spacing-4) !important;
	}
	
	table.liste td,
	table.liste th {
		padding: var(--spacing-3) !important;
		font-size: var(--font-size-sm) !important;
	}
}

/* ===========================
   PRINT STYLES
   =========================== */
@media print {
	body {
		background: white !important;
	}
	
	.fiche,
	.card,
	table.liste {
		box-shadow: none !important;
		border: 1px solid var(--gray-300) !important;
	}
	
	.button,
	.butAction,
	.pagination {
		display: none !important;
	}
}

/* ===========================
   SCROLLBAR STYLING (Webkit)
   =========================== */
::-webkit-scrollbar {
	width: 10px;
	height: 10px;
}

::-webkit-scrollbar-track {
	background: var(--gray-100);
	border-radius: var(--radius-sm);
}

::-webkit-scrollbar-thumb {
	background: var(--gray-400);
	border-radius: var(--radius-sm);
	transition: var(--transition-base);
}

::-webkit-scrollbar-thumb:hover {
	background: var(--gray-500);
}

/* ===========================
   ACCESSIBILITY
   =========================== */
.sr-only {
	position: absolute !important;
	width: 1px !important;
	height: 1px !important;
	padding: 0 !important;
	margin: -1px !important;
	overflow: hidden !important;
	clip: rect(0, 0, 0, 0) !important;
	white-space: nowrap !important;
	border-width: 0 !important;
}

/* Focus Visible for Keyboard Navigation */
*:focus-visible {
	outline: 2px solid var(--color-link) !important;
	outline-offset: 2px !important;
}

/* ===========================
   FINAL POLISH
   =========================== */
/* Smooth Page Transitions */
.page-content {
	animation: fadeIn 0.3s ease-in-out;
}

@keyframes fadeIn {
	from {
		opacity: 0;
		transform: translateY(10px);
	}
	to {
		opacity: 1;
		transform: translateY(0);
	}
}

/* Selection Color */
::selection {
	background: rgba(59, 130, 246, 0.2);
	color: inherit;
}

::-moz-selection {
	background: rgba(59, 130, 246, 0.2);
	color: inherit;
}

<?php
// Include the global.inc.php that include the badges, btn, info-box, dropdown, progress...
require __DIR__.'/global.inc.php';

if (is_object($db)) {
	$db->close();
}