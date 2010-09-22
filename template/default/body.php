<div id="global">
	<?php
		// $GLOBALS['objCms']->adminMenu();
	?>
	<div id="header">
		<h1><a href="<?php echo CMS_WWW_URL; ?>">TaskFreak CMS integrated demo</a></h1>
		<?php include CMS_WWW_PATH.'template/default/include/user.php'; ?>
	</div>
	<?php
	if ($GLOBALS['objPage']->module == 'taskfreak') {
		echo '<div id="main" class="content">';
		$objPage->view();
		echo '</div>';
	} else {
		$objMenus->p('nav1','menu'); 
		
	?>
	<div id="content" class="content">
		<?php $objPage->view(); ?>
	</div>
	<?php
	}
	?>
	<hr class="clear" />
</div>
<?php
include CMS_TEMPLATE_PATH.'default/include/footer.php';