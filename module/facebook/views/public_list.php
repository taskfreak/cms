<?php

$this->intro->printContent();

if ($this->content->rMore()) {

/* --- LIST OF CONTACTS ------------------------------------------- */
	
	$prevIsContact = false;
	
	while ($objItem = $this->content->rNext()) {
	
		if ($objItem->getOption('is_category')) {
			if ($prevIsContact) {
				echo '</ul>';
				$prevIsContact = false;
			}
			echo '<h3 class="contact_cat">'.$objItem->_join->get('name').'</h3>';
			continue;
		}
		
		if (!$prevIsContact) {
			echo '<ul class="contact_lst">';
		}
		
		$prevIsContact = true;
		
		echo '<li>';
		if ($objItem->_join->email) {
			echo '<a href="'.$objItem->_join->mailto().'">';
			$objItem->_join->pImg('photo');
			echo '</a>';
		} else {
			$objItem->_join->pImg('photo');
		}
		?>
		<address>
			<strong><?php echo $objItem->_join->get('name'); ?></strong><br />
			<?php
			$objItem->pTxt('body');
			if (trim($objItem->_join->note)) {
				echo '<br /><small>';
				$objItem->_join->pTxt('note');
				echo '</small>';
			}
			?>
		</address>
	<?php
	
		echo '</li>';
		
	}

	if ($prevIsContact) {
		echo '</ul>';
	}

	// pagination
	if ($this->content->hasPrevious() || $this->content->hasNext()) {
		echo '<p class="blog_pagination pagination">';
		$this->content->pPaginationFull($GLOBALS['objPage']->getUrl(), 'pg');
		echo '</p>';
	}

} else {

/* --- NO ARTICLE FOUND ------------------------------------------- */

?>
<p class="empty ctr">Aucun contact trouv&eacute;</p>
<?php
}
?>