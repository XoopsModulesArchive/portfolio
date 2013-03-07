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

if (!defined('XOOPS_ROOT_PATH')) {
	die("XOOPS root path not defined");
}

$modversion['name'] = _MI_PORTFOLIO_NAME;
$modversion['version'] = 1.0;
$modversion['description'] = _MI_PORTFOLIO_DESC;
$modversion['author'] = "Hervé Thouzard - Instant Zero (http://www.instant-zero.com)";
$modversion['credits'] = "Solo for his Popgen module";
$modversion['help'] = '';
$modversion['license'] = 'GPL';
$modversion['official'] = 0;
$modversion['image'] = 'images/portfolio_logo.png';
$modversion['dirname'] = 'portfolio';

$modversion['sqlfile']['mysql'] = '';

$modversion['hasAdmin'] = 1;
$modversion['adminindex'] = 'admin/index.php';
$modversion['adminmenu'] = 'admin/menu.php';

// ********************************************************************************************************************
// Blocks *************************************************************************************************************
// ********************************************************************************************************************

// ********************************************************************************************************************
// Menu ***************************************************************************************************************
// ********************************************************************************************************************
$modversion['hasMain'] = 1;

// ********************************************************************************************************************
// Recherche **********************************************************************************************************
// ********************************************************************************************************************
$modversion['hasSearch'] = 0;

// ********************************************************************************************************************
// Commentaires *******************************************************************************************************
// ********************************************************************************************************************
$modversion['hasComments'] = 0;

// ********************************************************************************************************************
// Templates **********************************************************************************************************
// ********************************************************************************************************************
$cptt = 0;

$cptt++;
$modversion['templates'][$cptt]['file'] = 'portfolio_index.html';
$modversion['templates'][$cptt]['description'] = "Module index";

$cptt++;
$modversion['templates'][$cptt]['file'] = 'portfolio_df1.html';
$modversion['templates'][$cptt]['description'] = "Df Gallery v1";

$cptt++;
$modversion['templates'][$cptt]['file'] = 'portfolio_df1.xml';
$modversion['templates'][$cptt]['description'] = "XML file for Df Gallery v1";

// ********************************************************************************************************************
// Préférences ********************************************************************************************************
// ********************************************************************************************************************
$cpto = 0;
/**
 * Folder (path) where to save pictures
 */
$cpto++;
$modversion['config'][$cpto]['name'] = 'images_path';
$modversion['config'][$cpto]['title'] = '_MI_PORTFOLIO_OPTION1';
$modversion['config'][$cpto]['description'] = '';
$modversion['config'][$cpto]['formtype'] = 'textbox';
$modversion['config'][$cpto]['valuetype'] = 'text';
$modversion['config'][$cpto]['default'] = XOOPS_UPLOAD_PATH;

/**
 * Folder (url) where to save pictures
 */
$cpto++;
$modversion['config'][$cpto]['name'] = 'images_url';
$modversion['config'][$cpto]['title'] = '_MI_PORTFOLIO_OPTION2';
$modversion['config'][$cpto]['description'] = '';
$modversion['config'][$cpto]['formtype'] = 'textbox';
$modversion['config'][$cpto]['valuetype'] = 'text';
$modversion['config'][$cpto]['default'] = XOOPS_UPLOAD_URL;

/**
 * Mime Types
 * Default values : Web pictures (png, gif, jpeg), zip, pdf, gtar, tar, pdf
 */
$cpto++;
$modversion['config'][$cpto]['name'] = 'mimetypes';
$modversion['config'][$cpto]['title'] = '_MI_PORTFOLIO_OPTION3';
$modversion['config'][$cpto]['description'] = '';
$modversion['config'][$cpto]['formtype'] = 'textarea';
$modversion['config'][$cpto]['valuetype'] = 'text';
$modversion['config'][$cpto]['default'] = "image/gif\nimage/jpeg\nimage/pjpeg\nimage/x-png\nimage/png";

/**
 * MAX Filesize Upload in kilo bytes
 */
$cpto++;
$modversion['config'][$cpto]['name'] = 'maxuploadsize';
$modversion['config'][$cpto]['title'] = '_MI_PORTFOLIO_OPTION4';
$modversion['config'][$cpto]['description'] = '';
$modversion['config'][$cpto]['formtype'] = 'textbox';
$modversion['config'][$cpto]['valuetype'] = 'int';
$modversion['config'][$cpto]['default'] = 1048576;

/**
 * Width, resize picures to ..
 */
$cpto++;
$modversion['config'][$cpto]['name'] = 'resize_width';
$modversion['config'][$cpto]['title'] = '_MI_PORTFOLIO_OPTION5';
$modversion['config'][$cpto]['description'] = '_MI_PORTFOLIO_OPTION5_DSC';
$modversion['config'][$cpto]['formtype'] = 'textbox';
$modversion['config'][$cpto]['valuetype'] = 'int';
$modversion['config'][$cpto]['default'] = '640';

/**
 * Width, resize picures to ..
 */
$cpto++;
$modversion['config'][$cpto]['name'] = 'resize_height';
$modversion['config'][$cpto]['title'] = '_MI_PORTFOLIO_OPTION6';
$modversion['config'][$cpto]['description'] = '_MI_PORTFOLIO_OPTION6_DSC';
$modversion['config'][$cpto]['formtype'] = 'textbox';
$modversion['config'][$cpto]['valuetype'] = 'int';
$modversion['config'][$cpto]['default'] = '480';

/**
 * Photos par ligne dans l'administration
 */
$cpto++;
$modversion['config'][$cpto]['name'] = 'photos_per_line';
$modversion['config'][$cpto]['title'] = '_MI_PORTFOLIO_OPTION7';
$modversion['config'][$cpto]['description'] = '';
$modversion['config'][$cpto]['formtype'] = 'textbox';
$modversion['config'][$cpto]['valuetype'] = 'int';
$modversion['config'][$cpto]['default'] = '1';

/**
 * Create thumbs ?
 */
$cpto++;
$modversion['config'][$cpto]['name'] = 'create_thumbs';
$modversion['config'][$cpto]['title'] = '_MI_PORTFOLIO_OPTION8';
$modversion['config'][$cpto]['description'] = '';
$modversion['config'][$cpto]['formtype'] = 'yesno';
$modversion['config'][$cpto]['valuetype'] = 'int';
$modversion['config'][$cpto]['default'] = 0;

/**
 * Thumbs path
 */
$cpto++;
$modversion['config'][$cpto]['name'] = 'thumbs_path';
$modversion['config'][$cpto]['title'] = '_MI_PORTFOLIO_OPTION16';
$modversion['config'][$cpto]['description'] = '';
$modversion['config'][$cpto]['formtype'] = 'textbox';
$modversion['config'][$cpto]['valuetype'] = 'text';
$modversion['config'][$cpto]['default'] = XOOPS_UPLOAD_PATH;

/**
 * Thumbs URL
 */
$cpto++;
$modversion['config'][$cpto]['name'] = 'thumbs_url';
$modversion['config'][$cpto]['title'] = '_MI_PORTFOLIO_OPTION17';
$modversion['config'][$cpto]['description'] = '';
$modversion['config'][$cpto]['formtype'] = 'textbox';
$modversion['config'][$cpto]['valuetype'] = 'text';
$modversion['config'][$cpto]['default'] = XOOPS_UPLOAD_URL;

/**
 * Thumbs Prefix
 */
$cpto++;
$modversion['config'][$cpto]['name'] = 'thumbs_prefix';
$modversion['config'][$cpto]['title'] = '_MI_PORTFOLIO_OPTION11';
$modversion['config'][$cpto]['description'] = '';
$modversion['config'][$cpto]['formtype'] = 'textbox';
$modversion['config'][$cpto]['valuetype'] = 'text';
$modversion['config'][$cpto]['default'] = 'thumbs_';

/**
 * Thumbs Width
 */
$cpto++;
$modversion['config'][$cpto]['name'] = 'thumbs_width';
$modversion['config'][$cpto]['title'] = '_MI_PORTFOLIO_OPTION9';
$modversion['config'][$cpto]['description'] = '';
$modversion['config'][$cpto]['formtype'] = 'textbox';
$modversion['config'][$cpto]['valuetype'] = 'int';
$modversion['config'][$cpto]['default'] = '100';

/**
 * Thumbs Height
 */
$cpto++;
$modversion['config'][$cpto]['name'] = 'thumbs_height';
$modversion['config'][$cpto]['title'] = '_MI_PORTFOLIO_OPTION10';
$modversion['config'][$cpto]['description'] = '';
$modversion['config'][$cpto]['formtype'] = 'textbox';
$modversion['config'][$cpto]['valuetype'] = 'int';
$modversion['config'][$cpto]['default'] = '100';

/**
 * Text for the link
 */
$cpto++;
$modversion['config'][$cpto]['name'] = 'text_link';
$modversion['config'][$cpto]['title'] = '_MI_PORTFOLIO_OPTION12';
$modversion['config'][$cpto]['description'] = '';
$modversion['config'][$cpto]['formtype'] = 'textbox';
$modversion['config'][$cpto]['valuetype'] = 'text';
$modversion['config'][$cpto]['default'] = _MI_PORTFOLIO_OPTION121;

/**
 * Style de la vignette
 */
$cpto++;
$modversion['config'][$cpto]['name'] = 'thumb_style';
$modversion['config'][$cpto]['title'] = '_MI_PORTFOLIO_OPTION13';
$modversion['config'][$cpto]['description'] = '';
$modversion['config'][$cpto]['formtype'] = 'textbox';
$modversion['config'][$cpto]['valuetype'] = 'text';
$modversion['config'][$cpto]['default'] = "border:1px inset; padding:3px; margin: 3px;";

/**
 * Max pictures per page.
 */
$cpto++;
$modversion['config'][$cpto]['name'] = 'per_page';
$modversion['config'][$cpto]['title'] = '_MI_PORTFOLIO_OPTION15';
$modversion['config'][$cpto]['description'] = '';
$modversion['config'][$cpto]['formtype'] = 'textbox';
$modversion['config'][$cpto]['valuetype'] = 'int';
$modversion['config'][$cpto]['default'] = 10;

/**
 * Method to use to rename pictures and files when they are uploaded
 */
$cpto++;
$modversion['config'][$cpto]['name'] = 'rename_method';
$modversion['config'][$cpto]['title'] = "_MI_PORTFOLIO_RENAME_METHOD";
$modversion['config'][$cpto]['description'] = '';
$modversion['config'][$cpto]['formtype'] = 'select';
$modversion['config'][$cpto]['valuetype'] = 'text';
$modversion['config'][$cpto]['options'] = array(
											"my photo.jpg => myphoto.jpg" => 'method1',
											"my photo.jpg => 5a15b7eb5baf6525.jpg" => 'method2',
											"my photo.jpg => myphoto-5a15b7eb.jpg" => 'method3',
											"my photo.jpg => 20091231-myphoto-5a15b7eb.jpg" => 'method4',
											"my photo.jpg => 20091231-myphoto.jpg" => 'method5',
											"my photo.jpg => _20091231-myphoto.jpg" => 'method6'

											);
$modversion['config'][$cpto]['default'] = 'method2';


/**
 * Type d'effet à utiliser pour créer le portfolio
 */
$cpto++;
$modversion['config'][$cpto]['name'] = 'effect_method';
$modversion['config'][$cpto]['title'] = "_MI_PORTFOLIO_EFFECT_TYPE";
$modversion['config'][$cpto]['description'] = '';
$modversion['config'][$cpto]['formtype'] = 'select';
$modversion['config'][$cpto]['valuetype'] = 'text';
$modversion['config'][$cpto]['options'] = array(
											"Df Gallery v1" => 'dfg1'
											);
$modversion['config'][$cpto]['default'] = 'dfg1';

/**
 * Titre du portfolio
 */
$cpto++;
$modversion['config'][$cpto]['name'] = 'portfolio_title';
$modversion['config'][$cpto]['title'] = '_MI_PORTFOLIO_PORTFOLIO_TITLE';
$modversion['config'][$cpto]['description'] = '';
$modversion['config'][$cpto]['formtype'] = 'textbox';
$modversion['config'][$cpto]['valuetype'] = 'text';
$modversion['config'][$cpto]['default'] = _MI_PORTFOLIO_MY_PORTFOLIO;

/**
 * Titre de l'album
 */
$cpto++;
$modversion['config'][$cpto]['name'] = 'album_title';
$modversion['config'][$cpto]['title'] = '_MI_PORTFOLIO_ALBUM_TITLE';
$modversion['config'][$cpto]['description'] = '';
$modversion['config'][$cpto]['formtype'] = 'textbox';
$modversion['config'][$cpto]['valuetype'] = 'text';
$modversion['config'][$cpto]['default'] = _MI_PORTFOLIO_MY_ALBUM;

/**
 * Texte à afficher sur la page d'index du module
 */
$cpto++;
$modversion['config'][$cpto]['name'] = 'index_text';
$modversion['config'][$cpto]['title'] = '_MI_PORTFOLIO_INDEX_TEXT';
$modversion['config'][$cpto]['description'] = '';
$modversion['config'][$cpto]['formtype'] = 'textarea';
$modversion['config'][$cpto]['valuetype'] = 'text';
$modversion['config'][$cpto]['default'] = '';

/**
 * Largeur de la galerie
 */
$cpto++;
$modversion['config'][$cpto]['name'] = 'gallery_width';
$modversion['config'][$cpto]['title'] = '_MI_PORTFOLIO_GALLERY_WIDTH';
$modversion['config'][$cpto]['description'] = '';
$modversion['config'][$cpto]['formtype'] = 'textbox';
$modversion['config'][$cpto]['valuetype'] = 'text';
$modversion['config'][$cpto]['default'] = '100%';

/**
 * Hauteur de la galerie
 */
$cpto++;
$modversion['config'][$cpto]['name'] = 'gallery_height';
$modversion['config'][$cpto]['title'] = '_MI_PORTFOLIO_GALLERY_HEIGHT';
$modversion['config'][$cpto]['description'] = '';
$modversion['config'][$cpto]['formtype'] = 'textbox';
$modversion['config'][$cpto]['valuetype'] = 'text';
$modversion['config'][$cpto]['default'] = '640';

/**
 * Délai entre chaque photo
 */
$cpto++;
$modversion['config'][$cpto]['name'] = 'photos_delay';
$modversion['config'][$cpto]['title'] = '_MI_PORTFOLIO_PHOTOS_DELAY';
$modversion['config'][$cpto]['description'] = '';
$modversion['config'][$cpto]['formtype'] = 'textbox';
$modversion['config'][$cpto]['valuetype'] = 'int';
$modversion['config'][$cpto]['default'] = 3;

// ********************************************************************************************************************
// Notifications ******************************************************************************************************
// ********************************************************************************************************************
$modversion['hasNotification'] = 0;
?>