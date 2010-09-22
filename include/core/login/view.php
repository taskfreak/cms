<?php

/* === PREPARE HTML ======================================================== */

$GLOBALS['objHeaders']->add('css','common.css');
$GLOBALS['objHeaders']->add('css','admin.css');
$GLOBALS['objHeaders']->add('css','form.css');

$GLOBALS['objHeaders']->add('jsOnLoad','document.forms[0].elements[1].focus()');

/* === HTML ================================================================= */

TznCms::getHeader(true);

?>
<div id="centric" class="box compact">
	<h2>Login</h2>
	<div class="boxed">
<?php
if ($objCms->isLoggedIn) {

	include CMS_INCLUDE_PATH.'language/'.CMS_LANGUAGE.'/login_already.php';

} else {
  
	include CMS_INCLUDE_PATH.'core/login/form.php'; 	
	
	include CMS_INCLUDE_PATH.'language/'.CMS_LANGUAGE.'/login_info.php';
}
?>
	</div>
	<div class="footer"><?php
		if ($GLOBALS['objCms']->settings->get('password_reminder')) {
			echo '<a href="'.TznCms::getUri('logminder.php').'">'.TznCms::getTranslation('password_reminder','langUser').'</a>';
		} else {
			echo '...';
		}
	?></div>
</div>
<?php

TznCms::getFooter(true);