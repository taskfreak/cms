<?php
/****************************************************************************\
* TaskFreak!                                                                 *
* multi user                                                                 *
******************************************************************************
* Version: 0.5.7                                                             *
* Authors: Stan Ozier <taskfreak@gmail.com>                                  *
* License:  http://www.gnu.org/licenses/gpl.txt (GPL)                        *
\****************************************************************************/


?>
<h3 class="ctr">Merci <?php echo $this->objMember->getName(); ?>!</h3>
<?php
if ($GLOBALS['objCms']->message) {
?>
<div class="highlight mellow">
	<p>Votre demande d'inscription a bien &eacute;t&eacute; prise en compte.</p>
	<p>N&eacute;anmoins, une erreur est survenue lors de l'envoi du message &eacute;lectronique de confirmation. 
	Nous allons donc devoir proc&eacute;der &agrave; une activation manuelle de votre compte.<br />
	Nous vous contacterons tr&egrave;s bient&ocirc;t pour confirmation.</p>
	<p class="error"><?php echo $GLOBALS['objCms']->message; ?></p>
</div>
<?php
} else {

	switch ($GLOBALS['objCms']->settings->get('registration')) {
	case 1:
?>
<div class="highlight mellow">
	<p class="ctr">Nous avons bien re&ccedil;u votre demande.<br />
	Nous vous contacterons de nouveau pour confirmer l'activation de votre compte.</p>
</div>
<?php
		break;
	case 2:
?>
<div class="highlight mellow">
	<h2>V&eacute;rifiez vos emails</h2>
	<p>Un message &eacute;l&eacute;ctronique vient de vous &ecirc;tre envoy&eacute;</p>
	<p>Suivez les instructions indiqu&eacute;es dans cet email afin de finaliser la proc&eacute;dure d'inscription</p>
    <p><a href="<?php echo TznCms::getUri('login.php'); ?>">Revenir &agrave; la page d'identification</a></p>
</div>
<?php
	}
}