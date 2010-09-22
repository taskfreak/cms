<?php

if ($this->content->getOption('member_only') && !$GLOBALS['objUser']->isLoggedIn()) {

	// no access for the non member
	$this->noaccess->printContent();

	return false;
}

// show dashboard

$this->content->printContent();

?>
<div id="col-comm" class="col">
	<h2>Derniers commentaires</h2>
	<?php
	while ($objComment = $this->dataComms->rNext()) {
	?>
	<div id="comment_<?php echo $objComment->id; ?>" class="comment_item easyclick">
		<?php 
			if ($GLOBALS['objUser']->hasAccess(6,'blog',$objComment->member->id)) 
			{
			?>
			<div class="comment_action">
				<a href="ajax.php?module=comment&amp;action=edit&amp;id=<?php echo $objComment->id; ?>" rel="ajax comment_body_<?php echo $objComment->id; ?>"><?php echo $GLOBALS['langComment']['edit']; ?></a> | 
				<a href="javascript:{}" onclick="if (confirm('<?php echo $GLOBALS['langComment']['delete_confirm']; ?>')) ajaxify_request('ajax.php?module=comment&amp;action=delete&amp;id=<?php echo $objComment->id; ?>');"><?php echo $GLOBALS['langComment']['delete']; ?></a>
			</div>
			<?php
			}
		?>
		<div class="comment_head"><?php
			echo $objComment->member->getAvatar(); 
			echo '<p>'.$objComment->getAuthorName().'</p>';
		?></div>
		<p class="comment_date"><?php echo $objComment->getPostDate(); ?></p>
		<h4><?php echo '<a href="'.CMS_WWW_URI.$objComment->shortcut.'.html#comment_'.$objComment->id.'" rel="clickme">'.$objComment->blog->get('title').'</a>';?></h4>
		<div id="comment_body_<?php echo $objComment->id; ?>" class="comment_body"><?php
			echo $objComment->getStr('body',150); 
		?></div>
	</div>
	<?php
	}
	?>
</div>
<div id="col-news" class="col cl2">
	<h2>
		<?php
		if ($lnk = $GLOBALS['confModule']['dashboard']['link_new_blog']) {
			echo '<a href="'.$lnk.'" class="button submit frgt" title="Publier un article">+</a>';
		}
		?>
		Actualit&eacute;s
	</h2>
	<?php
	while ($obj = $this->dataBlogs->rNext()) {
	?>
	<div class="item easyclick">
		<h4><a href="<?php echo $obj->getUrl(); ?>"><?php echo $obj->_join->get('title'); ?></a></h4>
		<p class="head"><?php echo 'post&eacute; le '.$obj->_join->get('postDate','%d %B').'  <span class="comments">'.$obj->getCommentCount().'&nbsp;com.</span>'; ?></p>
		<p><?php echo $obj->getSummary(100); ?></p>
	</div>
	<?php
	}
	?>
</div>
<div id="col-events" class="col">
	<h2>
		<?php
		if ($lnk = $GLOBALS['confModule']['dashboard']['link_new_event']) {
			echo '<a href="'.$lnk.'" class="button submit frgt" title="Annoncer un &eacute;v&eacute;nement">+</a>';
		}
		?>
		&Eacute;v&eacute;nements
	</h2>
	<?php
	while ($obj = $this->dataEvents->rNext()) {
	?>
	<div class="item easyclick">
		<h4><a href="<?php echo $obj->getUrl(); ?>"><?php echo $obj->_join->get('title'); ?></a></h4>
		<p class="head"><?php echo $obj->getDates('%d %B').' <span class="comments">'.$obj->getCommentCount().'&nbsp;com.</span>'; ?></p>
		<p><?php echo $obj->getSummary(100); ?></p>
	</div>
	<?php
	}
	?>
</div>
<hr class="clear" />