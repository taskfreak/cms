<?php
/****************************************************************************\
* TZN CMS                                                                    *
******************************************************************************
* Version: 4.0                                                               *
* Authors: Stan Ozier <stan@tirzen.com>                                      *
* License:  http://www.gnu.org/licenses/gpl.txt (GPL)                        *
\****************************************************************************/

/* --- Search config file ------------------------------------------------- */

include 'include/config.php';

$GLOBALS['langPlugin'] = array();
$GLOBALS['langModule'] = array();

/* --- import classes ----------------------------------------------------- */

include CMS_CLASS_PATH.'tzn_generic.php';
include CMS_CLASS_PATH.TZN_DB_CLASS;

include CMS_CLASS_PATH.'tzn_outline.php';
include CMS_CLASS_PATH.'tzn_user.php';
include CMS_CLASS_PATH.'tzn_document.php';

include CMS_CLASS_PATH.'pkg_cms.php';
include CMS_CLASS_PATH.'pkg_content.php';
include CMS_CLASS_PATH.'pkg_member.php';


/* --- missing PHP4 functions ---------------------------------------------- */

if (!function_exists('file_put_contents')) {
	define('FILE_APPEND', 1);
	function file_put_contents($n, $d, $flag = false) {
		$mode = ($flag == FILE_APPEND || strtoupper($flag) == 'FILE_APPEND') ? 'a' : 'w';
		$f = @fopen($n, $mode);
		if ($f === false) {
			return 0;
		} else {
			if (is_array($d)) $d = implode($d);
			$bytes_written = fwrite($f, $d);
			fclose($f);
			return $bytes_written;
		}
	}
}

$pMaxSize = ini_get('upload_max_filesize');
if (intval(ini_get('post_max_size')) < intval($pMaxSize)) {
	$pMaxSize = ini_get('post_max_size');
}
define('CMS_UPLOAD_MAX_SIZE', $pMaxSize);
unset($pMaxSize);