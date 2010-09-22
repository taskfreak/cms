<?php

/* Pages management (site map) */

define('CMS_ADMIN', true); // yes, we are in admin section
// this will include required javascripts such as mootools core

include '../_include.php';

// initialize CMS
TznCms::init(5, true); // admin page, auto load modules

$objController = TznController::factory('page');
$objController->main();


/* === HTML ================================================================ */

$objController->view();
