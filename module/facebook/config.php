<?php

$GLOBALS['confModule']['facebook'] = array(
	'autoload' 		=> 1,
	'editor_mode'	=> 0, // 0: textarea, 1: tzn editor, 2: ck editor
	'img_wdh'		=> 130,
	'img_hgt'		=> 170,
	'thb_wdh'		=> 40,
	'thb_hgt'		=> 60,
	'page_per_contact'	=> false
);

$GLOBALS['confModuleRights']['facebook'] = array(
	0 => '000000000000',
	1 => '000000000000',
	2 => '000000000000',
	3 => '111110000000',
	4 => '111111011111',
	5 => '111111111111'
);

// 1: see unpublished
// 6: edit all comments

// $GLOBALS['confAdminMenu']['content']['content_contact'] = 'admin/special.php?module=content_contact';