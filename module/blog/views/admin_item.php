	<ol class="fields">
		<li>
			<label><?php echo $GLOBALS['langBlogAdminForm']['publishon']; ?> :</label>
			<?php $this->content->_join->qDateSelect('cms_date','postDate','NOW'); ?>
		</li>
		<?php
		// multiple page choice
		if (!$GLOBALS['objPage']->id) {
		?>
			<li>
				<label>Page:</label>
				<?php $this->objPageList->qSelect('pageId','title',$this->content->pageId); ?>
			</li>
		<?php
		}
		
		// title
		// shall we update shortcut field automatically ?
		$strUpdAuto = '';
		if (!$this->content->shortcut) {
			$strUpdAuto = 'onblur="this.form.shortcut.value=cms_shortcut(this.value);"';
		}
		?>
		<li>
			<label><?php echo $GLOBALS['langBlogAdminForm']['title']; ?> :</label>
			<?php $this->content->_join->qText('title','','wxl',$strUpdAuto); ?>
		</li>
		<li>
			<label><?php echo $GLOBALS['langSetHeaders']['shortcut']; ?> :</label>
			<?php 
				echo $GLOBALS['objPage']->shortcut.'/';
				$this->content->qText('shortcut','','wl','maxlength=60'); 
			?>
		</li>
		<?php
		// summary
		?>
		<li>
			<label><?php echo $GLOBALS['langBlogAdminForm']['summary']; ?> :</label>
			<?php $this->content->_join->qTextArea('summary','','wxl hs'); ?>
		</li>
		<?php
		// event or not
		if ($GLOBALS['confModule'][$this->folder]['allow_events']) {
		?>
		<li class="inline">
			<?php $this->content->_options->qCheckBox('option_is_event','','','onclick="cms_toggle(\'publish_event\')"'); ?><label for="c_option_is_event"><?php echo $GLOBALS['langBlogAdminForm']['event_is_event']; ?></label>
			<ul id="publish_event" style="margin-left:20px;display:<?php echo ($this->content->_options->get('option_is_event'))?'block':'none'; ?>">
				<li>
					<label><?php echo $GLOBALS['langBlogAdminForm']['event_date_begin']; ?> :</label>
					<?php $this->content->_join->qDateSelect('cms_begin','eventStart',''); ?>
				</li>
				<li>
					<label><?php echo $GLOBALS['langBlogAdminForm']['event_date_end']; ?> :</label>
					<?php $this->content->_join->qDateSelect('cms_end','eventStop',''); ?>
				</li>
			</ul>			
		</li>
		<?php
		}
		?>
		<li class="inline">
			<?php $this->content->_join->qCheckbox('sticky'); ?>
			<label for="c_sticky"><?php echo $GLOBALS['langBlogAdminForm']['sticky_help']; ?></label>
        </li>
		<li class="inline">
			<?php $this->content->_join->qCheckBox('publish','','','onclick="cms_toggle(\'publish_options\')"'); ?>
			<label for="c_publish"><?php echo $GLOBALS['langSetHeaders']['display_ok']; ?></label>
			<ul id="publish_options" style="margin-left:20px;display:<?php echo ($this->content->_join->publish)?'block':'none'; ?>">
			<?php
				if (!$GLOBALS['objPage']->private) {
			?>
				<li><input type="radio" id="blogpr0" name="private" value="0" <?php if (!$this->content->_join->private) echo 'checked="checked" '; ?>/><label for="blogpr0"><?php echo $GLOBALS['langPrivateView']['public']; ?></label></li>
			<?php
				}
				if ($GLOBALS['objPage']->private <= 1) {
			?>
				<li><input type="radio" id="blogpr1" name="private" value="1" <?php if ($this->content->_join->private == 1) echo 'checked="checked" '; ?>/><label for="blogpr1"><?php echo $GLOBALS['langPrivateView']['protected']; ?></label></li>
			<?php
				}
				if ($GLOBALS['objPage']->private <= 2) {
			?>
				<li><input type="radio" id="blogpr2" name="private" value="2" <?php if ($this->content->_join->private == 2) echo 'checked="checked" '; ?>/><label for="blogpr2"><?php echo $GLOBALS['langPrivateView']['private']; ?></label></li>
			<?php
				}
			?>
			</ul>
		</li>
		<?php
		if ($GLOBALS['confModule'][$this->folder]['comments']) {
		?>
		<li class="inline">
			<?php $this->content->_options->qCheckBox('option_comments_allow','','','onclick="cms_toggle(\'publish_comments\')"'); ?>
			<label for="c_option_comments_allow"><?php echo $GLOBALS['langBlogAdminForm']['allowcomment']; ?></label>
			<ul id="publish_comments" style="display:<?php echo ($this->content->_options->get('option_comments_allow'))?'block':'none'; ?>">
				<li>
					<?php $this->content->_options->qCheckBox('option_comments_private'); ?>
					<label for="c_option_comments_private"><?php echo $GLOBALS['langBlogAdminForm']['membersonly']; ?></label>
				</li>
			</ul>			
		</li>
		<?php
		}
		?>
	</table>
	<hr class="sep" />
	<?php
	
	$this->content->qEditArea();
	
	echo '<input type="hidden" name="item" value="'.$this->content->_join->id.'" />';
	
	//if ($this->content->_join->isLoaded()) {
		//Tzn::qHidden('item',$this->content->_join->id);
	//}
	//Tzn::qHidden('handle','blog');
	?>