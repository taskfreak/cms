	<ol class="fields">
		<li>
			<label>post&eacute; le</label>
			<?php echo $this->content->_join->getDtm('postDate','LNG'); ?>
		</li>
		<?php echo $this->content->printFormAdmin(); ?>
		<li>
			<label>Memo</label>
			<?php $this->content->_join->qTextArea('memo','','wxl hm'); ?>
		</li>
	</ol>
	<?php
	if (!$GLOBALS['objPage']->id) {
		echo '<input type="hidden" name="pageId" value="'.$this->content->pageId.'" />';
	}
	echo '<input type="hidden" name="item" value="'.$this->content->_join->id.'" />';
	?>