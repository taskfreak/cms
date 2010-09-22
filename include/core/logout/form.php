	<?php
	if (TznUtils::hasMessage()) {
		echo '<p class="highlight redish homer">'.TznUtils::getMessages().'</p>';
	} else {
		echo '<p class="highlight mellow ctr homer">'.$GLOBALS['langLogout']['nowlogout'].'</p>';
	}
	?>
	<ol class="fields side">
		<li><span><?php echo $GLOBALS['langLogout']['lastlogin']; ?></span></li>
		<li>
			<label><?php echo $GLOBALS['langAdmin']['date']; ?> :</label>
			<span><?php $GLOBALS['objUser']->pDtm("lastLoginDate","%a %d %b %Y <small>%H:%M</small>",$this->userTimeZone); ?></span>
		</li>
		<li class="linefree">
			<label><?php echo $GLOBALS['langLogout']['from']; ?> :</label>
			<span><?php $GLOBALS['objUser']->pStr("lastLoginAddress");?></span>
		</li>
	</ol>
	<hr class="clear" />
