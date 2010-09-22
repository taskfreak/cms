<?php

class SettingController extends TznController
{

	function SettingController() {
		parent::TznController('setting');
	}
	
	function main() {

		include CMS_INCLUDE_PATH.'language/'.CMS_LANGUAGE.'/system.php';
		
		// set referring page
		TznCms::setAdminRef();
		
		/* === ADMIN STUFF ========================================================= */
		
		$GLOBALS['objCms']->initSubmitting(1,2);
		
		if ($GLOBALS['objCms']->submitMode) {
		
			$GLOBALS['objCms']->settings->postSettings();
		
			if ($GLOBALS['objCms']->submitMode == 1) {	
				TznUtils::redirect(TznUtils::getReferrer(false,true),$GLOBALS['langSystemStuff']['settings_saved']);
			} else {
				TznUtils::redirect(TznUtils::getReferrer(true,true),$GLOBALS['langSystemStuff']['settings_saved']);
			}
		
		}
		
		$this->languageList = new CmsLanguageList();
		
	}
	
}