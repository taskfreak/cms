<?php
// error_log('plugin mailing included');

class PluginMailingList extends TznPlugin 
{
	var $objMember;

	function PluginMailingList() {
		parent::TznPlugin('mailing_list', 'member', 'member_news_letter');
		// late plugin for user
		$GLOBALS['objUser']->initPlugin('member_news_letter', new MemberNewsLetter());
		// $this->data = new MemberNewsLetter(); -OLD VERSION-
	}
		
	function main($obj) {
		parent::main();
		$this->objMember = $obj;
		// ... set view ...
		$this->setView('admin_plugin');
	}
	
}

class MemberNewsletter extends TznDb {

	function MemberNewsletter() {
		parent::TznDb('memberNewsletter');
		$this->addProperties(array(
			'id'					=> 'UID',
			'subscribeDate'			=> 'DTM',
			'subscribeEnable'		=> 'BOL',
			'subscribeHtml'			=> 'BOL',
			'newsletterId'			=> 'NUM',
			'newsletterDate'		=> 'DTM'
		));
		$this->subscribeHtml = 1; // HTML format by default
	}
	
	function getStatus() {
		return $this->subscribeEnable;
	}
	
	function getFormat() {
	   if ($this->subscribeHtml) {
	       return 'HTML';
	   } else {
	       return 'Texte';
	   }
	}
	
	function initNewsletterTest($id=0) {
		$this->newsletterId = intval($id);
		$this->newsletterDate = '0000-00-00 00:00:00';
		return $this->update('newsletterId,newsletterDate');
	}
	
	function initNewsletterMass($id=0) {
		$this->getConnection();
		return $this->query('UPDATE '.$this->gTable()
			.' SET newsletterId='.$id
			.', newsletterDate=\'0000-00-00 00:00:00\''
			.' WHERE subscribeEnable=1');
	}
	
	function sentAlready($id=0) {
		if ((($id && $id == $this->newsletterId) || (!$id)) 
			&& ($this->newsletterDate && !preg_match('/^(9999|0000)/',$this->newsletterDate)))
		{
			return true;
		} else {
			return false;
		}
	}
	
	function setAuto($data) {
	   parent::setAuto($data);
	   // echo '<pre>';var_dump($data); exit;
	   if (is_array($data)) {
	   		$this->subscribeEnable = $data['subscribeEnable']?1:0;
	   }
	   // error_log('after setting, you are '.(($this->subscribeEnable)?'enabled':'disabled'));
    }
    
    function check() {
    	return true;
    }
	
	function add() {
	   $this->_setSubscribeDate();
	   return parent::add();
	}
	
	function replace() {
	   $this->_setSubscribeDate();
	   return parent::replace();
	}
	
	function update($fields='') {
	   $this->_setSubscribeDate();
	   if ($fields) {
	       $fields .= ',subscribeDate';
	   }
	   return parent::update($fields);
	}
	
	function _setSubscribeDate() {
	   if ($this->subscribeEnable) {
	       $this->setDtm('subscribeDate','NOW');
	   } else {
	       $this->subscribeDate = '0000-00-00 00:00:00';
	   }
	}

}