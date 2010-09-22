<?php

class ModuleHtml extends TznModule
{

	function ModuleHtml() {
		parent::TznModule('html');
	}
	
	/**
	* install/enable module
	function installEnable() {
		// enable as 1: basic, 2: autoload file, 3: instanciate automatically
		parent::installEnable(1);
	}
	**/
		
	/**
	* called when uninstalling
	function installDisable() {
		parent::installDisable();
	}
	*/
	
	/**
	* called when giving options
	function installOptions() {
	} 
	*/
	
	/**
	* default call when editing page with this module
	*/
	function adminDefault() {
		// decide submit modes
		$GLOBALS['objCms']->initSubmitting(1,2); // save and save and close
	
		// initialize object content
		$this->content = new CmsHtml();
		
		// load content corresponding to page
		$this->content->loadContent($GLOBALS['objPage']->id);
		
	}
		
	/**
	* called on public page (basically sending content)
	*/
	function publicDefault() {
		// initialize object content
		$this->content = new CmsHtml();
		
		// load content corresponding to page
		$this->content->loadContent($GLOBALS['objPage']->id);
		
	}
	
}

/**
* simple implementation of CmsObject to manage direct HTML content
*/
class CmsHtml extends CmsObject
{
	function CmsHtml() {
		parent::CmsObject();
	}
	
	/**
	* form to display
	*/
	function adminContent() {
		$this->qTextArea('body','','wxxl hxl');
		//$GLOBALS['objCms']->includeScript = 'include/content.php';
	}
}