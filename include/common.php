<?php
/**
 * ****************************************************************************
 * portfolio - MODULE FOR XOOPS
 * Copyright (c) Hervé Thouzard of Instant Zero (http://www.instant-zero.com)
 *
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @copyright       Hervé Thouzard of Instant Zero (http://www.instant-zero.com)
 * @license         http://www.fsf.org/copyleft/gpl.html GNU public license
 * @package         portfolio
 * @author 			Hervé Thouzard of Instant Zero (http://www.instant-zero.com)
 *
 * Version : $Id:
 * ****************************************************************************
 */
if (!defined("XOOPS_ROOT_PATH")) {
 	die("XOOPS root path not defined");
}

if( !defined("PORTFOLIO_DIRNAME") ) {
	define("PORTFOLIO_DIRNAME", 'portfolio');
	define("PORTFOLIO_URL", XOOPS_URL.'/modules/'.PORTFOLIO_DIRNAME.'/');
	define("PORTFOLIO_PATH", XOOPS_ROOT_PATH.'/modules/'.PORTFOLIO_DIRNAME.'/');
	define("PORTFOLIO_IMAGES_URL", PORTFOLIO_URL.'images/');		// Les images du module (l'url)
	define("PORTFOLIO_IMAGES_PATH", PORTFOLIO_PATH.'images/');	// Les images du module (le chemin)
	define("PORTFOLIO_JS_URL", PORTFOLIO_URL.'js/');
}

// Chargement des handler et des autres classes
require_once PORTFOLIO_PATH.'class/portfolio_utils.php';
require_once PORTFOLIO_PATH.'config.php';

// Définition des images
if( !defined("_PORTFOLIO_EDIT")) {
	global $xoopsConfig;
	if (isset($xoopsConfig) && file_exists(PORTFOLIO_PATH.'language/'.$xoopsConfig['language'].'/main.php')) {
			require PORTFOLIO_PATH.'language/'.$xoopsConfig['language'].'/main.php';
	} else {
		require PORTFOLIO_PATH.'language/english/main.php';
	}

	$icones = array(
		'edit' => "<img src='". PORTFOLIO_IMAGES_URL ."edit.png' alt='"._PORTFOLIO_EDIT."' align='middle' />",
		'delete' => "<img src='". PORTFOLIO_IMAGES_URL ."delete.png' alt='"._PORTFOLIO_DELETE."' align='middle' />"
	);
}

?>