<?php

class ModuleHighlight extends TznModule
{

	function ModuleHighlight() {
		parent::TznModule('highlight');
	}
	
	/**
	* install/enable module
	*/
	function installEnable() {
		// enable as 1: basic, 2: autoload file, 3: instanciate automatically
		parent::installEnable($GLOBALS['confModule']['highlight']['autoload']);
		$objDb = new TznDb('highlight');
		$objDb->query(
			'CREATE TABLE IF NOT EXISTS '.$objDb->gTable('highlight').' ('
			.'`highlightId` mediumint(8) unsigned NOT NULL, '
			.'`postDate` datetime NOT NULL, '
			.'`rankpos` smallint(8) unsigned NOT NULL, '
			.'`title` varchar(255) NOT NULL, '
			.'`linkurl` varchar(255) NOT NULL, '
			.'`imgfile` varchar(155) NOT NULL, '
			.'PRIMARY KEY `highlightId` (`highlightId`), '
			.'INDEX `rankpos` (`rankpos`) '
			.') ENGINE=MyISAM'
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
	
		// load intro and options
		$this->content = new ContentHighlightIntro();
		$this->content->loadContent($GLOBALS['objPage']->id);
		
		// initialize CK editor
		$this->content->initAdmin('Full',2);
		
		// add JS to sort pictures
		$GLOBALS['objHeaders']->add('jsOnLoad',"mySortables = new Sortables('piclist');");
		
		// only if on page
		$GLOBALS['objCms']->initSubmitting(1,2); // save and save and close
	
		// initialize object content
		$this->data = new ContentHighlight();
		
		// load list corresponding to the page
		if ($GLOBALS['objPage']->id) {
			$this->data->addWhere('pageId='.$GLOBALS['objPage']->id);
		}
		$this->data->addOrder('rankpos ASC, postDate DESC');
		$this->data->loadList();
		
		// set script for form
		$this->baseLink = CMS_WWW_URI.'admin/page.php?id='.$GLOBALS['objPage']->id;

		$GLOBALS['objHeaders']->add('cssModule','highlight.admin');

		$this->setView('admin_page');
		
	}
	
	/**
	* change default saving action on page (add categories)
	*/
	function adminDefaultAction() {
	
		$n = 1;
		
		if ($_POST['pic']) {
			foreach($_POST['pic'] as $id) {
				// update / reorder pictures
				$objItem = new Highlight();
				$objItem->setUid($id);
				$objItem->set('rankpos', $n);
				$objItem->update('rankpos');
				$n++;
			}
			
			if ($GLOBALS['objCms']->submitMode == 1) {
				// reload list if not closing
				$this->data->loadList();
			}
		}		
		parent::adminDefaultAction();
	}
	
	/**
	* default action from left menu (special)
	*/
	function adminSpecial() {
		$this->adminDefault();
		$this->baseLink = CMS_WWW_URI.'admin/special.php?module=highlight';
		$this->setView('admin_list');
	}
	
	function adminEdit() {
	
		// decide submit modes
		$GLOBALS['objCms']->initSubmitting(1,2); // save and save and close
	
		// initialize object content
		$this->content = new ContentHighlight();
		
		// load item if editing
		if ($pItemId = intval($_REQUEST['item'])) {
			$this->content->loadByFilter('contentId='.$pItemId);
		}
		
		// load list of pages
		if (!$GLOBALS['objPage']->id) {
			$this->objPageList = new TznPage();
			$this->objPageList->addWhere("module = 'highlight'");
			$this->objPageList->addOrder('position');
			$this->objPageList->loadList();
		}
		
		// initialize FCK editor
		if ($GLOBALS['confModule']['highlight']['fck_editor']) {
			include CMS_WWW_PATH.'assets/fckeditor/fckeditor.php';
		}
	
		// set script for forM		
		$GLOBALS['objHeaders']->add('jsCalendar','cms_date');
		$this->setView('admin_item');
	}
	
	function adminDelete() {
		$this->content = new ContentHighlight();
		if ($this->content->loadByFilter('contentId='.intval($_REQUEST['item']))) {
			$this->content->delete();
		}
		TznUtils::redirect(TznUtils::getReferrer(true,true));
	}
	
	function adminDeletePage($pageId) {
		$objItemList = new ContentHighlight();
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
	
		$this->content = new CmsContent();
		$this->content->loadContent($GLOBALS['objPage']->id);
		
		$this->data = new ContentHighlight();
		$this->data->addOrder('rankpos ASC, postDate DESC');
		// -TODO- paging
		if ($psize = $this->content->getOption('page_size')) {
			$this->data->setPagination($psize, intval($_REQUEST['pg']));
		}
		$this->data->loadList();
		
		switch ($this->content->getOption('mode')) {
			case 2:
				$GLOBALS['objHeaders']->add('cssModule','highlight.horizontal');
				$GLOBALS['objHeaders']->add('jsScript',CMS_WWW_URI.'module/highlight/js/highlight.horizontal.js');
				$GLOBALS['objHeaders']->add('jsScriptCode','var cur = 1; var maxi = '
					.$this->data->rCount().';');
				$this->setView('highlight');
				break;
			case 1:
				$GLOBALS['objHeaders']->add('cssModule','highlight.full');
				$this->setView('public_full');
				break;
			default:
				$GLOBALS['objHeaders']->add('cssModule','highlight.thumbs');
				$GLOBALS['objHeaders']->add('cssModule','highlight.milkbox');
				$this->setView('public_thumbs');
				break;
		}
		
	}
	
}

class Highlight extends TznDb
{
	
	function Highlight() {
		parent::TznDb('highlight');
		$this->addProperties(array(
			'id'		=> 'UID',
			'postDate'	=> 'DTE',
			'rankpos'	=> 'NUM',
			'title'		=> 'STR',
			'linkurl'	=> 'URL',
			'imgfile'	=> 'IMG,'
				.'(w:'.$GLOBALS['confModule']['highlight']['img_wdh']
				.',h:'.$GLOBALS['confModule']['highlight']['img_hgt'].',f:gallery/)'
		));
	}
		
}

class ContentHighlight extends CmsContent
{

	function ContentHighlight() {
		parent::CmsContent('highlight');
		$this->handle = 'highlight';
	}
	
	function getTitle() {
		return $this->_join->get('title');
	}
	
	function getDescription() {
		$str = $this->getTitle();
		if (trim($this->body)) {
			$str = $this->get('body');
		}
		return str_replace('"','&quot;', $str);
	}
	
	function check() {
		return $this->_join->checkEmpty('title');
	}
	
}

class ContentHighlightIntro extends CmsContent
{
	
	function ContentHighlightIntro() {
		parent::CmsContent();
		$this->handle = '';
		$this->addOptions(array(
			'mode'		=> 'NUM',
			'page_size'	=> 'NUM'
		));
	}
	
}