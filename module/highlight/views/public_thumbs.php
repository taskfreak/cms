<?php

$this->content->printContent();

if ($this->data->rMore()) {

/* --- LIST OF REFERENCES ----------------------------------------- */

	echo '<ul id="thumbs">';

	while ($objItem = $this->data->rNext()) {
		echo '<li>'
			.'<a href="'.$objItem->_join->getImgUrl('imgfile').'" rel="milkbox[gal]" title="'.$objItem->getDescription().'">'
			.'<img src="'.$objItem->_join->getImgUrl('imgfile','',2).'" alt="'
			.$objItem->getTitle().'" />'
			.'</a><strong>'.$objItem->getTitle().'</strong></li>';
	}
	echo '</ul>';
	
	if ($this->content->getOption('page_size') && ($this->data->hasNext() || $this->data->hasPrevious())) {
		echo '<div class="paging">';
		// echo '<p>'.$this->data->getPaginationStats().'</p>';
		$this->data->pPaginationFull($GLOBALS['objPage']->getUrl(), 'pg');
		echo '</div>';
	}
	
} else {

/* --- NO ARTICLE FOUND ------------------------------------------- */

?>
<p class="empty ctr"><?php echo $GLOBALS['langTznCommon']['list_empty']; ?></p>
<?php
}

