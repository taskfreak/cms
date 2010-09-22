<?php

/* config */

$GLOBALS['langModule']['mailing_list'] = array(
	'name'			=> 'Newsletter',
	'description'	=> 'Lettre d\'information par email et gestion de la liste de diffusion',
	'plugin'		=> 'Newsletter'
);

$GLOBALS['langAdminMenuItem']['mailing_list'] = 'Liste de diffusion';

/* email alerts */

$GLOBALS['langEmailAlert']['mailing_list_subscribed'] = array(
	'subject'		=> 'Confirmation d\'inscription à la liste de diffusion',
	'description'	=> 'Newsletter: confirmation d\'inscription'
);

$GLOBALS['langEmailAlert']['mailing_list_unsubscribed'] = array(
	'subject'		=> 'Suppression de la liste de diffusion',
	'description'	=> 'Newsletter: confirmation de suppression de la liste'
);

$GLOBALS['langEmailAlert']['mailing_list_send'] = array(
	'subject'		=> 'Lettre d\'information',
	'description'	=> 'Newsletter: envoi de la lettre d\'information'
);

$GLOBALS['langPlugin']['mailing_list']= array(
	'name'	=> 'Newsletter'
);

$pSiteTitle = $GLOBALS['objCms']->settings->get('site_title');

$GLOBALS['langNewsLetterCNIL'] = <<<EOS
Les informations recueillies font l'objet d'un traitement informatique destiné à la gestion de la liste de diffusion $pSiteTitle. Conformément à la loi "informatique et libertés" du 6 janvier 1978, vous bénéficiez d'un droit d'accès et de rectification aux informations qui vous concernent. Si vous souhaitez exercer ce droit et obtenir communication des informations vous concernant, veuillez nous contacter.
EOS;

unset($pSiteTitle);