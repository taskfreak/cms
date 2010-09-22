<h3 class="acctog">Liste des articles en attente de publication</h3>
<div class="accinf">
	<?php
		include CMS_MODULE_PATH.'blog/views/admin_list.php';
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
		$this->confirm->qEditArea('body_confirm');
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
	<h4>Types d'articles autoris&eacute;s</h4>
	<?php
		$allowedTypes = $this->content->getOption('allowed_types');
	?>
	<ol class="fields">
		<li class="inline">
			<input type="radio" id="opt10" name="option_allowed_types" value="0"<?php
				if (!$allowedTypes) echo ' checked="checked"';
			?> />
			<label for="opt10">Tous types d'articles</label>
		</li>
		<li class="inline">
			<input type="radio" id="opt11" name="option_allowed_types" value="1"<?php
				if ($allowedTypes == 1) echo ' checked="checked"';
			?> />
			<label for="opt11">Articles simples uniquement (pas d'&eacute;v&eacute;nements)</label>
		</li>
		<li class="inline">
			<input type="radio" id="opt12" name="option_allowed_types" value="2"<?php
				if ($allowedTypes == 2) echo ' checked="checked"';
			?> />
			<label for="opt12">Ev&eacute;nements uniquement</label>
		</li>
	</ol>
</div>