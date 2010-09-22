<?php

$GLOBALS['confModule']['mailing_list'] = array(
	'autoload' 		=> 2,
	'comments' 		=> 0,
	'fck_editor'	=> false
);

// $GLOBALS['confPlugin']['member'][] = 'mailing_list';

$GLOBALS['confModuleRights']['mailing_list'] = array(
	0 => '000000000000',
	1 => '000000000000',
	2 => '000000000000',
	3 => '111110000000',
	4 => '111111011111',
	5 => '111111111111'
);

// 1:edit newsletter, 2:test newsletter, 3:send newsletter to the masses

$GLOBALS['confAdminMenu']['content']['mailing_list'] = 'admin/special.php?module=mailing_list';

