<?php

$this->intro->printContent();

if ($this->content->rMore()) {

/* --- LIST OF ARTICLES ------------------------------------------- */

	while ($objItem = $this->content->rNext()) {
?>
	<div class="blog_item easyclick">
		<h3><a href="<?php echo $objItem->getUrl(); ?>"><?php echo $objItem->getTitle(); ?></a></h3>
		<?php
		if ($objItem->isEvent()) {
			echo '<h5>'.$objItem->getDates('%d %B %Y').'</h5>';
		} else {
			if ($this->intro->getOption('date_in_list') || $this->intro->getOption('author_in_list')) {
				echo '<h5>';
				if ($this->intro->getOption('date_in_list')) {
					echo 'post&eacute; le '.$objItem->_join->get('postDate',TZN_DATE_LNG);
				}
				if ($this->intro->getOption('author_in_list')) {
					echo ' par '.$objItem->author->getShortName(); 
				}
				echo '</h5>';
			}
		}
		?>
		<div><?php echo $objItem->getSummary(); ?></div>
		<p class="blog_link"><?php echo $objItem->getLinkMore(); ?></p>
	</div>
	<?php
	}

	// pagination
	if ($this->content->hasPrevious() || $this->content->hasNext()) {
		echo '<p class="blog_pagination pagination">';
		$this->content->pPaginationFull($GLOBALS['objPage']->getUrl(), 'pg');
		echo '</p>';
	}
?>
<?php
} else {

/* --- NO ARTICLE FOUND ------------------------------------------- */

?>
<p class="empty ctr">Aucun article trouv&eacute;</p>
<?php
}
?>