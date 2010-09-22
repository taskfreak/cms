<?php

TznCms::getHeader(true);

?>
<form id="main" name="cms_admin_form" action="<?php 
		echo CMS_WWW_URI.'admin/team.php'; 
	?>" method="post" class="box">
	<h2>Gestion des groupes</h2>
<div class="boxed">
	<div class="info">
		<?php
		if ($GLOBALS['objUser']->hasAccess(15)) {
		?>
		<a class="button create frgt" href="<?php echo TznCms::getUri('admin/team.php?id=new'); ?>"><?php echo $GLOBALS['langTeam']['new_team']; ?></a>
		<?php
		}
		?>
		<p>
			<img src="<?php echo CMS_WWW_URI; ?>assets/images/i_team_prvt.png" /><?php
				echo $GLOBALS['langTeam']['visi_private']; ?>
			&nbsp;
			<img src="<?php echo CMS_WWW_URI; ?>assets/images/i_team_prtd.png" /><?php 
				echo $GLOBALS['langTeam']['visi_protected']; ?>
			&nbsp;
			<img src="<?php echo CMS_WWW_URI; ?>assets/images/i_team_pblc.png" /><?php
				echo $GLOBALS['langTeam']['visi_public']; ?>
			&nbsp;
			<img src="<?php echo CMS_WWW_URI; ?>assets/images/i_team_off.png" /><?php 
				echo $GLOBALS['langTeam']['visi_disabled']; ?>
		</p>
	</div>
	<!-- div class="quicksearch">
	  <label><?php echo $GLOBALS['langUserAdmin']['search_name']; ?>:</label>
	  <input type="text" name="userSearch" value="<?php echo($_SESSION['userSearch']); ?>" class="fMd fDefault" />
	  <select name="userOrder" onChange="this.form.submit()">
		<option value="0"><?php echo $GLOBALS['langUserAdmin']['order_visit']; ?></option>
	  	<option value="1"<?php if ($_SESSION['userOrder'] == 1) echo ' selected="selected"'; ?>><?php echo $GLOBALS['langUserAdmin']['order_rights']; ?></option>
	  	<option value="2"<?php if ($_SESSION['userOrder'] == 2) echo ' selected="selected"'; ?>><?php echo $GLOBALS['langUserAdmin']['order_creation']; ?></option>
	  </select>
	  <button type="submit" name="userSearchSubmit" value="1"><?php echo $GLOBALS['langButton']['search']; ?></button>
	  <button type="button" onclick="cms_search_reset('userSearch')">X</button>
	</div -->
	<div class="table clickable hl">
	<?php
	while ($objItem = $this->teamList->rNext()) {
	?>
		<div id="us_<?php echo $objItem->id; ?>" class="row">
			<div class="col c40"><?php
			
				if ($GLOBALS['objUser']->hasAccess(22) || $objItem->memberTeam->checkRights(2)) {
					echo '<a href="javascript:cms_team_enable('.$objItem->id.')"><img id="cms_item_'.$objItem->id.'" '
						.'src="'.$objItem->getIcon().'" class="flft" /></a>';
				} else {
					echo '<img src="'.$objItem->getIcon().'" width="16" height="16" class="flft" />';
				}
				
				echo '<a href="'.CMS_WWW_URI.'admin/team.php?id='.$objItem->id.'" rel="clickme">'
					.$objItem->get('name').'</a>'; 
				
			?></div>
			<div class="col c20"><?php
				echo $objItem->memberTeam->getPosition(); 
			?></div>
			<div class="col c20"><?php 
				echo $objItem->get('memberTeamCount').' '.$GLOBALS['langTeam']['members']; 
				/*
				if ($GLOBALS['objUser']->checkAccess(19) || $objItem->memberTeam->checkRights(3)) { 
					echo '<a href="'.CMS_WWW_URI.'admin/team.php?id='.$objItem->id.'#members">'.$str.'</a>';
				} else {
					echo $str;
				} 
				*/
			?></div>
			<div class="col c20 action"><?php 
			
				// delete
				if ($GLOBALS['objUser']->hasAccess(17) && $GLOBALS['objUser']->level > $objItem->level) {
					echo '<a href="'.TznCms::getUri('admin/team.php?id='.$objItem->id.'&amp;mode=delete')
					 .'" onclick="return confirm(\''.$GLOBALS['langAdmin']['del_confirm_user'].'\')">'
					 .$GLOBALS['langAdmin']['delete'].'</a>';
				} else {
					echo '<span>'.$GLOBALS['langAdmin']['delete'].'</span>';
				}
				
				// edit
				if ($GLOBALS['objUser']->hasAccess(16) && $GLOBALS['objUser']->level >= $objItem->level) {
					echo '<a href="'.TznCms::getUri('admin/team.php?id='.$objItem->id).'" rel="clickme">'
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
	$this->teamList->pPaginationFull(TznCms::getUri('admin/member.php'), 'userPage');
?>
</div>
</form>
<?php

TznCms::getFooter(true);