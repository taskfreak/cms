<?php

$this->content->printContent();

if ($this->data->rMore()) {

/* --- LIST OF REFERENCES ----------------------------------------- */

	echo '<ul id="fulls">';

	while ($objItem = $this->data->rNext()) {
		echo '<li>'
			.'<strong>'.$objItem->getTitle().'</strong>'
			.'<span><img src="'.$objItem->_join->getImgUrl('imgfile','',1).'" alt="'
			.$objItem->getTitle().'" /></span>'
			.'</li>';
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

