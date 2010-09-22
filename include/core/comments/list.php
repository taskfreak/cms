<div class="comment_list">
	<h3><?php echo $GLOBALS['langComment']['comments']; ?></h3>
<?php
	if ($this->comments->rMore()) {
	
		// === list comments ========================================
		
		while ($objComment = $this->comments->rNext()) {
?>
	<div id="comment_<?php echo $objComment->id; ?>" class="comment_item">
		<?php 
			if ($GLOBALS['objUser']->hasAccess(6,$this->folder,$objComment->author->id)) 
			{
			?>
			<div class="comment_action">
				<a href="/ajax.php?module=comment&amp;action=edit&amp;id=<?php echo $objComment->id; ?>" rel="ajax comment_body_<?php echo $objComment->id; ?>"><?php echo $GLOBALS['langComment']['edit']; ?></a> | 
				<a href="javascript:{}" onclick="if (confirm('<?php echo $GLOBALS['langComment']['delete_confirm']; ?>')) ajaxify_request('/ajax.php?module=comment&amp;action=delete&amp;id=<?php echo $objComment->id; ?>');"><?php echo $GLOBALS['langComment']['delete']; ?></a>
			</div>
			<?php
			}
		?>
		<div class="comment_head"><?php
			echo $objComment->author->getAvatar(); 
			echo '<p>'.$objComment->getAuthorName().'</p>';
		?></div>
		<p class="comment_date"><?php echo $objComment->_options->getDtm('option_post_date','LNX'); ?></p>
		<div id="comment_body_<?php echo $objComment->id; ?>" class="comment_body"><?php
			$objComment->p('body'); 
		?></div>
	</div>
<?php
		}
	} else {
		// --- no comment found ---
		echo '<p>'.$GLOBALS['langComment']['none'].'</p>';
	}
		
?>
</div>