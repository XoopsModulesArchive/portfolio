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

/*
 * Page d'index du module, liste des articles
 */
require 'header.php';
$xoopsOption['template_main'] = 'portfolio_index.html';
require XOOPS_ROOT_PATH.'/header.php';

// Texte à afficher sur la page d'accueil
$welcomeText = xoops_trim(portfolio_utils::getModuleOption('index_text'));

// Chargement des librairies Javascript
portfolio_utils::callJavascriptFile('jquery/jquery.js');
portfolio_utils::callJavascriptFile('noconflict.js');
portfolio_utils::callJavascriptFile('jquery.swfobject/jquery.swfobject.min.js');

// Variable pour le template
$xoopsTpl->assign('welcomeMsg', nl2br($welcomeText));
$xoopsTpl->assign('effectMethod', portfolio_utils::getModuleOption('effect_method'));
$xoopsTpl->assign('galleryHeight', portfolio_utils::getModuleOption('gallery_height'));
$xoopsTpl->assign('galleryWidth', portfolio_utils::getModuleOption('gallery_width'));
$xoopsTpl->assign('albumTitle', portfolio_utils::getModuleOption('album_title'));
$xoopsTpl->assign('portfolioTitle', portfolio_utils::getModuleOption('portfolio_title'));

portfolio_utils::setMetas(portfolio_utils::getModuleOption('portfolio_title'), portfolio_utils::getModuleOption('album_title'));
require XOOPS_ROOT_PATH.'/footer.php';
?>