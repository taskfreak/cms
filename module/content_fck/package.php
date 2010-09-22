<?php

class ModuleContentFck extends TznModule
{

	function ModuleContentFck() {
		parent::TznModule('content_fck');
	}
	
	/**
	* default call when editing page with this module
	*/
	function adminDefault() {
		// decide submit modes
		$GLOBALS['objCms']->initSubmitting(1,2); // save and save and close
	
		// initialize object content
		$this->content = new ContentFck();
		
		// load content corresponding to page
		$this->content->loadContent($GLOBALS['objPage']->id);
	
		// set script for form
		$this->content->initAdmin('full', 2);
		
		$this->setView('admin_form');
		
	}
	
		
	/**
	* called on public page (basically sending content)
	*/
	function publicDefault() {
		// initialize object content
		$this->content = new ContentFck();
		
		// load content corresponding to page
		$this->content->loadContent($GLOBALS['objPage']->id);
		
		// check options
		$this->content->initPublic();
		
	}
	
}

class ContentFck extends CmsContent
{

	var $_sitemap;
	var $_blog;
	var $_parentCurrent;
	
	function ContentFck() {
		parent::CmsContent();
		$this->addOptions(array(
			'sitemap_inc'	=> 'BOL',
			'sitemap_idx'	=> 'NUM',
			'blog_inc'		=> 'BOL',
			'blog_tag'		=> 'STR'
		));
		$this->_sitemap = false;
		$this->_blog = false;
	}
	
	function initAdmin($mode, $upl) {
		
		parent::initAdmin($mode, $upl);
		
		$this->_sitemap = new TznPage();
		$this->_sitemap->addWhere('display=1');
		$this->_sitemap->loadTree();
	}
	
	function initPublic() {
		if ($this->getOption('sitemap_inc')) {
			// load site map
			$id = $this->getOption('sitemap_idx');
			$this->_sitemap = new TznPage();
			$this->_sitemap->addWhere('display=1');
			$this->_sitemap->loadChildren($id, 3);
		}
		if ($tag = $this->getOption('include_blog')) {
			// load blog articles
		}
	}
	
	function printContent() {
	
		echo parent::printContent();
		
		if ($this->_sitemap) {
			$item = $this->_sitemap->rNext();
			$this->_printMultiRecurse($item);
		}
	}
	
	function _printMultiRecurse($objItem, $id='') {
		$go_on = true;
		$curLevel = $objItem->getOutlineLevel();
		$mid = ($id)?' id="'.$id.'"':'';
		echo "<ul$mid>";
		do {
			echo '<li';
			if ($objItem->id == $GLOBALS['objPage']->id ||
				$this->_parentCurrent && $objItem->isOutlineParentOf($GLOBALS['objPage']->getOutline()))
			{
				echo ' class="current"';
			}
			echo '><a href="'.$objItem->getUrl().'">'.$objItem->get('menu').'</a>';
			$objItem = $this->_sitemap->rNext();
			if ($objItem) {
				$nextLevel = $objItem->getOutlineLevel();
				if ($nextLevel > $curLevel) {
					if ($objItem = $this->_printMultiRecurse($objItem)) {
						$nextLevel = $objItem->getOutlineLevel();
					} else {
						$nextLevel = 0;
					}
				} 
				if ($nextLevel < $curLevel) {
					$go_on = false;
				} else {
					$curLevel = $nextLevel;
				}
			}
			echo '</li>';
		} while ($objItem && $go_on);
		echo '</ul>';
		return $objItem;
	}
}