<?php
if ($this->intro->getOption('intro_in_item')) {
	$this->intro->printContent();
}
?>
<a href="<?php echo $GLOBALS['objPage']->getUrl(); ?>" class="blog_back puce">retour liste articles</a>
<?php
if ($this->content->isLoaded()) {
?>
<h2 class="blog_title"><?php $this->content->_join->p('title'); ?></h2>
<?php
	if ($this->intro->getOption('date_in_item') || $this->intro->getOption('author_in_item')) {
		echo '<h5>';
		if ($this->intro->getOption('date_in_item')) {
			echo 'post&eacute; le '.$this->content->_join->get('postDate',TZN_DATE_LNG);
		}
		if ($this->intro->getOption('author_in_item')) {
			echo ' par '.$this->content->author->getShortName(); 
		}
		echo '</h5>';
	}
?>
<?php
// show article
$this->content->printContent();

// show comments and comment form
$this->commentsView();

} else {

	echo '<p>Blog article not available</p>'; // -TRANSLATE-

}