<?php
/* Coding or SQL error page */

$GLOBALS['objHeaders']->add('css','common.css');
$GLOBALS['objHeaders']->add('css','admin.css');

$pPageTitle = 'CMS Error';

TznCms::getHeader();

?>
<div id="centric" class="full">
	<h1><a href="./" title="retour &agrave; la page d'accueil"><img src="<?php echo $GLOBALS['objCms']->getLogo(); ?>" alt="tzn cms" /></a></h1>
	<h2>Following error occured :</h2>
	<p><?php
		echo TznUtils::getMessages($isError, true);
	?></p>
</div>
<?php

TznCms::getFooter();