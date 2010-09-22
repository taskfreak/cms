<?php

TznCms::getHeader(true);

?>
<form action="<?php echo CMS_WWW_URI.'admin/page.php'; ?>" method="post" enctype="multipart/form-data" id="main" class="box">
<h2><?php echo $this->page->p('title'); ?></h2>
<div id="accordion" class="boxed">
	<?php
	if ($pAction = TznUtils::sanitize(TZN_SANITIZE_SIMPLE,$_REQUEST['action'])) {
		echo '<input type="hidden" name="action" value="'.$pAction.'" />';
	} else {
		// show header only on default action
	?>
	<div id="pageheader" class="info">
		<?php
		if ($this->page->canHeader()) {
			// link to page header edit
			echo '<a class="button frgt" href="'.CMS_WWW_URI.'admin/page.php?id='.$this->page->id.'&amp;mode=header"'
				.' rel="ajaxed">'.TznCms::getTranslation('setup','langAdmin').'</a>';
		}
		?>
		<ol class="multicol">
			<li class="double"><label>URL : </label><?php 
				echo '<a href="'.CMS_WWW_URI.$this->page->shortcut.'.html">'
					.CMS_WWW_URI.$this->page->shortcut.'.html</a>'; 
			?></li>
			<li><label><?php echo $GLOBALS['langSetHeaders']['status']; ?> : </label><?php echo $this->page->getStatus(); ?></li>
			<li class="newrow"><label><?php echo $GLOBALS['langSetHeaders']['menu']; ?> : </label><?php $this->page->p('title'); ?></li>
			<li><label><?php echo $GLOBALS['langSetHeaders']['module']; ?> : </label><?php $this->page->p('module'); ?></li>
			<li><label><?php echo $GLOBALS['langSetHeaders']['template']; ?> : </label><?php $this->page->p('template'); ?></li>
		</ol>
	</div>
	<hr class="clear separator" />
	<?php
	}
	$this->page->qHidden('id');
	
	$this->module->adminView();
	
	?>
</div>
<div class="footer"><?php
	if (is_object($this->page) && !$this->page->display) {
	?>
	<div class="frgt">
		<input id="c_forcePublish" type="checkbox" name="forcePublish" value="1" />
		<label for="c_forcePublish"><?php echo $GLOBALS['langSetHeaders']['publish_auto']; ?></label>
	</div>
	<?php
	}
	$GLOBALS['objCms']->adminSubmitButtons();
	echo ' <a href="'.TznUtils::getReferrer(true, false).'" class="close">'.TznCms::getTranslation('close','langSubmit').'</a>';
?></div>
</form>
<?php

TznCms::getFooter(true);