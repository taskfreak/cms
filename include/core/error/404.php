<?php
/* 404 page */

header("HTTP/1.0 404 Not Found");

$GLOBALS['objHeaders']->add('css','common.css');
$GLOBALS['objHeaders']->add('css','admin.css');

$pPageTitle = 'Page not found';

TznCms::getHeader();

?>
<div id="centric" class="full">
	<h1><a href="./" title="retour &agrave; la page d'accueil"><img src="<?php echo $GLOBALS['objCms']->getLogo(); ?>" alt="tzn cms" /></a></h1>
	<h2>404 : Page not found</h2>
	<p>Page you have requested can not be found... Sorry!</p>
	<h2>404 : Page introuvable</h2>
	<p>La page recherch&eacute;e est introuvable... D&eacute;sol&eacute;!</p>
</div>
<?php

TznCms::getFooter();