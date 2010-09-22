<h3 class="acctog">Liste des inscrits</h3>
<div class="accinf">
	<div id="subscribers-search" class="quicksearch lft">
		<div class="frgt"><?php echo $this->subscribers->rTotal().' inscrit(s) au total'; ?></div>
		<label>Rechercher un inscrit : </label>
		<input type="text" id="subscribers-keyword" name="subscribersKeyword" value="<?php echo($_SESSION['mailingSubscriberKeyword']); ?>" class="wm" /> 
		<button type="submit" id="subscribers-submit">Chercher</button>
		<button type="button" id="subscribers-reset">X</button>
	</div>
	<div id="subscribers-list" class="table clickable hfix">
	<?php
		if ($this->subscribers->rMore()) {
			$this->subscribers->renderList();
			//$objSubscriberList->getPaginationNav()
		} else {
			echo '<p class="empty ctr">Aucun inscrit &agrave; la newsletter</p>';
		}
    ?>
    </div>
</div>