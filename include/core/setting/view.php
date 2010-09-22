<?php

$objCms =& $GLOBALS['objCms'];

TznCms::getHeader(true);

?>
<form id="main" action="<?php echo CMS_WWW_URI.'admin/setting.php'; ?>" method="post" class="box">
	<h2><?php echo $GLOBALS['langSystemStuff']['site_legend']; ?></h2>
	<div id="accordion" class="boxed">
		<div class="info">
			<input type="hidden" name="maintenance_old" value="<?php echo $objCms->settings->get('maintenance'); ?>" />
			<input type="hidden" name="registration_old" value="<?php echo $objCms->settings->get('registration'); ?>" />
			<input type="hidden" name="auto_login_old" value="<?php echo $objCms->settings->get('auto_login'); ?>" />
			<input type="hidden" name="password_reminder_old" value="<?php echo $objCms->settings->get('password_reminder'); ?>" />
		</div>
		<h3 class="acctog">Options du site</h3>
		<div class="accinf"><ol class=" fields">
			<li>
				<label><?php echo $GLOBALS['langSystemStuff']['site_title']; ?> :</label>
				<input type="text" name="site_title" value="<?php echo $objCms->settings->get('site_title'); ?>" class="wxxl" /></p>		
			</li>
			<li>
				<label><?php echo $GLOBALS['langSystemStuff']['site_footer']; ?> :</label>
				<input type="text" name="site_footer" value="<?php echo $objCms->settings->get('site_footer'); ?>" class="wxxl" />
			</li>
			<li class="inline">
				<input id="c_maintenance" type="checkbox" name="maintenance" value="1" onclick="cms_toggle('maintenance_opt')" <?php
		        if ($objCms->settings->get('maintenance')) echo 'checked="checked" ';
			    ?>/><label for="c_maintenance"><?php echo $GLOBALS['langSystemStuff']['site_offline']; ?></label>
	        	<div id="maintenance_opt" style="display:<?php 
	        		echo ($objCms->settings->get('maintenance'))?'block':'none'; ?>">
	            	<textarea name="maintenance_message" class="wxxl hm"><?php echo $objCms->settings->get('maintenance_message'); ?></textarea>
		        </div>
			</li>
		</ol></div>
		<h3 class="acctog"><?php echo $GLOBALS['langSystemStuff']['interface_legend']; ?></h3>
		<div class="accinf"><ol class=" fields">
			<li class="inline">
				<label><?php echo $GLOBALS['langSystemStuff']['interface_lang']; ?> : </label>
	        	<?php $this->languageList->qSelect('default_language',$objCms->settings->get('default_language')); ?>
	        </li>
	        <li class="inline">
	        	<label><?php echo $GLOBALS['langSystemStuff']['interface_date_format']; ?>:</label>
	        	<input id="date_0"type="radio" name="date_us_format" value="0" <?php
	    		if (!$objCms->settings->get('date_us_format')) echo 'checked="checked" ';
	    		?>/><label for="date_0"><?php echo $GLOBALS['langSystemStuff']['interface_date_eur']; ?></label>
		    	<input id="date_1" type="radio" name="date_us_format" value="1"<?php
	    			if ($objCms->settings->get('date_us_format')) echo 'checked="checked" ';
	    		?>/><label for="date_1"><?php echo $GLOBALS['langSystemStuff']['interface_date_usa']; ?></label>
	        </li>
		</ol></div>
		<h3 class="acctog"><?php echo $GLOBALS['langSystemStuff']['options_legend']; ?></h3>
		<div class="accinf"><ol class=" fields">
			<li class="inline">
				<input id="set_log" type="checkbox" name="auto_login" value="1" <?php
    	    		if ($objCms->settings->get('auto_login')) echo 'checked="checked" ';
				?>/><label for="set_log"><?php echo $GLOBALS['langSystemStuff']['options_auto_login']; ?></label>
			</li>
			<li class="inline">
				<input id="set_pwd" type="checkbox" name="password_reminder" value="1" <?php
					if ($objCms->settings->get('password_reminder')) echo 'checked="checked" ';
				?>/><label for="set_pwd"><?php echo $GLOBALS['langSystemStuff']['options_pass_reminder']; ?></label>
			</li>
			<li class="inline">
				<input id="set_reg" type="checkbox" name="registration_toggle" value="1" onclick="cms_toggle('registration_opt')" <?php
					if ($objCms->settings->get('registration')) echo 'checked="checked" ';
				?>/><label for="set_reg"><?php echo $GLOBALS['langSystemStuff']['options_register']; ?></label>
				<span id="registration_opt" style="margin:4px 0px 3px 20px;display:<?php 
					echo ($objCms->settings->get('registration'))?'inline':'none'; ?>">
				<select name="registration_value">
					<option value="1"<?php if ($objCms->settings->get('registration') == 1) echo ' selected="selected"'; ?>><?php echo $GLOBALS['langSystemStuff']['options_register_man']; ?></option>
					<option value="2"<?php if ($objCms->settings->get('registration') == 2) echo ' selected="selected"'; ?>><?php echo $GLOBALS['langSystemStuff']['options_register_auto']; ?></option>
				</select>
				</span>
			</li>
		</ol></div>
	</div>
	<div class="footer"><?php
		$GLOBALS['objCms']->adminSubmitButtons(); 
		echo ' <a href="'.TznUtils::getReferrer(true, false).'" class="close">fermer</a>'; // -TODO-TRANSLATE-
	?></div>
</form>
<?php

TznCms::getFooter(true);