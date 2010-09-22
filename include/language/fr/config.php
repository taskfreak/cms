<?php
/****************************************************************************\
* Tirzen CMS                                                                 *
******************************************************************************
* Version: 3.1                                                               *
* Authors: Stan Ozier <stan@tirzen.com>                                      *
* License:  http://www.gnu.org/licenses/gpl.txt (GPL)                        *
\****************************************************************************/

setLocale(LC_ALL,'fr.UTF-8','fr_FR.UTF-8');
// setLocale(LC_ALL,'fr_FR.ISO8859-1','fr_FR');

$GLOBALS['langGlobalPosition'] = array(
	0	=> 'visiteur',
	1	=> 'invit&eacute;',
	2	=> 'membre',
	3	=> 'mod&eacute;rateur',
	4	=> 'administrateur'
);

$GLOBALS['langTeamPosition'] = array(
	0	=> 'invit&eacute;',
	1	=> 'membre',
	2	=> 'mod&eacute;rateur',
	3	=> 'responsable'
);

$GLOBALS['langPrivateView'] = array(
	'public'	=> 'contenu public (visible par tout visiteur)',
	'protected'	=> 'contenu prot&eacute;g&eacute; (visible par les membres uniquement)',
	'private'	=> 'contenu priv&eacute; (visible par les membres associ&eacute;s uniquement)'
);

$GLOBALS['langModuleTitle'] = array();