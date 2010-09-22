<?php

TznCms::getHeader(true);

?>
<form id="main" action="<?php echo CMS_WWW_URI.'admin/email.php'; ?>" method="post" class="box">
  <h2><?php echo $GLOBALS['langAdminTitle']['emails']; ?></h2>
  <div id="accordion" class="boxed">
<?php

	/* --- EMAIL LIST ------------------------------------------------------ */

?>
	<h3 class="acctog"><?php echo $GLOBALS['langAdminTitle']['emails_alerts']; ?></h3>
	<div class="accinf">
		<div class="info"><p class="ctr">
			<img src="<?php echo CMS_WWW_URI; ?>assets/images/i_email_on.png" width="16" height="16" />
			<?php echo $GLOBALS['langSystem']['email_list_help_on']; ?> &nbsp;
			<img src="<?php echo CMS_WWW_URI; ?>assets/images/i_email_off.png" width="16" height="16" />
			<?php echo $GLOBALS['langSystem']['email_list_help_off']; ?> &nbsp;
			<img src="<?php echo CMS_WWW_URI; ?>assets/images/i_email_in.png" width="16" height="16" />
			<?php echo $GLOBALS['langSystem']['email_list_help_in']; ?> &nbsp;
			<img src="<?php echo CMS_WWW_URI; ?>assets/images/i_email_out.png" width="16" height="16" />
			<?php echo $GLOBALS['langSystem']['email_list_help_out']; ?>
		</p></div>
		<div class="table hxl clickable">
	    <?php
	        while ($objItem = $this->itemList->rNext()) {
	    ?>
	        <div id="em_<?php echo $objItem->id; ?>" class="row">
	          <div class="col c5 ctr">
				<img id="cms_item_<?php echo $objItem->id; ?>" src="<?php echo $objItem->getIcon(); ?>" border="0" alt="" />
	          </div>
	          <div class="col c5 ctr"><?php echo $objItem->getDirection(); ?></div>
	          <div class="col c60">
	            <a href="?id=<?php echo $objItem->id; ?>"<?php echo $actStyle; ?> rel="ajaxed"><?php echo $GLOBALS['langSystemEmail'][$objItem->description]; ?></a><br />
	            <small><?php echo $objItem->getSubject(); ?></small>
	          </div>
	          <div class="col c30"><?php
	            echo (($objItem->direction)?'To: ':'From: ').$objItem->get('recipientAddress').'<br />';
	            echo '<small>Cc: ';
	            $objItem->pStr('recipientCc','-');
	            echo '</small>';
	          ?></div>
	        </div>
	    <?php
	        }
	    ?>
	    </div>	
	</div>
	<h3 class="acctog"><?php echo $GLOBALS['langAdminTitle']['emails_setup']; ?></h3>
	<div class="accinf">
		<p>
			<label><?php echo $GLOBALS['langSystemEmailStuff']['setup_prefix']; ?>:</label>
			<?php Tzn::qText('email_prefix',$GLOBALS['objCms']->settings->get('email_prefix'),'width:100px'); ?>
		</p>
		<p>
			<label><?php echo $GLOBALS['langSystemEmailStuff']['setup_address']; ?>:</label>
			<?php Tzn::qText('default_email',$GLOBALS['objCms']->settings->get('default_email'),'width:250px'); ?>
		</p>
		<p>
    	<input type="checkbox" id="smtp_toggle" name="smtp_toggle" value="1" onclick="$('smtp_opt').toggleClass('faded')" <?php
        if ($GLOBALS['objCms']->settings->get('email_smtp')) echo 'checked="checked" ';
    	?>/><label for="smtp_toggle"><?php echo $GLOBALS['langSystemEmailStuff']['setup_smtp']; ?></label></p>
        <ol id="smtp_opt" class="fields<?php echo ($GLOBALS['objCms']->settings->get('email_smtp'))?'':' faded'; ?>">
            <li>
            	<label><?php echo $GLOBALS['langSystemEmailStuff']['setup_server']; ?> :</label>
            	<?php Tzn::qText('smtp1',$arrSmtp[0]); ?>
			</li>
			<li>
				<label><?php echo $GLOBALS['langUser']['username']; ?> :</label>
				<?php Tzn::qText('smtp2',$arrSmtp[1]); ?>
			</li>
			<li>
				<label><?php echo $GLOBALS['langUser']['password']; ?> :</label>
				<input type="password" name="smtp3" value="<?php echo $arrSmtp[2];?>" />
			</li>
        </ol>
	</div>
  </div>
  <div class="footer"><?php
		$GLOBALS['objCms']->adminSubmitButtons(); 
		echo ' <a href="'.TznUtils::getReferrer(true, false).'" class="close">fermer</a>'; // -TODO-TRANSLATE-
	?></div>
</form>
<?php

TznCms::getFooter(true);