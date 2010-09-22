<?php

include '_include.php';

// initialize CMS
TznCms::init(1, false); //member page, do not autoload modules

$_REQUEST['id'] = $GLOBALS['objUser']->id;

$objController = TznController::factory('member');
$objController->main();

$GLOBALS['objHeaders']->add('css','common.css');
$GLOBALS['objHeaders']->add('css','admin.css');
$GLOBALS['objHeaders']->add('css','form.css');

$GLOBALS['objHeaders']->add('jsScript',array('mootools-1.2.4.js','common.js', 'admin.js'));

$objController->view();