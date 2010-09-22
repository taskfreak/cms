	<form action="<?php echo TznCms::getUri('login.php'); ?>" method="post"<?php
		if (@constant('TZN_USER_PASS_MODE') == 5) {
			echo ' onsubmit="return cms_secure_login(this);"';
		}
		?>>
        <?php
        
        if ($this->errorMessage) {
			echo '<p class="highlight redish homer ctr">'.$this->errorMessage.'</p>';
		}
        
        $GLOBALS['objUser']->qLoginTimeZone(); 
        ?>
        <ol class="fields side linefree">
            <li>
                <label><?php
                	if (TZN_USER_LOGIN == 'email') {
                		echo $GLOBALS['langTznUser']['login_email']; 
                	} else {
                		echo $GLOBALS['langTznUser']['login_username']; 
                	}
                ?>:</label>
                <?php Tzn::qText('username',$_REQUEST['username'],'wm'); ?>
            </li>
            <li>
                <label><?php echo $GLOBALS['langTznUser']['login_password']; ?>:</label>
                <input type="password" name="password" value="" class="wm" />
            </li>
            <?php
            // activation?
            if ($this->activationRequest) {
            ?>
            <li>
                <label><?php echo $GLOBALS['langTznUser']['login_activation']; ?>:</label>
                <input type="text" name="activation" value="<?php echo $_REQUEST['activation']; ?>" class="wm" />
            </li>
            <?php
			}
			?>
			<?php
			// auto login
			if ($GLOBALS['objCms']->settings->get('auto_login')) { 
	        ?>
			<li class="inline"><?php 
				Tzn::qCheckBoxLabel($GLOBALS['langTznUser']['login_remember'],'remember');
			?></li>
			<?php
			}
			?>
			<li class="inline"><button type="submit" name="login" value="1" class="submit"><?php echo $GLOBALS['langTznUser']['button_login']; ?></button></li>
		</ol>
        <?php
        if (@constant('TZN_USER_PASS_MODE') == 5) {
        	Tzn::qHidden('challenge',$_SESSION['challenge']);
        }
        ?>
	</form>