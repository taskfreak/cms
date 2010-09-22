<?php

/* === PREPARE HTML ======================================================== */

$GLOBALS['objHeaders']->add('css','common.css');
$GLOBALS['objHeaders']->add('css','admin.css');
$GLOBALS['objHeaders']->add('css','form.css');

/* === HTML ================================================================= */

TznCms::getHeader(true);

?>
<div id="centric" class="box compact">
	<h2>Logout</h2>
	<div class="boxed">
<?php
  
	include CMS_INCLUDE_PATH.'core/logout/form.php'; 	

?>
	</div>
	<div class="footer">
		<a href="<?php echo TznCms::getUri(); ?>"><?php echo TznCms::getTranslation('back_to_home','langUser'); ?></a>
		|
		<a href="<?php echo TznCms::getUri('login.php'); ?>"><?php echo TznCms::getTranslation('back_login_again','langUser'); ?></a>
	</div>
</div>
<?php

TznCms::getFooter(true);