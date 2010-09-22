<?php
/****************************************************************************\
* Tirzen CMS                                                                 *
******************************************************************************
* Version: 0.1                                                               *
* Authors: Stan Ozier <stan@tirzen.com>                                      *
* License:  http://www.gnu.org/licenses/gpl.txt (GPL)                        *
\****************************************************************************/

?>
<p class="box">Vous &ecirc;tes d&eacute;j&agrave; connect&eacute; en tant que<br /><b><?php echo $objCms->user->getName(); ?></b></p>
<p class="subbox">Que d&eacute;sirez-vous faire?
	<ul>
	<li><a href="./">Aller &agrave; la page d'accueil</a></li>
	<?php 
	if ($objCms->user->checkAccess(24)) {
	?>
	<li><a href="<?php echo TznCms::getUri('admin.php'); ?>">Aller au panneau d'administration</a></li>
	<?php
	}
	?>
	<li><a href="<?php echo TznCms::getUri('admin/member.php?id='.$objCms->user->id); ?>">Editer votre profil</a></li>
	<li><a href="logout.php">Vous d&eacute;connecer</a></li>
	</ul>
</p>
