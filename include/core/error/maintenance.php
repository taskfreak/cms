<?php
/* maintenance page */

$GLOBALS['objHeaders']->add('css','common.css');
$GLOBALS['objHeaders']->add('css','admin.css');

$pPageTitle = 'Under maintenance';

TznCms::getHeader();

?>
<div id="centric" class="full">
	<h1><a href="./" title="retour &agrave; la page d'accueil"><img src="<?php echo $GLOBALS['objCms']->getLogo(); ?>" alt="tzn cms" /></a></h1>
	<h2>Under maintenance</h2>
	<p>This site is currently being updated. Please check again in a few moments.</p>
	<h2>Maintenance en cours</h2>
	<p>Le site est en cours de mise &agrave; jour. Ca ne sera pas long, revenez vite.</p>
</div>
<?php

TznCms::getFooter();