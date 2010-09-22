<?php

class ModuleContent extends TznModule
{

	function ModuleContent() {
		parent::TznModule('content');
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
		$this->content = new CmsContent();
		
		// load content corresponding to page
		$this->content->loadContent($GLOBALS['objPage']->id);
	
		// set script for form
		$this->content->initAdmin('Default',1);
		
		$this->setView('admin_form');
		
	}
	
		
	/**
	* called on public page (basically sending content)
	*/
	function publicDefault() {
		// initialize object content
		$this->content = new CmsContent();
		
		// load content corresponding to page
		$this->content->loadContent($GLOBALS['objPage']->id);
		
		// initialize public
		$this->content->initPublic();
				
	}
	
}