<?php

// main navigation
$GLOBALS['objMenus'] = new TznMenus();
$GLOBALS['objMenus']->add('nav1','menu_list',1,1);

$parentId = $GLOBALS['objPage']->getUid();
switch ($GLOBALS['objPage']->getOutlineLevel()) {
	case 3:
		$parentId = $GLOBALS['objPage']->getParentId();
		break;
	case 4:
		$parentId = $GLOBALS['objPage']->getParent()->getParentId();
		break;
}
$GLOBALS['objMenus']->add('nav2','menu_list_local',$parentId,2);


// CSS and JS
$GLOBALS['objHeaders']->add('css','default.css');
$GLOBALS['objHeaders']->add('jsScript','default.js');

/*
// SQUEEZEBOX
$GLOBALS['objHeaders']->add('css',CMS_WWW_URI.'assets/css/squeezebox.css');
$GLOBALS['objHeaders']->add('jsScript','squeezebox.js');
*/

// User login security option (will encrypt password before sending it through HTTP)
if (!$GLOBALS['objUser']->isLoggedIn() && @constant('TZN_USER_PASS_MODE') == 5) {
	$GLOBALS['objHeaders']->add('jsScript','md5.js');
	$_SESSION['challenge'] = Tzn::getRdm(64);
}