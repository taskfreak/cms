<?php
$objModule = TznCms::newModuleObject('mailing_list');

// init
$objContentList = new NewsletterPage();


if ($objContentList->loadList()) {

	include dirname(__FILE__).'/admin_home_list.php';

}
