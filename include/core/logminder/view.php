<?php
/* === PREPARE HTML ======================================================== */

$GLOBALS['objHeaders']->add('css','common.css');
$GLOBALS['objHeaders']->add('css','admin.css');
$GLOBALS['objHeaders']->add('css',CMS_WWW_URI.'assets/css/form.css');

$GLOBALS['objHeaders']->add('jsOnLoad','document.forms[0].elements[0].focus()');

/* === HTML ================================================================= */

TznCms::getHeader(true);

?>
<div id="centric" class="box compact">
	<h2><?php echo ucFirst(TznCms::getTranslation('password_reminder','langUser')); ?></h2>
	<div class="boxed">
<?php
if ($this->reminded) {
		
	/* --- REMINDED ------------------------------------------------ */
		// -TODO-TRANSLATE-
?>
	<h3>V&eacute;rifiez vos emails</h3>
	<p>Un message &eacute;l&eacute;ctronique contenant vos codes d'acc&egrave;s<br />vient de vous &ecirc;tre envoy&eacute;</p>
    <p><a href="login.php">Revenir &agrave; la page d'identification</a></p>
<?php
} else {
	
	/* --- FORM ---------------------------------------------------- */
	
	include CMS_CORE_PATH.'logminder/form.php';

}
?>
	</div>
	<div class="footer"><a href="<?php echo TznCms::getUri('login.php'); ?>"><?php
		echo TznCms::getTranslation('back_to_login','langUser');
	?></a></div>
</div>
<?php

TznCms::getFooter(true);