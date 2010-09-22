<?php
/****************************************************************************\
* Tirzen CMS                                                                 *
******************************************************************************
* Version: 0.1                                                               *
* Authors: Stan Ozier <stan@tirzen.com>                                      *
* License:  http://www.gnu.org/licenses/gpl.txt (GPL)                        *
\****************************************************************************/

?>
<p class="box">You are already connected as<br /><b><?php echo $objCms->user->getName(); ?></b></p>
<p class="subbox">What would you like to do?
	<ul>
	<li><a href="./">Go to site's home page</a></li>
	<?php 
	if ($objCms->user->checkAccess(24)) {
	?>
	<li><a href="admin.php">Got to admin section</a></li>
	<?php
	}
	?>
	<li><a href="<?php echo CMS_WWW_URI; ?>admin/member.php?id=<?php echo $objCms->user->id; ?>">Modify your account</a></li>
	<li><a href="logout.php">Logout</a></li>
	</ul>
</p>
