<h3 class="acctog">Introduction</h3>
<div class="accinf">
	<?php
		$this->content->qEditArea();
	?>
</div>
<h3 class="acctog">Page non membre (si acc&egrave;s non autoris&eacute;)</h3>
<div class="accinf">
	<?php
		$this->noaccess->qEditArea('body_noaccess');
	?>
</div>
<h3 class="acctog">Options</h3>
<div class="accinf">
	<h4>Autorisation d'acc&egrave;s &agrave; la fonction</h4>
	<?php
		$allowedUsers = $this->content->getOption('member_only');
	?>
	<ol class="fields">
		<li class="inline">
			<input type="radio" id="opt00" name="option_member_only" value="0"<?php
				if (!$allowedUsers) echo ' checked="checked"';
			?> />
			<label for="opt00">Accessible par tous</label>
		</li>
		<li class="inline">
			<input type="radio" id="opt01" name="option_member_only" value="1"<?php
				if ($allowedUsers) echo ' checked="checked"';
			?> />
			<label for="opt01">R&eacute;serv&eacute; aux membres inscrits</label>
		</li>
	</ol>
	<h4>&Eacute;l&eacute;ments &agrave; afficher</h4>
	<?php
		$allowedTypes = $this->content->getOption('allowed_types');
	?>
	<ol class="fields">
		<li class="inline">
			<label for="opt_pgs">Nombre d'&eacute;l&eacute;ments par type&nbsp;:</label>
			<input id="pgs" type="text" name="option_page_size" class="wxs" value="<?php echo $this->content->getOption('page_size'); ?>" />
		</li>
	</ol>
</div>