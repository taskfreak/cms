<?php

class ModulePictureGallery extends TznModule
{

	function ModulePictureGallery() {
		parent::TznModule('picture_gallery');
	}
	
	/**
	* install/enable module
	*/
	function installEnable() {
		// enable as 1: basic, 2: autoload file, 3: instanciate automatically
		parent::installEnable($GLOBALS['confModule']['picture_gallery']['autoload']);
		$objDb = new TznDb('pictureGallery');
		$objDb->query(
			'CREATE TABLE IF NOT EXISTS '.$objDb->gTable('pictureGallery').' ('
			.'`pictureGalleryId` mediumint(8) unsigned NOT NULL, '
			.'`postDate` datetime NOT NULL, '
			.'`rankpos` smallint(8) unsigned NOT NULL, '
			.'`title` varchar(255) NOT NULL, '
			.'`imgfile` varchar(155) NOT NULL, '
			.'PRIMARY KEY `pictureGalleryId` (`pictureGalleryId`), '
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
		$this->content = new ContentPictureGalleryIntro();
		$this->content->loadContent($GLOBALS['objPage']->id);
		
		// initialize CK editor
		$this->content->initAdmin('Full',2);
		
		// add JS to sort pictures
		$GLOBALS['objHeaders']->add('jsOnLoad',"mySortables = new Sortables('piclist');");
		
		// only if on page
		$GLOBALS['objCms']->initSubmitting(1,2); // save and save and close
	
		// initialize object content
		$this->data = new ContentPictureGallery();
		
		// load list corresponding to the page
		if ($GLOBALS['objPage']->id) {
			$this->data->addWhere('pageId='.$GLOBALS['objPage']->id);
		}
		$this->data->addOrder('rankpos ASC, postDate DESC');
		$this->data->loadList();
		
		// set script for form
		$this->baseLink = CMS_WWW_URI.'admin/page.php?id='.$GLOBALS['objPage']->id;

		$GLOBALS['objHeaders']->add('cssModule','picture_gallery.admin');

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
				$objItem = new PictureGallery();
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
		$this->baseLink = CMS_WWW_URI.'admin/special.php?module=picture_gallery';
		$this->setView('admin_list');
	}
	
	function adminEdit() {
	
		// decide submit modes
		$GLOBALS['objCms']->initSubmitting(1,2); // save and save and close
	
		// load page settings
		$this->content = new ContentPictureGalleryIntro();
		$this->content->loadContent($GLOBALS['objPage']->id);
	
		// initialize object content
		$this->content = new ContentPictureGallery();
		
		// load item if editing
		if ($pItemId = intval($_REQUEST['item'])) {
			$this->content->loadByFilter('contentId='.$pItemId);
		}
		
		// load list of pages
		if (!$GLOBALS['objPage']->id) {
			$this->objPageList = new TznPage();
			$this->objPageList->addWhere("module = 'picture_gallery'");
			$this->objPageList->addOrder('position');
			$this->objPageList->loadList();
		}
		
		// initialize FCK editor
		if ($GLOBALS['confModule']['picture_gallery']['fck_editor']) {
			include CMS_WWW_PATH.'assets/fckeditor/fckeditor.php';
		}
	
		// set script for forM		
		$GLOBALS['objHeaders']->add('jsCalendar','cms_date');
		$this->setView('admin_item');
	}
	
	function adminDelete() {
		$this->content = new ContentPictureGallery();
		if ($this->content->loadByFilter('contentId='.intval($_REQUEST['item']))) {
			$this->content->delete();
		}
		TznUtils::redirect(TznUtils::getReferrer(true,true));
	}
	
	function adminDeletePage($pageId) {
		$objItemList = new ContentPictureGallery();
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
	
		$this->content = new ContentPictureGalleryIntro();
		$this->content->loadContent($GLOBALS['objPage']->id);
		
		$this->data = new ContentPictureGallery();
		$this->data->addWhere('pageId='.$GLOBALS['objPage']->id);
		$this->data->addOrder('rankpos ASC, postDate DESC');
		// -TODO- paging
		if ($psize = $this->content->getOption('page_size')) {
			$this->data->setPagination($psize, intval($_REQUEST['pg']));
		}
		$this->data->loadList();
		
		switch ($this->content->getOption('mode')) {
			case 2:
				$GLOBALS['objHeaders']->add('cssModule','picture_gallery.horizontal');
				$GLOBALS['objHeaders']->add('jsScript',CMS_WWW_URI.'module/picture_gallery/js/picture_gallery.horizontal.js');
				$GLOBALS['objHeaders']->add('jsScriptCode','var cur = 1; var maxi = '
					.$this->data->rCount().';');
				$this->setView('public_horizontal');
				break;
			case 1:
				$GLOBALS['objHeaders']->add('cssModule','picture_gallery.full');
				$this->setView('public_full');
				break;
			default:
				$GLOBALS['objHeaders']->add('cssModule','picture_gallery.thumbs');
				$GLOBALS['objHeaders']->add('cssModule','picture_gallery.milkbox');
				$GLOBALS['objHeaders']->add('jsScript',CMS_WWW_URI.'module/picture_gallery/js/milkbox.js');
				$this->setView('public_thumbs');
				break;
		}
		
	}
	
}

class PictureGallery extends TznDb
{
	
	function PictureGallery() {
		parent::TznDb('pictureGallery');
		$this->addProperties(array(
			'id'		=> 'UID',
			'postDate'	=> 'DTE',
			'rankpos'	=> 'NUM',
			'title'		=> 'STR',
			'imgfile'	=> 'IMG,'
				.'(w:'.$GLOBALS['confModule']['picture_gallery']['img_wdh']
				.',h:'.$GLOBALS['confModule']['picture_gallery']['img_hgt'].',f:gallery/),'
				.'(w:'.$GLOBALS['confModule']['picture_gallery']['thb_wdh']
				.',h:'.$GLOBALS['confModule']['picture_gallery']['thb_hgt'].',f:gallery/thumbs/)',
		));
	}
		
}

class ContentPictureGallery extends CmsContent
{

	function ContentPictureGallery() {
		parent::CmsContent('PictureGallery');
		$this->handle = 'picturegallery';
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

class ContentPictureGalleryIntro extends CmsContent
{
	
	function ContentPictureGalleryIntro() {
		parent::CmsContent();
		$this->handle = '';
		$this->addOptions(array(
			'mode'		=> 'NUM',
			'page_size'	=> 'NUM',
			'img_wdh'	=> 'NUM',
			'img_hgt'	=> 'NUM',
			'thb_wdh'	=> 'NUM',
			'thb_hgt'	=> 'NUM'
		));
		// set defaults
		$this->setOption('img_wdh',$GLOBALS['confModule']['picture_gallery']['img_wdh']);
		$this->setOption('img_hgt',$GLOBALS['confModule']['picture_gallery']['img_hgt']);
		$this->setOption('thb_wdh',$GLOBALS['confModule']['picture_gallery']['thb_wdh']);
		$this->setOption('thb_hgt',$GLOBALS['confModule']['picture_gallery']['thb_hgt']);
	}
	
	function loadContent($pid, $handle='') {
		if (parent::loadContent($pid, $handle)) {
			$arr = array('thb_hgt','thb_hgt','thb_wdh','thb_hgt');
			foreach ($arr as $k) {
				$s = $this->getOption($k);
				if ($s) {
					$GLOBALS['confModule']['picture_gallery'][$k] = $s;
				}
			}
			return true;
		}
		return false;
	}
	
}