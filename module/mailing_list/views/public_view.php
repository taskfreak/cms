<?php
/****************************************************************************\
* Tirzen CMS                                                                 *
******************************************************************************
* Version: 0.1                                                               *
* Authors: Stan Ozier <stan@tirzen.com>                                      *
* License:  http://www.gnu.org/licenses/gpl.txt (GPL)                        *
\****************************************************************************/

$this->content->printContent();
echo "\n\n";

if ($this->success) {

/* --- NEWSLETTER PREFERENCES SAVED -------------- */
	
	echo '<div id="mailing_form">';

	switch ($this->success) {
		case 1: // new subscriber
		case 2: // member has subcribed
			echo '<h2>F&eacute;licitations!</h2>';
			echo '<p class="high">Vous &ecirc;tes d&eacute;sormais inscrit &agrave; la liste de diffusion!</p>';
			echo '<p>Un email vient de vous &ecirc;tre envoy&eacute; afin de confirmer votre inscription.</p>';
			break;
		case 3: // unsubsribed
			echo '<h2>Au revoir...</h2>';
			echo '<p class="high">Vous n\'&ecirc;tes d&eacute;sormais plus inscrit &agrave; la liste de diffusion!</p>';
			echo '<p>Un email vient de vous &ecirc;tre envoy&eacute; afin de confirmer l\'op&eacute;ration.</p>';
			break;
	}
	
	echo '</div>';

} else {

/* --- FORM TO SUBSCRIBE / UNSUBSCRIBE ----------- */

?>
<form id="mailing_form" method="post" action="<?php echo $GLOBALS['objPage']->getUrl(); ?>">
<?php 
if ($this->errorMessage) {
	echo '<p class="message error">'.$this->errorMessage.'</p>';
} else if ($this->userIsLoggedIn) {
	echo '<p class="message high">'.$this->subscriber->getName().', ';
	if ($this->mailing->subscribeEnable) {
		echo 'vous &ecirc;tes inscrit et recevez d&eacute;j&agrave; la lettre d\'information.';	
	} else {
		echo 'vous n\'&ecirc;tes pas encore inscrit sur la liste de diffusion...';
	}
	echo '</p><p>Veuillez utiliser le formulaire ci-dessous pour modifier vos pr&eacute;f&eacute;rences.</p>';
} else {
	echo '<p class="message">Tous les champs sont obligatoires.</p>';
}
?>
<ol class="fields side lined">
	<?php
	if (!$this->userIsLoggedIn) {
	?>
	<li class="compulsory">
		<label for="firstName">Pr&eacute;nom:</label>
		<?php $this->subscriber->qText('firstName','','','id="firstName"'); ?>
	</li>
	<li class="compulsory">
		<label for="lastName">Nom:</label>
		<?php $this->subscriber->qText('lastName','','','id="lastName"'); ?>
	</li>
	<li class="compulsory">
		<label for="email">E-mail:</label>
		<?php $this->subscriber->qText('email','','','id="email"'); ?>
	</li>
	<?php
	}
	?>
	<li class="inline linefree"><input id="subscribeEnable" type="radio" name="subscribeEnable" value="1"<?php
		if (!$this->mailing->subscribeEnable) {
			echo ' checked="checked"';
		}
	?> onclick="$('newsletterForm').removeClass('faded')" />
	<label for="subscribeEnable">Je souhaite m'inscrire &agrave; la liste de diffusion.</label>
	<br /><span id="newsletterForm"<?php
		if ($this->mailing->subscribeEnable) {
			echo ' class="faded"';
		} else {
			echo ' class=""';
		}
	?>>
		<label>Format:</label>
		<?php
			echo '<input type="radio" id="newsformat1" name="subscribeHtml" value="1"';
			if ($this->mailing->subscribeHtml) {
				echo ' checked="checked"';
			}
			echo ' /> <label for="newsformat1">HTML</label> ';
			echo '<input type="radio" id="newsformat0" name="subscribeHtml" value="0"';
			if (!$this->mailing->subscribeHtml) {
				echo ' checked="checked"';
			}
			echo ' /> <label for="newsformat0">Texte</label> ';
		?>
	</span></li>
	<li class="inline"><input id="subscribeDisable" type="radio" name="subscribeEnable" value="0"<?php
		if ($this->mailing->subscribeEnable) {
			echo ' checked="checked"';
		}
	?> onclick="$('newsletterForm').addClass('faded')" />
	<label for="subscribeDisable">Je souhaite &ecirc;tre retir&eacute; de la liste de diffusion.</label></li>
	<li class="linefree"><button type="submit" name="send" value="1" class="submit">Envoyer ma demande</button></li>
</ol>
<div class="mailing_cnil"><?php echo $GLOBALS['langNewsLetterCNIL']; ?></div>
</form>
<?php
}
