<?php

class ModuleFormToData extends TznModule
{

	function ModuleFormToData() {
		parent::TznModule('form_to_data');
	}
	
	/**
	* install/enable module
	*/
	function installEnable() {
		// enable as 1: basic, 2: autoload file, 3: instanciate automatically
		parent::installEnable($GLOBALS['confModule']['form_to_data']['autoload']);
		$objDb = new TznDb('formData');
		$objDb->query(
			'CREATE TABLE IF NOT EXISTS '.$objDb->gTable().' ('
			.'`formDataId` mediumint(8) unsigned NOT NULL, '
			.'`postDate` datetime NOT NULL, '
			.'`name` varchar(255) NOT NULL, '
			.'`email` varchar(255) NOT NULL, '
			.'`memo` text NOT NULL, '
			.'`referrer` varchar(255) NOT NULL, '
			.'PRIMARY KEY `formDataId` (`formDataId`)'
			.') ENGINE=MyISAM '
		);
		// add email alerts
		$this->addEmailAlert(array(
			'form_to_data_admin' 	=> 1,
			'form_to_data_visitor'	=> 0
		));
	}
		
	/**
	* called when uninstalling
	*/
	function installDisable() {
		parent::installDisable();
		// remove email alerts
		$this->removeEmailAlert(array(
			'form_to_data_admin',
			'form_to_data_visitor'
		));
	}
	
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
	
		// load main content (intro) and settings
		$this->content = new ContentForm();
		$this->content->loadContent($GLOBALS['objPage']->id);
		
		$this->confirm = new CmsContent();
		$this->confirm->loadContent($GLOBALS['objPage']->id, 'confirm');
		$this->confirm->handle = 'confirm';
	
		// initialize list of forms
		$this->forms = new ContentFormData();
		
		// load list corresponding to the page
		if ($GLOBALS['objPage']->id) {
			$this->forms->addWhere('pageId='.$GLOBALS['objPage']->id);
		}
		$this->forms->addOrder('postDate DESC');
		$this->forms->setPagination(10, $_REQUEST['pg']);
		$this->forms->loadList();
		
		// load list of forms templates
		$this->templates = new FormTemplate();
		$this->templates->loadList();
		
	
		// set script for form
		$this->content->initAdmin('Full',2);
		$this->confirm->initAdmin('Full',2);
		
		$this->baseLink = CMS_WWW_URI.'admin/page.php?id='.$GLOBALS['objPage']->id;
		
		$this->setView('admin_page');
		
	}
	
	function adminDefaultAction() {

		// saving item or page settings?
		if (!intval($_REQUEST['item'])) { // no item
	
			// save confirm content
			$this->confirm->setHttp('body',$_POST['confirm_body']);
		
			// try to force page ID
			$this->confirm->pageId = intval($_POST['pageId']);
		
			// set page ID if empty
			if (!$this->confirm->pageId) {
				// called when editing page
				$this->confirm->pageId = $GLOBALS['objPage']->id;
			}
			
			// now do the DB work
			switch ($GLOBALS['objCms']->submitMode) {
				case 1: // save
				case 2:	// saveclose
				case 3: // saveadd
					$this->confirm->save();
					// error_log('-> confirm : saving !');
					break;
				case 4: // delete
					$this->confirm->delete();
					break;
			}
		}
		
		// save content (intro)
		parent::adminDefaultAction();
	}
	
	/**
	* default action from left menu (special)
	*/
	function adminSpecial() {
		$this->forms = new ContentFormData();
		if ($GLOBALS['objPage']->id) {
			$this->forms->addWhere('pageId='.$GLOBALS['objPage']->id);
		}
		$this->forms->addOrder('postDate DESC');
		$this->forms->setPagination(15, $_REQUEST['pg']);
		$this->forms->loadList();
		
		$this->baseLink = CMS_WWW_URI.'admin/special.php?module=form_to_data';
		
		$this->setView('admin_list');
	}
	
	function adminEdit() {
	
		// decide submit modes
		$GLOBALS['objCms']->initSubmitting(1,2); // save and save and close
	
		// initialize object content
		$this->content = new ContentFormData();
		$this->content->handle = 'form_data';
		
		// load item if editing
		if ($pItemId = intval($_REQUEST['item'])) {
			$this->content->loadByFilter('contentId='.$pItemId);
		}
	
		$this->setView('admin_item');
	}
	
	function adminDelete() {
		$this->content = new ContentFormData();
		if ($this->content->loadByFilter('contentId='.intval($_REQUEST['item']))) {
			$this->content->delete();
		}
		TznUtils::redirect(TznUtils::getReferrer(true,true));
	}
	
	function adminDeletePage($pageId) {
		$objItemList = new ContentFormData();
		if ($objItemList->loadList('pageId='.$pageId)) {
			while ($objItem = $objItemList->rNext()) {
				$objItem->delete();
			}
		}
	}
		
	/**
	* called on public page (basically sending content)
	*/
	function publicDefault() {
		
		if (isset($_REQUEST['ok'])) {
			// just show confirmation, that's it
			$this->content = new CmsContent();
			$this->content->loadContent($GLOBALS['objPage']->id, 'confirm');
			return true;	
		}
		
		// initialize object content
		$this->content = new ContentForm();
		
		$this->content->loadContent($GLOBALS['objPage']->id);
		
		// get form configuration
		// -TODO- check form exists
		include $this->content->getFormPath('config.php',true);
		
		// instanciate form
		$this->content->_form = new Form();
		
		if ($_POST['send']) {
		
			$objForm =& $this->content->_form;

			$data = $objForm->setCheck($_POST, $this->content->getRecipientArray());
			
			// error_log('DATA: '.$data);
			
			$pCaptcha = $GLOBALS['objPage']->checkCaptcha($this->content->getOption('captcha'));
			
			if (!$pCaptcha) {
				$objForm->e('CAPTCHA','Mauvais code syst&egrave;me anti-spam');
			}
			
			if ($data && $pCaptcha)	{
				$objItem = new ContentFormData();
				
				$objItem->_join->setHttp('name',$_POST['name']);
				$objItem->_join->setEml('email',$_POST['email']);
				$objItem->set('pageId',$GLOBALS['objPage->id']);
				$objItem->set('body',$data);
				
				if ($_REQUEST['debug']) {
				/*
					echo '<pre>';
					var_dump($_POST);
					echo '</pre><hr />';
					echo '<pre>';
					var_dump($objForm);
					echo '</pre><hr />';
				*/
					echo $data;
					exit;
				}
			
				if ($objItem->addForm(
					$this->content->getOption('ref_list'),
					$this->content->getOption('ref_default'),
					$_SESSION['sFormToDataReferrer'])
				) {
				
					// form successfully submitted, send email?
					$email = '';
					if ($idx = intval($_POST['recipient'])) {
						$email = $this->content->getRecipientEmail($idx);
					}
					
					$altEmail = $this->content->getOption($altEmail);
					
					if ($this->content->getOption('alert_admin')) {
						$objItem->sendEmailAdmin($email, 
							$this->content->getOption('alert_full')?$data:false, 
							$altEmail);
					}
					
					if ($this->content->getOption('alert_visitor')) {
						$objItem->sendEmailVisitor($objForm->name, $objForm->email, $altEmail);
					}
					
					// clean session (referrer)
					$_SESSION['sFormToDataReferrer'] = '';
					
					// redirect to thank you page
					TznUtils::redirect($GLOBALS['objPage']->getUrl().'?ok');
					
				} else {
					
					// can not add form to DB
					$objForm->_error = array_merge($objItem->_error,$objForm->_error);
				
				}
			
			} // end if data and captcha OK
			
			$GLOBALS['objHeaders']->add('jsScript','common.js');
			
		} // end if sending
		
		// prepare HTML (form)
		$GLOBALS['objHeaders']->add('css','form.css');
		if ($this->content->getFormPath('form.css', true)) {
			$GLOBALS['objHeaders']->add('css',$this->content->getFormPath('form.css', false));
		}
		
	}
	
}

class ContentForm extends CmsContent
{
	function ContentForm() {
		parent::CmsContent();
		$this->addOptions(array(
			'form'			=> 'STR',
			'captcha'		=> 'BOL',
			'alert_admin'	=> 'BOL',
			'alert_contacts'=> 'TXT',
			'alert_visitor'	=> 'BOL',
			'alert_email'	=> 'STR',
			'alert_full'	=> 'BOL',
			'two_cols'		=> 'BOL',
			'ref_list'		=> 'TXT',
			'ref_default'	=> 'STR'
		));
	}
	
	function getFormPath($file,$real=false) {
		return (($real)?CMS_WWW_PATH:CMS_WWW_URI)
			.'template/_forms/'.$this->getOption('form')
			.'/'.$file;
	}
	
	function _parseContacts() {
		$str = trim($this->getOption('alert_contacts'));
		if (empty($str)) {
			return false;
		}
		$arrContacts = array();
		$arrList = explode("\n", $str);
		if (count($arrList)) {
			foreach($arrList as $strContact) {
				$arr = explode('|',$strContact);
				$con = trim($arr[0]);
				if ($tac = TznUtils::sanitize('EML',$arr[1])) {
					$arrContacts[$con] = $tac;
				}
			}
			return count($arrContacts)?$arrContacts:false;
		}
		return false;
	}
	
	function getRecipientArray() {
		return $this->_parseContacts();
	}
	
	function getRecipientEmail($idx) {
		if ($arrContacts = $this->_parseContacts()) {
			$i = 1;
			foreach ($arrContacts as $label => $email) {
				if ($i == $idx) {
					return trim($email);
				}
				$i++;
			}
		}
		return false;
	}
	
	function qRecipient($nochoice='', $style='', $xtra='') {
		if ($arrContacts = $this->_parseContacts()) {
			$idx = intval($_POST['recipient']);
			echo '<select id="q_recipient" name="recipient" ';
			echo Tzn::_style($style);
			if ($xtra) {
				echo $xtra.' ';
			}
			echo '>';
			if ($nochoice) {
				echo '<option value="">'.$nochoice.'</option>';
			}
			$i = 1;
			foreach ($arrContacts as $label => $email) {
				echo '<option value="'.$i.'"';
				if ($i == $idx) {
					echo ' selected="selected"';
				}
				echo '>'.$label.'</option>';
				$i++;
			}
			echo '</select>';
			if (!$idx && isset($_POST['send'])) {
				echo '<span class="tznError">Selectionnez un destinataire</span>';
			}
		}
	}
	
	function printContent() {
	
		$objPage =& $GLOBALS['objPage'];
		$objForm =& $this->_form;
	
		parent::printContent();

		if ($this->getOption('form')) {
			include $this->getFormPath('form.php',true);
		} else {
			echo '<p class="empty">- formulaire non d&eacute;fini -</p>';
		}
		
	}
}

class FormData extends TznDb
{
	function FormData()
	{
		parent::TznDb('formData');
		$this->addProperties(array(
			'id'	 			=> 'UID',
			'postDate'			=> 'DTM',
			'name'				=> 'STR',
			'email'				=> 'EML',
			'memo'				=> 'BBS',
            'referrer'          => 'STR'
		));
	}    

}

class ContentFormData extends CmsContent
{

	function ContentFormData() {
		parent::CmsContent('FormData');
		$this->handle = 'data';
	}

	function getTitle() {
		$str = $this->_join->get('name');
		if ($this->_join->email) {
			$str = '<a href="mailto:'.$this->_join->email.'">'.$str.'</a>';
		}
		return $str;
	}
	
	function getSummary($cut=210) {
		$str = $this->_join->referrer;
		if ($this->_join->memo) {
			$str .= ': '.$this->_join->getStr('memo',$cut);
		}
		return $str;
	}
	
	function printFormAdmin() {
		$str = $this->body;
		$str = preg_replace('/(<\/?dl>)/','', $str);
		$str = preg_replace('/(<dt>)/','<li><label>',$str);
		$str = preg_replace('/(<\/dt>)/','</label>',$str);
		$str = preg_replace('/(<dd>)/','<span>',$str);
		$str = preg_replace('/(<\/dd>)/','</span></li>',$str);
		echo $str;
	}
	
	function addForm($opt_list, $opt_default, $referrer=null) {
    
    	//error_log("submitting FORM $referrer (default: $opt_default)");
    
    	$this->_join->postDate = TZN_SQL_NOW;

		$referrer = trim($referrer);

		if ($referrer && $opt_list) {
			// check if referrer is in list
			$arr = explode("\n",$opt_list);
			$ok = false;
			foreach($arr as $value) {
				if (trim($value) == $referrer) {
					// found in list of autorized referrers
					$ok = true;
					break;
				}
			}
			if (!$ok) {
				// not found
				$this->_join->e('referrer','Origine de la demande non reconnue');
				return false;
			}
		} else {
			// no control over referrer
			
		}
		if (!$referrer) {
			if ($opt_default) {
				$referrer = $opt_default;
			} else if ($opt_list) {
				$this->_join->e('referrer','Origine de la demande non pr&eacute;cis&eacute;e');
				return false;
			} else {
				// really, no control whatsoever
				$referrer = 'direct';
			}
		}
		
		$this->_join->referrer = $referrer;
    	
    	$this->pageId = $GLOBALS['objPage']->id;
    	return $this->add();
    }
    
    function sendEmailAdmin($email='', $full=false, $altmail='') {
    	
    	include_once(CMS_CLASS_PATH.'pkg_com.php');
    	
    	$objMessage = new EmailMessage();
		$objMessage->loadByKey('description','form_to_data_admin'.($altmail?('_'.$altmail):''));
    	
    	$body = "Formulaire:\t"
    		.substr(CMS_WWW_URL,0,-1).$GLOBALS['objPage']->getUrl()."\n"
			."Origine:\t".$this->_join->referrer."\n\n";

		if ($full) {
			$arrSearch = array('<dl>','</dl>','<dd>','</dd>','<dt>','</dt>');
			$arrReplace = array('','',"\t",'','','',':');
			$full = strip_tags(str_replace($arrSearch,$arrReplace,$full));
			$body .= $full;
		} else {
			$body .= "Pour voir le contenu du formulaire envoyé:\n"
			.CMS_WWW_URL."admin/page.php?id=".$GLOBALS['objPage']->id
			.'&action=edit&item='.$this->id."\n\n";
		}
    	
    	if ($email) {
    		$objMessage->recipientAddress = $email;
    	}
    	
    	$from = $GLOBALS['objCms']->settings->get('default_email');
    	if (!$from) 
    	{
    		$objMessage->e('send','no email specified');
    		return false;
    	}
    	
		if (!$objMessage->send($body, $from)) 
		{
			if (@constant('TZN_EMAIL_DEBUG')) {
				error_log('-> error sending email');
				foreach($objMessage->_error as $key => $value) {
					error_log('  -> '.$key.': '.$value);
				}
			}
			return false;
		}
		return true;
    }
    
    function sendEmailVisitor($name, $email, $altmail='') {
    	
    	include_once(CMS_CLASS_PATH.'pkg_com.php');
    	
    	$objMessage = new EmailMessage();
		$objMessage->loadByKey('description','form_to_data_visitor'.($altmail?('_'.$altmail):''));
    	
    	if (!$email) {
    		$objMessage->e('send','no email specified');
    		return false;
    	}
		if (!$objMessage->send($name, $email) && @constant('TZN_EMAIL_DEBUG')) {
			error_log('-> error sending email');
			foreach($objMessage->_error as $key => $value) {
				error_log('  -> '.$key.': '.$value);
			}
		}
    }
}

class FormGeneric extends Tzn
{
	
	function FormGeneric() {
		parent::Tzn();
		$this->addProperties('name','STR');
	}
	
	function setCheck($data, $arrContacts=false) {
		$allOk = true;
		foreach ($this->_properties as $key => $value) {
			if ($key == 'name' && !empty($data[$key])) {
				$data[$key] = ucwords(strtolower($data[$key]));
			}
			$this->setHttp($key, $data[$key]);
			if (method_exists($this,'check')) {
				if (!$this->check($key)) {
					$allOk = false;
				}
			}
			//error_log('setting ['.$key.'] as "'.$data[$key].'"');
			//error_log(' -> '.$this->$key);
    	}
    	
    	if (is_array($arrContacts)) {
    		// we have a list of recipients, check one is selected
    		$idx = intval($_POST['recipient']);
    		if ($idx <= 0 || $idx > count($arrContacts)) {
    			$this->e('recipient', 's&eacute;l&eacute;ctionnez un destinataire');
    			$allOk = false;
    		}
    	}
    	
    	// echo '<pre>';var_dump($this); exit;
    	if (!$this->checkEmpty('name') || !$allOk) {
    		// some compulsory field (including name) is not valid
    		return false;
    	}
    	$str = "<dl>\n";
    	foreach ($this->_properties as $key => $value) {
    		if (!empty($this->$key)) {
	    		$str .= "\t<dt>".str_replace('_',' ',$key)."</dt>\n";
	    		if (is_array($this->$key)) {
	    			$str .= "\t<dd>";
	    			$str .= implode("</dd>\n\t<dd>",$this->$key);
	    			$str .= "</dd>\n";
	    		} else {
	    			$str .= "\t<dd>".$this->f($key)."</dd>\n";
	    		}
	    	}
    	}
    	return $str."</dl>\n";
    }
    
}

class FormTemplate extends TznCollection
{

	function FormTemplate() {
		parent::TznCollection(null);
		$this->loadList();
	}
	/**
	* Load Module list in Admin section
	*/
	function loadList() {

		$this->_data = array();

		if ($handle = opendir(CMS_WWW_PATH.'template/_forms/')) {

			while (false !== ($file = readdir($handle))) {
				if (is_dir(CMS_WWW_PATH.'template/_forms/'.$file) && $file != '.' && $file != '..' && $file != 'CVS') {
					$this->_data[$file] = str_replace('_',' ',$file);
				}
			}
			
			ksort($this->_data);

		   closedir($handle);
		}

	}
}

class FormOrderList extends TznCollection
{
    function FormOrderList() {
		parent::TznCollection($GLOBALS['langFormToDataOrderList']);
    }
}
