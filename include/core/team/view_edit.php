<?php

TznCms::getHeader(true);

?>
<form id="main" name="cms_admin_form" action="<?php 
		echo CMS_WWW_URI.'admin/team.php'; 
	?>" method="post" class="box">
	<input type="hidden" name="id" value="<?php echo $_REQUEST['id']; ?>" />
	<h2>Gestion des membres</h2>
	<div class="boxed tabs">
		<h3><?php echo $GLOBALS['langTeam']['tab_info']; ?></h3>
		<div>
		<?php
		if (!$this->team->isLoaded() || $this->memberTeam->checkRights(1) || $GLOBALS['objUser']->hasAccess(19)) {
				
			// --- CREATE / EDIT FORM -----------------
		?>
	        <p><?php echo $GLOBALS['langForm']['compulsory_legend']; ?></p>
			<ol class="fields">
				<li class="compulsory">
					<label><?php echo $GLOBALS['langTeam']['name']; ?></label>
					<?php $this->team->qText('name','','wl'); ?>
				</li>
				<li>
					<label><?php echo $GLOBALS['langTeam']['description']; ?>:</label>
					<?php $this->team->qTextArea('description','','wl hm'); ?>
				</li>
				<li class="linefree inline">
					<?php $this->team->qCheckBox('enabled',$this->team->enabled,'','onclick="cms_toggle(\'visi_options\')"'); ?>
					<label for="c_display"><?php echo $GLOBALS['langTeam']['enabled_label']; ?></label>
					<span id="visi_options" style="display:<?php echo ($this->team->enabled)?'inline':'none'; ?>">
						<?php $this->visibilityList->qSelect('visibility',$this->team->visibility,'','wm'); ?>
					</span>
				</li>
			</ol>
		<?php
		} else {
				
			// --- VIEW ONLY ---------------------------
		?>
			<p><?php echo $GLOBALS['langTeam']['help_no_edit']; ?></p>
			<h4><?php $this->team->p('name'); ?></h4>
			<?php $this->team->p('description'); ?>
			<p><?php 
				echo '<img src="'.$this->team->getIcon().'" alt="" /> '; 
				echo $this->team->getVisibility(); 
			?></p>
		<?php
		
		} // end if new or edit with rights
		
		?>
		</div>
		<?php
		
		// ==== if item is loaded, show more info ==========================
		
		if ($this->team->isLoaded()) {
		
			// --- TEAM MEMBERS --------------------------------------------
		?>
		<h3><?php echo $GLOBALS['langTeam']['tab_members']; ?></h3>
		<div>
			<?php
				// -- list of members --
			?>
			<div id="team_member_list" class="table hl">
			  	<div class="row header">
					<div class="col c50"><?php echo $GLOBALS['langUser']['user']; ?></div>
					<div class="col c30"><?php echo $GLOBALS['langTeam']['position']; ?></div>
					<div class="col c20 action"><a href="<?php
					echo CMS_WWW_URI.'admin/team.php?id='.$this->team->id.'&amp;mode=member_add';
					?>" rel="ajaxed"><?php echo $GLOBALS['langTeam']['new_member']; ?></a></div>
				</div>
			  <?php
			  	while ($objItem = $this->memberList->rNext()) {
			  ?>
			    <div id="mbt_<?php echo $objItem->member->id; ?>" class="row">
			    	<div class="col c50"><?php echo $objItem->member->getName(); ?></div>
			    	<div class="col c30" id="mbp_<?php echo $objItem->member->id; ?>"><?php echo $objItem->getPosition(); ?></div>
			    	<div class="col c20 action">
			    	<?php
			    	  if ($GLOBALS['objUser']->checkAccess(19) || $this->memberTeam->position > $objItem->position) {
			    	?>
			    		<a href="<?php
			    			echo CMS_WWW_URI.'admin/team.php?id='.$this->team->id
			    				.'&amp;mode=member_remove'
			    				.'&amp;member='.$objItem->member->id;
			    		?>" rel="ajax"><?php echo $GLOBALS['langAdmin']['remove']; ?></a>
			    		<!-- a href="javascript:cms_team_position(<?php echo $objItem->member->id.','.$this->team->id; ?>)"><?php 
			    			echo $GLOBALS['langAdmin']['edit']; ?></a -->
			    	<?php
			    	  } else {
			    		echo '<span>'.$GLOBALS['langAdmin']['remove'].'</span>'
			    			.'<span>'.$GLOBALS['langAdmin']['edit'].'</span>';
			    	  }
			    	?>
			    	</div>
			    </div>
			  <?php
			  	}
			  ?>
			</div>
		</div>
		<?php
		/*
			if ($GLOBALS['objUser']->hasAccess(19)) {
			
			// --- TEAM PAGES ----------------------------------------------
		?>
		<h3><?php echo $GLOBALS['langTeam']['tab_pages']; ?></h3>
		<div>
			<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
			<p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem. Ut enim ad minima veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur? Quis autem vel eum iure reprehenderit qui in ea voluptate velit esse quam nihil molestiae consequatur, vel illum qui dolorem eum fugiat quo voluptas nulla pariatur?</p>
		</div>
		<?php
			} // end pages
			
		*/
		} // end more info if loaded
		?>
	</div>
	<div class="footer">
	<?php
	
	$GLOBALS['objCms']->adminSubmitButtons();
	
	echo '<a href="'.TznUtils::getReferrer(true).'" class="close">'.$GLOBALS['langSubmit']['closenosave'].'</a>';
	
	if (!$GLOBALS['objUser']->isLoggedIn()) {
		echo $GLOBALS['objCms']->settings->get('registration_footer');
	}
	?>
	</div>
</form>
<?php

TznCms::getFooter(true);
