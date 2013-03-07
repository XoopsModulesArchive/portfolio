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
 * @author 			Hervé Thouzard of Instant Zero
 * @link 			http://www.instant-zero.com
 *
 * Version : $Id:
 * ****************************************************************************
 */

/**
 * Post traitement avant l'arrivée de Xoops pour laisser passer l'upload multiple de fichiers
 */
if(isset($_POST['sessionName']) && isset($_POST['sessionValue'])) {
	$_COOKIE[$_POST['sessionName']] = $_POST['sessionValue'];
}

require_once '../../../include/cp_header.php';
require_once '../include/common.php';
require_once XOOPS_ROOT_PATH.'/class/pagenav.php';
require_once PORTFOLIO_PATH.'admin/functions.php';
require_once XOOPS_ROOT_PATH.'/class/xoopsformloader.php';
require_once XOOPS_ROOT_PATH.'/class/xoopslists.php';

$op = 'default';
if (isset($_POST['op'])) {
	$op = $_POST['op'];
} else {
	if ( isset($_GET['op'])) {
    	$op = $_GET['op'];
	}
}

// Vérification de l'existence et de l'état d'écriture des différents répertoire de stockage et de cache
portfolio_utils::prepareFolder(portfolio_utils::getModuleOption('images_path'));

// Lecture de certains paramètres de l'application ********************************************************************
$baseurl = PORTFOLIO_URL.'admin/'.basename(__FILE__);	// URL de ce script
$conf_msg = portfolio_utils::javascriptLinkConfirm(_AM_PORTFOLIO_CONF_DELITEM);

$resize_width = portfolio_utils::getModuleOption('resize_width');
$resize_height = portfolio_utils::getModuleOption('resize_height');
$createThumbs = portfolio_utils::getModuleOption('create_thumbs');
$thumbs_width = portfolio_utils::getModuleOption('thumbs_width');
$thumbs_height = portfolio_utils::getModuleOption('thumbs_height');
$thumbs_prefix = portfolio_utils::getModuleOption('thumbs_prefix');
$text_link = portfolio_utils::getModuleOption('text_link');
$text_link_ahref = portfolio_utils::makeHrefTitle($text_link);
$limit = portfolio_utils::getModuleOption('per_page');

$destname = '';


/**
 * Affichage du pied de page de l'administration
 *
 * PLEASE, KEEP THIS COPYRIGHT *INTACT* !
 */
function show_footer()
{
	echo "<br /><br /><div align='center'><a href='http://www.instant-zero.com' target='_blank' title='Instant Zero'><img src='../images/instantzero.gif' alt='Instant Zero' /></a></div>";
}


global $xoopsConfig;
portfolio_utils::loadLanguageFile('modinfo.php');
portfolio_utils::loadLanguageFile('main.php');

// ******************************************************************************************************************************************
// **** Main ********************************************************************************************************************************
// ******************************************************************************************************************************************
switch ($op) {

	// ****************************************************************************************************************
	case 'default':
	// ****************************************************************************************************************
        xoops_cp_header();
        portfolio_adminMenu(0);
		portfolio_utils::htitle(_MI_PORTFOLIO_ADMENU0, 4);
		portfolio_utils::callJavascriptFile('swfupload/swfupload.js');
		portfolio_utils::callJavascriptFile('swfupload/plugins/swfupload.queue.js');
		portfolio_utils::callJavascriptFile('swfupload/fileprogress.js');
		portfolio_utils::callJavascriptFile('swfupload/handlers.js');
		echo "<link href=\"".PORTFOLIO_URL."css/swfupload.css\" rel=\"stylesheet\" type=\"text/css\" />\n";

		//echo _AM_PORTFOLIO_UPLOAD;
		$sessionName = ini_get('session.name');
		$sessionName = ($xoopsConfig['use_mysession'] && $xoopsConfig['session_name'] != '') ? $xoopsConfig['session_name'] : session_name();
		$sessionValue = $_COOKIE[$sessionName];
		$_SESSION['portfolio_secret'] = uniqid(rand(), true);
		$secret = md5($_SESSION['portfolio_secret']);
?>
		<script type="text/javascript">
			var paramUploadUrl = "<?php echo $baseurl; ?>";
			var paramFlashUrl = "<?php echo PORTFOLIO_URL;?>js/swfupload/Flash/swfupload.swf";
			var paramButtonName = "<?php echo _AM_PORTFOLIO_ADD;?>";
			var paramSessionName = "<?php echo $sessionName;?>";
			var paramSessionValue = "<?php echo $sessionValue;?>";
			var paramMaxUploadSize = "<?php echo portfolio_utils::getPhpUploadMaxFilesize();?> B";
			var paramSecret = "<?php echo $secret;?>";
		</script>
<?php
		portfolio_utils::callJavascriptFile('swfu.js');
?>
	<form id="form1" action="<?php echo $baseurl;?>" method="post" enctype="multipart/form-data">
		<div class="fieldset flash" id="fsUploadProgress">
			<span class="legend"><?php echo _AM_PORTFOLIO_UPLOAD_LIST;?></span>
		</div>
		<div id="divStatus"><?php echo _AM_PORTFOLIO_ZERO_UPLOADED;?></div>
			<div>
				<span id="spanButtonPlaceHolder"></span>
				<input id="btnCancel" type="button" value="<?php echo _AM_PORTFOLIO_CANCEL_UPLOAD;?>" onclick="swfu.cancelQueue();" disabled="disabled" style="margin-left: 2px; font-size: 8pt; height: 29px;" />
			</div>
	</form>
<?php
        show_footer();
		break;

	// ****************************************************************************************************************
	case 'savePhoto':    // Ajout d'une photo (en Ajax via swfupload)
	// ****************************************************************************************************************
        // Vérification de la sécurité
		if(!isset($_POST['secret'])) {
        	exit(1);
        }
        if($_POST['secret'] != md5($_SESSION['portfolio_secret'])) {
        	exit(2);
        }

		if (!isset($_FILES["Filedata"]) || !is_uploaded_file($_FILES["Filedata"]["tmp_name"]) || $_FILES["Filedata"]["error"] != 0) {
			return '';
		}
		$fileName = (get_magic_quotes_gpc()) ? stripslashes($_FILES['Filedata']['name']) : $_FILES['Filedata']['name'];
		$newFileName = portfolio_utils::createUploadName(portfolio_utils::getModuleOption('images_path'), $fileName);

		if (move_uploaded_file($_FILES['Filedata']['tmp_name'], portfolio_utils::getModuleOption('images_path').'/'.$newFileName)) {
	        if($resize_width > 0 && $resize_height > 0) {
		        $retval = portfolio_utils::createThumb(portfolio_utils::getModuleOption('images_path').'/'.basename($newFileName), portfolio_utils::getModuleOption('images_path').'/'.basename($newFileName), $resize_width, $resize_height, true);
	        }
	        if($createThumbs && $thumbs_width > 0 && $thumbs_height > 0) {
                $newDestName2 = portfolio_utils::getModuleOption('thumbs_path').'/'.$thumbs_prefix.basename($newFileName);
                $retval2 = portfolio_utils::createThumb(portfolio_utils::getModuleOption('images_path').'/'.basename($newFileName), $newDestName2, $thumbs_width, $thumbs_height, true);
	        }
		}
		return;
		break;

	// ****************************************************************************************************************
	case 'deletePhoto':	// Suppression d'une photo
	// ****************************************************************************************************************
        xoops_cp_header();
		$id = isset($_GET['photo']) ? $_GET['photo'] : 0;
		if(empty($id)) {
			portfolio_utils::redirect(_AM_PORTFOLIO_ERROR_1, $baseurl, 5);
		}
		$opRedirect = 'liste';
		$filename = portfolio_utils::getModuleOption('images_path').DIRECTORY_SEPARATOR.$id;
		$thumbName = portfolio_utils::getModuleOption('thumbs_path').DIRECTORY_SEPARATOR.$thumbs_prefix.$id;
		if(file_exists($filename)) {
            @unlink($filename);
            if(file_exists($thumbName)) {    // Suppression de la vignette associée
                @unlink($thumbName);
            }
			portfolio_utils::updateCache();
			portfolio_utils::redirect(_AM_PORTFOLIO_FILE_DELETE_OK, $baseurl.'?op='.$opRedirect, 1);
		} else {
		    portfolio_utils::redirect(_AM_PORTFOLIO_NOT_FOUND, $baseurl.'?op='.$opRedirect, 5);
		}
		break;

    // ****************************************************************************************************************
	case 'liste':    // Liste des photos existantes
    // ****************************************************************************************************************
        xoops_cp_header();
        portfolio_adminMenu(1);
		portfolio_utils::htitle(_MI_PORTFOLIO_ADMENU1, 4);

        // Liste des photos existantes
        $photos = array();
        $photos = XoopsLists::getImgListAsArray(portfolio_utils::getModuleOption('images_path'));
        sort($photos);
        if(isset($_GET['start'])) {
            $start = intval($_GET['start']);
        } elseif(isset($_SESSION['photoupload_start'])) {
            $start = $_SESSION['photoupload_start'];
        } else {
            $start = 0;
        }
        $_SESSION['photoupload_start'] = $start;
        $itemsCount = count($photos);
        if( $itemsCount >  $limit) {
            $pagenav = new XoopsPageNav($itemsCount, $limit, $start, 'start', 'op=liste');
            $photos = array_slice($photos, $start, $limit);
        }
        $photosUrl = portfolio_utils::getModuleOption('images_url');
        $photosPath = portfolio_utils::getModuleOption('images_path');
        if($createThumbs && xoops_trim($thumbs_prefix) != '') {
            $prefixLength = strlen($thumbs_prefix);
        } else {
        	$prefixLength = 512;
        }
        $thumbsPath = portfolio_utils::getModuleOption('thumbs_path');
        $thumbsUrl = portfolio_utils::getModuleOption('thumbs_url');

        if(count($photos) > 0) {
		    if(isset($pagenav) && is_object($pagenav)) {
    			echo "<div align='left'>".$pagenav->renderNav().'</div><br />';
		    }
            echo '<div id="PhotosList" style="width: 100%; height: 600px; overflow: auto;">';
            echo "<table border='0' style='size: auto;'><tr>\n";
            $rupture = 0;
            $picturesPerLine = portfolio_utils::getModuleOption('photos_per_line');
            foreach($photos as $photo) {
                if(substr($photo, 0, $prefixLength) !== $thumbs_prefix) {
                    $rupture++;
                    if($createThumbs) {
                        echo "</tr><tr>\n";
                        $rupture = 0;
                    } elseif(!($rupture % $picturesPerLine)) {
                        echo "</tr><tr>\n";
                        $rupture = 0;
                    }
                    $pictureDimensions = getimagesize($photosPath.DIRECTORY_SEPARATOR.$photo);
                    $pictureWidth = $pictureDimensions[0];
                    $pictureHeight = $pictureDimensions[1];
                    echo "<td><a target='_blank' href='$photosUrl/$photo'><img style='max_width: 640px; max-height: 480px;' src='".$photosUrl.'/'.$photo."' /></a><br />$photo ($pictureWidth x $pictureHeight) - <a $conf_msg href='$baseurl?op=deletePhoto&photo=$photo'>"._DELETE."<br /><br /></a></td>";
                    if($createThumbs) {
                        $thumbName = $thumbsPath.DIRECTORY_SEPARATOR.$thumbs_prefix.$photo;
                        if(file_exists($thumbName)) {
                            echo "<td><a target='_blank' href='$thumbsUrl/$thumbs_prefix$photo'><img src='".$thumbsUrl.'/'.$thumbs_prefix.$photo."' /></a><br /><a $conf_msg href='$baseurl?op=deletePhoto&photo=$photo'>"._DELETE."</a></td>";
                            echo "<td><a target='_blank' href='".PORTFOLIO_URL."admin/seecode.php?photo=$photo'><img src='".PORTFOLIO_IMAGES_URL."code2.png' alt='"._AM_PORTFOLIO_SEE_CODE."'/><br />"._AM_PORTFOLIO_SEE_CODE."</a></td>\n";
                        } else {
                            echo "<td>&nbsp;</td><td>&nbsp;</td>\n";
                        }
                    }
                }
            }
            echo "</tr></table>\n";
            echo "</div>";
		    if(isset($pagenav) && is_object($pagenav)) {
    			echo "<br /><div align='left'>".$pagenav->renderNav().'</div>';
		    }
        }
        show_footer();
		break;


	// ****************************************************************************************************************
	case 'instant-zero';	// Publicité
	// ****************************************************************************************************************
        xoops_cp_header();
        portfolio_adminMenu(2);
		echo "<iframe src='http://www.instant-zero.com/modules/liaise/?form_id=2' width='100%' height='600' frameborder='0'></iframe>";
		show_footer();
		break;
}

xoops_cp_footer();
?>