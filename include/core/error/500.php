<?php
/* 500 page */

// header("HTTP/1.0 500 Server Error");

$GLOBALS['objHeaders']->add('css','common.css');
$GLOBALS['objHeaders']->add('css','admin.css');

$pPageTitle = 'Page not found';

TznCms::getHeader();

?>
<div id="centric" class="full">
	<h1><a href="./" title="retour &agrave; la page d'accueil"><img src="<?php echo $GLOBALS['objCms']->getLogo(); ?>" alt="tzn cms" /></a></h1>
	<h2>500 : Server error</h2>
	<p><?php
		if (TznUtils::hasMessage()) {
			echo TznUtils::getMessages($isError, true);
		} else {
			echo 'Ooops, something unexpected happened. Please try again.';
		}
	?></p>
	<h2>404 : Erreur serveur</h2>
	<p>Zut, quelque chose d'inattendu est survenue. Essaye encore.</p>
</div>
<?php

TznCms::getFooter();