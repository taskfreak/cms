<div id="quick_admin">
	<h4><?php
		echo '<a href="'.CMS_WWW_URI.'loguser.php?id='.$GLOBALS['objUser']->id.'">'
			.$GLOBALS['objUser']->getName().'</a>';
	?>
	<small>(<a href="<?php echo CMS_WWW_URI.'logout.php'; ?>"><?php echo TznCms::getTranslation('logout','langMenu'); ?></a>)</small>
	</h4>
	<p>
		<a href="<?php echo CMS_WWW_URI.'admin/'; ?>"><?php echo TznCms::getTranslation('administration','langMenu'); ?></a> |
		<a href="<?php echo CMS_WWW_URI.'admin/page.php'; ?>"><?php echo TznCms::getTranslation('sitemap','langMenu'); ?></a> |
		<a href="<?php echo CMS_WWW_URI.'admin/page.php?id='.$GLOBALS['objPage']->id
			.(($_REQUEST['item'])?('&amp;action=edit&amp;item='.$_REQUEST['item']):''); ?>&amp;backtopage"><?php echo TznCms::getTranslation('edit','langMenu'); ?></a>
	</p>
</div>