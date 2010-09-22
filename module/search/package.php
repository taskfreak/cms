<?php

class ModuleSearch extends TznModule
{

	function ModuleSearch() {
		parent::TznModule('search');
	}
	
	function adminDefault() {
		// decide submit modes
		$GLOBALS['objCms']->initSubmitting(1,2); // save and save and close
	
		// initialize object content
		$this->content = new SearchIntro();
		
		// load content corresponding to page
		$this->content->loadContent($GLOBALS['objPage']->id);

		// list of pages		
		$this->objPageList = new TznPage();
		$this->objPageList->addOrder('position');
		$this->objPageList->loadList();
	
		// initialize CK editor
		$this->content->initAdmin('Full',2);
		
		$this->setView('admin_form');
		
	}
	
		
	/**
	* called on public page (basically sending content)
	*/
	function publicDefault() {
	
		// initialize object content
		$this->content = new SearchIntro();
		
		// load content corresponding to page
		$this->content->loadContent($GLOBALS['objPage']->id);
		
		$root = '';
		$objPageRoot = new TznPage();
		$objPageRoot->setUid($this->content->getOption('root_page'));
		if ($objPageRoot->load()) {
			$root = $objPageRoot->getOutlineRoot(false).'%';
		}
		unset($objPageRoot);
		 
		$this->results = new CmsObjectPage();
		if ($this->keyword = Tzn::getHttp($_REQUEST['keyword'])) {
		
			$this->words = explode(' ',trim($this->keyword));
		
			$pKeyword = '%'.str_replace(' ','%',$this->keyword).'%';
			$this->results->addWhere("(page.title LIKE '$pKeyword' OR body LIKE '$pKeyword')");
			$this->results->addWhere('page.display = 1');
			if ($root) {
				$this->results->addWhere("page.position LIKE '$root'");
			}
			$this->results->loadList();
		}
		
		$this->setView('public_page');
	}
	
}

class SearchIntro extends CmsContent
{
	function SearchIntro() {
		parent::CmsContent();
		$this->addOptions(array(
			'root_page'		=> 'NUM',
			'result_length'	=> 'NUM'
		));
	}
}