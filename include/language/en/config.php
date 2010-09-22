<?php
/****************************************************************************\
* Tirzen CMS                                                                 *
******************************************************************************
* Version: 3.0                                                               *
* Authors: Stan Ozier <stan@tirzen.com>                                      *
* License:  http://www.gnu.org/licenses/gpl.txt (GPL)                        *
\****************************************************************************/

setLocale(LC_ALL,'en.UTF-8','en_US.UTF-8');
// setLocale(LC_ALL,'en_US.ISO8859-1, en_US.US-ASCII');

$GLOBALS['langGlobalPosition'] = array(
	0	=> 'visitor',
	1	=> 'guest',
	2	=> 'member',
	3	=> 'moderator',
	4	=> 'administrator'
);

$GLOBALS['langTeamPosition'] = array(
	0	=> 'guest',
	1	=> 'member',
	2	=> 'moderator',
	3	=> 'administrator'
);

$GLOBALS['langPrivateView'] = array(
	'public'	=> 'public content (seen by all visitors)',
	'protected'	=> 'protected content (seen by members only)',
	'private'	=> 'private content (seen by specific members only)'
);

$GLOBALS['langModuleTitle'] = array();