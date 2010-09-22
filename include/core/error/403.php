<?php
/* 403 page */

header("HTTP/1.0 403 Forbidden");

$GLOBALS['objHeaders']->add('css','common.css');
$GLOBALS['objHeaders']->add('css','admin.css');

$pPageTitle = 'Access denied';

TznCms::getHeader();

?>
<div id="centric" class="full">
	<h1><a href="./" title="retour &agrave; la page d'accueil"><img src="<?php echo $GLOBALS['objCms']->getLogo(); ?>" alt="tzn cms" /></a></h1>
	<h2>403 : Forbidden</h2>
	<p>You can not access the page you have requested... Sorry!</p>
	<h2>403 : Acc&egrave;s interdit</h2>
	<p>Vous n'avez pas acc&egrave;s &agrave; la page demand&eacute;e... D&eacute;sol&eacute;!</p>
</div>
<?php

TznCms::getFooter();