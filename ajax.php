<?php

include '_include.php';

// initialize CMS
TznCms::init(0, false); //public page, do not autoload modules

//include CMS_CLASS_PATH.'pkg_content.php';

if (!$_REQUEST['module']) {
	header('Status: 404 Not Found');
	exit;
}

if (!preg_match('/^[a-z0-9_\-]*$/i', $_REQUEST['module'])) {
	header('Status: 500 Server Error');
	echo 'Oops, '.$_REQUEST['module'].' is a bogus module name';
	exit;
}

if (!$_REQUEST['action']) {
	header('Status: 404 Not Found');
	exit;
}

$method = $_REQUEST['action'];

if (!preg_match('/^[a-z0-9_\-]*$/i', $method)) {
	header('Status: 500 Server Error');
	echo 'Oops, '.$method.' is a bogus method name';
	exit;
}

$method = 'ajax'.ucFirst($method);

// Mootools bug: sends in UTF-8
if (CMS_CHARSET != 'UTF-8') {
	foreach($_POST as $key => $value) {
		$_POST[$key] = utf8_decode($value);
	}
}

if ($_REQUEST['module'] == 'comment') {

	CmsComment::$method();

} else {

	$objModuleAjax = $GLOBALS['objCms']->getModuleObject($_REQUEST['module']);
	
	if (is_object($objModuleAjax)) {
		
		$objModuleAjax->$method(); // -TODO- add args

		$objModuleAjax->ajaxView();

	} else {
		/*
		if (is_object($objModuleAjax)) {
			echo 'object class : '.get_class($objModuleAjax);
		} else {
			var_dump($objModuleAjax);
		}
		*/
		header('Status: 500 Server Error');
		echo 'Oops, can not initialize object '.$_REQUEST['module'];
		exit;
	}
}