<h3 class="acctog">Liste des formulaires re&ccedil;us</h3>
<div class="accinf">
	<?php
		include $this->filePath('views/admin_list.php');
	?>
</div>
<h3 class="acctog">Introduction</h3>
<div class="accinf">
	<?php
		$this->content->qEditArea();
	?>
</div>
<h3 class="acctog">Confirmation</h3>
<div class="accinf">
	<?php
		$this->confirm->qEditArea('confirm_body');
	?>
</div>
<h3 class="acctog">Options</h3>
<div class="accinf">
	<h4>Formulaire</h4>
	<ol class="fields">
		<li>
			<label>Mod&egrave;le : </label>
			<?php $this->templates->qSelect('option_form', $this->content->getOption('form')); ?>
		</li>
		<li class="inline">
			<input type="checkbox" id="opt_captcha" name="option_captcha" value="1"<?php
				if ($this->content->getOption('captcha')) echo ' checked="checked"';
			?> />
			<label for="opt_captcha">Syst&egrave;me anti-spam (captcha)</label>
		</li>
	</ol>
	<h4>A la reception d'un nouveau formulaire</h4>
	<ol class="fields">
		<li class="inline">
			<input type="checkbox" id="opt1" name="option_alert_admin" value="1"<?php
			if ($this->content->getOption('alert_admin')) echo ' checked="checked"';
			?> onclick="$('opt1_more').toggleClass('faded')" />
			<label for="opt1">Alerter le webmaster par email.</label>
			<ul id="opt1_more"<?php echo ($this->content->getOption('alert_admin'))?'':' class="faded"'; ?>>
				<li>
					<textarea name="option_alert_contacts" class="wxl hm"><?php echo $this->content->getOption('alert_contacts'); ?></textarea>
					<br /><small>Saisir un contact par ligne, au format suivant: 
					<code>nom apparaissant dans la liste|email@domain.com</code><br />
					Laissez vide pour utiliser l'adresse par d&eacute;faut.</small>
				</li>
				<li class="inline">
					<input type="checkbox" id="opt1_1" name="option_alert_full" value="1"<?php
					if ($this->content->getOption('alert_full')) echo ' checked="checked"';
					?> />
					<label for="opt1_1">inclure le message complet dans l'email</label>
				</li>
			</ul>
		</li>
		<li class="inline">
			<input type="checkbox" id="opt2" name="option_alert_visitor" value="1"<?php
			if ($this->content->getOption('alert_visitor')) echo ' checked="checked"';
			?> />
			<label for="opt2">Envoyer email de confirmation au visiteur.</label>
		</li>
		<li>
			<label>Email alternative : </label>
			<input type="text" name="option_alert_email" value="<?php echo $this->content->getOption('alert_email'); ?>" />
			<small>Laissez vide pour utiliser le message par d&eacute;faut</small>
		</li>
	</ol>
	<h4>Affichage des donn&eacute;es</h4>
	<ol class="fields">
		<li class="inline">
			<input type="checkbox" id="opt3" name="option_two_cols" value="1"<?php
			if ($this->content->getOption('two_cols')) echo ' checked="checked"';
			?> />
			<label for="opt3">Sur 2 colonnes.</label>
		</li>
	</ol>
	<h4>Contr&ocirc;le de l'origine</h4>
	<ol class="fields">
		<li>
			<label for="q_ref_default">Origine <small>par d&eacute;faut</small> :</label>
			<input id="q_ref_default" name="option_ref_default" value="<?php
				echo $this->content->getOption('ref_default');
			?>" class="wl" />
			<small>Laissez vide si obligatoire</small>
		</li>
		<li>
			<label for="q_ref_list">Autres origines reconnues :</label>
			<textarea id="q_ref_list" name="option_ref_list" class="wl hs"><?php
				echo $this->content->getOption('ref_list');
			?></textarea>
			<small>Saisir un code par ligne</small>
		</li>
	</ol>
</div>