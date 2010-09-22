<?php

TznCms::getHeader(true);

?>
<form action="<?php echo CMS_WWW_URI.'admin/page.php'; ?>" method="post" enctype="multipart/form-data" id="main" class="box">
	<h2><?php echo $GLOBALS['langAdminTitle']['pages']; ?></h2>
	<div class="table boxed hl clickable">
		<div class="info">
			<p>
			<img src="<?php echo CMS_WWW_URI; ?>assets/images/i_section_on.png" alt="" /> <?php echo $GLOBALS['langAdmin']['sectionactive']; ?> &nbsp;
			<img src="<?php echo CMS_WWW_URI; ?>assets/images/i_section_off.png" alt=""  /> <?php echo $GLOBALS['langAdmin']['sectionhidden']; ?> &nbsp;
			<img src="<?php echo CMS_WWW_URI; ?>assets/images/i_page_on.png" alt="" /> <?php echo $GLOBALS['langAdmin']['pageactive']; ?> &nbsp;
			<img src="<?php echo CMS_WWW_URI; ?>assets/images/i_page_ono.png" alt="" /> <?php echo $GLOBALS['langAdmin']['pagenomenu']; ?> &nbsp;
			<img src="<?php echo CMS_WWW_URI; ?>assets/images/i_page_off.png" alt="" /> <?php echo $GLOBALS['langAdmin']['pagehidden']; ?>
			<?php 
				// echo $GLOBALS['langAdminHelpSiteMap']; 
			?>
			</p>
		</div>
<?php
	while ($this->page = $this->pageList->rNext()) {
		$level = $this->page->getOutlineLevel();
?>
		<div id="pg_<?php echo $this->page->id; ?>" class="row">
			<div class="col c40"><?php
			
				// -- private ? --
				switch ($this->page->private) {
				case 1:
					echo ' <img src="'.CMS_WWW_URI.'assets/images/i_protected.png" alt="members only" class="frgt" />';
					break;
				case 2:
					echo ' <img src="'.CMS_WWW_URI.'assets/images/i_private.png" alt="specific members only" class="frgt" />';
					break;
				}
			
				// -- level indent --
				for ($lvl = $level; $lvl > 1; $lvl--) {
					echo '<div class="flft">&nbsp;&nbsp;&nbsp;&nbsp;</div>';
				}
				
				// -- status --
				if ($GLOBALS['objUser']->hasAccess(8)) {
					echo '<a href="javascript:cms_display('.$this->page->id.')"><img id="cms_item_'.$this->page->id.'" '
						.'src="'.$this->page->getIcon().'" class="flft" /></a>';
				} else {
					echo '<img src="'.$this->page->getIcon().'" class="flft" />';
				}
				
				// -- title --
				echo '<a id="cms_itlnk_'.$this->page->id.'" href="'.$this->page->getUrl().'"';
				if (!$this->page->display) {
					echo ' class="disabled"';
				}
				echo ' title="'.$this->page->get('title').'">'.$this->page->get('menu',40).'</a>';
				
				// -- module & template --
			?></div>
			<div class="col c15"><?php
				echo $this->page->getTemplate();
			?></div>
			<div class="col c15"><?php
				echo TznCms::getTranslation($this->page->module, 'langModule', 'name');
			?></div>
			<div class="col c30 action"><?php
			
				// -- delete --
				if ($this->page->canDelete()) {
					echo '<a href="?id='.$this->page->id.'&amp;mode=delete" onclick="return confirm(\''
						.$GLOBALS['langAdmin']['confirmdel'].'\')">'.$GLOBALS['langAdmin']['delete'].'</a>';
				} else {
					echo '<span>'.$GLOBALS['langAdmin']['delete'].'</span>';
					$first = false;
				}
				
				// -- add sub page --
				if ($this->page->canAdd()) {
					echo '<a href="?id='.$this->page->id.'&amp;mode=add" rel="ajaxed" title="ajouter une sous-page">'.$GLOBALS['langAdmin']['add'].'</a>';
				} else {
					echo '<span>'.$GLOBALS['langAdmin']['add'].'</span>';
				}
				
				// -- move up --
				if ($this->page->canMove()) {
					echo '<a href="?mode=up&amp;id='.$this->page->id.'">'.$GLOBALS['langAdmin']['up'].'</a>';
				} else {
					echo '<span>'.$GLOBALS['langAdmin']['up'].'</span>';
				}
				
				// -- header --
				if ($this->page->canHeader()) {
					echo '<a href="?id='.$this->page->id.'&amp;mode=header" rel="ajaxed">'.$GLOBALS['langAdmin']['setup'].'</a>';
				} else {
					echo '<span>'.$GLOBALS['langAdmin']['setup'].'</span>';
				}
			
				// -- edit --
				if ($this->page->canEdit()) {
					echo '<a href="?id='.$this->page->id.'" rel="clickme">'.$GLOBALS['langAdmin']['edit'].'</a>';
				} else if ($this->page->module) {
					// -TODO- use CMS message instead of alert
					// -TODO-TRANSLATE-
					echo '<a class="disabled" rel="clickme" '
						.'href="javascript:alert(\'Cannot modify content (protected)\')">'
						.$GLOBALS['langAdmin']['edit'].'</a>';
				} else {
					echo '<span>'.$GLOBALS['langAdmin']['edit'].'</span>';
				}
				
				
			?></div>
		</div>
<?php
	} // end loop through pages
?>
	</div>
	<div class="footer"><?php
	if ($GLOBALS['objUser']->hasAccess(7)) {
	?>
	<a href="<?php echo TznCms::getUri('admin/page.php?mode=addsection'); ?>"><?php 
		echo $GLOBALS['langAdmin']['addsection']; ?></a>
	<?php
	} else {
		echo '...';
	}
	?></div>
</form>
<?php

TznCms::getFooter(true);