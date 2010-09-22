<?php
if ($this->forms->rMore()) {

/* --- LIST OF ARTICLES ------------------------------------------- */

?>
<div class="table clickable hl">
<?php
	while ($objItem = $this->forms->rNext()) {
?>
	<div class="row">
		<div class="col c50"><?php echo $objItem->getTitle(); ?></div>
		<div class="col c15"><?php $objItem->_join->p('memo'); ?></div>
		<div class="col c15"><?php $objItem->_join->p('postDate',CMS_DATETIME); ?></div>
		<div class="col c20 action">
			<a href="<?php echo $this->baseLink.'&amp;action=delete&amp;item='.$objItem->id; ?>" onclick="return confirm('r&eacute;ellement supprimer ce formulaire?')">supprimer</a>
			<a href="<?php echo $this->baseLink.'&amp;action=edit&amp;item='.$objItem->id; ?>" rel="clickme">modifier</a>
		</div>
	</div>
<?php
	}
?>
</div>
<p>
<?php
	// --- PAGINATION -----
	if ($this->forms->hasPrevious() || $this->forms->hasNext()) {
		echo '<p class="pagination">';
		$this->forms->pPaginationFull($this->baseLink, 'pg');
		echo '</p>';
	}
?>
</p>
<?php
} else {

/* --- NO ARTICLE FOUND ------------------------------------------- */

?>
<p class="empty ctr">Aucun formulaire trouv&eacute;</p>
<?php
}
?>