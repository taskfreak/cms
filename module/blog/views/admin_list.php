	<div class="info">
		<?php
		if ($GLOBALS['objUser']->hasAccess(2, 'blog')) {
			echo '<a href="'.$this->baseLink.'&amp;action=edit" class="button create frgt">Creer un nouvel article</a>';
		}
		?>
		<p class="stats"><?php
		$this->data->pPrevious($this->baseLink, 'pg');
		echo ' '.$this->data->getPaginationStats().' ';
		$this->data->pNext($this->baseLink, 'pg');
		?></p>
		<hr class="sep" />
	</div>
<?php
if ($this->data->rMore()) {

/* --- LIST OF ARTICLES ------------------------------------------- */

?>
	<div class="table clickable hl">
<?php
	while ($objItem = $this->data->rNext()) {
?>
	<div class="row">
		<div class="col c50"><?php 
			// -- private ? --
			switch ($objItem->_join->private) {
			case 1:
				echo ' <img src="'.CMS_WWW_URI.'assets/images/i_protected.png" alt="members only" class="frgt" />';
				break;
			case 2:
				echo ' <img src="'.CMS_WWW_URI.'assets/images/i_private.png" alt="specific members only" class="frgt" />';
				break;
			}
			
			// -- published ? --
			echo '<img id="cms_item_'.$objItem->id.'" '.'src="'
				.$objItem->getIcon('blog',$objItem->_join->publish).'" class="flft" />';
				
			// -- title --
			echo '<a href="'.$objItem->getUrl().'">'.$objItem->getTitle().'</a>'; 
			
		?></div>
		<div class="col c20"><?php echo $objItem->author->getName(); ?></div>
		<div class="col c10"><?php $objItem->_join->p('postDate',CMS_DATE); ?></div>
		<div class="col c20 action">
		<?php
		if ($GLOBALS['objUser']->hasAccess(4, 'blog')) {
			echo '<a href="'.$this->baseLink.'&amp;action=delete&amp;item='.$objItem->id.'" '
				.'onclick="return confirm(\'r&eacute;ellement supprimer cet article?\')">supprimer</a> ';
		} else {
			echo '<span>supprimer</span> ';
		}
		if ($GLOBALS['objUser']->hasAccess(3, 'blog')) {
			echo '<a href="'.$this->baseLink.'&amp;action=edit&amp;item='.$objItem->id.'" rel="clickme">modifier</a>';
		} else {
			echo '<span>modifier</span>';
		}
		?>
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
<p class="empty ctr">Aucun article trouv&eacute;</p>
<?php
}
?>