<form name="cms_admin_form" action="<?php echo TznCms::getUri('logister.php'); ?>" method="post">
	<?php
	
	include CMS_CORE_PATH.'member/form.php';
	
	echo '<p>';
	$GLOBALS['objCms']->adminSubmitButtons();
	echo '<a href="'.TznUtils::getReferrer(true).'" class="close">'.$GLOBALS['langSubmit']['closenosave'].'</a>';
	echo '</p>';
	
	if (!$GLOBALS['objUser']->isLoggedIn()) {
		echo $GLOBALS['objCms']->settings->get('registration_footer');
	}
	
	?>	
</form>