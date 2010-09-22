<?php
/****************************************************************************\
* TaskFreak!                                                                 *
* multi user                                                                 *
******************************************************************************
* Version: 0.5.7                                                             *
* Authors: Stan Ozier <taskfreak@gmail.com>                                  *
* License:  http://www.gnu.org/licenses/gpl.txt (GPL)                        *
\****************************************************************************/


?>
<h3 class="ctr">Thank you <?php echo $this->objMember->getName(); ?>!</h3>
<?php
if ($GLOBALS['objCms']->message) {
?>
<div class="highlight mellow">
	<p>Your request has been received and will be processed.</p>
	<p>However, an error occured while trying to send you the confirmation email.
	This means we'll have to manually active your account.<br />
	We will contact you once this is done.</p>
	<p class="error"><?php echo $GLOBALS['objCms']->message; ?></p>
</div>
<?php
} else {

	switch ($GLOBALS['objCms']->settings->get('registration')) {
	case 1:
?>
<div class="highlight mellow">
	<p class="ctr">Your request has been received and will be processed.<br />
	We will contact you as soon as your account is activated.</p>
</div>
<?php
		break;
	case 2:
?>
<div class="highlight mellow">
	<p><strong>Please check your emails</strong></p>
	<p>We have just sent a message.</p>
	<p>Please read it and follow the instructions to complete the registration process</p>
    <p><a href="login.php">Click here to come back to login page</a></p>
</div>
<?php
	}
}
