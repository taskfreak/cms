<?php
if ($this->errorMessage) {
	echo '<p class="highlight redish homer ctr">'.$this->errorMessage.'</p>';
} else {
?>
<p><?php echo TznCms::getTranslation('password_intro','langUser'); ?></p>
<?php
}
?>
<form action="<?php echo TznCms::getUri('logminder.php'); ?>" method="post">
	<ol class="fields linefree">
		<li>
			<label><?php echo TznCms::getTranslation('email_address','langUser'); ?> :</label>
			<?php Tzn::qText('email',$_POST['email'],'wl'); ?>
		</li>
		<li>
			<button type="submit" name="reminder" onclick="this.disabled=true; this.form.submit(); return false;" class="wm bart"><?php
				echo TznCms::getTranslation('send','langButton');
			?></button>
		</li>
	</ol>
</form>
<p><?php echo TznCms::getTranslation('password_footer','langUser'); ?></p>