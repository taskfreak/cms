<div id="global">
	<div id="header">
		<h1>
			<a href="/"><?php echo $GLOBALS['objCms']->settings->get('website_name'); ?></a>
		</h1>
	</div>
	<?php
	if ($GLOBALS['objUser']->isLoggedIn()) {
	?>
	<div id="user">
		<a href="<?php echo TznCms::getUri('logout.php'); ?>" id="logout">logout</a>
		<a href="<?php echo TznCms::getUri('loguser.php'); ?>" id="account"><?php echo $GLOBALS['objUser']->getName(); ?></a>
	</div>
	<?php
	}
	?>
	<div id="usermore">
	<?php
	if (TznUtils::hasMessage()) {
		echo TznUtils::getMessages($isError, false);
	} else {
		echo '...';
	}
	?>
	</div>