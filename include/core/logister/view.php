<?php

/* === PREPARE HTML ======================================================== */

$GLOBALS['objHeaders']->add('css','common.css');
$GLOBALS['objHeaders']->add('css','admin.css');
$GLOBALS['objHeaders']->add('css',CMS_WWW_URI.'assets/css/form.css');

$GLOBALS['objHeaders']->add('jsOnLoad','document.forms[0].elements[1].focus()');

/* === HTML ================================================================= */

TznCms::getHeader(true);

?>
<div id="centric" class="box">
	<h2>Demande d'ouverture de compte</h2>
	<div class="boxed">
<?php
if ($this->dude) {

	include CMS_INCLUDE_PATH.'language/'.CMS_LANGUAGE.'/register_info.php';

} else {
	
	if ($this->errorMessage) {
		echo '<p class="ctr error">'.$this->errorMessage.'</p>';
	}
	
	include CMS_CORE_PATH.'/logister/form.php';

}
?>
	</div>
	<div class="footer"><a href="<?php echo TznCms::getUri('login.php'); ?>"><?php
		echo TznCms::getTranslation('back_to_login','langUser');
	?></a></div>
</div>
<?php

TznCms::getFooter(true);