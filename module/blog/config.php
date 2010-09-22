<?php

$GLOBALS['confModule']['blog'] = array(
	'autoload' 		=> 1,
	'comments'		=> 1,
	'allow_events'	=> true,
	'editor_full'	=> 'default',
	'defaults'		=> array(
		'layout'	=> '3_top_right',
		'publish'	=> 1,
		'private'	=> 0
	)
);

$GLOBALS['confModuleRights']['blog'] = array(
	0 => '00000'.'000000',
	1 => '00000'.'000000',
	2 => '00000'.'000000',
	3 => '11111'.'100000',
	4 => '11111'.'100000'
);

// 1: read unpublished
// 2: add new article
// 3: edit any article
// 4: delete any article
// 5: enable articles
// 6: edit all comments

$GLOBALS['confAdminMenu']['content']['blog'] = 'admin/special.php?module=blog';