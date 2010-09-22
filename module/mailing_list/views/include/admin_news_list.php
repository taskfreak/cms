<h3 class="acctog">Liste des lettres d'information</h3>
<div class="accinf">
	<div id="letters-search" class="quicksearch lft">
		<a href="<?php echo $this->baseLink.'&amp;action=edit';	?>" class="button create frgt">Creer une nouvelle lettre</a>
		<label>Rechercher une lettre : </label>
		<input type="text" id="letters-keyword" name="lettersKeyword" value="<?php echo($_SESSION['mailingLetterKeyword']); ?>" class="wm" /> 
		<button type="submit" id="letters-submit">Chercher</button>
		<button type="button" id="letters-reset">X</button>
	</div>
	<div id="letters-list" class="table clickable hfix">
	<?php
		if ($this->letters->rMore()) {
			$this->letters->renderList();
		} else {
			echo '<p class="empty ctr">Aucune lettre d\'information d&eacute;finie</p>';
		}
	?>
	</div>
</div>