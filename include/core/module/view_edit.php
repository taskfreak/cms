<?php

TznCms::getHeader();

?>
<form action="<?php echo CMS_WWW_URI.'admin/module.php'; ?>" id="squeezed" method="post">
	<h1>Options du module : <?php echo ($GLOBALS['langModule'][$key]['name'])?$GLOBALS['langModule'][$key]['name']:$key;?></h1>
	<?php 
	echo '<input type="hidden" name="mode" value="options" />';
	echo '<input type="hidden" name="key" value="'.$this->module->folder.'" />'; 
	
	include $this->module->includeScript;
	
	?>
	<p class="ctr">
		<?php $GLOBALS['objCms']->adminSubmitButtons(); ?>
	</p>	
</form>
<?php

TznCms::getFooter();