<?php

class ModuleController extends TznController
{

	function ModuleController() {
		parent::TznController('module');
	}
	
	function main() {
			
		$key = TznUtils::sanitize(TZN_SANITIZE_SIMPLE,$_REQUEST['key']);
		if ($_REQUEST['key'] && !$key) {
			echo 'Module name "'.$key.'" is not valid'; exit;
		}
		
		switch($_REQUEST['mode']) {
		case 'options':
			/* === LOAD MODULE ======================================================== */

			$this->module = $GLOBALS['objCms']->getModuleObject($key);
			if (!$this->module) {
				echo 'Module "'.$key.'" not enabled'; exit;
			}
			
			$GLOBALS['objCms']->initSubmitting(1); // save only (actually, will close the window too)
			
			$this->module->installOptions();
			$this->setView('view_edit');
			return true; // popup
			break;
		case 'enable':
		case 'disable':
			$action = 'install'.ucfirst($_REQUEST['mode']);
			$this->module = $GLOBALS['objCms']->getModuleObject($key);
			$this->module->$action();
			TznUtils::redirect(CMS_WWW_URI.'admin/module.php', $GLOBALS['langTznCommon']['operation_successful']);
			break;
		}
		
		/* === LOAD STUFF ========================================================== */
		
		// set referring page
		TznCms::setAdminRef();
		
		$this->moduleList = new TznModuleList();
		$this->moduleList->loadList('uninstalled');
		foreach($this->moduleList->_data as $folder => $mix) {
			TznModule::includeLanguage($folder);
		}
		
		/* === HTML ================================================================ */
		
		$GLOBALS['objHeaders']->add('css',CMS_WWW_URI.'assets/css/squeezebox.css');
		
		$GLOBALS['objHeaders']->add('jsScript','squeezebox.js');
		
	}
	
}