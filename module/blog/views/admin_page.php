<h3 class="acctog">Liste des articles</h3>
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
<h3 class="acctog">Options</h3>
<div class="accinf">
	<ol class="fields">
		<li>
			<?php 
				$val = $this->content->getOption('order_type'); 
				$sel = ' selected="selected"';
			?>
			<label>Type d'articles :</label>
			<select name="option_order_type">
				<option value="0"<?php if (!$val) echo $sel; ?>>Blog : Du plus r&eacute;cent au plus ancien</option>
				<option value="1"<?php if ($val==1) echo $sel; ?>>Blog Archives : Du plus ancien au plus r&eacute;cent</option>
				<option value="2"<?php if ($val==2) echo $sel; ?>>Calendrier : Du plus proche au plus lointain</option>
				<option value="3"<?php if ($val==3) echo $sel; ?>>Calendrier Archives : Du plus r&eacute;cent au plus lointain</option>
			</select>
		</li>
		<li>
			<label>Articles / Page :</label>
			<input type="text" name="option_pagination" class="wxs" value="<?php
				if ($this->content->getOption('pagination')) {
					echo $this->content->getOption('pagination');
				} else {
					echo '10'; // -TODO- default from settings
				}
			?>" />
			<small>Nombre d'articles &agrave; afficher par page</small>
		</li>
		<li class="inline">
			<input type="checkbox" id="opt5" name="option_page_only" value="1"<?php
				if ($this->content->getOption('page_only')) echo ' checked="checked"';
			?> />
			<label for="opt5">Afficher les articles de cette page uniquement</label>
		</li>
	</ol>
	<h4>Options d'affichage liste d'articles</h4>
	<ol class="fields">
		<li class="inline">
			<input type="checkbox" id="opt0" name="option_date_in_list" value="1"<?php
				if ($this->content->getOption('date_in_list')) echo ' checked="checked"';
			?> />
			<label for="opt0">Afficher la date</label>
		</li>
		<li class="inline">
			<input type="checkbox" id="opt1" name="option_author_in_list" value="1"<?php
				if ($this->content->getOption('author_in_list')) echo ' checked="checked"';
			?> />
			<label for="opt1">Afficher nom de l'auteur</label>
		</li>
	</ol>
	<h4>Options d'affichage d&eacute;tails de l'article</h4>
	<ol class="fields">
		<li class="inline">
			<input type="checkbox" id="opt2" name="option_intro_in_item" value="1"<?php
				if ($this->content->getOption('intro_in_item')) echo ' checked="checked"';
			?> />
			<label for="opt2">Afficher l'introduction</label>
		</li>
		<li class="inline">
			<input type="checkbox" id="opt3" name="option_date_in_item" value="1"<?php
				if ($this->content->getOption('date_in_item')) echo ' checked="checked"';
			?> />
			<label for="opt3">Afficher la date</label>
		</li>
		<li class="inline">
			<input type="checkbox" id="opt4" name="option_author_in_item" value="1"<?php
				if ($this->content->getOption('author_in_item')) echo ' checked="checked"';
			?> />
			<label for="opt4">Afficher nom de l'auteur</label>
		</li>
	</ol>
</div>