<?php

class ModuleFacebook extends TznModule
{

	function ModuleFacebook() {
		parent::TznModule('facebook');
	}
	
	/**
	* install/enable module
	*/
	function installEnable() {
		// enable as 1: basic, 2: autoload file, 3: instanciate automatically
		parent::installEnable($GLOBALS['confModule']['facebook']['autoload']);
		$objDb = new TznDb('facebook');
		$objDb->query(
			'CREATE TABLE IF NOT EXISTS '.$objDb->gTable().' ('
			.'`facebookId` mediumint(8) unsigned NOT NULL, '
			.'`pos` smallint(5) unsigned NOT NULL, '
			.'`photo` varchar(255) NOT NULL, '
			.'`name` varchar(255) NOT NULL, '
			.'`email` varchar(255) NOT NULL, '
			.'`note` text NOT NULL, '
			.'PRIMARY KEY `facebookId` (`facebookId`)'
			.') ENGINE=MyISAM '
		);
	}
		
	/**
	* called when uninstalling
	*
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
		if ($GLOBALS['objPage']->id) {
			// load intro and options
			$this->content = new CmsContent();
			$this->content->loadContent($GLOBALS['objPage']->id);
			
			$this->content->initAdmin('Full',2);
			
			// only if on page
			$GLOBALS['objCms']->initSubmitting(1,2); // save and save and close
		}
	
		// initialize object content
		$this->data = new ContentFacebook();
		
		// load list corresponding to the page
		$this->data->loadFacebookList($GLOBALS['objPage']->id);
	
		// set script for form
		$this->baseLink = CMS_WWW_URI.'admin/page.php?id='.$GLOBALS['objPage']->id;
		
		$GLOBALS['objHeaders']->add('cssModule','facebook.admin');
		$this->setView('admin_page');
		
	}
	
	function adminEdit() {
	
		// decide submit modes
		$GLOBALS['objCms']->initSubmitting(1,2); // save and save and close
	
		// initialize object content
		$this->content = new ContentFacebook();
		
		// load item if editing
		if ($pItemId = intval($_REQUEST['item'])) {
			$this->content->loadByFilter('contentId='.$pItemId);
		}
		
		// initialize editor
		switch ($GLOBALS['confModule']['facebook']['editor_mode']) {
			case 2:
				$this->content->initAdmin('Full',2);
				break;
			case 1:
				$this->content->initAdmin('Default',1);
				break;
			default:
				$GLOBALS['confModule']['facebook']['editor_mode'] = 0;
				// simple text area
				break;
		}
		
		$this->setView('admin_item');
	}
	
	function adminDelete() {
		$this->content = new ContentFacebook();
		if ($this->content->loadByFilter('contentId='.intval($_REQUEST['item']))) {
			$this->content->delete();
		}
		TznUtils::redirect(TznUtils::getReferrer(true,true));
	}
	
	function adminDeletePage($pageId) {
		$objItemList = new ContentFacebook();
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
	
		$this->intro = new CmsContent();
		$this->intro->loadContent($GLOBALS['objPage']->id);
		
		$this->content = new ContentFacebook();
		
		// load content corresponding to page
		/*
		if ($pItemId = TznUtils::sanitize(TZN_SANITIZE_SIMPLE,$_REQUEST['item'])) {
		
			// load article
			$filter = 'pageId='.$GLOBALS['objPage']->id.' AND contentId='.$pItemId;
			$this->content->loadByFilter($filter);
			$this->content->initPublic();
			
			$this->setView('public_item');
			
		} else {
		*/
		
			// load list of entries
			$this->content->loadFacebookList($GLOBALS['objPage']->id);
				
			$this->setView('public_list');
			
		// }
		
		$GLOBALS['objHeaders']->add('cssModule','facebook');
		
	}
	
}

class Facebook extends TznDb
{

	function Facebook() {
		parent::TznDb('facebook');
		$this->addProperties(array(
			'id'			=> 'UID',
			'pos'			=> 'NUM',
			'photo'			=> 'IMG,'
				.'(w:'.$GLOBALS['confModule']['facebook']['img_wdh']
				.',h:'.$GLOBALS['confModule']['facebook']['img_hgt'].',f:gallery/),'
				.'(w:'.$GLOBALS['confModule']['facebook']['thb_wdh']
				.',h:'.$GLOBALS['confModule']['facebook']['thb_hgt'].',f:gallery/thumbs/)',
			'name'			=> 'STR',
			'email'			=> 'EML',
			'note'			=> 'TXT'
		));
	}
	
	function mailto() {
		if ($this->email) {
			return 'mailto:'.$this->email;
		} else {
			return "javascript:{}";
		}
	}
	
}

class ContentFacebook extends CmsContent
{

	function ContentFacebook() {
		parent::CmsContent('Facebook');
		$this->addOptions(array(
			'is_category'	=> 'BOL'
		));
		$this->handle = 'facebook';
	}
		
	function check() {
		return $this->_join->checkEmpty('name');
	}
	
	function loadFacebookList($pageId) {
		if (!$pageId) {
			return false;
		}
		$this->addWhere($this->gField('pageId').'='.$pageId);
		$this->addOrder('j1.pos ASC');
				
		return $this->loadList();
	}
	
}