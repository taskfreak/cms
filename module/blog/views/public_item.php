<?php
if ($this->intro->getOption('intro_in_item')) {
	$this->intro->printContent();
}
?>
<div class="blog_header">
	<a href="<?php echo $GLOBALS['objPage']->getUrl(); ?>" class="blog_back puce">retour liste articles</a>
	<h1><?php $this->content->_join->p('title'); ?></h1>
<?php
if ($this->content->isLoaded()) {
	if ($this->intro->getOption('date_in_item') && $this->intro->getOption('author_in_item')) {
		echo $this->content->author->getAvatar();
		if ($this->content->isEvent()) {
			echo '<h4>'.ucfirst($this->content->getDates('%d %B %Y')).'</h4>';
		}
		echo '<p>auteur : '.$this->content->author->getShortName().'</p>';
		echo '<p>publication : '.$this->content->_join->get('postDate','LNX').'</p>';
	} else if ($this->intro->getOption('date_in_item') || $this->intro->getOption('author_in_item')) {
		echo '<h5>';
		if ($this->intro->getOption('date_in_item')) {
			echo 'post&eacute; le '.$this->content->_join->get('postDate',TZN_DATE_LNG);
		}
		if ($this->intro->getOption('author_in_item')) {
			echo ' par '.$this->content->author->getShortName(); 
		}
		echo '</h5>';
	}
	// echo '<hr class="clear" />';
	echo '</div>';
	echo '<div class="blog_body">';
	
	// show article
	$this->content->printContent();
	
	echo '</div>';
	
	// show comments and comment form
	$this->commentsView();

} else {
	echo '</div>';
	echo '<p>Article indisponible</p>'; // -TRANSLATE-

}