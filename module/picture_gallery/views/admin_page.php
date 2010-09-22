<h3 class="acctog"><?php echo $GLOBALS['langModule']['picture_gallery']['admin_list']; ?></h3>
<div class="accinf">
	<?php
		include $this->filePath('views/admin_list.php');
	?>
</div>
<h3 class="acctog">Introduction</h3>
<div class="accinf">
	<?php
		$this->content->qEditArea();
	?>
</div>
<h3 class="acctog">Options</h3>
<div class="accinf">
	<ol class="fields">
		<li>
			<label>Mode :</label>
			<ul>
				<li>
					<input id="opt_md_0" type="radio" name="option_mode" value="0"<?php
					if (!$this->content->getOption('mode')) echo ' checked="checked"';
					?> />
					<label for="opt_md_0">Liste miniatures</label>
				</li>
				<li>
					<input id="opt_md_1" type="radio" name="option_mode" value="1"<?php
					if ($this->content->getOption('mode') == 1) echo ' checked="checked"';
					?> />
					<label for="opt_md_1">Liste grand format</label>
				</li>
				<li>
					<input id="opt_md_2" type="radio" name="option_mode" value="2"<?php
					if ($this->content->getOption('mode') == 2) echo ' checked="checked"';
					?> />
					<label for="opt_md_2">Horizontal</label>
				</li>
			</ul>
		</li>
		<li>
			<label for="opt_twh">Taille aper&ccedil;us&nbsp;:</label>
			<input id="opt_twh" type="text" name="option_thb_wdh" class="wxs" value="<?php echo $this->content->getOption('thb_wdh'); ?>" />
			x <input id="opt_tht" type="text" name="option_thb_hgt" class="wxs" value="<?php echo $this->content->getOption('thb_hgt'); ?>" />
		</li>
		<li>
			<label for="opt_iwh">Grand format&nbsp;:</label>
			<input id="opt_iwh" type="text" name="option_img_wdh" class="wxs" value="<?php echo $this->content->getOption('img_wdh'); ?>" />
			x <input id="opt_iht" type="text" name="option_img_hgt" class="wxs" value="<?php echo $this->content->getOption('img_hgt'); ?>" />
		</li>
		<li>
			<label for="opt_pgs">Elements / page&nbsp;:</label>
			<input id="opt_pgs" type="text" name="option_page_size" class="wxxs" value="<?php echo $this->content->getOption('page_size'); ?>" />
		</li>
		<?php
		// -TODO- allow members to upload pictures
		?>
	</ol>
</div>
