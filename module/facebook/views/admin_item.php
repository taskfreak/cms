	<ol class="fields">
		<li>
			<label>Pos :</label>
			<?php $this->content->_join->qText('pos','','wxs'); ?>
		</li>
		<li>
			<label>Nom :</label>
			<?php $this->content->_join->qText('name','','wxl'); ?>
		</li>
		<?php if (!$GLOBALS['confModule']['contact']['editor_mode']) { ?>
		<li>
			<label>Description :</label>
			<?php $this->content->qTextArea('body','','wxl hm'); ?>
		</li>
		<?php } ?>
		<li class="inline">
			<input type="checkbox" id="optcat" name="option_is_category" value="1" onclick="$('contact_opt').toggleClass('faded')" <?php
				if ($this->content->getOption('is_category')) echo 'checked="checked" ';
			?>/>
			<label for="optcat">Cet &eacute;l&eacute;ment repr&eacute;sente une cat&eacute;gorie</label>
		</li>
	</ol>
	<ol id="contact_opt" class="fields<?php echo ($this->content->getOption('is_category'))?' faded':''; ?>">
		<li>
			<label>Photo :</label>
			<?php $this->content->_join->qImage('photo'); ?>
			<small><?php echo 'taille image JPG, GIF ou PNG recommand&eacute;e : '
				.$GLOBALS['confModule']['contact']['img_wdh'].' x '.$GLOBALS['confModule']['contact']['img_hgt'].' pixels'; ?></small>
		</li>
		<li>
			<label>Email :</label>
			<?php $this->content->_join->qText('email','','wxl'); ?>
		</li>
		<li>
			<label>Note :</label>
			<?php $this->content->_join->qTextArea('note','','wxl hs'); ?>
		</li>
	</ol>
	<?php
	
	if ($GLOBALS['confModule']['contact']['editor_mode']) {
		$this->content->qEditArea();
	}
		
	echo '<input type="hidden" name="item" value="'.$this->content->_join->id.'" />';
	
	?>