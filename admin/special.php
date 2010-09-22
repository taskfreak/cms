<?php
/* this file is directly called from customized left menu to manage special features from installed modules */

define('CMS_ADMIN', true); // yes, we are in admin section
// this will include required javascripts such as mootools core

include '../_include.php';

// initialize CMS
TznCms::init(5, true); // admin page, auto load modules

// set referring page
TznCms::setAdminRef();

// check module request
$pModule = TznUtils::sanitize(TZN_SANITIZE_SIMPLE, $_REQUEST['module']);
if (!$pModule) {
	TznUtils::redirect(TznUtils::getReferrer(true, true),'Module non pr&eacute;cis&eacute;');
}

$objModule = $GLOBALS['objCms']->getModuleObject($pModule);

$pAction = TznUtils::sanitize(TZN_SANITIZE_SIMPLE, $_REQUEST['action']);
$pAction = 'admin'.(($pAction)?TznUtils::strToCamel($pAction,true):'Special');
if (!method_exists($objModule, $pAction)) {
	TznUtils::redirect(TznUtils::getReferrer(true, true),'Module non pr&eacute;cis&eacute;');
}

$objModule->$pAction();

if ($GLOBALS['objCms']->submitMode) {
	$objModule->adminDefaultAction();
}

/* === PREPARE HTML ======================================================== */


/* === HTML ================================================================ */

TznCms::getHeader(true);

?>
<form action="<?php echo CMS_WWW_URI.'admin/special.php'; ?>" method="post" enctype="multipart/form-data" id="main" class="box">
	<?php
	Tzn::qHidden('module', $pModule);
	if ($pAction = TznUtils::sanitize(TZN_SANITIZE_SIMPLE,$_REQUEST['action'])) {
		Tzn::qHidden('action', $pAction);
	}
	?>
	<h2><?php echo $objCms->getTranslation($pModule,'langAdminMenuItem'); ?></h2>
	<div id="accordion" class="boxed">
	<?php
	
	$objModule->adminView();
	
	?>
	</div>
	<div class="footer"><?php
		$GLOBALS['objCms']->adminSubmitButtons(); 
		echo ' <a href="'.TznUtils::getReferrer(true, false).'" class="close">fermer</a>'; // -TODO-TRANSLATE-
	?></div>
</form>
<?php

TznCms::getFooter(true);