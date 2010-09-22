<?php

/* --- COMMON INFO --------------------------------------------------------- */

?>
<input type="hidden" name="id" value="<?php echo $_REQUEST['id']; ?>" />
<input type="hidden" name="save_mode" value="submit" />
<h3 class="acctog">Informations et coordonn&eacute;es</h3>
<div class="accinf">
    <div class="accsub"><?php echo $GLOBALS['langForm']['compulsory_legend']; ?></div>
    <?php
    if ($GLOBALS['objUser']->isLoggedIn()) {
    ?>
    <div class="frgt froption">
    	<ol class="fields top">
    		<li>
    			<label>Avatar :</label>
    			<?php $this->objMember->qImageOptional('avatar'); ?>
    		</li>
    		<li class="styled">
    			<label>Pseudo :</label>
    			<?php $this->objMember->qText('nickName','','wm'); ?>
    		</li>
    	</ol>	
    </div>
    <?php
    }
    ?>
    <ol class="fields side styled lined">
		<li>
			<label><?php echo $GLOBALS['langUser']['title']; ?> :</label>
			<?php $this->objMember->qText('title','','wxxs'); ?> <small>Mr. Mrs. Ms. Dr. Pr. etc...</small>
		</li>
		<li class="compulsory">
			<label><?php echo $GLOBALS['langUser']['first_name']; ?> :</label>
			<?php $this->objMember->qText('firstName','','wl'); ?>
		</li>
		<?php
		if ($GLOBALS['langUser']['middle_name']) {
		?>
		<li>
			<label><?php echo $GLOBALS['langUser']['middle_name']; ?> :</label>
			<?php $this->objMember->qText('middleName','','wl'); ?>
		</li>
		<?php
		}
		?>
		<li class="compulsory">
			<label><?php echo $GLOBALS['langUser']['last_name']; ?> :</label>
			<?php $this->objMember->qText('lastName','','wl'); ?>
		</li>
		<li>
			<label><?php echo $GLOBALS['langUser']['company']; ?> :</label>
			<?php $this->objMember->qText('companyName','','wl'); ?>
		</li>
		<li>
			<label><?php echo $GLOBALS['langUser']['address']; ?> :</label>
			<?php $this->objMember->qTextArea('address','','wl hs'); ?>
		</li>
		<li>
			<label><?php echo $GLOBALS['langUser']['zip_code']; ?> :</label>
			<?php $this->objMember->qText('zipCode','','wm'); ?>
		</li>
		<li>
			<label><?php echo $GLOBALS['langUser']['city']; ?> :</label>
			<?php $this->objMember->qText('city','','wl'); ?>
		</li>
		<li<?php if ($GLOBALS['objCms']->pageToSubmit == 'logister.php') echo ' class="compulsory"'; ?>>
			<label><?php echo $GLOBALS['langUser']['country']; ?> :</label>
			<?php 
				$this->objCountryList->rReset(); 
				$this->objCountryList->qSelect('countryId','name',$this->objMember->country->id,'','wl'); 
			?>
		</li>
		<li>
			<label><?php echo $GLOBALS['langUser']['state']; ?> :</label>
			<?php
				$this->objStateList->qSelect('stateCode',$this->objMember->stateCode,'for US members only','wl'); 
				$this->objMember->pError('stateCode');
			?>
		</li>
	</ol>
	<hr class="clear separator" />
	<ol class="fields side styled lined">
		<li<?php if (!$GLOBALS['objUser']->isLoggedIn()) echo ' class="compulsory"'; ?>>
			<label><?php echo $GLOBALS['langUser']['email']; ?> :</label>
			<?php $this->objMember->qText('email','','wl'); ?>
		</li>
		<li>
			<label><?php echo $GLOBALS['langUser']['language']; ?> :</label>
			<?php
				$this->objLanguageList->qSelect('cmsLanguage',$this->objMember->cmsLanguage,'','wl');
			?>
		</li>
	</ol>
</div>
<?php

/* --- PLUGIN -------------------------------------------------------------- */

if (count($this->arrPlugins)) {
	foreach($this->arrPlugins as $folder => $objPlugin) {
		//echo '<h3 class="acctog">'.$GLOBALS['langModule'][$str]['plugin'].'</h3>';
		echo '<h3 class="acctog">'.$GLOBALS['langPlugin'][$folder]['name'].'</h3>'; //-TODO-TRANSLATE-
		echo '<div class="accinf">';
		$objPlugin->view();
		echo '</div>';
	}
}

/* --- ACCOUNT, USERNAME, PASSWORD ----------------------------------------- */

?>
<h3 class="acctog">Compte utilisateur</h3>
<div class="accinf">
	<div class="accsub"><?php echo $GLOBALS['langUser']['account_legend']; ?></div>
	<ol class="fields side styled lined">
		<?php
			if (TZN_USER_LOGIN != 'email') {
		?>
		<li class="compulsory">
			<label><?php echo $GLOBALS['langUser']['username']; ?> :</label>
			<?php $this->objMember->qText('username','','wm'); ?>
		</li>
		<?php
			} else {
		?>
		<li class="compulsory">
			<label><?php echo $GLOBALS['langUser']['nickname']; ?> :</label>
			<?php
				$this->objMember->qText('username'); 
				echo '<small>'.$GLOBALS['langUser']['nickname_legend'].'</small>';
			?>
		</li>
		<?php
			} 
		?>
	</ol>
	<?php
	if ($this->objMember->isLoaded()) {
		echo '<p class="accsub">'.$GLOBALS['langUser']['password_legend'].'</p>'; 
	}
	?>
	<ol class="fields side styled lined">
		<li class="compulsory">
			<label><?php echo $GLOBALS['langUser']['password']; ?> :</label>
			<input type="password" name="password1" value="<?php echo $_POST['password1']; ?>" class="wm" /><?php
			 if ($strErr = $this->objMember->e('pass')) {
				echo '<span class="tznError">'.$strErr.'</span>';
			}
		?></li>
		<li class="compulsory">
			<label><?php echo $GLOBALS['langUser']['password_confirm']; ?> :</label>
			<input type="password" name="password2" value="<?php echo $_POST['password1']; ?>" class="wm" />
		</li>
		<?php

		if (!$GLOBALS['objUser']->isLoggedIn()) {
			// security image when requesting new account
		
			echo TznPage::getCaptcha(true,'li', $this->securityError);
		
		} else
		
		if ($GLOBALS['objUser']->hasAccess(18) && ($GLOBALS['objUser']->id != $this->objMember->id)) {
			// --- user is admin and is not editing himself -> edit user rights ---
		?>
		<li class="inline">
            <?php $this->objMember->qCheckBox('enabled',$this->objMember->enabled,'','onclick="cms_toggle(\'cust1\')"'); ?> <label for="c_enabled"><?php echo $GLOBALS['langUser']['enabled_label']; ?></label>
            <span id="cust1" style="display:<?php echo ($this->objMember->enabled)?'inline':'none'; ?>"><?php 
            	$this->objPositionList->qSelect('level',$this->objMember->level); 
            ?></span>
        </p>
		<?php
		}
		?>
	</ol>
	<?php
	if ($this->registerMessage) {
		echo '<p>'.$this->registerMessage.'</p>';
	}
	?>
</div>
<?php
			
/* --- TEAM LIST ----------------------------------------------------------- */
	
if (@constant('CMS_TEAM_ENABLE') && $this->objMember->isLoaded()) {
			
	?>
	<h3 class="acctog"><?php echo $GLOBALS['langUser']['teams_legend']; ?></h3>
	<div class="accinf table clickable hl">
	<?php
	$first = true;
	while ($objItem = $this->objTeamList->rNext()) {
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
			
				/* delete
				if ($GLOBALS['objUser']->hasAccess(17) && $GLOBALS['objUser']->level > $objItem->level) {
					echo '<a href="'.TznCms::getUri('admin/team.php?id='.$objItem->id.'&amp;mode=delete')
					 .'" onclick="return confirm(\''.$GLOBALS['langAdmin']['del_confirm_user'].'\')">'
					 .$GLOBALS['langAdmin']['delete'].'</a>';
				} else {
					echo '<span>'.$GLOBALS['langAdmin']['delete'].'</span>';
				}
				*/
				
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
	}
	?>
	</div>
	<?php
}

/* ------------------------------------------------------------------------- */

?>