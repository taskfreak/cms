<?php
/****************************************************************************\
* Tirzen CMS                                                                 *
******************************************************************************
* Version: 4.0                                                               *
* Authors: Stan Ozier <stan@tirzen.com>                                      *
* License:  http://www.gnu.org/licenses/gpl.txt (GPL)                        *
\****************************************************************************/

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php if (isset($GLOBALS['objPage'])) { echo $GLOBALS['objPage']->encoding; } else { echo CMS_CHARSET; } ?>" />
<title><?php

/* --- PAGE TITLE --------------------------------------------------------- */

$str = '';
if (isset($GLOBALS['objPage'])) {
	$str .= $GLOBALS['objPage']->get('title');
} else if (isset($objEditPage)) {
	if ($str) {
		$str .= 'CMS: ';
	}
	$str .= $objEditPage->get('title');
} else if ($pPageTitle) {
	if ($str) {
		$str .= 'CMS: ';
	}
	$str .= $pPageTitle;
}
if ($str) {
	$str .= ' | ';
}
$str .= htmlentities(stripslashes($GLOBALS['objCms']->settings->get('site_title')));
echo $str;
unset($str);

?></title>
<?php

/* --- META DESCRIPTION --------------------------------------------------- */

if ($GLOBALS['objPage']->description) {
?>
<meta name="description" content="<?php echo $GLOBALS['objPage']->get('description'); ?>" />
<?php
}
	
/* --- META KEYWORD ------------------------------------------------------- */
	
if ($GLOBALS['objPage']->keyword) {
		
?>
<meta name="keywords" content="<?php echo $GLOBALS['objPage']->get('keyword'); ?>" />
<?php
}

/* --- SEARCH ENGINES VERIFICATION CODE (webmaster tools) ------------------------- */

if (defined('CMS_GOOGLE_VERIFY')) {
	echo '<meta name="google-site-verification" content="'.CMS_GOOGLE_VERIFY.'" />'."\n";
}
if (defined('CMS_YAHOO_VERIFY')) {
	echo '<meta name="y_key" content="'.CMS_YAHOO_VERIFY.'" />'."\n";
}


/* --- FAVICON ------------------------------------------------------------ */
if (is_file(CMS_WWW_PATH.'favicon.ico')) {
	echo '<link rel="shortcut icon" href="/favicon.ico" />'."\n";
}

/* --- JAVASCRIPT & CSS --------------------------------------------------- */

$GLOBALS['objHeaders']->printHead();

?>
</head>
<body<?php $GLOBALS['objHeaders']->printBodyJs(); ?>>
