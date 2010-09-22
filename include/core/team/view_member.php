<?php

TznCms::getHeader();

?>
<form action="<?php echo CMS_WWW_URI.'admin/team.php'; ?>" id="squeezed" method="post" onsubmit="return ajaxify_form(this,'tmres')">
	<h1><?php echo $this->team->get('name').' : '.$GLOBALS['langTeam']['new_member']; ?></h1>
	<input type="hidden" name="id" value="<?php echo $this->team->id; ?>" />
	<input type="hidden" name="mode" value="member_add" />
	<p>
		<?php echo $GLOBALS['langTeam']['search_member'].': '; ?>
		<input type="text" name="searchMember" class="wm" />
		<button type="submit" name="search"><?php echo $GLOBALS['langButton']['search']; ?></button>
	</p>
	<div id="tmres">
		<p>&nbsp;</p>
	</div>
</form>
<?php

TznCms::getFooter();

?>