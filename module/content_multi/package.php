<?php

class ModuleContentMulti extends TznModule
{

	function ModuleContentMulti() {
		parent::TznModule('content_multi');
	}
	
	/**
	* default call when editing page with this module
	*/
	function adminDefault() {
		// decide submit modes
		$GLOBALS['objCms']->initSubmitting(2); // save and save and close
	
		// initialize object content
		$this->content = array();
		$this->content[0] = new CmsContentMulti();
		$this->content[0]->loadContent($GLOBALS['objPage']->id,'');
		$c = $this->content[0]->getOption('multi_count');
		
		for ($i=1; $i<$c; $i++) {
			$this->content[$i] = new CmsContent();
			$this->content[$i]->loadContent($GLOBALS['objPage']->id,'side-'.$i);
		}
		
	
		// set script for form
		for ($i=0; $i<$c; $i++) {
			$this->content[$i]->initAdmin('full', 2);
		}
		
		$this->c = $c;
		$this->setView('admin_form');
		
	}
	
	function adminDefaultAction() {
	
		// set body value
		$this->content[0]->setOption('multi_count', intval($_POST['option_multi_count']));
		$this->content[0]->setHttp('body',$_POST['body0']);
		
		for ($i=1; $i<$this->c; $i++) {
			$field = 'body'.$i;
			$this->content[$i]->handle = 'side-'.$i;
			$this->content[$i]->pageId = $GLOBALS['objPage']->id;
			$this->content[$i]->setHttp('body',$_POST[$field]);
		}
		
		// now do the DB work
		switch ($GLOBALS['objCms']->submitMode) {
			case 1: // save
			case 2:	// saveclose
			case 3: // saveadd
				for ($i=0; $i<$this->c; $i++) {
					$this->content[$i]->save();
				}
				break;
			case 4: // delete
				for ($i=0; $i<$this->c; $i++) {
					$this->content[$i]->delete();
				}
				break;
		}
		
		// and redirect user
		$GLOBALS['objCms']->adminSubmitNext();
	}
		
	/**
	* called on public page (basically sending content)
	*/
	function publicDefault() {
		
		$this->content = array();
		$this->content[0] = new CmsContentMulti();
		$this->content[0]->loadContent($GLOBALS['objPage']->id,'');
		$c = $this->content[0]->getOption('multi_count');
		
		for ($i=1; $i<$c; $i++) {
			$this->content[$i] = new CmsContent();
			$this->content[$i]->loadContent($GLOBALS['objPage']->id,'side-'.$i);
		}
		
	}
	
}

class CmsContentMulti extends CmsContent
{

	function CmsContentMulti() {
		parent::CmsContent();
		$this->addOptions(array(
			'multi_count'	=> 'NUM'
		));
		$this->setOption('multi_count',2);
	}

}