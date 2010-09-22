<?php

TznCms::getHeader(true);

?>
<form id="main" name="cms_admin_form" action="<?php 
		echo CMS_WWW_URI.'admin/member.php'; 
	?>" method="post" class="box">
	<h2><?php echo $GLOBALS['langAdminTitle']['members']; ?></h2>
<div class="boxed">
	<div class="info">
		<?php
		if ($GLOBALS['objUser']->hasAccess(15)) {
		?>
		<a class="button create frgt" href="<?php echo TznCms::getUri('admin/member.php?id=new'); ?>"><?php echo $GLOBALS['langUserAdmin']['newuser']; ?></a>
		<?php
		}
		?>
		<p>
			<img src="<?php echo CMS_WWW_URI; ?>assets/images/i_user_admin.png" /><?php
				$iMax = count($GLOBALS['langGlobalPosition']);
				echo $GLOBALS['langGlobalPosition'][$iMax-1]; ?>
			&nbsp;
			<img src="<?php echo CMS_WWW_URI; ?>assets/images/i_user_moderator.png" /><?php 
				echo $GLOBALS['langGlobalPosition'][$iMax-2]; ?>
			&nbsp;
			<img src="<?php echo CMS_WWW_URI; ?>assets/images/i_user_member.png" /><?php
				 echo $GLOBALS['langGlobalPosition'][1]; ?>
			&nbsp;
			<img src="<?php echo CMS_WWW_URI; ?>assets/images/i_user_guest.png" /><?php 
				echo $GLOBALS['langGlobalPosition'][0]; ?>
			&nbsp;
			<img src="<?php echo CMS_WWW_URI; ?>assets/images/i_user_off.png" /><?php echo $GLOBALS['langUserAdmin']['contact']; ?>
		</p>
	</div>
	<div class="quicksearch">
	  <label><?php echo $GLOBALS['langUserAdmin']['search_name']; ?>:</label>
	  <input type="text" name="userSearch" value="<?php echo($_SESSION['userSearch']); ?>" class="fMd fDefault" />
	  <select name="userOrder" onChange="this.form.submit()">
		<option value="0"><?php echo $GLOBALS['langUserAdmin']['order_visit']; ?></option>
	  	<option value="1"<?php if ($_SESSION['userOrder'] == 1) echo ' selected="selected"'; ?>><?php echo $GLOBALS['langUserAdmin']['order_rights']; ?></option>
	  	<option value="2"<?php if ($_SESSION['userOrder'] == 2) echo ' selected="selected"'; ?>><?php echo $GLOBALS['langUserAdmin']['order_creation']; ?></option>
	  </select>
	  <button type="submit" name="userSearchSubmit" value="1"><?php echo $GLOBALS['langButton']['search']; ?></button>
	  <button type="button" onclick="this.form.elements[0].value='';this.form.submit()">X</button>
	</div>
	<div class="table clickable hl">
	<?php
	while ($objItem = $this->objMemberList->rNext()) {
	?>
		<div id="us_<?php echo $objItem->id; ?>" class="row">
			<div class="col c40"><?php
				if ($GLOBALS['objUser']->hasAccess(18) && $GLOBALS['objUser']->level > $objItem->level) {
					echo '<a href="javascript:cms_enable('.$objItem->id.')"><img id="cms_item_'.$objItem->id.'" '
						.'src="'.$objItem->getIcon().'" width="16" height="16" class="flft" /></a>';
				} else {
					echo '<img src="'.$objItem->getIcon().'" width="16" height="16" class="flft" />';
				}
				
				echo $objItem->getName(); 
				
			?></div>
			<div class="col c20"><?php
				echo $objItem->getLocation(); 
			?></div>
			<div class="col c20"><?php 
				echo $objItem->getDtm(($this->memberDate)?$this->memberDate:'lastLoginDate',CMS_DATETIME,TZN_TZDEFAULT,'-'); 
			?></div>
			<div class="col c20 action"><?php 
			
				// delete
				if ($GLOBALS['objUser']->hasAccess(17) && $GLOBALS['objUser']->level > $objItem->level) {
					echo '<a href="'.TznCms::getUri('admin/member.php?id='.$objItem->id.'&amp;mode=delete')
					 .'" onclick="return confirm(\''.$GLOBALS['langAdmin']['del_confirm_user'].'\')">'
					 .$GLOBALS['langAdmin']['delete'].'</a>';
				} else {
					echo '<span>'.$GLOBALS['langAdmin']['delete'].'</span>';
				}
				
				// edit
				if ($GLOBALS['objUser']->hasAccess(16) && $GLOBALS['objUser']->level >= $objItem->level) {
					echo '<a href="'.TznCms::getUri('admin/member.php?id='.$objItem->id).'" rel="clickme">'
						.$GLOBALS['langAdmin']['edit'].'</a>';
				} else {
					echo '<span>'.$GLOBALS['langAdmin']['edit'].'</span>';
				}
				
			?>
			</div>
		</div>
	<?php
	} // end loop throught members
	
	// Pagination
	?>
	</div>
</div>
<div class="footer pagination">
<?php
	$this->objMemberList->pPaginationFull(TznCms::getUri('admin/member.php'), 'userPage');
?>
</div>
</form>
<?php

TznCms::getFooter(true);