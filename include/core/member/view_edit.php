<?php

TznCms::getHeader(true);

?>
<form id="main" name="cms_admin_form" action="<?php 
	if ($this->objMember->id == $GLOBALS['objUser']->id) {
		echo CMS_WWW_URI.'loguser.php'; 
	} else {
		echo CMS_WWW_URI.'admin/member.php'; 
	}
	?>" method="post" enctype="multipart/form-data" class="box"<?php
		if (!is_array($GLOBALS['confAdminMenu'])) {
			echo ' style="margin: 10px 100px 0 100px"';
		}
	?>>
	<h2><?php echo $GLOBALS['langAdminTitle']['members']; ?></h2>
<div id="accordion" class="boxed<?php
	if (!$this->objMember->isLoaded()) {
		echo ' showall';
	}
?>">
	<div id="pageheader" class="info">
		<ol class="multicol">
			<li class="double"><?php
				if ($this->objMember->isLoaded()) {
					echo '<label>'.$GLOBALS['langUser']['name'].' :</label> '.$this->objMember->getName();
				} else if ($GLOBALS['objCms']->pageToSubmit == 'logister.php') {
					echo ucfirst($GLOBALS['langUserAdmin']['newregister']);
				} else {
					echo ucfirst($GLOBALS['langUserAdmin']['newuser']);
				}
			?></li>
			<?php 
			if ($this->objMember->isLoaded()) { 
				?>
				<li><label><?php echo $GLOBALS['langUserAdmin']['info_visits']; ?>:</label> 
				<?php $this->objMember->p('visits'); ?></li>
				<li class="newrow double"><label><?php echo $GLOBALS['langUserAdmin']['info_creation']; ?>:</label>
					<?php $this->objMember->p('creationDate',CMS_DATETIME); ?></li>
				<li><label><?php echo $GLOBALS['langUserAdmin']['info_login']; ?>:</label> 
					<?php $this->objMember->p('lastLoginDate',CMS_DATETIME); ?></li>
				<?php
	    		if ($this->objMember->badAccess) {
		    		echo '<li class="newrow"><label>'.$GLOBALS['langUserAdmin']['info_failed']
	    				.'</label>'.$this->objMember->badAccess.'</li>';
	    		}
	    	}
			?>
		</ol>
	</div>
	<hr class="clear separator" />

<?php

	include CMS_CORE_PATH.'member/form.php';

?>
</div>
<div id="accfoo">
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