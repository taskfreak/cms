<form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post" id="form_to_data">
	<input type="hidden" name="page" value="<?php echo $objPage->id; ?>" />
	<?php
		if ($objForm->hasError()) {
	?>
		<div id="boxError">
			<p>Le formulaire comporte des erreurs, veuillez v&eacute;rifier
				<span>(<a href="javascript:cms_toggle('boxErrorList')">voir d&eacute;tails</a>)</span>
			</p>
			<div id="boxErrorList" style="display:none">
				<?php $objForm->printErrorListHTML(); ?>
			</div>
		</div>
	<?php
		}
	?>
	<fieldset>
		<legend>Vos coordonn&eacute;es</legend>
		<ol class="fields top styled">
			<li class="compulsory">
				<label for="q_name">Pr&eacute;nom / Nom</label>
				<?php $objForm->qText('name','','wl'); ?>
			</li>
			<li class="compulsory">
				<label for="q_email">Courriel</label>
				<?php $objForm->qText('email','','wl'); ?>
			</li>
			<li>
				<label for="q_company">Soci&eacute;t&eacute;</label>
				<?php $objForm->qText('company','','wl'); ?>
			</li>
			<li>
				<label for="q_telephone">T&eacute;l&eacute;phone</label>
				<?php $objForm->qText('telephone','','wl'); ?>
			</li>
		</ol>
	</fieldset>
	<fieldset>
		<legend>Votre message</legend>
		<ol class="fields top styled">
			<li class="compulsory">
				<label for="q_type_message">Quel est le sujet de votre message ?</label>
				<?php
					$objForm->qSel(
						'type_message', // name of field
						$objForm->type_message, // default value
						'',	// optional (no value)
						'wl'	// CSS class
					);				
				?>
			</li>
			<li>
				<label for="q_message">Informations compl&eacute;mentaires</label>
				<?php $objForm->qTextArea('message','','hm','style="width:380px"'); ?>
			</li>
		</ol>
	</fieldset>
	<ol class="fields linefree">
		<?php echo $objPage->getCaptcha($this->getOption('captcha'),'li'); ?>
		<li class="buttons">
			<button type="submit" name="send" value="1" class="submit">Envoyer le message</button>
		</li>
	</ol>
	<p class="legal">
		Les informations recueillies font l'objet d'un traitement informatique destin&eacute; &agrave; contacter la soci&eacute;t&eacute; [INDIQUEZ VOTRE NOM]. Conform&eacute;ment &agrave; la loi &quot;informatique et libert&eacute;s&quot; du 6 janvier 1978, vous b&eacute;n&eacute;ficiez d'un droit d'acc&egrave;s et de rectification aux informations qui vous concernent. Si vous souhaitez exercer ce droit et obtenir communication des informations vous concernant, veuillez nous contacter directement.
	</p>
</form>