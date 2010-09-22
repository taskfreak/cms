<?php
class MenuList {
	
	var $_startId;
	var $_levels;
	var $_menuOnly;
	var $_pageList;
	var $_parentCurrent;
	
	function MenuList($arrArgs) {
		$this->_startId = 1;
		$this->_levels = null;
		$this->_menuOnly = true;
		$this->_parentCurrent = true;
		switch(count($arrArgs)) {
			case 4:
				$this->_parentCurrent = $arrArgs[3];
			case 3:
				$this->_menuOnly = $arrArgs[2];
			case 2:
				$this->_levels = $arrArgs[1];
			case 1:
				$this->_startId = intval($arrArgs[0]);
		}
		$this->init();
	}
	
	/**
	* Print the menu list
	*/
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
			$objItem = $this->_pageList->rNext();
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
	/**
	* Print the menu
	*/
	function p($id='') {
		if ($id == 'menu_bot') {
			$first = true;
			$this->_pageList->rReset();
			while ($objItem = $this->_pageList->rNext()) {
				if ($first) {
					$first = false;
				} else {
					echo ' | ';
				}
				echo '<a href="'.$objItem->getUrl().'">'.$objItem->get('menu').'</a>';
			}
		} else if ($objItem = $this->_pageList->rNext()) {
			$this->_printMultiRecurse($objItem,$id);
		}
	}
	/**
	* load pages
	*/
	function init() {
		$this->_pageList = new TznPage();
		// only pages to be shown in menu
		if ($this->_menuOnly) {
			$this->_pageList->addWhere('showInMenu=1');
		}
		// only pages to be published
		$this->_pageList->addWhere('display=1');
		// only public, protected or private pages
		if ($GLOBALS['objUser']->isLoggedIn()) {
			$sql = 'private=0';
			if ($GLOBALS['objUser']->hasAccess(1)) {
				// can access protected
				$sql .= ' OR private=1';
			}
			if ($GLOBALS['objUser']->hasAccess(2)) {
				// can access private
				$sql .= ' OR private=2';
			}
			if ($sql2 = $GLOBALS['objUser']->getUserPagesSql()) {
				$sql .= ' OR pageId '.$sql2;
			}
			if ($sql) {
				$this->_pageList->addWhere('('.$sql.')');
			}
		} else {
			// visitors can see public pages only
			$this->_pageList->addWhere('private=0');
		}
		$this->_pageList->loadChildren($this->_startId,$this->_levels);
	}

}
