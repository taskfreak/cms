	<div class="info">
		<a href="<?php echo $this->baseLink.'&amp;action=edit';	?>" class="button create frgt">Creer un nouveau contact</a>
		<hr class="sep" />
	</div>
<?php
if ($this->data->rMore()) {

/* --- LIST OF CONTACTS ------------------------------------------- */

?>
	<div class="table clickable hxxl">
<?php
	while ($objItem = $this->data->rNext()) {
		$link = $this->baseLink.'&amp;action=edit&amp;item='.$objItem->id;
		if ($GLOBALS['confModule']['contact']['page_per_contact']) {
			$link = $objItem->getUrl();
		}
?>
	<div class="row<?php if ($objItem->getOption('is_category')) { echo ' hsm'; } ?>">
		<div class="col c30"><?php 
		if ($objItem->getOption('is_category')) {
			echo '<h4><small>'.$objItem->_join->get('pos').'.</small> <a href="'.$link.'">'.$objItem->_join->get('name').'</a></h4>';
		} else {
			$objItem->_join->pImgThb('photo','',0,0,'style="float:left; margin-right: 12px;"');
			echo '<a href="'.$link.'">'.$objItem->_join->get('name').'</a><br /><small>';
			$objItem->_join->p('email');
			echo '<br />position : '.$objItem->_join->get('pos').'</small>';
		}
		?></div>
		<div class="col c50"><?php  
			echo '<small style="line-height: 1.5em">'; $objItem->pBbs('body'); echo '</small>';
		?></div>
		<div class="col c20 action">
			<a href="<?php echo $this->baseLink.'&amp;action=delete&amp;item='.$objItem->id; ?>" onclick="return confirm('r&eacute;ellement supprimer ce contact?')">supprimer</a>
			<a href="<?php echo $this->baseLink.'&amp;action=edit&amp;item='.$objItem->id; ?>" rel="clickme">modifier</a>
		</div>
	</div>
<?php
	}
?>
</div>
<?php
} else {

/* --- NO ARTICLE FOUND ------------------------------------------- */

?>
<p class="empty ctr">Aucun contact trouv&eacute;</p>
<?php
}
?>