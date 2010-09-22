<?php

include '_include.php';

// initialize CMS
TznCms::init(0, true); //public page, autoload modules

// site under maintenance 
if ($GLOBALS['objCms']->settings->get('maintenance') && (!$GLOBALS['objUser']->hasAccess(3))) {
	$objCms->errorPage('maintenance');
}

// load requested page
$GLOBALS['objPage'] = new TznPage();

/*
echo '<pre>';
print_r($_GET); 
echo '</pre>';
// exit;
*/

if ($_GET['content']) {
	$objContent = new CmsObjectPage();
	$shortcut = TznUtils::sanitize(TZN_SANITIZE_SIMPLE, $_REQUEST['content']);
	if ($objContent->loadByKey('shortcut',$shortcut)) {
		$objPage = $objContent->page;
		$objPage->_loaded = true;
		$_REQUEST['item'] = $objContent->id;
	}
	
} else if ($_GET['title']) {
	$shortcut = TznUtils::sanitize(TZN_SANITIZE_SIMPLE, $_GET['title']);
	$objPage->loadByKey('shortcut',$shortcut);
} else if ($id = intval($_REQUEST['page'])) {
	$objPage->loadByKey('id',$id);
} else {
	$req = trim($_SERVER['REQUEST_URI'],'/');
	if ($req) {
		if ($pos = strrpos($_SERVER['REQUEST_URI'],'.html')) {
			$req = substr($req, 0, $pos-1);
		} else if ($pos = strrpos($_SERVER['REQUEST_URI'],'?')) {
			$req = substr($req, 0, $pos-1);
		}
		$GLOBALS['arrReqPrm'] = explode('/',$req);
		if ($GLOBALS['arrReqPrm'][1]) {
			$objContent = new CmsObjectPage();
			if ($objContent->loadByKey('shortcut',$GLOBALS['arrReqPrm'][1])) {
				$objPage = $objContent->page;
				$objPage->_loaded = true;
				$_REQUEST['item'] = $objContent->id;
			}
		}
		
		if (!$objPage->isLoaded()) {
			$objPage->loadByKey('shortcut',$GLOBALS['arrReqPrm'][0]);
		}
		
	} else {
		$objPage->loadFirstPage();
	}
}

// requested page not found	
if (!$objPage->isLoaded()) {
	$objCms->errorPage('404');
		
} 

// page is section -> load sub page
if (!$objPage->module) {
	$objPageParent = $objPage->clone4();
	$objPage = $objPageParent->loadChildWithModule();
}

// --- Module check --------------------

if (!$objPage || !$objPage->isLoaded()) {
	// page not found
	$objCms->errorPage('404');
			
}

if (!$objPage->module) {
	
	// still no module page or no children defined
	$objCms->errorPage('404');

}

// --- Private pages: check access ----

if (!$objPage->canAccess()) {
	// visitor does not have access, show error message
	$objCms->errorPage('403');
} 

// --- Page not published (and no access) ---

if (!$objPage->display) {
	if ($GLOBALS['objUser']->hasAccess(3)) {
		TznUtils::addMessage('Attention: cette page n\'est pas publiée!');
	} else {
		$objCms->errorPage('unpublished');
	}
}

// -- Everything's fine, let's show the page ------------



// --- Define Template --------------------------------------------------
	
if (!$objPage->template) {
	$objPage->template = $objCms->settings->get('default_template');
}
define ('CMS_TEMPLATE',$objPage->template);


// Referrer
TznUtils::setReferrer();

if (@constant('CMS_ADMIN')) {
	$GLOBALS['objHeaders']->add('jsScript', array('mootools-1.2.4.js','common.js'));
} else if (CMS_MOOTOOLS) {
	$GLOBALS['objHeaders']->add('jsScript', 'mootools-1.2.4.js');
}

// --- template initialization --------
if (file_exists(CMS_TEMPLATE_PATH.$objPage->template.'/init.php')) {
	include CMS_TEMPLATE_PATH.$objPage->template.'/init.php';
}

// --- module initialization ----------
if ($objPage->module) {
	$objModule = $GLOBALS['objCms']->getModuleObject($objPage->module);
	if (!$objModule) {
		TznCms::errorFatal('module '.$objPage->module.' does not exist or is not enabled'); //-TODO- translate
	}
	// call module init method
	$notyet = true;
	if ($_REQUEST['action']) {
		$method = 'public'.ucFirst($_REQUEST['action']);
		if (preg_match('/^[a-z0-9_\-]*$/i', $method) && method_exists($objModule, $method)) {
			$objModule->$method();
			$notyet = false;
		}
	}
	if ($notyet && method_exists($objModule, 'publicDefault')) {
		$objModule->publicDefault();
	}
}

// --- more template stuff ------------
if (file_exists(CMS_TEMPLATE_PATH.$objPage->template.'/core.php')) {
	include CMS_TEMPLATE_PATH.$objPage->template.'/core.php';
}
	
// --- HTML Header --------------------

TznCms::getHeader();

// --- template body ------------------
if (file_exists(CMS_TEMPLATE_PATH.$objPage->template.'/body.php')) {
	include CMS_TEMPLATE_PATH.$objPage->template.'/body.php';
} else if (file_exists(CMS_MODULE_PATH.$objPage->module.'/'.$objCms->pageToInclude)) {
	include CMS_MODULE_PATH.$objPage->module.'/'.$objCms->pageToInclude;	
}

// --- page footer --------------------

TznCms::getFooter();