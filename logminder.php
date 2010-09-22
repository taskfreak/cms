<?php

include '_include.php';

// initialize CMS
TznCms::init(0, false); //public page, do not autoload modules

$objController = TznController::factory('logminder');
$objController->main();
$objController->view();