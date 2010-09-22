<?php
class MenuListLocal {
	
	var $_startId;
	var $_levels;
	var $_menuOnly;
	var $_pageList;
	
	function MenuListLocal($arrArgs) {
		$this->_startId = 1;
		$this->_levels = null;
		$this->_menuOnly = true;
		switch(count($arrArgs)) {
			case 3:
				$this->_menuOnly = $arrArgs[2];
			case 2:
				$this->_levels = $arrArgs[1];
			case 1:
				$this->_startId = intval($arrArgs[0]);
		}
		$this->init();
	}
		
	function _searchNextLocalItem($objItem, $activeLine, $activeLevel, $minLevel) {
		do {
			$curLevel = $objItem->getOutlineLevel();
			$str = '';
			for ($i=1; $i < $curLevel; $i++) {
				$str .= ':';
			}
			//error_log($str.' '.$objItem->menu.' ('.$curLevel.'/'.$activeLevel.')');
			
			if ($curLevel == $minLevel) {
				return $objItem;
			}
			if ($curLevel < $activeLevel) {
				if ($objItem->isOutlineParentOf($activeLine)) {
					return $objItem;
				}
			}
			if ($curLevel == $activeLevel) {
				if ($objItem->isOutlineBrotherOf($activeLine)) {
					return $objItem;
				}
			}
			if ($curLevel == $activeLevel + 1) {
				if ($objItem->isOutlineChildrenOf($activeLine)) {
					return $objItem;
				}
			}
			$objItem = $this->_pageList->rNext();
		} while ($objItem);
		return $objItem;
	}
	/**
	* Print the menu list
	*/
	function _printMultiRecurse($activeId, $activeLine, $activeLevel, $minLevel, $objItem, $id='') {
		$go_on = true;
		$curLevel = $objItem->getOutlineLevel();
		$mid = ($id)?' id="'.$id.'"':'';
		echo "<ul$mid>";
		do {
			echo '<li';
			if ($objItem->id == $activeId || $objItem->isOutlineParentOf($activeLine)) {
				echo ' class="current"';
			}
			echo '><a href="'.$objItem->getUrl().'">'.$objItem->get('menu').'</a>';
			if ($objItem = $this->_pageList->rNext()) {
				$objItem = $this->_searchNextLocalItem($objItem, $activeLine, $activeLevel, $minLevel);
			}
			if ($objItem) {
				$nextLevel = $objItem->getOutlineLevel($objItem->getOutline());
				//error_log($curLevel .' =/= '.$nextLevel.' : '.$objItem->menu);
				if ($nextLevel > $curLevel) {
					if ($objItem = $this->_printMultiRecurse($activeId, $activeLine, $activeLevel, $minLevel, $objItem)) {
						$nextLevel = $objItem->getOutlineLevel();
					} else {
						break;
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
		if ($_REQUEST['debug'] == 'menu') {
			echo 'DEBUG MENU<br />start='.$this->_startId.' levels='.$this->_levels;
			echo '<br />found '.$this->_pageList->rTotal().' item(s)';
			echo '<ul>';
			while ($objItem = $this->_pageList->rNext()) {
				echo '<li>'.$objItem->id.': '.$objItem->get('menu').'</li>';
			}
			echo '</ul>';
		} else
		if ($objItem = $this->_pageList->rNext()) {
			$this->_printMultiRecurse(
				$GLOBALS['objPage']->id,
				$GLOBALS['objPage']->getOutline(),
				$GLOBALS['objPage']->getOutlineLevel(),
				$objItem->getOutlineLevel(), 
				$objItem, $id
			);
		}
	}
	/**
	* coming soon
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
