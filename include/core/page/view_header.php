<?php

TznCms::getHeader();

?>
<form action="<?php echo TznCms::getUri('admin/page.php'); ?>" id="squeezed" method="post">
<?php
if ($this->page->id) {
?>
	<div class="frgt">
		<?php
		
		if ($this->page->canCopy()) {
		?>
		<a href="<?php echo TznCms::getUri('admin/page.php?id='.$this->page->id.'&amp;mode=copy'); ?>" onclick="return confirm('<?php echo $GLOBALS['langSetHeaders']['duplicate_confirm']; ?>')" class="button"><?php echo $GLOBALS['langSetHeaders']['duplicate']; ?></a>
		<?php
		}
		
		$GLOBALS['objCms']->adminSubmitButtons(); 
		
		?>
	</div>
	<h1><?php $this->page->p('title'); ?></h1>
<?php
} else {
	echo '<h1>'.$GLOBALS['langSetHeaders']['newpage'].'</h1>';
}

echo '<input type="hidden" name="mode" value="'.$this->mode.'" />';

if ($this->page->id) {
	// editing
	$this->page->qHidden('id');
} else {
	// adding?
	$this->pageParent->qHidden('id');
}

?>
	<h3><?php echo $GLOBALS['langSetHeaders']['basic']; ?></h3>
	<ol class="fields">
		<li>
			<label><?php echo $GLOBALS['langSetHeaders']['title']; ?> :</label>
			<?php $this->page->qText('title','','wl',$this->jsUpdAuto); ?>
		</li>
		<li>
			<label><?php echo $GLOBALS['langSetHeaders']['menu']; ?> :</label>
			<?php $this->page->qText('menu','','wl'); ?>
		</li>
		<?php
			if (!$this->page->getLvl('protected',2)) {
		?>
		<li>
			<label><?php echo $GLOBALS['langSetHeaders']['shortcut']; ?> :</label>
			<?php $this->page->qText('shortcut','','wl','maxlength=60'); ?>
		</li>
		<li>
			<label><?php echo $GLOBALS['langSetHeaders']['parent']; ?> :</label>
			<?php $this->pageList->qSelect('parent','title',$this->pageParent->id,'(root)','wl',($this->page->getLvl('protected',4))?'disabled="disabled"':''); ?>
		</li>
		<li>
			<label><?php echo $GLOBALS['langSetHeaders']['template']; ?> :</label>
			<?php $this->templateList->qSelect('template',$this->page->template,null,'wl'); ?>
		</li>
		<li class="linefree">
			<label><?php echo $GLOBALS['langSetHeaders']['module']; ?> :</label>
			<?php $this->moduleList->qSelect('module',$this->page->module,'(section)','wl'); ?>
		</li>
		<?php
			} else {
				$this->page->qHidden('shortcut');
			}
		?>
	</ol>
<?php
	if ($this->mode != 'add') {
?>
	<h3><?php echo $GLOBALS['langSetHeaders']['advanced']; ?></h3>
	<ol class="fields">
		<li>
			<label><?php echo $GLOBALS['langSetHeaders']['charset']; ?> :</label>
			<?php $this->page->qText('encoding',CMS_CHARSET,'wm'); ?>
		</li>
		<li>
			<label><?php echo $GLOBALS['langSetHeaders']['description']; ?> :</label>
			<?php
				$this->page->qTextArea('description','','wl hl');
				/* -TODO-
				echo '<br />';
				Tzn::qButton('apply',$GLOBALS['langSetHeaders']['apply_to_children'],'bsave','onclick="cms_apply_description(this.form)"');
				*/
			?>
		</li>
		<li>
			<label><?php echo $GLOBALS['langSetHeaders']['keywords']; ?> :</label>
			<?php $this->page->qTextArea('keyword','','wl hm'); ?>
		</li>
		<li class="linefree inline">
			<?php $this->page->qCheckBox('display','','','onclick="cms_toggle(\'publish_options\')"'); ?>
			<label for="c_display"><?php echo $GLOBALS['langSetHeaders']['display_ok']; ?></label>
			<ul id="publish_options" style="display:<?php echo ($this->page->display)?'block':'none'; ?>">
				<li>
					<input type="radio" id="c_private0" name="private" value="0" <?php if (!$this->page->private) echo 'checked="checked" '; ?>/>
					<label for="c_private0"><?php echo $GLOBALS['langPrivateView']['public']; ?></label>
				</li>
				<li>
					<input type="radio" id="c_private1" name="private" value="1" <?php if ($this->page->private == 1) echo 'checked="checked" '; ?>/>
					<label for="c_private1"><?php echo $GLOBALS['langPrivateView']['protected']; ?></label>
				</li>
				<li>
					<input type="radio" id="c_private2" name="private" value="2" <?php if ($this->page->private == 2) echo 'checked="checked" '; ?>/>
					<label for="c_private2"><?php echo $GLOBALS['langPrivateView']['private']; ?></label>
				</li>
				<li>
					<?php $this->page->qCheckBox('showInMenu'); ?>
					<label for="c_showInMenu"><?php echo $GLOBALS['langSetHeaders']['display_menu']; ?></label>
				</li>
			</ul>
		</li>
	</ol>
	<?php
	
	if ($GLOBALS['objUser']->hasAccess(9)) {
	
	?>
	<h3><?php echo $GLOBALS['langSetHeaders']['protection']; ?></h3>
	<ol class="fields multicol linefree">
		<li class="inline">
			<?php $this->page->qLevel('protected',1,FALSE); ?>
			<label for="c_protected-0"><?php echo $GLOBALS['langSetHeaders']['nocontent']; ?></label>
		</li>
		<li class="inline">
			<?php $this->page->qLevel('protected',2,FALSE); ?>
			<label for="c_protected-1"><?php echo $GLOBALS['langSetHeaders']['nosetup']; ?></label>
		</li>
		<li class="inline newrow">
			<?php $this->page->qLevel('protected',3,FALSE); ?>
			<label for="c_protected-2"><?php echo $GLOBALS['langSetHeaders']['noadd']; ?></label>
		</li>
		<li class="inline">
			<?php $this->page->qLevel('protected',4,FALSE); ?>
			<label for="c_protected-3"><?php echo $GLOBALS['langSetHeaders']['nomove']; ?></label>
		</li>
		<li class="inline newrow">
			<?php $this->page->qLevel('protected',5,FALSE); ?>
			<label for="c_protected-4"><?php echo $GLOBALS['langSetHeaders']['nodelete']; ?></label>
		</li>
	</ol>
	<?php
	}
	
	?>
	<hr class="clear" />
<?php
}
?>
	<p class="ctr">
		<?php $GLOBALS['objCms']->adminSubmitButtons(); ?>
	</p>	
</form>
<?php

TznCms::getFooter();