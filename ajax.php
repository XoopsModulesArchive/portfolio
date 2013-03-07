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
error_reporting(0);
@$xoopsLogger->activated = false;

$return = '';

switch(portfolio_utils::getModuleOption('effect_method')) {
	case 'dfg1':
		require_once XOOPS_ROOT_PATH.'/class/template.php';
		require_once XOOPS_ROOT_PATH.'/class/xoopslists.php';
		$xoopsTpl = new XoopsTpl();
		$xoopsTpl->assign('albumTitle', portfolio_utils::getModuleOption('album_title'));
		$xoopsTpl->assign('portfolioTitle', portfolio_utils::getModuleOption('portfolio_title'));
		$xoopsTpl->assign('thumbnail_dir', portfolio_utils::getModuleOption('thumbs_url').'/');
		$xoopsTpl->assign('image_dir', portfolio_utils::getModuleOption('images_url').'/');
		$xoopsTpl->assign('photosDelay', portfolio_utils::getModuleOption('photos_delay'));
		$xoopsTpl->assign('moduleName', portfolio_utils::getModuleName());
        $photos = XoopsLists::getImgListAsArray(portfolio_utils::getModuleOption('images_path'));
        sort($photos);
        $photosUrl = portfolio_utils::getModuleOption('images_url');
        $photosPath = portfolio_utils::getModuleOption('images_path');
        $createThumbs = portfolio_utils::getModuleOption('create_thumbs');
        $thumbs_prefix = portfolio_utils::getModuleOption('thumbs_prefix');
        
        if($createThumbs && xoops_trim($thumbs_prefix) != '') {
            $prefixLength = strlen($thumbs_prefix);
        } else {
        	$prefixLength = 512;
        }
        $thumbsPath = portfolio_utils::getModuleOption('thumbs_path');
        $thumbsUrl = portfolio_utils::getModuleOption('thumbs_url');
        foreach($photos as $photo) {
        	$data = array();
        	$pictureTime = filemtime($photosPath.DIRECTORY_SEPARATOR.$photo);
        	if($pictureTime !== false) {
        		$data['date'] = formatTimestamp($pictureTime, 's');
        	} else {
        		$data['date'] = formatTimestamp(time(), 's');
        	}
        	$data['name'] = $photo;
        	$data['image'] = $photo;
        	
            if($createThumbs) {
                $thumbName = $thumbsPath.DIRECTORY_SEPARATOR.$thumbs_prefix.$photo;
                if(file_exists($thumbName)) {
                	$data['thumbnail'] = $thumbs_prefix.$photo;
                } else {
                    $data['thumbnail'] = '';
                }
            }
            $xoopsTpl->append('photos', $data);
        }		
		$return = $xoopsTpl->fetch('db:portfolio_df1.xml');
		break;
}
if(xoops_trim($return) != '') {
	echo utf8_encode($return);
}
?>