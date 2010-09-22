<?php

class EmailController extends TznController
{

	function EmailController() {
		parent::TznController('email');
	}
	
	function main() {
	
		include CMS_INCLUDE_PATH.'language/'.CMS_LANGUAGE.'/system.php';
		
		include CMS_CLASS_PATH.'pkg_com.php';
		
		$GLOBALS['objCms']->initSubmitting(1,2);
		
		if ($_REQUEST['id']) {
		
			// edit email alert
			$this->actionEdit();
			
		} else {
		
			// list emails and settings
			$this->actionList();
			
		}

	}
	
	function actionList() {
	
		// === OPTIONS EMAIL ====================================================
		
		$arrSmtp = array();
		if ($GLOBALS['objCms']->settings->get('email_smtp')) {
			$arrSmtp = explode('|',$GLOBALS['objCms']->settings->get('email_smtp'));
			// hide real pass
			$arrSmtp[2] = 'fakepass';
		}
		
		if ($GLOBALS['objCms']->submitMode) {
		
			$GLOBALS['objCms']->settings->set('email_prefix',Tzn::getHttp($_POST['email_prefix']));
			$GLOBALS['objCms']->settings->set('default_email',Tzn::getHttp($_POST['default_email']));
			if ($_POST['smtp_toggle']) {
				$arrSmtp[0] = Tzn::getHttp($_POST['smtp1']);
				$arrSmtp[1] = Tzn::getHttp($_POST['smtp2']);
				if ($_POST['smtp3'] != 'fakepass') {
					$arrSmtp[2] = Tzn::getHttp($_POST['smtp3']);
				}
				$str = implode('|',$arrSmtp);
				if (strlen($str) > 2) {
					$GLOBALS['objCms']->settings->set('email_smtp',$str);
				} else {
					$GLOBALS['objCms']->settings->set('email_smtp','');
				}
			} else {
				$GLOBALS['objCms']->settings->set('email_smtp','');
			}
			$GLOBALS['objCms']->settings->saveSettings('email_prefix,default_email,email_smtp');
			// hide real pass
			
			if ($GLOBALS['objCms']->submitMode == 1) {	
				TznUtils::redirect(TznUtils::getReferrer(false,true),$GLOBALS['langSystemStuff']['settings_saved']);
			} else {
				TznUtils::redirect(TznUtils::getReferrer(true,true),$GLOBALS['langSystemStuff']['settings_saved']);
			}
		}
		
		// === LIST EMAIL ALERTS ================================================
	
		$this->itemList = new EmailMessage();
		// order
		$this->itemList->addOrder('emailMessageId');
		// load (all or own only)
		$this->itemList->loadList();
		
		// set referring page
		TznCms::setAdminRef();
		
		$GLOBALS['objHeaders']->add('css',CMS_WWW_URI.'assets/css/squeezebox.css');

		$GLOBALS['objHeaders']->add('jsScript','squeezebox.js');
	}


	function actionEdit() {
	
		// check non direct access to page
		if (!defined('CMS_SECURITY')) {
			exit;
		}
		
		/* === LOAD EMAIL ========================================================== */
		
		$this->item = new EmailMessage();
		$this->item->setUid($_REQUEST['id']);
		
		if ($this->item->load()) {
		
		    if(isset($_REQUEST['mode'])) {
		
		        // --- ENABLE / DISABLE ---
		        $this->item->active = ($_REQUEST['mode'])?1:0;
		        $this->item->update('active');
		        
		        TznUtils::redirect(TznUtils::getReferrer(false),$GLOBALS['langTznCommon']['saved_success']);
		
		    } else if ($GLOBALS['objCms']->submitMode) {
		
		        // --- UPDATE ---
		        $this->item->setAuto($_POST);
		        if (!$_POST['active']) {
		            $this->item->active = 0;
		        }
		        $this->item->update();
		
				TznUtils::redirect(TznUtils::getReferrer(false),$GLOBALS['langTznCommon']['saved_success']);
		    }
    
		} else {
			
			// pas de bras, pas de chocolat
			TznUtils::redirect(TznUtils::getReferrer(false),$GLOBALS['langTznCommon']['data_not_found']);
		}
		
		$GLOBALS['objCms']->initSubmitting(1); // save only (actually, will close the window too)
		
		/* === HTML ================================================================ */
		
		$this->direction = ($this->item->direction)?$GLOBALS['langSystemEmailStuff']['to']:$GLOBALS['langSystemEmailStuff']['from'];
		
		$this->typeFunc = 'qTextArea';
		if ($this->item->html) {
			$this->typeFunc = 'qBbs';
		}
		
		$this->setView('view_edit');
	
	}
}