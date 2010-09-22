<?php

// 000000 000000 000000 000000 000000 000000
define('TZN_OUTLINE_FIED','outline');
define('TZN_OUTLINE_DIGITS',8);
define('TZN_OUTLINE_LEVELS',12);

class TznOutline extends TznDb {

	var $_outlineDigits;
	var $_outlineLevels;
	var $_outlineField;
	var $_outlineArray;

	function TznOutline($table = 'outline', $field=null, $digits=null, $levels=null) {
		parent::TznDb($table);
		$this->_outlineField = ($field)?$field:TZN_OUTLINE_FIELD;
		$this->addProperties(
			array(
				'id'		=> 'UID',
				$this->_outlineField	=> 'STR'
			)
		);
		
		$this->_outlineDigits = ($digits)?$digits:TZN_OUTLINE_DIGITS;
		$this->_outlineLevels = ($levels)?$levels:TZN_OUTLINE_LEVELS;

		$outlineTotal = $this->_outlineDigits * $this->_outlineLevels;
		for ($i=1; $i<=$outlineTotal; $i++) {
			$this->_outlineSample .= "0";
		}
	}

	function getOutline() {
		return $this->get($this->_outlineField);
	}

	function getOutlineArray($outline = null) {
		if (!$outline) {
			if (is_array($this->_outlineArray)) {
				return $this->_outlineArray;
			} else {
				$outline = $this->getOutline();
			}
		} else if (is_array($outline)) {
			$this->_outlineArray = $outline;
			return $outline;
	    }
	    $arrOutline = array();
	    // $outlineTotal = $this->_outlineDigits * $this->_outlineLevels;
	    $outlineTotal = strlen($outline);
		for ($i=0; $i<$outlineTotal; $i += $this->_outlineDigits) {
			$arrOutline[] = substr($outline,$i,$this->_outlineDigits);
		}
		$this->_outlineArray = $arrOutline;
	    return $arrOutline;
	}

	function getOutlineLevel($outline = null) {

	    $arrOutline = $this->getOutlineArray($outline);

	    $level = 0;

	    foreach ($arrOutline as $step) {

			if (intval($step)) {
				$level++;
			} else {
				break;
			}
	    }

	    return $level;
	}

	function getOutlineRoot($complete, $outline = null) {
	    
	    $arrOutline = $this->getOutlineArray($outline);
	    
	    $level = 0;
	    $outline = '';

	    for ($i=0; $i < $this->_outlineLevels; $i++) {

				if (intval($arrOutline[$i])) {
					$outline .= $arrOutline[$i];
					$level++;
				} else {
					break;
				}
		}

	    if ($complete) {
			$outline .= '%';

			if (is_numeric($complete)) {
				// number of level requested
				$level += $complete;
			} else {
				// TRUE = 1 level
				$level += 1;
			}

			for ($i=$level; $i < $this->_outlineLevels; $i++) {
				$outline .= str_pad('',$this->_outlineDigits,'0');
			}
	    }

	    return $outline;
	}
	
	function isOutlineParentOf($testOutline) {
		$testRoot = $this->getOutlineRoot(false);
		//error_log("testing parent $testRoot against $testOutline");
		return preg_match('/^'.$testRoot.'/',$testOutline);
	}
	
	function isOutlineChildrenOf($testOutline) {
		$testRoot = $this->getOutlineRoot(false,$testOutline);
		//error_log("testing child $testRoot against $testOutline");
		return preg_match('/^'.$testRoot.'/',$this->getOutline());
	}
	
	function isOutlineBrotherOf($testOutline) {
		$myRoot = $this->getOutlineParent(false);
		$testRoot = $this->getOutlineParent(false,$testOutline);
		//error_log("testing bro' $testRoot against $myRoot : ".(($testRoot == $myRoot)?'OK':'NO'));
		return ($testRoot == $myRoot);
	}

	function getOutlineParent($complete = true, $outline = null) {
	    
	    $arrOutline = $this->getOutlineArray($outline);
		$level = $this->getOutlineLevel($arrOutline) - 1;

	    $outline = '';

	    for ($i=0; $i < $level; $i++) {

			if (intval($arrOutline[$i])) {
				$outline .= $arrOutline[$i];
			} else {
				break;
			}

	    }

	    if ($complete) {
			for ($i=$level; $i < $this->_outlineLevels; $i++) {
				$outline .= str_pad('',$this->_outlineDigits,'0');
			}
	    }

	    return $outline;
	}

    function getParent($outline=null) {
		$arrOutline = $this->getOutlineArray($outline);

        $outline = $this->getOutlineParent(true,$arrOutline);

		//echo "i:".$this->position."<br>p:".$outline;

        $className = ucFirst(get_class($this));
        $objParent = new $className();

        if ($outline) {
            $objParent->loadByKey($this->_outlineField, $outline);
        }

        return $objParent;
    }
    
    function getParentId($outline=null) {
    	$objParent = $this->getParent($outline);
    	return $objParent->id;
    }

    function deleteOutlineTree() {
		if (!$this->getOutline()) {
			if (!$this->load()) {
				return false;
			}
		}

		if ($searchText = $this->getOutlineRoot(false)) {

			/* $arrOutline = $this->getOutlineArray();
			$curLevel = $this->getOutlineLevel($arrOutline); */

			//$nextBro = $this->getOutlineNextBrother();

			// delete branch and children
			$className = ucFirst(get_class($this));
			$objOutlineList = new $className();
			$objOutlineList->delete($this->_outlineField." LIKE '".$searchText."%'");

			// move up next siblings
			$this->_moveNextSiblingsUp();
			/*
			$curLine = intval(substr($searchText,-($this->_outlineDigits)));
			$searchText = substr($searchText,0,-($this->_outlineDigits));
			$curLimit = $this->_outlineDigits * ($curLevel - 1);
			$sql = 'UPDATE tzn_page '
				.'SET position = CONCAT(SUBSTRING(position,1,'.$curLimit
				.'),LPAD((CAST(SUBSTRING(position,'.($curLimit+1).','.$this->_outlineDigits.') AS UNSIGNED) - 1),'
				.$this->_outlineDigits.',\'0\'),SUBSTRING(position,'.($curLimit+1+$this->_outlineDigits).'))'
				.'WHERE (position LIKE \''.$searchText.'%\') AND (CAST(SUBSTRING(position,'
				.($curLimit+1).','.$this->_outlineDigits.') AS UNSIGNED) > '.$curLine.')';
			$this->query($sql);
			*/
			/*
			$className = ucFirst(get_class($this));
			$objBrother = new $className(); 

			if ($objBrother->loadByKey($this->_outlineField, $nextBro)) {
				error_log('moving up '.$nextBro);
				$objBrother->moveOutlineUp();
			}
			*/

			return true;

		}

		return false;

	}

	function canOutlineAddChild($outline=null) {
		$arrOutline = $this->getOutlineArray($outline);
		$curLevel = $this->getOutlineLevel($arrOutline);

		if ($curLevel >= $this->_outlineLevels) {
			$this->_error['outline'] = 'max level reached';
			return false;
		} else {
			return $curLevel;
		}
	}

	function getOutlineNextSibling($outline=null) {
		$arrOutline = $this->getOutlineArray($outline);
		$curLevel = $this->getOutlineLevel($arrOutline)-1;

		$lastOutId = intval($arrOutline[$curLevel]);
		$nextOutId = str_pad(++$lastOutId,$this->_outlineDigits,'0',STR_PAD_LEFT);
		$arrOutline[$curLevel] = $nextOutId;
		return implode('',$arrOutline);
	}

	function getOutlineNextChild($outline=null) {
		// check level is not max level
		$curLevel = $this->canOutlineAddChild($outline);
		if ($curLevel === false) {
			return false;
		}
		$lastRank = 1;
		$root = $this->getOutlineRoot(TRUE,$outline);

		// search children
		$className = ucFirst(get_class($this));
		$objChildList = new $className;
		$objChildList->addOrder($this->_outlineField." DESC");
		$objChildList->addWhere($this->_outlineField." LIKE '".$root."'");
		$objChildList->addWhere($this->_outlineField." <> '".$this->getOutline()."'");
		$objChildList->setPagination(1);
		if ($objChildList->loadList(TZN_DB_COUNT_OFF)) {
			// has children: get last one
			$objLastItem = $objChildList->rNext();
			$lastOutline = $objLastItem->getOutlineArray();
			$lastLevel = $objLastItem->getOutlineLevel();
			$lastRank = intval($lastOutline[$lastLevel-1]) + 1;
		} 

		// new outline
		$root = $this->getOutlineRoot(FALSE,$outline);
		$outline = $root.str_pad($lastRank,$this->_outlineDigits,'0',STR_PAD_LEFT);
		for ($i = $curLevel+1; $i < $this->_outlineLevels; $i++) {
			$outline .= str_pad('',$this->_outlineDigits,'0');
		}

		return $outline;
	}

	function setOutlineNextChild($outline) {
		$this->set($this->_outlineField,$this->getOutlineNextChild($outline));
	}

	function setOutlineNextRoot() {
		$this->setOutlineNextChild(str_pad('',$this->_outlineLevels * $this->_outlineDigits,'0'));
	}

	function canOutlineUp($arrOutline=null) {
		if ($arrOutline == null) {
			$arrOutline =& $this->getOutlineArray();
		}
	    return (intval($arrOutline[$this->getOutlineLevel()-1]) > 1);
	}

	function moveOutlineUp() {
		$arrLine = $this->getOutlineArray($this->getOutlineRoot(FALSE));
		if ($this->canOutlineUp($arrLine)) {
			// echo "root is ".$this->getOutlineRoot(FALSE);
			$lastIdx = count($arrLine)-1;
			$lastItem = $arrLine[$lastIdx];
			$currentNb = intval($lastItem);
			$previousNb = $currentNb - 1;
			$currentNb = str_pad($currentNb,$this->_outlineDigits,'0',STR_PAD_LEFT);
			$arrLine[$lastIdx] = $currentNb;
			$currentNb = implode('',$arrLine);
			$previousNb = str_pad($previousNb,$this->_outlineDigits,'0',STR_PAD_LEFT);
			$arrLine[$lastIdx] = $previousNb;
			$previousNb = implode('',$arrLine);
			// echo "switching $currentNb and $previousNb";
			// return false;
			$this->_moveAll($currentNb,$previousNb);
			return true;
		} else {
			return false;
		}
	}

	function _moveAll($srcline,$destline) {
		$className = ucFirst(get_class($this));
		$objSrcList = new $className();
		$objSrcList->addWhere($this->_outlineField." LIKE \"".$srcline."%\"");
		$objSrcList->addOrder($this->_outlineField." ASC");
		$objSrcList->loadList();
		$objDestList = new $className();
		$objDestList->addWhere($this->_outlineField." LIKE \"".$destline."%\"");
		$objDestList->addOrder($this->_outlineField." ASC");
		$objDestList->loadList();
		//echo("moving src ($srcline)...<ul>");
		while($objItem = $objSrcList->rNext()) {
			$curline = $objItem->get($this->_outlineField);
			//echo("<li>$curline =&gt; ");
			$curline = preg_replace("/^".$srcline."/",$destline,$curline);
			//echo("$curline</li>");
			$objItem->set($this->_outlineField,$curline);
			$objItem->getConnection();
			$objItem->update($this->_outlineField);
		}
		//echo("</ul>moving dest ($destline)...<ul>");
		while($objItem = $objDestList->rNext()) {
			$curline = $objItem->get($this->_outlineField);
			//echo("<li>$curline =&gt; ");
			$curline = preg_replace("/^".$destline."/",$srcline,$curline);
			//echo("$curline</li>");
			$objItem->set($this->_outlineField,$curline);
			$objItem->getConnection();
			$objItem->update($this->_outlineField);
		}
	}

	function _moveNextSiblingsUp($outline=null) {
		$arrOutline = $this->getOutlineArray($outline);
		$curLevel = $this->getOutlineLevel($arrOutline);
		$searchText = $this->getOutlineRoot(false);
		$curLine = intval(substr($searchText,-($this->_outlineDigits)));
		$searchText = substr($searchText,0,-($this->_outlineDigits));
		$curLimit = $this->_outlineDigits * ($curLevel - 1);
		$sql = 'UPDATE '.$this->gTable()
			.' SET position = CONCAT(SUBSTRING(position,1,'.$curLimit
			.'),LPAD((CAST(SUBSTRING(position,'.($curLimit+1).','.$this->_outlineDigits.') AS UNSIGNED) - 1),'
			.$this->_outlineDigits.',\'0\'),SUBSTRING(position,'.($curLimit+1+$this->_outlineDigits).'))'
			.'WHERE (position LIKE \''.$searchText.'%\') AND (CAST(SUBSTRING(position,'
			.($curLimit+1).','.$this->_outlineDigits.') AS UNSIGNED) > '.$curLine.')';
		// echo $sql; exit;
		return $this->query($sql);
	}

	function moveOutlineParent($outlineParent) {
		//error_log('moving '.$this->id.' ('.$this->getOutline().') to '.$outlineParent);
		$oultineOrigin = $this->getOutline();
		$outlineOldRoot = $this->getOutlineRoot(FALSE);
		//error_log('old root = '.$outlineOldRoot);
		
		// 1. get parent's next outline
		$this->setOutlineNextChild($outlineParent);
		//error_log('new outline = '.$this->getOutline());
		$outlineNewRoot = $this->getOutlineRoot(FALSE,$this->getOutline());
		//error_log('new root = '.$outlineNewRoot);
		
		// 2. move all children
		$this->getConnection();
		$sql = 'UPDATE '.$this->gTable().' SET '
			.$this->_outlineField.'=RPAD(CONCAT(\''.$outlineNewRoot.'\',SUBSTRING('.$this->_outlineField.','
			.(strlen($outlineOldRoot)+1).')),'.$this->_outlineDigits*$this->_outlineLevels.',\'0\')'
			.' WHERE '.$this->_outlineField.' REGEXP \'^'.$outlineOldRoot.'\'';
		//error_log($sql);
		$this->query($sql);

		// 3. move all next siblings up
		$this->_moveNextSiblingsUp($oultineOrigin);
	}
	
	function _initTree() {
		$this->title = "root";
	}

	function loadChildren($parent,$level=1, $sql='') {
		$className = ucFirst(get_class($this));
		$objParent = new $className();
		if (is_object($parent)) {
			$objParent = $parent;
		} else {
			$objParent->setUid($parent);
			if (!$objParent->load()) {
				return false;
			}
		}
		if ($objParent->getOutlineLevel() > $objParent->_outlineLevels) {
			return false;
		}
		$this->addOrder($this->_outlineField.", title ASC");
		$this->addWhere($this->_outlineField." LIKE '".$objParent->getOutlineRoot($level)."'");
		$this->addWhere($this->_outlineField." <> '".$objParent->get($objParent->_outlineField)."'");
		return $this->loadList($sql);
	}

	function loadTree() {
		$this->addOrder($this->_outlineField." ASC");
		return parent::loadList();
	}
	
	function qSelect($name,$keyval,$default='',$nochoice='',
		$style='tznFormSelect',$xtra='')
	{
		$form = '<select name="'.$name.'" ';
		$form .= Tzn::_style($style);
		if ($xtra) {
			$form .= $xtra.' ';
		}
		$form .= '>';
		if ($nochoice) {
			$form .='<option value="">'.$nochoice.'</option>';
		}
		if (is_object($this)) {
			if ($this->rMore()) {
				while ($item = $this->rNext()) { 
					$form .= '<option value="'.$item->id.'"';
					if ($item->id == $default) {
						$form .= ' selected="true"';
					}
					$form .= '>';
					/*
					for ($lvl = $item->getOutlineLevel(); $lvl > 1; $lvl--) {
						$form .='&nbsp; ';
					}
					*/
					$str = '';
					$level = $item->getOutlineLevel();
					if ($level > 1) {
						for ($i=1; $i<$level; $i++) {
							$str .= '&nbsp; ';
						}
						$str .= '- ';
					}
					$form .= $str.$item->_value($keyval).'</option>';
				}
			}
		}
		$form .= '</select>';
		print $form;
		Tzn::pError($name);
	}

}
