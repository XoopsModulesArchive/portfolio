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


/**
 * A set of useful and common functions
 *
 * @package portfolio
 * @author Hervé Thouzard - Instant Zero (http://xoops.instant-zero.com)
 * @copyright (c) Instant Zero
 *
 * Note: You should be able to use it without the need to instanciate it.
 *
 */
if (!defined('XOOPS_ROOT_PATH')) {
	die("XOOPS root path not defined");
}

class portfolio_utils
{
    // Pour la portabilité de module à module
	const MODULE_NAME = 'portfolio';
	const MODULE_DIRNAME = PORTFOLIO_DIRNAME;
	const MODULE_PATH = PORTFOLIO_PATH;
	const MODULE_URL = PORTFOLIO_URL;
	const MODULE_JS_URL = PORTFOLIO_JS_URL;
	

	/**
	 * Access the only instance of this class
     *
     * @return	object
     *
     * @static
     * @staticvar   object
	 */
	function &getInstance()
	{
		static $instance;
		if (!isset($instance)) {
			$instance = new portfolio_utils();
		}
		return $instance;
	}


	/**
  	 * Returns a module's option (with cache)
  	 *
	 * @param string $option	module option's name
	 * @param boolean $withCache	Do we have to use some cache ?
	 * @return mixed option's value
 	 */
	function getModuleOption($option, $withCache = true)
	{
		global $xoopsModuleConfig, $xoopsModule;
		$repmodule = self::MODULE_NAME;
		static $options = array();
		if(is_array($options) && array_key_exists($option,$options) && $withCache) {
			return $options[$option];
		}

		$retval = false;
		if (isset($xoopsModuleConfig) && (is_object($xoopsModule) && $xoopsModule->getVar('dirname') == $repmodule && $xoopsModule->getVar('isactive'))) {
			if(isset($xoopsModuleConfig[$option])) {
				$retval= $xoopsModuleConfig[$option];
			}
		} else {
			$module_handler =& xoops_gethandler('module');
			$module =& $module_handler->getByDirname($repmodule);
			$config_handler =& xoops_gethandler('config');
			if ($module) {
			    $moduleConfig = $config_handler->getConfigsByCat(0, $module->getVar('mid'));
	    		if(isset($moduleConfig[$option])) {
		    		$retval= $moduleConfig[$option];
	    		}
			}
		}
		$options[$option] = $retval;
		return $retval;
	}



	/**
 	 * Create (in a link) a javascript confirmation's box
 	 *
	 * @param string $message	Message to display
	 * @param boolean $form	Is this a confirmation for a form ?
	 * @return string the javascript code to insert in the link (or in the form)
	 */
	function javascriptLinkConfirm($message, $form = false)
	{
		if(!$form) {
			return "onclick=\"javascript:return confirm('".str_replace("'"," ",$message)."')\"";
		} else {
			return "onSubmit=\"javascript:return confirm('".str_replace("'"," ",$message)."')\"";
		}
	}


	/**
	 * Remove module's cache
	 */
	function updateCache()
	{
		global $xoopsModule;
		$folder = $xoopsModule->getVar('dirname');
		$tpllist = array();
		require_once XOOPS_ROOT_PATH.'/class/xoopsblock.php';
		require_once XOOPS_ROOT_PATH.'/class/template.php';
		$tplfile_handler =& xoops_gethandler('tplfile');
		$tpllist = $tplfile_handler->find(null, null, null, $folder);
		xoops_template_clear_module_cache($xoopsModule->getVar('mid'));			// Clear module's blocks cache

		foreach ($tpllist as $onetemplate) {	// Remove cache for each page.
			if( $onetemplate->getVar('tpl_type') == 'module' ) {
				//	Note, I've been testing all the other methods (like the one of Smarty) and none of them run, that's why I have used this code
				$files_del = array();
				$files_del = glob(XOOPS_CACHE_PATH.'/*'.$onetemplate->getVar('tpl_file').'*');
				if(count($files_del) >0 && is_array($files_del)) {
					foreach($files_del as $one_file) {
						if(is_file($one_file)) {
    						unlink($one_file);
					    }
				    }
			    }
		    }
	    }
	}



	/**
	 * Redirect user with a message
	 *
	 * @param string $message message to display
	 * @param string $url The place where to go
	 * @param integer timeout Time to wait before to redirect
 	 */
	function redirect($message='', $url='index.php', $time=2)
	{
		redirect_header($url, $time, $message);
		exit();
	}


	/**
	 * Internal function used to get the handler of the current module
	 *
	 * @return object The module
	 */
	protected function _getModule()
	{
		static $mymodule;
		if (!isset($mymodule)) {
			global $xoopsModule;
			if (isset($xoopsModule) && is_object($xoopsModule) && $xoopsModule->getVar('dirname') == PORTFOLIO_DIRNAME ) {
				$mymodule =& $xoopsModule;
			} else {
				$hModule = &xoops_gethandler('module');
				$mymodule = $hModule->getByDirname(PORTFOLIO_DIRNAME);
			}
		}
		return $mymodule;
	}

	/**
	 * Returns the module's name (as defined by the user in the module manager) with cache
	 * @return string Module's name
	 */
	function getModuleName()
	{
		static $moduleName;
		if(!isset($moduleName)) {
			$mymodule = self::_getModule();
			$moduleName = $mymodule->getVar('name');
		}
		return $moduleName;
	}


	/**
	 * Create a title for the href tags inside html links
	 *
	 * @param string $title Text to use
	 * @return string Formated text
 	 */
	function makeHrefTitle($title)
	{
		$s = "\"'";
		$r = '  ';
		return strtr($title, $s, $r);
	}




	/**
	 * Mark the mandatory fields of a form with a star
	 *
	 * @param object $sform The form to modify
	 * @param string $caracter The character to use to mark fields
	 * @return object The modified form
	 */
	function &formMarkRequiredFields(&$sform)
	{
		if(self::needsAsterisk()) {
			$required = array();
			foreach($sform->getRequired() as $item) {
				$required[] = $item->_name;
			}
			$elements = array();
			$elements = & $sform->getElements();
			$cnt = count($elements);
			for($i=0; $i<$cnt; $i++) {
				if( is_object($elements[$i]) && in_array($elements[$i]->_name, $required)
				) {
					$elements[$i]->_caption .= ' *';
		}
			}
		}
		return $sform;
	}


	/**
	 * Create an html heading (from h1 to h6)
	 *
	 * @param string $title The text to use
	 * @param integer $level Level to return
	 * @return string The heading
	 */
	function htitle($title = '', $level = 1) {
		printf("<h%01d>%s</h%01d>",$level,$title,$level);
	}

	/**
	 * Replace html entities with their ASCII equivalent
	 *
	 * @param string $chaine The string undecode
	 * @return string The undecoded string
	 */
	function unhtml($chaine)
	{
		$search = $replace = array();
		$chaine = html_entity_decode($chaine);

		for($i=0; $i<=255; $i++) {
			$search[] = '&#'.$i.';';
			$replace[] = chr($i);
		}
		$replace[]='...'; $search[]='…';
		$replace[]="'";	$search[]='‘';
		$replace[]="'";	$search[]= "’";
		$replace[]='-';	$search[] ="&bull;";	// $replace[] = '•';
		$replace[]='—'; $search[]='&mdash;';
		$replace[]='-'; $search[]='&ndash;';
		$replace[]='-'; $search[]='&shy;';
		$replace[]='"'; $search[]='&quot;';
		$replace[]='&'; $search[]='&amp;';
		$replace[]='ˆ'; $search[]='&circ;';
		$replace[]='¡'; $search[]='&iexcl;';
		$replace[]='¦'; $search[]='&brvbar;';
		$replace[]='¨'; $search[]='&uml;';
		$replace[]='¯'; $search[]='&macr;';
		$replace[]='´'; $search[]='&acute;';
		$replace[]='¸'; $search[]='&cedil;';
		$replace[]='¿'; $search[]='&iquest;';
		$replace[]='˜'; $search[]='&tilde;';
		$replace[]="'"; $search[]='&lsquo;';	// $replace[]='‘';
		$replace[]="'"; $search[]='&rsquo;';	// $replace[]='’';
		$replace[]='‚'; $search[]='&sbquo;';
		$replace[]="'"; $search[]='&ldquo;';	// $replace[]='“';
		$replace[]="'"; $search[]='&rdquo;';	// $replace[]='”';
		$replace[]='„'; $search[]='&bdquo;';
		$replace[]='‹'; $search[]='&lsaquo;';
		$replace[]='›'; $search[]='&rsaquo;';
		$replace[]='<'; $search[]='&lt;';
		$replace[]='>'; $search[]='&gt;';
		$replace[]='±'; $search[]='&plusmn;';
		$replace[]='«'; $search[]='&laquo;';
		$replace[]='»'; $search[]='&raquo;';
		$replace[]='×'; $search[]='&times;';
		$replace[]='÷'; $search[]='&divide;';
		$replace[]='¢'; $search[]='&cent;';
		$replace[]='£'; $search[]='&pound;';
		$replace[]='¤'; $search[]='&curren;';
		$replace[]='¥'; $search[]='&yen;';
		$replace[]='§'; $search[]='&sect;';
		$replace[]='©'; $search[]='&copy;';
		$replace[]='¬'; $search[]='&not;';
		$replace[]='®'; $search[]='&reg;';
		$replace[]='°'; $search[]='&deg;';
		$replace[]='µ'; $search[]='&micro;';
		$replace[]='¶'; $search[]='&para;';
		$replace[]='·'; $search[]='&middot;';
		$replace[]='†'; $search[]='&dagger;';
		$replace[]='‡'; $search[]='&Dagger;';
		$replace[]='‰'; $search[]='&permil;';
		$replace[]='Euro'; $search[]='&euro;';		// $replace[]='€'
		$replace[]='¼'; $search[]='&frac14;';
		$replace[]='½'; $search[]='&frac12;';
		$replace[]='¾'; $search[]='&frac34;';
		$replace[]='¹'; $search[]='&sup1;';
		$replace[]='²'; $search[]='&sup2;';
		$replace[]='³'; $search[]='&sup3;';
		$replace[]='á'; $search[]='&aacute;';
		$replace[]='Á'; $search[]='&Aacute;';
		$replace[]='â'; $search[]='&acirc;';
		$replace[]='Â'; $search[]='&Acirc;';
		$replace[]='à'; $search[]='&agrave;';
		$replace[]='À'; $search[]='&Agrave;';
		$replace[]='å'; $search[]='&aring;';
		$replace[]='Å'; $search[]='&Aring;';
		$replace[]='ã'; $search[]='&atilde;';
		$replace[]='Ã'; $search[]='&Atilde;';
		$replace[]='ä'; $search[]='&auml;';
		$replace[]='Ä'; $search[]='&Auml;';
		$replace[]='ª'; $search[]='&ordf;';
		$replace[]='æ'; $search[]='&aelig;';
		$replace[]='Æ'; $search[]='&AElig;';
		$replace[]='ç'; $search[]='&ccedil;';
		$replace[]='Ç'; $search[]='&Ccedil;';
		$replace[]='ð'; $search[]='&eth;';
		$replace[]='Ð'; $search[]='&ETH;';
		$replace[]='é'; $search[]='&eacute;';
		$replace[]='É'; $search[]='&Eacute;';
		$replace[]='ê'; $search[]='&ecirc;';
		$replace[]='Ê'; $search[]='&Ecirc;';
		$replace[]='è'; $search[]='&egrave;';
		$replace[]='È'; $search[]='&Egrave;';
		$replace[]='ë'; $search[]='&euml;';
		$replace[]='Ë'; $search[]='&Euml;';
		$replace[]='ƒ'; $search[]='&fnof;';
		$replace[]='í'; $search[]='&iacute;';
		$replace[]='Í'; $search[]='&Iacute;';
		$replace[]='î'; $search[]='&icirc;';
		$replace[]='Î'; $search[]='&Icirc;';
		$replace[]='ì'; $search[]='&igrave;';
		$replace[]='Ì'; $search[]='&Igrave;';
		$replace[]='ï'; $search[]='&iuml;';
		$replace[]='Ï'; $search[]='&Iuml;';
		$replace[]='ñ'; $search[]='&ntilde;';
		$replace[]='Ñ'; $search[]='&Ntilde;';
		$replace[]='ó'; $search[]='&oacute;';
		$replace[]='Ó'; $search[]='&Oacute;';
		$replace[]='ô'; $search[]='&ocirc;';
		$replace[]='Ô'; $search[]='&Ocirc;';
		$replace[]='ò'; $search[]='&ograve;';
		$replace[]='Ò'; $search[]='&Ograve;';
		$replace[]='º'; $search[]='&ordm;';
		$replace[]='ø'; $search[]='&oslash;';
		$replace[]='Ø'; $search[]='&Oslash;';
		$replace[]='õ'; $search[]='&otilde;';
		$replace[]='Õ'; $search[]='&Otilde;';
		$replace[]='ö'; $search[]='&ouml;';
		$replace[]='Ö'; $search[]='&Ouml;';
		$replace[]='œ'; $search[]='&oelig;';
		$replace[]='Œ'; $search[]='&OElig;';
		$replace[]='š'; $search[]='&scaron;';
		$replace[]='Š'; $search[]='&Scaron;';
		$replace[]='ß'; $search[]='&szlig;';
		$replace[]='þ'; $search[]='&thorn;';
		$replace[]='Þ'; $search[]='&THORN;';
		$replace[]='ú'; $search[]='&uacute;';
		$replace[]='Ú'; $search[]='&Uacute;';
		$replace[]='û'; $search[]='&ucirc;';
		$replace[]='Û'; $search[]='&Ucirc;';
		$replace[]='ù'; $search[]='&ugrave;';
		$replace[]='Ù'; $search[]='&Ugrave;';
		$replace[]='ü'; $search[]='&uuml;';
		$replace[]='Ü'; $search[]='&Uuml;';
		$replace[]='ý'; $search[]='&yacute;';
		$replace[]='Ý'; $search[]='&Yacute;';
		$replace[]='ÿ'; $search[]='&yuml;';
		$replace[]='Ÿ'; $search[]='&Yuml;';
		$chaine = str_replace($search, $replace, $chaine);
		return $chaine;
	}

	/**
	 * Création d'une titre pour être utilisé par l'url rewriting
	 *
	 * @param string $content Le texte à utiliser pour créer l'url
	 * @param integer $urw La limite basse pour créer les mots
	 * @return string Le texte à utiliser pour l'url
	 */
	function makeSeoUrl($content, $urw=1, $removeStartingMinus = false)
	{
		$s = "ÀÁÂÃÄÅÒÓÔÕÖØÈÉÊËÇÌÍÎÏÙÚÛÜŸÑàáâãäåòóôõöøèéêëçìíîïùúûüÿñ '()";
		$r = "AAAAAAOOOOOOEEEECIIIIUUUUYNaaaaaaooooooeeeeciiiiuuuuyn----";
		$content = self::unhtml($content);	// First, remove html entities
		$content = strtr($content, $s, $r);
		$content = strip_tags($content);
		$content = strtolower($content);
		$content = htmlentities($content);	// TODO: Vérifier
		$content = preg_replace('/&([a-zA-Z])(uml|acute|grave|circ|tilde);/','$1',$content);
		$content = html_entity_decode($content);
		$content = eregi_replace('quot',' ', $content);
		$content = eregi_replace("'",' ', $content);
		$content = eregi_replace('-',' ', $content);
		$content = eregi_replace('[[:punct:]]','', $content);
		// Selon option mais attention au fichier .htaccess !
		// $content = eregi_replace('[[:digit:]]','', $content);
		$content = preg_replace("/[^a-z|A-Z|0-9]/",'-', $content);

		$words = explode(' ', $content);
		$keywords = '';
		foreach($words as $word) {
			if( strlen($word) >= $urw )	{
				$keywords .= '-'.trim($word);
			}
		}
		if( !$keywords) {
			$keywords = '-';
		}
		// Supprime les tirets en double
		$keywords = str_replace('---','-',$keywords);
		$keywords = str_replace('--','-',$keywords);
		// Supprime un éventuel tiret à la fin de la chaine
		if(substr($keywords, strlen($keywords)-1, 1) == '-') {
			$keywords = substr($keywords, 0, strlen($keywords)-1);
		}
		// Et un tiret en début de chaine
		if($removeStartingMinus) {
		    if(substr($keywords, 0, 1) == '-') {
		        $keywords = substr($keywords, 1);
		    }
		}
		return $keywords;
	}

	/**
	 * Fonction interne chargée de créer un nom unique (en fonction de paramètres)
	 *
	 * @param string $folder The folder where the file will be saved
	 * @param string $fileName Original filename (coming from the user)
	 * @param boolean $trimName Do we need to create a short unique name ?
	 * @return string The unique filename to use (with its extension)
	 */
	private function createRandomUniqueName($folder, $fileName, $trimName = false)
	{
		$workingfolder = $folder;
		if( xoops_substr($workingfolder,strlen($workingfolder)-1,1) != '/' ) {
			$workingfolder .= '/';
		}
		$ext = basename($fileName);
		$ext = explode('.', $ext);
		$ext = '.'.$ext[count($ext)-1];

		$true = true;
		while($true) {
			$ipbits = explode('.', $_SERVER['REMOTE_ADDR']);
			list($usec, $sec) = explode(' ',microtime());
			$usec = (integer) ($usec * 65536);
			$sec = ((integer) $sec) & 0xFFFF;

			if($trimName) {
				$uid = sprintf("%04x%04x%04x",($ipbits[0] << 24) | ($ipbits[1] << 16) | ($ipbits[2] << 8) | $ipbits[3], $sec, $usec);
				$uid = substr($uid, -9);
			} else {
				$uid = sprintf("%08x-%04x-%04x",($ipbits[0] << 24) | ($ipbits[1] << 16) | ($ipbits[2] << 8) | $ipbits[3], $sec, $usec);
			}
       		if(!file_exists($workingfolder.$uid.$ext)) {
	       		$true = false;
       		}
		}
		return $uid.$ext;
	}

	/**
	 * Create a unique upload filename
	 *
	 * @param string $folder The folder where the file will be saved
	 * @param string $fileName Original filename (coming from the user)
	 * @param boolean $trimName Do we need to create a short unique name ?
	 * @return string The unique filename to use (with its extension)
	 */
	function createUploadName($folder, $fileName, $trimName=false)
	{
		$ext = basename($fileName);
		$ext = explode('.', $ext);
		$ext = '.'.$ext[count($ext)-1];
		$prefix = str_replace($ext, '', $fileName);

		switch(self::getModuleOption('rename_method')) {
		    case 'method1':    // my photo.jpg => myphoto.jpg
	            return self::makeSeoUrl($prefix, 1, true).$ext;
		        break;
		    case 'method2':    // my photo.jpg => 5a15b7eb5baf6525.jpg
	            return self::createRandomUniqueName($folder, $fileName, $trimName);
                break;
		    case 'method3':    // my photo.jpg => myphoto-5a15b7eb.jpg
	            return self::makeSeoUrl($prefix, 1, true).'-'.self::createRandomUniqueName($folder, $fileName, true);
		        break;
		    case 'method4':    // my photo.jpg => 20091231-myphoto-5a15b7eb.jpg
	            return date('Ymd').'-'.self::makeSeoUrl($prefix, 1, true).'-'.self::createRandomUniqueName($folder, $fileName, true);
		        break;
		    case 'method5':    // my photo.jpg => 20091231-myphoto.jpg
	            return date('Ymd').'-'.self::makeSeoUrl($prefix, 1, true).$ext;
		        break;
		    case 'method6':    // my photo.jpg => _20091231-myphoto.jpg
	            return '_'.date('Ymd').'-'.self::makeSeoUrl($prefix, 1, true).$ext;
		        break;
		}
	}


	/**
	 * Fonction chargée de gérer l'upload
	 *
	 * @param integer $indice L'indice du fichier à télécharger
	 * @return mixed True si l'upload s'est bien déroulé sinon le message d'erreur correspondant
	 */
	function uploadFile($indice, $dstpath = XOOPS_UPLOAD_PATH, $mimeTypes = null, $uploadMaxSize = null, $maxWidth = null, $maxHeight = null)
	{
		require_once XOOPS_ROOT_PATH.'/class/uploader.php';
		global $destname;
		if(isset($_POST['xoops_upload_file'])) {
			require_once XOOPS_ROOT_PATH.'/class/uploader.php';
			$fldname = '';
			$fldname = $_FILES[$_POST['xoops_upload_file'][$indice]];
			$fldname = (get_magic_quotes_gpc()) ? stripslashes($fldname['name']) : $fldname['name'];
			if(xoops_trim($fldname != '')) {
				$destname = self::createUploadName($dstpath, $fldname, true);
				if($mimeTypes === null) {
					$permittedtypes = explode("\n",str_replace("\r",'', self::getModuleOption('mimetypes')));
					array_walk($permittedtypes, 'trim');
				} else {
					$permittedtypes = $mimeTypes;
				}
				if($uploadMaxSize === null) {
					$uploadSize = self::getModuleOption('maxuploadsize');
				} else {
					$uploadSize = $uploadMaxSize;
				}
				$uploader = new XoopsMediaUploader($dstpath, $permittedtypes, $uploadSize, $maxWidth, $maxHeight);
				//$uploader->allowUnknownTypes = true;
				$uploader->setTargetFileName($destname);
				if ($uploader->fetchMedia($_POST['xoops_upload_file'][$indice])) {
					if ($uploader->upload()) {
						return true;
					} else {
						return _ERRORS.' '.htmlentities($uploader->getErrors());
					}
				} else {
					return htmlentities($uploader->getErrors());
				}
			} else {
				return false;
			}
		} else {
			return false;
		}
	}

	/**
	 * Resize a Picture to some given dimensions (using the wideImage library)
	 *
	 * @param string $src_path	Picture's source
	 * @param string $dst_path	Picture's destination
	 * @param integer $param_width Maximum picture's width
	 * @param integer $param_height	Maximum picture's height
	 * @param boolean $keep_original	Do we have to keep the original picture ?
	 * @param string $fit	Resize mode (see the wideImage library for more information)
	 */
	function resizePicture($src_path , $dst_path, $param_width , $param_height, $keep_original = false, $fit = 'inside')
	{
	    require_once PORTFOLIO_PATH.'class/wideimage/WideImage.inc.php';
	    $img = wiImage::load($src_path);
	    $result = $img->resize($param_width, $param_height, $fit);
	    $result->saveToFile($dst_path);
	    if(!$keep_original) {
   			@unlink( $src_path ) ;
	    }
	    return true;
	}

	/**
	 * Retourne le type Mime d'un fichier en utilisant d'abord finfo puis mime_content
	 *
	 * @param string $filename	Le fichier (avec son chemin d'accès complet) dont on veut connaître le type mime
	 * @return string
	 */
	function getMimeType($filename)
	{
	   	if(function_exists('finfo_open')) {
   			$pathToMagic = PORTFOLIO_PATH.'mime/magic';
   			$finfo = new finfo(FILEINFO_MIME, $pathToMagic);
   			$mimetype = $finfo->file($filename);
   			finfo_close($finfo);
   			unset($finfo);
			return $mimetype;
		} else {
			if (function_exists('mime_content_type')) {
				return mime_content_type($filename);
			} else {
				return '';
			}
		}
	}


	/**
 	 * Fonction chargée de vérifier qu'un répertoire existe, qu'on peut écrire dedans et création d'un fichier index.html
 	 *
 	 * @param string $folder	Le chemin complet du répertoire à vérifier
 	 * @return void
 	 */
     function prepareFolder($folder)
     {
        if(!is_dir($folder)) {
        	mkdir($folder, 0777);
            file_put_contents($folder.'/index.html', '<script>history.go(-1);</script>');
        }
    }

	function isAdmin()
	{
		global $xoopsUser, $xoopsModule;
		if(is_object($xoopsUser)) {
			if(in_array(XOOPS_GROUP_ADMIN, $xoopsUser->getGroups())) {
				return true;
			} elseif(isset($xoopsModule) && $xoopsUser->isAdmin($xoopsModule->getVar('mid'))) {
				return true;
			}
		}
		return false;
	}
	
    /**
     * Appelle un fichier Javascript à la manière de Xoops
     *
     * @note, l'url complète ne doit pas être fournie, la méthode se charge d'ajouter
     * le chemin vers le répertoire js en fonction de la requête, c'est à dire que si
     * on appelle un fichier de langue, la méthode ajoute l'url vers le répertoire de
     * langue, dans le cas contraire on ajoute l'url vers le répertoire JS du module.
     *
     * @param string $javascriptFile
     * @return void
     */
    function callJavascriptFile($javascriptFile, $inLanguageFolder = false, $oldWay = false)
    {
        global $xoopsConfig, $xoTheme;
        $fileToCall = $javascriptFile;
        if($inLanguageFolder) {
            $root = self::MODULE_PATH;
            $rootUrl = self::MODULE_URL;
            if (file_exists($root.'language'.DIRECTORY_SEPARATOR.$xoopsConfig['language'].DIRECTORY_SEPARATOR.$javascriptFile)) {
    	        $fileToCall = $rootUrl.'language/'.$xoopsConfig['language'].'/'.$javascriptFile;
            } else {    // Fallback
    	        $fileToCall = $rootUrl.'language/english/'.$javascriptFile;
            }
        } else {
            $fileToCall = self::MODULE_JS_URL.$javascriptFile;
        }
        if(!$oldWay && isset($xoTheme)) {
            $xoTheme->addScript($fileToCall);
        } else {
            echo "<script type=\"text/javascript\" src=\"".$fileToCall."\"></script>\n";
        }
    }
    
    /**
     * Création d'une vignette en tenant compte des options du module concernant le mode de création des vignettes
     *
     * @param string $src_path
     * @param string $dst_path
     * @param integer $param_width
     * @param integer $param_height
     * @param boolean $keep_original
     * @param string $fit	Utilisé uniquement pour la méthode 0 (redimensionnement)
     * @return boolean
     */
    function createThumb($src_path, $dst_path, $param_width, $param_height, $keep_original = false, $fit = 'inside')
    {
        require_once self::MODULE_PATH . 'class/wideimage/WideImage.inc.php';
        $resize = true;
        // On commence par vérifier que l'image originale n'est pas plus petite que les dimensions demandées auquel cas il n'y a rien à faire
        $pictureDimensions = getimagesize($src_path);
        if (is_array($pictureDimensions)) {
            $pictureWidth = $pictureDimensions[0];
            $pictureHeight = $pictureDimensions[1];
            if ($pictureWidth < $param_width && $pictureHeight < $param_height) {
                $resize = false;
            }
        }
        $img = wiImage::load($src_path);
        if ($resize) { // L'image est suffisament grande pour être réduite
            $thumbMethod = 0;
            if ($thumbMethod == 0) { // Redimensionnement de l'image
                $result = $img->resize($param_width, $param_height, $fit);
            } else { // Récupération d'une partie de l'image depuis le centre
                // Calcul de left et top
                $left = $top = 0;
                if (is_array($pictureDimensions)) {
                    if ($pictureWidth > $param_width) {
                        $left = intval(($pictureWidth / 2) - ($param_width / 2));
                    }
                    if ($pictureHeight > $param_height) {
                        $top = intval(($pictureHeight / 2) - ($param_height / 2));
                    }
                }
                $result = $img->crop($left, $top, $param_width, $param_height);
            }
            $result->saveToFile($dst_path);
        } else {
            @copy($src_path, $dst_path);
        }
        if (!$keep_original) {
            @unlink($src_path);
        }
        return true;
    }

    /**
     * Load a language file
     *
     * @param string $languageFile	The required language file
     * @param string $defaultExtension	Default extension to use
     * @since 2.2.2009.02.13
     */
    function loadLanguageFile($languageFile, $defaultExtension = '.php')
    {
        global $xoopsConfig;
        $root = self::MODULE_PATH;
        if(strstr($languageFile, $defaultExtension) === false) {
            $languageFile .= $defaultExtension;
        }
        if (file_exists($root . 'language' . DIRECTORY_SEPARATOR . $xoopsConfig['language'] . DIRECTORY_SEPARATOR . $languageFile)) {
            require_once $root . 'language' . DIRECTORY_SEPARATOR . $xoopsConfig['language'] . DIRECTORY_SEPARATOR . $languageFile;
        } else {    // Fallback
            require_once $root . 'language' . DIRECTORY_SEPARATOR . 'english' . DIRECTORY_SEPARATOR . $languageFile;
        }
    }

	/**
	 * Set the page's title, meta description and meta keywords
	 * Datas are supposed to be sanitized
	 *
	 * @param string $pageTitle	Page's Title
	 * @param string $metaDescription	Page's meta description
	 * @param string $metaKeywords	Page's meta keywords
	 * @return void
	 */
	function setMetas($pageTitle = '', $metaDescription = '', $metaKeywords = '')
	{
		global $xoTheme, $xoTheme, $xoopsTpl;
		$xoopsTpl->assign('xoops_pagetitle', $pageTitle);
		if(isset($xoTheme) && is_object($xoTheme)) {
			if(!empty($metaKeywords)) {
				$xoTheme->addMeta( 'meta', 'keywords', $metaKeywords);
			}
			if(!empty($metaDescription)) {
				$xoTheme->addMeta( 'meta', 'description', $metaDescription);
			}
		} elseif(isset($xoopsTpl) && is_object($xoopsTpl)) {	// Compatibility for old Xoops versions
			if(!empty($metaKeywords)) {
				$xoopsTpl->assign('xoops_meta_keywords', $metaKeywords);
			}
			if(!empty($metaDescription)) {
				$xoopsTpl->assign('xoops_meta_description', $metaDescription);
			}
		}
	}
	
	/**
	 * Conversion des notations du style 256M propres aux fichiers ini de Php en valeur numérique
	 *
	 * @param string $val	La valeur à convertir
	 * @return integer		La valeur convertie
	 */
	private function returnInBytes($val) {
    	$val = trim($val);
    	$last = strtolower($val{strlen($val)-1});
    	switch($last) {
	        case 'g':	// Le modifieur 'G' est disponible depuis PHP 5.1.0
            	$val *= 1024;
        	case 'm':
	            $val *= 1024;
        	case 'k':
	            $val *= 1024;
	    }
    	return $val;
	}

	/**
	 * Retourne la taille maximale autorisée pour l'upload selon la configuration de Php
	 * 
	 * @return integer
	 */
	function getPhpUploadMaxFilesize()
	{
		return self::returnInBytes(ini_get('upload_max_filesize'));
	}
}
?>