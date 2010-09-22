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
<p><b>Si vous n'&ecirc;tes pas encore membre</b> mais que vous souhaitez le devenir, <a href="logister.php">faites-en la demande ici</a>.</p>
<?php
}
if ($pErrorMessage) {
?>
<p><b>Si vous rencontrez des probl&egrave;mes pour vous connecter</b>, cela peut provenir des raisons suivantes :</p>
<ul>
  <li>Vous n'&ecirc;tes pas identifi&eacute; ou la session a expir&eacute;e</li>
  <li>Vous avez saisi un mot de passe erron&eacute;
  <?php
  if ($GLOBALS['objCms']->settings->get('password_reminder')) {
  ?>
  <br /><a href="logminder.php">Demandez un nouveau mot de passe par email</a></li>
  <?php
  }
  ?>
  <li>Votre navigateur n'interprete pas les fonctions javascript</li>
  <li>Votre navigateur n'accepte pas les cookies</li>
  <li>Vous n'avez pas l'autorisation d'acc&eacute;der &agrave; la page demand&eacute;e</li>
</ul>
<?php
} else if ($GLOBALS['objCms']->settings->get('password_reminder')) {
?>
<p><b>Si vous avez oubli&eacute; vos codes d'acc&egrave;s</b>, demandez <a href="logminder.php">un nouveau mot de passe par email</a>
<?php
}