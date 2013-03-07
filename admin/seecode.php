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
require_once '../../../include/cp_header.php';
require_once '../include/common.php';

require_once PORTFOLIO_PATH.'admin/functions.php';

$resize_width = portfolio_utils::getModuleOption('resize_width');
$resize_height = portfolio_utils::getModuleOption('resize_height');
$createThumbs = portfolio_utils::getModuleOption('create_thumbs');
$thumbs_width = portfolio_utils::getModuleOption('thumbs_width');
$thumbs_height = portfolio_utils::getModuleOption('thumbs_height');
$thumbs_prefix = portfolio_utils::getModuleOption('thumbs_prefix');
$text_link = portfolio_utils::getModuleOption('text_link');
$text_link_ahref = portfolio_utils::makeHrefTitle($text_link);
$photosUrl = portfolio_utils::getModuleOption('images_url');
$photosPath = portfolio_utils::getModuleOption('images_path');
$thumbStyle = portfolio_utils::getModuleOption('thumb_style');
if($createThumbs) {
    $prefixLength = strlen($thumbs_prefix) - 1;
}

$photo = isset($_GET['photo']) ? $_GET['photo'] : '';
if($photo == '') {
     exit(_AM_PORTFOLIO_ERROR_1);
}

$thumbName = portfolio_utils::getModuleOption('thumbs_path').DIRECTORY_SEPARATOR.$thumbs_prefix.$photo;
if(file_exists($thumbName)) {
    // Génération du code pour voir l'image
    $pictureFullUrl = "$photosUrl/$photo";
    $thumbFullUrl = portfolio_utils::getModuleOption('thumbs_url')."/$thumbs_prefix$photo";
    $pictureDimensions = getimagesize($photosPath.DIRECTORY_SEPARATOR.$photo);
    $thumbDimensions = getimagesize($thumbName);
    $pictureWidth = $pictureDimensions[0] + 20;
    $pictureHeight = $pictureDimensions[1] + 20;
    $thumbWidth = $thumbDimensions[0];
    $thumbHeight = $thumbDimensions[1];
    echo "<h3>"._AM_PORTFOLIO_HERE_IS_THE_CODE."</h3>";
    $text_link = "<a onclick=\"pop=window.open('', 'wclose', 'width=$pictureWidth, height=$pictureHeight, dependent=yes, toolbar=no, scrollbars=no, status=no, resizable=no, fullscreen=no, titlebar=no, left=0, top=0', 'false'); pop.focus(); \" href=\"$pictureFullUrl\" target=\"wclose\" title=\"$text_link_ahref\"><img src=\"$thumbFullUrl\" width=\"$thumbWidth\" height=\"$thumbHeight\" alt=\"$text_link_ahref\" style=\"$thumbStyle\" /></a>";
    echo "<textarea rows=10 cols=70 readonly name='thecode' id='thecode'>".htmlentities($text_link)."</textarea>";
    echo "<br />"._AM_PORTFOLIO_COPY;
    echo "<br /><br /><b>"._AM_PORTFOLIO_COPY_WYSIWYG."</b><br />";
    echo "<div>".$text_link."</div>\n";
} else {
    echo _AM_PORTFOLIO_ERROR_2;
}
?>