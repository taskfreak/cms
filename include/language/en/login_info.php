<?php
/****************************************************************************\
* Tirzen CMS                                                                 *
******************************************************************************
* Version: 0.1                                                               *
* Authors: Stan Ozier <stan@tirzen.com>                                      *
* License:  http://www.gnu.org/licenses/gpl.txt (GPL)                        *
\****************************************************************************/


if ($GLOBALS['objCms']->settings->get('registration')) {
?>
<p><b>If you are not a member yet</b> but would like to become one, <a href="<?php echo TznCms::getUri('logister.php'); ?>">please request an account here</a>.</p>
<?php
}
if ($pErrorMessage) {
?>
<p><b>If you are experiencing problems to login</b>, that could be because of one of the following reasons :</p>
<ul style="margin:-10px 0px 0px 40px;padding:0px">
  <li>You are not connected or your session has expired</li>
  <li>You entered a wrong password
  <?php
  if ($GLOBALS['objCms']->settings->get('password_reminder')) {
  ?>
  <br /><a href="logminder.php">Request a new one here</a></li>
  <?php
  }
  ?>
  <li>Your browser does not support javascript</li>
  <li>Your browser does not accept cookies</li>
  <li>You do not have sufficient rights to access the requested information</li>
</ul>
<?php
} 

/* else if ($GLOBALS['objCms']->settings->get('password_reminder')) {
?>
<p><b>If you forgot your access codes</b>, <a href="logminder.php">request them back here</a>
<?php
} */
