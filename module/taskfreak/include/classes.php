<?php
/****************************************************************************\
* TaskFreak!                                                                 *
* multi user                                                                 *
******************************************************************************
* Version: 0.6.2                                                             *
* Authors: Stan Ozier <stan@tirzen.net>                                      *
* License:  http://www.gnu.org/licenses/gpl.txt (GPL)                        *
******************************************************************************
* This file is part of "TaskFreak! multi user" program.                      *
*                                                                            *
* TaskFreak! multi user is free software; you can redistribute it and/or     *
* modify it under the terms of the GNU General Public License as published   *
* by the Free Software Foundation; either version 2 of the License, or (at   *  
* your option) any later version.                                            *
*                                                                            *
* TaskFreak! multi user is distributed in the hope that it will be           *
* useful, but WITHOUT ANY WARRANTY; without even the implied warranty of     *
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the              *
* GNU General Public License for more details.                               *
*                                                                            *
* You should have received a copy of the GNU General Public License          *
* along with this program; if not, write to the Free Software                *
* Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA *
\****************************************************************************/

//define('PRJ_DTE_NOW',strtotime(gmdate('Y-m-d',time()+Tzn::_getUserTZ())));
define('PRJ_DTE_NOW',strtotime(date('Y-m-d',time()+Tzn::_getUserTZ())));

class ItemStatus extends TznDb
{
	
	function ItemStatus() {
		parent::TznDb('itemStatus');
		$this->addProperties(array(
			'id'			=> 'UID',
			'itemId'		=> 'NUM',
			'statusDate'	=> 'DTM',
			'statusKey'		=> 'NUM',
			'member'		=> 'OBJ'
		));
	}
	
	function getStatus() {
		return $GLOBALS['langItemStatus'][$this->statusKey];
	}
	

    function add() {
        if (!$this->statusDate) {
            $this->setDtm('statusDate','NOW');
        }
        return parent::add();
    }
}

class Item extends TznDb
{
	function Item()
	{
		parent::TznDb('item');
		$this->addProperties(array(
			'id'	 			=> 'UID',
			'project'			=> 'OBJ',
			'priority'			=> 'NUM',
			'context'			=> 'NUM',
			'title'				=> 'STR',
			'description'		=> 'BBS',
            'deadlineDate'      => 'DTE',
            'expectedDuration'  => 'NUM',
			'showInCalendar'	=> 'BOL',
			'showPrivate'   	=> 'NUM',
			'creationDate'		=> 'DTM',
			'lastChangeDate'	=> 'DTM',
			'lastChangeAuthorId'=> 'NUM',
            'member'            => 'OBJ',
            'authorId'          => 'NUM'
		));
		// default values
		$this->showPrivate = (defined('FRK_DEFAULT_VISIBILITY'))?FRK_DEFAULT_VISIBILITY:1;
	}
	
	function getShortDescription() {
		$value = $this->getStr('description',150);
		return preg_replace('/\.\.$/',' [...]',$value);
	}

    function getDescription() {
        $value = Tzn::_value('description');
		$value = preg_replace("/(?<!\")((http|ftp)+(s)?"
			.":\/\/[^<>\s]+)/i", "<a href=\"\\0\" target=\"_blank\">\\0</a>", $value);
		return nl2br($value);
    }
    
    function getViewStatus(&$title) {
    	if (!$_SESSION['tskViewItems']) {
    		$_SESSION['tskViewItems'] = array();
    	}
    	if (in_array($this->id, $_SESSION['tskViewItems'])) {
    		return false;
    	}
    	if ($this->creationDate > $_SESSION["tznUserLastLogin"] && $this->authorId != $_SESSION['tznUserId']) {
    		$title = 'Nouveau!';
    		return 'i_new.png';
    	}
    	if ($this->lastChangeDate > $_SESSION["tznUserLastLogin"] && $this->lastChangeAuthorId != $_SESSION['tznUserId']) {
    		$title = 'Mise à jour!';
    		return 'i_upd.png';
    	}
    	return false;
    }
    
    function setViewStatus() {
    	if (!$_SESSION['tskViewItems']) {
    		$_SESSION['tskViewItems'] = array();
    	}
    	if (!in_array($this->id, $_SESSION['tskViewItems'])) {
    		$_SESSION['tskViewItems'][] = $this->id;
    	}
    }

    function setStatus($status,$userId) {
        $objItemStatus = new ItemStatus();
        $objItemStatus->itemId = $this->id;
        $objItemStatus->member->id = $userId;
        $objItemStatus->statusKey = $status;
        $objItemStatus->add();
    }

    function getContext($mode=false) { // mode=0/short, 1/long
        $str = $GLOBALS['langItemContext'][$this->context];
		if ($mode) {
            return $str;
        } else {
            return '<span style="background-color:'.$GLOBALS['confContext'][$this->context].'" title="'.$str.'">'
            	.substr($str,0,1).'</span>';
        }
	}
	
    function sameSame($objOld) {
        if ($this->priority == $objOld->priority
            && $this->deadlineDate == $objOld->deadlineDate)
        {
            return true;
        } else {
            return false;
        }
    }

	function check() {
		$this->checkEmpty('title');
		if (!$this->priority) {
			$this->priority = 4;
		}
		if (!$this->deadlineDate) {
			$this->deadlineDate = '9999-00-00';
		}
		return (count($this->_error) == 0);
	}
	
	function addUpdateFiles($data=null) {
		if (is_null($data)) {
			$data = $_POST;
		}
		if ($data['files2del']) {
			$arr = explode(';',$data['files2del']);
			foreach ($arr as $id) {
				if (!intval($id)) {
					continue;
				}
				$objFile = new ItemFile();
				$objFile->setUid($id);
				if ($objFile->load()) {
					$objFile->delete();
				}
			}
		}
		if (!count($data['uplfile'])) {
			return false;
		}
		
		$arrFiles = array();
		
		foreach ($data['uplfile'] as $fstr) {
			$arr = explode(';',$fstr); // ftemp+';'+freal+';'+ftype+';'+fsize
			$objFile = new TznFile();
			$objFile->origName = $arr[1];
			$objFile->tempName = $arr[0];
			$objFile->fileName = $arr[1];
			$objFile->fileType = $arr[2];
			$objFile->fileSize = $arr[3];
			$objAttachement = new ItemFile();
			$objAttachement->itemId = $this->id;
			$objAttachement->setFile($objFile);
			$objAttachement->add();
			
			$f = $objAttachement->getFileUrl();
			$arrFiles[$f] = $objAttachement->get('fileTitle');
		}
		
		return $arrFiles;
	}

    function getDir($pDir, $key='') {
		$pDir = ($key == 'deadline' || $key == 'lastChangeDate')?-$pDir:$pDir;
		if ($pDir == 1) {
			return 'ASC';
		} else {
			return 'DESC';
		}
	}

	function setOrder($pSort,&$pDir) {
		switch($pSort) {
			case 'deadline';
				$this->addOrder('deadlineDate '.$this->getDir($pDir,'deadline')
					.', priority '.$this->getDir($pDir));
				break;
			case 'priority':
				$this->addOrder('priority '.$this->getDir($pDir)
					.', deadlineDate '.$this->getDir($pDir,'deadline'));
				break;
			default:
				$this->addOrder($pSort.' '.$this->getDir($pDir, $pSort)
					.', deadlineDate ASC, priority ASC');
				break;
		}
	}

	function loadList($sql='') {
		if ($sql) {
			return parent::loadList($sql);
		} else {
			$sql = 'SELECT ii.*, ';
			if (@constant('FRK_MYSQL_VERSION_GT_4_1')) {
				$sql .= 'iis.statusDate as itemStatus_statusDate, iis.statusKey as itemStatus_statusKey, ';
			} else {
				$sql .= 'SUBSTRING(MAX(CONCAT(iis.statusDate,iis.statusKey)),1,19) AS itemStatus_statusDate, '
					.'SUBSTRING(MAX(CONCAT(iis.statusDate,iis.statusKey)),20) AS itemStatus_statusKey, ';
			}
			$sql .= 'pp.name as project_name, '
				.'mm.title as member_title, mm.firstName as member_firstName, mm.middleName as member_middleName, '
				.'mm.lastName as member_lastName, mm.username as member_username';
			if ($userId) {
				$sql .= ', mp.position';
			}
			$sql .= ' FROM '.$this->gTable().' as ii '
				.'INNER JOIN '.$this->gTable('itemStatus').' AS iis ON ii.itemId = iis.itemId '
				.'LEFT JOIN '.$this->gTable('project').' AS pp ON ii.projectId = pp.projectId';
			if ($userId) {
				$sql .= ' LEFT JOIN '.$this->gTable('memberProject')
					.' AS mp ON ii.projectId = mp.projectId AND mp.memberId='.$userId;
			}
			$sql .= ' LEFT JOIN '.$this->gTable('member').' AS mm ON ii.memberId = mm.memberId ';
			$this->addGroup('ii.itemId');
			if (@constant('FRK_MYSQL_VERSION_GT_4_1')) {
				$this->addWhere('iis.statusDate=(SELECT MAX(iis2.statusDate) FROM '.$this->gTable('itemStatus')
					.' AS iis2 WHERE ii.itemId = iis2.itemId)');
			}
			return parent::loadList($sql);
		}
	}

	function delete() {
		if (parent::delete()) {
			$this->query('DELETE FROM '.$this->gTable('itemStatus').' WHERE itemId='.$this->id);
			$this->query('DELETE FROM '.$this->gTable('itemComment').' WHERE itemId='.$this->id);
			// delete files
			$objFiles = new ItemFile();
			$objFiles->addWhere('itemId='.$this->id);
			if ($objFiles->loadList()) {
				while ($obj = $objFiles->rNext()) {
					$obj->delete();
				}
			}
			
			return true;
		}
		return false;
	}

}

class ItemStats extends Item
{
	function ItemStats()
	{
		parent::Item();
		$this->addProperties(array(
            'itemStatus'		=> 'OBJ',
			'itemCommentCount'	=> 'NUM',
			'itemFileCount'		=> 'NUM',
            'position'          => 'NUM'
		));
	}

    function getDeadline($short=false) {
        if (preg_match('/(9999|0000)/',$this->deadlineDate)) {
			return '-';
		} else {
			$dead = strtotime($this->deadlineDate);
			$diff = $dead - intval(PRJ_DTE_NOW) ;
			if ($diff < 0) {
                $format = ($short)?'SHT':'SHX';
				if ($this->itemStatus->statusKey < FRK_STATUS_LEVELS) {
					return '<span class="dlate">'.$this->getDte('deadlineDate',$format).'</span>';
				} else {
					return '<span class="ddone">'.$this->getDte('deadlineDate',$format).'</span>';
				}
			} else if ($diff == 0) {
				if (@constant('FRK_DATEDIFF_MODE') == 'date') {
					return '<span class="dday">'.$this->getDte('deadlineDate',($short)?'SHT':'SHX','').'</span>';
				} else {
					return '<span class="dday">'.$GLOBALS['langDate']['today'].'</span>';
				}
			} else if ($short) {
                $diff = $diff / 3600 / 24;
				switch (@constant('FRK_DATEDIFF_MODE')) {
				case 'day':
					if (@constant('FRK_DATEDIFF_TOMORROW') && $diff == 1) {
						return $GLOBALS['langTznDate']['tomorrow'];
					} else if ($diff < 7) {
					    $day = strtolower(date('l',$dead));
					    if (array_key_exists($day,$GLOBALS['langDateDay'])) {
					       $day = ucfirst($GLOBALS['langDateDay'][$day]);
					    }
					    return '<span class="small">'.ucFirst($day).'</span>';
					} else {
					    return '<span class="small">'
							.$this->getDte('deadlineDate',($short)?'SHT':'SHX','')
							.'</span>';
					}
					break;
				case 'diff':
					switch($diff) {
						case '1':
							if (@constant('FRK_DATEDIFF_TOMORROW')) {
								return $GLOBALS['langDate']['tomorrow'];
							} else {
								return '1 '.$GLOBALS['langDate']['day'];
							}
							break;
						case '2':
						case '3':
						case '4':
						case '5':
						case '6':
							return $diff.' '.$GLOBALS['langDateMore']['days'];
							break;
						default:
							return '<span class="small">'
								.$this->getDte('deadlineDate',($short)?'SHT':'SHX','')
								.'</span>';
							break;
					}
					break;
				default:
					return '<span class="small">'
						.$this->getDte('deadlineDate','SHT','')
						.'</span>';
					break;
				}
            } else {
                return '<span class="dtodo">'.$this->getDte('deadlineDate','SHX').'</span>'; 
            }
        }
    }

    function pDeadline() {
		echo $this->getDeadline(true);
	}

    function pStatus() {
		echo $this->itemStatus->getStatus();
	}
	
	function qSelectStatus($name, $extra='') {
    	echo '<select name="status"';
    	if ($extra) {
    		echo ' '.$extra;
    	}
    	echo '>';
		for ($i = 0; $i <= FRK_STATUS_LEVELS; $i++) {
			echo '<option value="'.$i.'"';
			if ($this->itemStatus->statusKey == $i) {
				echo ' selected="selected"';
			}
			echo '>'.$GLOBALS['langItemStatus'][$i].'</option>';
			echo "\n";
		}
		echo '</select>';
    }
	
	function getSummary($url='') {
		$str = '';
		switch ($this->showPrivate) {
            case 0:
                $str .= '<img src="'.FRK_IMAGES.'priv0.png" title="tâche visible de tous" />';
                break;
            case 2:
                $str .= '<img src="'.FRK_IMAGES.'priv2.png" title="tâche personnelle" />';
                break;
        }
        if ($this->description) {
            $str .= '<img src="'.FRK_IMAGES.'desc.png" alt="note" title="'.$this->getShortDescription().'" />';
        }
        if ($this->itemFileCount) {
            $str .= '<img src="'.FRK_IMAGES.'attachment.png" alt="'
            	.$objItem->itemFileCount.' fichier(s) attach&eacute;(s)" title="'
            	.$objItem->itemFileCount.' fichier(s) attach&eacute;(s)" />';
        }
        // new task ?
        $stl = $this->get('title');
        if (!$this->itemStatus->statusKey) {
        	$stl = '<strong>'.$stl.'</strong>';
        }
        // deadline ?
        if ($url) {
        	$str .= '<a href="'.$url.'">'.$stl.'</a>';
        } else {
	        $str .= $stl;
	    }
        
        return $str;
	}
	
	function getListStat() {
		$str = '';
		if ($img = $this->getViewStatus($ttl)) {
        	$str = '<img id="uvs'.$this->id.'" src="'.FRK_IMAGES.$img.'" title="'.$ttl.'" alt="" />';
        }
		return $str.$this->getDtm('lastChangeDate','%d %b %y, %H:%M');
	}

    function checkRights($userId, $level=0, $userCanToo=false, $publicIsOk=false) {
    	/*
    		error_log("-> check rights userId: $userId, memberId=".$this->member->id.",authorId=".$this->authorId
    			.", level=$level, ".(($userCanToo)?'user OK':'').', position: '.$this->position);
    	*/
    	if (!$this->position) {
    		$this->position = 0;
    	}
    	if ($publicIsOk && !$this->showPrivate) {
    		return true;
    	} else if ($userCanToo && $userId == $this->member->id) {
            return true;
        } else if ($userId == $this->authorId) {
            return true;
        } else if ($level) {
            $level--;
            return ($GLOBALS['confProjectRights'][$this->position]{$level} == '1');
        } else {
            return (intval($this->position) > 0);
        }
    }

    function _cleanProperties() {
    	$this->_position = $this->position; // save position
        unset($this->_properties['itemCommentCount']);
        unset($this->_properties['itemFileCount']);
        unset($this->_properties['position']);
        unset($this->_properties['itemStatus']);
        unset($this->itemStatus);
        unset($this->itemCommentCount);
        unset($this->itemFileCount);
        unset($this->position);
    }

    function add() {
        $this->_cleanProperties();
        parent::add();
        $this->position = $this->_position;
    }
	
    function update($param='') {
        $this->_cleanProperties();
        parent::update($param);
        $this->position = $this->_position;
    }

	function load($userId) {
		if (!$this->id) {
			return false;
		}
        if (@constant('FRK_MYSQL_VERSION_GT_4_1')) {
            // optimize for mysql > 4.1
            $sql = 'SELECT ii.*, count(iic.postDate) as itemCommentCount, count(iif.postDate) as itemFileCount, '
                .'iis.statusDate as itemStatus_statusDate, iis.statusDate, iis.statusKey as itemStatus_statusKey, '
                .'pp.name as project_name, '
                .'mm.title as member_title, mm.firstName as member_firstName, mm.middleName as member_middleName, '
                .'mm.lastName as member_lastName, mm.username as member_username, mp.position '
                .'FROM '.$this->gTable().' AS ii '
                .'INNER JOIN '.$this->gTable('itemStatus').' AS iis ON ii.itemId = iis.itemId '
                .'LEFT JOIN '.$this->gTable('project').' AS pp ON ii.projectId = pp.projectId '
                .'LEFT JOIN '.$this->gTable('memberProject').' AS mp ON ii.projectId = mp.projectId AND mp.memberId='.$userId
                .' LEFT JOIN '.$this->gTable('member').' AS mm ON ii.memberId = mm.memberId '
                .'LEFT JOIN '.$this->gTable('itemComment').' AS iic ON ii.itemId=iic.itemId '
                .'LEFT JOIN '.$this->gTable('itemFile').' AS iif ON ii.itemId=iif.itemId '
                .'WHERE iis.statusDate=(SELECT MAX(iis2.statusDate) FROM '.$this->gTable('itemStatus')
                .' AS iis2 WHERE ii.itemId = iis2.itemId) AND ii.itemId = '.$this->id.' GROUP BY ii.itemId';
        } else {
            $sql = 'SELECT ii.*, count(iic.postDate) as itemCommentCount, count(iif.postDate) as itemFileCount, '
                .'SUBSTRING(MAX(CONCAT(iis.statusDate,iis.statusKey)),1,19) AS itemStatus_statusDate, '
                .'SUBSTRING(MAX(CONCAT(iis.statusDate,iis.statusKey)),20) AS itemStatus_statusKey, '
                .'pp.name as project_name, '
                .'mm.title as member_title, mm.firstName as member_firstName, mm.middleName as member_middleName, '
                .'mm.lastName as member_lastName, mm.username as member_username, mp.position '
                .'FROM '.$this->gTable().' AS ii '
                .'INNER JOIN '.$this->gTable('itemStatus').' AS iis ON ii.itemId = iis.itemId '
                .'LEFT JOIN '.$this->gTable('project').' AS pp ON ii.projectId = pp.projectId '
                .'LEFT JOIN '.$this->gTable('memberProject').' AS mp ON ii.projectId = mp.projectId AND mp.memberId='.$userId
                .' LEFT JOIN '.$this->gTable('member').' AS mm ON ii.memberId = mm.memberId '
                .'LEFT JOIN '.$this->gTable('itemComment').' AS iic ON ii.itemId=iic.itemId '
                .'LEFT JOIN '.$this->gTable('itemFile').' AS iif ON ii.itemId=iif.itemId '
                .'WHERE ii.itemId = '.$this->id.' GROUP BY ii.itemId';
        }
		$this->getConnection();
		if ($result = $this->query($sql)) {
			if ($data = $result->rNext()) {
				$this->setAuto($data);
				$this->_loaded = true;
				return $this->id;
			}   
        }
		return false;
	}
	
	function addDateFilter($filter) {
		if (@constant('FRK_MYSQL_VERSION_GT_4_1')) {
			$this->addWhere('('.$filter.')');
		} else {
			$this->addHaving(str_replace('status','itemStatus_status',$filter));
		}
	}
	
	function loadList($userId=0) {
		$sql = 'SELECT ii.*, ';
        if (@constant('FRK_MYSQL_VERSION_GT_4_1')) {
			$sql .= 'count(iic.postDate) as itemCommentCount, '
				.'count(iif.postDate) as itemFileCount, '
				.'iis.statusDate as itemStatus_statusDate, iis.statusKey as itemStatus_statusKey, ';
        } else {
            $sql .= 'count(DISTINCT iic.postDate) as itemCommentCount, '
				.'count(DISTINCT iif.postDate) as itemFileCount, '
				.'SUBSTRING(MAX(CONCAT(iis.statusDate,iis.statusKey)),1,19) AS itemStatus_statusDate, '
                .'SUBSTRING(MAX(CONCAT(iis.statusDate,iis.statusKey)),20) AS itemStatus_statusKey, ';
        }
        $sql .= 'pp.name as project_name, '
            .'mm.title as member_title, mm.firstName as member_firstName, mm.middleName as member_middleName, '
            .'mm.lastName as member_lastName, mm.username as member_username';
        if ($userId) {
            $sql .= ', mp.position';
        }
        $sql .= ' FROM '.$this->gTable().' as ii '
            .'INNER JOIN '.$this->gTable('itemStatus').' AS iis ON ii.itemId = iis.itemId '
            .'LEFT JOIN '.$this->gTable('project').' AS pp ON ii.projectId = pp.projectId';
        if ($userId) {
            $sql .= ' LEFT JOIN '.$this->gTable('memberProject')
                .' AS mp ON ii.projectId = mp.projectId AND mp.memberId='.$userId;
        }
        $sql .= ' LEFT JOIN '.$this->gTable('member').' AS mm ON ii.memberId = mm.memberId '
            .'LEFT JOIN '.$this->gTable('itemComment').' AS iic ON ii.itemId=iic.itemId '
			.'LEFT JOIN '.$this->gTable('itemFile').' AS iif ON ii.itemId=iif.itemId ';
		$this->addGroup('ii.itemId');
        if (@constant('FRK_MYSQL_VERSION_GT_4_1')) {
            $this->addWhere('iis.statusDate=(SELECT MAX(iis2.statusDate) FROM '.$this->gTable('itemStatus')
                .' AS iis2 WHERE ii.itemId = iis2.itemId)');
        }
		return parent::loadList($sql);
	}
	
	function loadTaskList(&$arrOpts) {

		$arrFilters = array();
		$link = $arrOpts['link'];
			
		// --- filter: user ---
		if (!isset($_REQUEST['user']) && @constant('FRK_DEFAULT_VIEW_OWN_TASKS')) {
			// default view is own tasks only
			$arrOpts['user'] = $GLOBALS['objUser']->id;
		}
		
		if ($arrOpts['user']) {
			$this->addWhere('ii.memberId = \''.$arrOpts['user'].'\'');
		}
		
		$link=TznUtils::concatUrl($link,'user='.$arrOpts['user']);
		
		// --- private tasks --------------------------------------------------------
		
		$arrFilter = array();
		$arrFilter[] = 'showPrivate=0';
		
		// can user access internal tasks
		if ($GLOBALS['objUser']->hasAccess(12, 'taskfreak')) {
			$arrFilter[] = 'showPrivate=1';
		}
		
		// user can access his own private tasks (assigned or author)
		$arrFilter[] = '(showPrivate=2 AND (ii.memberId='.$GLOBALS['objUser']->id
			.' OR ii.authorId='.$GLOBALS['objUser']->id.'))';
		
		$this->addWhere('('.implode(' OR ',$arrFilter).')');
		
		// --- filter: context ------------------------------------------------------
		
		if ($arrOpts['context']) {
			$this->addWhere('context = \''.$arrOpts['context'].'\'');
		    $link=Tzn::concatUrl($link,'sContext='.$arrOpts['context']);
		}
		
		$sqlFilter = '';
		
		$pKeepNoDead = intval(@constant('FRK_NO_DEADLINE_KEEP') -1) * 86400;
		
		// --- Search ? -------------------------------------------------------------
		
		if ($arrOpts['search']) {
			$arrOpts['search'] = trim($arrOpts['search']);
			if ($arrOpts['search'] == '*') {
				$first = false;
				continue;
			}
			if (preg_match('/^".*"$/',$arrOpts['search'])) {
				$pSearchSql = str_replace('"','',$arrOpts['search']);
			} else if (preg_match('/\*/',$iParam)) {
				$pSearchSql = str_replace('*','%',$arrOpts['search']);
			} else {
				$pSearchSql = '%'.str_replace(' ','%',$arrOpts['search']).'%';
			}
			$pSearchSql = mysql_real_escape_string($pSearchSql);
			$this->addWhere("(ii.title LIKE '$pSearchSql' OR ii.description LIKE '$pSearchSql')");
			$arrOpts['show'] = 'all';
		}
		
		// --- Filter per date ------------------------------------------------------
		
		$pDefaultSort =	(defined('FRK_SORT_COLUMN'))?FRK_SORT_COLUMN:'deadlineDate';
		$pDefaultDir = (defined('FRK_SORT_ORDER'))?FRK_SORT_ORDER:1;
		
		if (empty($arrOpts['show'])) {
			$arrOpts['show'] = FRK_DEFAULT_VIEW_TYPE;
		}
		
		$link=TznUtils::concatUrl($link,'show='.$arrOpts['show']);
		
		switch ($arrOpts['show']) {
			case 'all':
				break;
			case 'recent':
				$sqlFilter = "lastChangeDate > '".$_SESSION['tznUserLastLogin']."' AND statusKey < ".FRK_STATUS_LEVELS;
				$pDefaultSort = 'lastChangeDate';
				$pDefaultDir = 1;
				break;
			case 'future':
				// show coming tasks
				$sqlFilter = '(deadlineDate >= \''
					.strftime(TZN_DATE_SQL,PRJ_DTE_NOW).'\' AND statusKey < '
					.FRK_STATUS_LEVELS.')';
		        // show uncompleted tasks with no deadline
				$sqlFilter .= ' OR (deadlineDate = \'9999-00-00\' AND statusKey < '
					.FRK_STATUS_LEVELS.')';
				break;
			case 'past':
				// show past tasks and already done
				$sqlFilter = '(statusKey = '.FRK_STATUS_LEVELS.')';
				break;
			case 'today':
				// show all future tasks (done + undone) and late tasks
				$pKeepNoDead = intval(@constant('FRK_NO_DEADLINE_KEEP') -1) * 86400;
				$sqlFilter = '(statusKey = '.FRK_STATUS_LEVELS.' AND statusDate > \''
					.gmdate('Y-m-d 00:00:00',time()-$pKeepNoDead).'\') ';
					
				// hide far future tasks ?
				$tmpFilter = '';
				if (@constant('FRK_DEFAULT_FAR_FUTURE_HIDE')) {
					$tmp = intval(FRK_DEFAULT_FAR_FUTURE_HIDE) * 86400;
					$tmpFilter .= 'deadlineDate < \''
						.gmdate('Y-m-d 00:00:00',time()+$tmp).'\'';
				}
				
				// show tasks with no deadline ?
				if (@constant('FRK_NO_DEADLINE_TOO')) {
					// yes
					if ($tmpFilter) {
						$sqlFilter .= 'OR ('.$tmpFilter
							.' OR deadlineDate = \'9999-00-00\')'
							. ' AND statusKey < '.FRK_STATUS_LEVELS;
					} else {
						$sqlFilter .= ' OR statusKey < '.FRK_STATUS_LEVELS;
					}
				} else {
					// don't show uncompleted non planned tasks
					if ($tmpFilter) {
						$sqlFilter .= ' OR ('.$tmpFilter.' AND statusKey < '
		    	        	.FRK_STATUS_LEVELS.')';
					} else {
			            $sqlFilter .= ' OR (deadlineDate <> \'9999-00-00\' AND statusKey < '
		    	        	.FRK_STATUS_LEVELS.')';
					}
				}
				
		        if (@constant('FRK_DEFAULT_CURRENT_TASKS')) {
		            $this->setPagination(FRK_DEFAULT_CURRENT_TASKS);
		        }
				break;
			default:
				if (preg_match('/^sta(\d+)$/', $arrOpts['show'], $arrRes)) {
					$sqlFilter = '(statusKey = '.$arrRes[1].')';
				} else if (preg_match('/^sta((\d,?)+)$/', $arrOpts['show'], $arrRes)) {
					$sqlFilter = '(statusKey IN ('.$arrRes[1].'))';
				}
				break;
		}
		
		// -TODO- Add filter current project only (no completed, no cancelled)
		
		if ($sqlFilter) {
			$this->addDateFilter($sqlFilter);
		}
		
		// --- Task order -----------------------------------------------------------
		
		$arrOpts['sort'] = ($_REQUEST['sort'])?$_REQUEST['sort']:$pDefaultSort;
		$arrOpts['dir'] = ($_REQUEST['dir'])?$_REQUEST['dir']:$pDefaultDir;
		
		//if ($pShow == 'past' && (!$_REQUEST['dir']) && $pDir == 1) $pDir = -$pDir;
		$this->setOrder($arrOpts['sort'], $arrOpts['dir']);
		
		// --- Send link back -------------------------------------------------------
		
		$arrOpts['link'] = $link;

		// --- Load -----------------------------------------------------------------
		
		return $this->loadList($objUser->id);
	}
}

class ItemContextList extends TznCollection
{
    function ItemContextList() {
		parent::TznCollection($GLOBALS['langItemContext']);
    }

    function getColor($code) {
        if (array_key_exists($code, $GLOBALS['confContext'])) {
            return $GLOBALS['confContext'][$code];
        } else {
            return '#666';
        }
    }

}

class ItemComment extends TznDb
{
	function ItemComment()
	{
		parent::TznDb('itemComment');
		$this->addProperties(array(
			'id'				=> 'UID',
			'itemId'			=> 'NUM',
			'member'			=> 'OBJ',
			'postDate'			=> 'DTM',
			'body'				=> 'BBS',
			'lastChangeDate'	=> 'DTM'
		));
	}
	
	/*
	function _idkey() {
		return 'taskId = '.$this->taskId
			.' AND memberId = '.$this->member->id
			.' AND postDate = \''.$this->postDate.'\'';
	}
	 */

	function checkRights($userId, $level=0, $objTask, $userCanToo=false) {
		//error_log('checkin #'.$this->id.'/'.$level.' : '.$userId.' = '.$this->member->id);
		if ($userId == $objTask->member->id && $userCanToo) {
			// user is assigned to task and can do action
			return true;
		} else if ($userId == $this->member->id) {
			// user is comment author
            return true;
        } else if ($level) {
        	// check level access
            $level--;
            return ($GLOBALS['confProjectRights'][$objTask->position]{$level} == '1');
		} else {
			return false;
		}
    }
	
	function add() {
		$this->body = trim($this->body);
		if (empty($this->body)) {
			return false;
		}
		$this->setDtm('postDate','NOW');
		return parent::add();
	}
	
	function update() {
		$this->setDtm('lastChangeDate','NOW');
		return parent::update();
	}
	
	/*
	function delete() {
		if ($this->taskId && $this->member->id && $this->postDate) {
			$this->getConnection();
			$sql = 'DELETE FROM taskComment WHERE '.$this->_idkey();
			return $this->query($sql);
		} else {
			return false;
		}
	}
	*/
	
}

class ItemCommentFull extends ItemComment
{

	function ItemCommentFull()
	{
		parent::TaskComment();
		$this->addProperties(array(
			'memberProject'			=> 'OBJ'
		));
	}
	
	function loadList() {
		$sql = 'SELECT iic.*, mm.username as member_username, mm.timeZone as member_timeZone,'
			.'mm.creationDate as member_creationDate, mm.firstName as member_firstName, '
			.'mm.middleName as member_middleName, mm.lastName as member_lastName, '
			.'mp.position as memberTeam_position '
			.'FROM '.$this->gTable('itemComment').' AS iic '
            .'INNER JOIN '.$this->gTable('member').' AS mm ON iic.memberId=mm.memberId '
			.'INNER JOIN '.$this->gTable('item').' AS ii ON ii.itemId = iic.itemId '
			.'LEFT JOIN '.$this->gTable('memberProject').' AS mp ON iic.memberId = mp.memberId '
			.'AND mp.teamId = ii.teamId';
		return parent::loadList($sql);
	}

}

class ItemFile extends TznDb
{
	function ItemFile()
	{
		parent::TznDb('itemFile');
		$this->addProperties(array(
			'id'				=> 'UID',
			'itemId'			=> 'NUM',
			'fileTitle'			=> 'STR',
			'filename'			=> 'DOC',
			'filetype'			=> 'STR',
			'filesize'			=> 'STR',
			'fileTags'			=> 'STR',
			'postDate'			=> 'DTM',
			'lastChangeDate'	=> 'DTM',
			'memberId'			=> 'NUM'
		));
	}
	
	function getFileUrl() {
		$folder = 'documents/';
		$value = $this->filename;
		if (preg_match('/(jpg|jpeg|png|gif)$/', $this->filetype)) {
			// file is image
			$folder = 'gallery/';
		} else {
			// file is document
			
		}
		return TZN_FILE_UPLOAD_URL.$folder.$this->filename;
		
	}
	
	function setFile($objFile) {
		if ($objFile->isImage()) {
			$objFile->saveOptions = array(
				array('w'=>'1024','h'=>'768','f'=>'gallery'),
				array('w'=>'200','h'=>'150','f'=>'gallery/thumbs'),
			);
		} else {
			$objFile->saveOptions = array(
				array('f'=>'documents')
			);
		}
		$this->filename = $objFile;
		$this->fileTitle = $objFile->fileName;
		$this->filetype = $objFile->fileType;
		$this->filesize = $objFile->fileSize;
		$this->set('postDate','NOW');
		$this->set('lastChangeDate','NOW');
		$this->memberId = $GLOBALS['objCms']->user->id;
	}
	
	function delete() {
		if (preg_match('/(jpg|jpeg|png|gif)$/', $this->filetype)) {
			$this->_properties['filename'] = 'IMG,(w:1024,h:768,f:gallery/),(w:200,h:150,f:gallery/thumbs/)';
		} else {
			$this->_properties['filename'] = 'DOC,(f:documents)';
		}
		parent::delete();
	}
	
}

// easy meat

class ItemPriority extends TznCollection
{
	
	function ItemPriority() {
		$prioCount = count($GLOBALS['langItemPriority']);
		$arrPrio = array();
		for($i=1;$i<=FRK_PRIORITY_LEVELS;$i++) {
			$arrPrio[$i] = $i;
			// $arrPrio[$i] = $GLOBALS['langItemPriority'][$i];
		}
		parent::TznCollection($arrPrio);
		//parent::TznCollection($GLOBALS['langItemPriority']);
	}
}


class ProjectStatusList extends TznCollection
{
    function ProjectStatusList() {
		parent::TznCollection($GLOBALS['langProjectStatus']);
    }
}

class ProjectStatus extends TznDb
{
	
	function ProjectStatus() {
		parent::TznDb('projectStatus');
		$this->addProperties(array(
			'id'			=> 'UID',
			'projectId'		=> 'NUM',
			'statusDate'	=> 'DTM',
			'statusKey'		=> 'NUM',
			'member'		=> 'OBJ'
		));
	}
	
	function getStatus() {
		return $GLOBALS['langProjectStatus'][$this->statusKey];
	}
	
}

class Project extends TznDb 
{

	function Project() {
		parent::TznDb('project');
		$this->addProperties(array(
			'id'	 			=> 'UID',
			'name'				=> 'STR',
			'description'		=> 'BBS'
		));
	}
	
	function setHttpAuto() {
		$id = $this->id; // needed because submitted ID is page ID, not content ID
		$this->setAuto($_POST);
		$this->id = intval($id);
	}
	
	function getShortName() {
		if (!$this->id) {
			return '-';
		}
		if (preg_match('/^Commission/i',$this->name)) {
			return substr($this->name,10);
		} else {
			return $this->get('name');
		}
	}
	
	function setStatus($status,$userId) {
		$objStatus = new ProjectStatus();
		$objStatus->projectId = $this->id;
		$objStatus->setDtm('statusDate','NOW');
		$objStatus->statusKey = $status;
        $objStatus->member->id = $userId;
		return $objStatus->add();
	}

    function pPosition($default='-') {
		$str = $this->memberProject->getPosition();
        print ($str)?$str:$default;
	}
	
	function check() {
		return $this->checkEmpty('name');
	}
	
	function add($status,$userId) {
		if (parent::add()) {
            // add poroject initial status
			if ($this->setStatus($status,$userId)) {
                // add user as project leader
                $objLeader = new MemberProject();
                $objLeader->initObjectProperties();
                $objLeader->project->id = $this->id;
                $objLeader->member->id = $userId;
                $objLeader->position = FRK_PROJECT_LEADER; // leader
                return $objLeader->add();
            } else {
                // -TODO- rollback
                return false;
            }
		} else {
			return false;
		}
	}
	
	function delete() {
		if ($this->id) {
			$this->getConnection();
			if (@constant('FRK_MYSQL_VERSION_GT_4_1')) {
				// mySQL 4.1 and later (SQL subqueries allowed)
				return $this->query('DELETE '.$this->gTable('project').', '.$this->gTable('memberProject').', '
					.$this->gTable('projectStatus').', '.$this->gTable('item').', '.$this->gTable('itemComment')
					.', '.$this->gTable('itemFile').', '.$this->gTable('itemStatus')
					.' FROM '.$this->gTable('project')
					.' INNER JOIN '.$this->gTable('memberProject').' ON '.$this->gTable('memberProject').'.projectId = '.$this->gTable('project').'.projectId'
					.' LEFT JOIN '.$this->gTable('projectStatus').' ON '.$this->gTable('projectStatus').'.projectId = '.$this->gTable('project').'.projectId'
					.' LEFT JOIN '.$this->gTable('item').' ON '.$this->gTable('item').'.projectId = '.$this->gTable('project').'.projectId'
					.' LEFT JOIN '.$this->gTable('itemComment').' ON '.$this->gTable('itemComment').'.itemId = '.$this->gTable('item').'.itemId'
					.' LEFT JOIN '.$this->gTable('itemFile').' ON '.$this->gTable('itemFile').'.itemId = '.$this->gTable('item').'.itemId'
					.' LEFT JOIN '.$this->gTable('itemStatus').' ON '.$this->gTable('itemStatus').'.itemId = '.$this->gTable('item').'.itemId'
					.' WHERE '.$this->gTable('project').'.projectId = '.$this->id);
			} else {
				// mysql 3.23 and 4.0 -TODO- transactions
				// 1. delete project
				if (parent::delete()) {
					// 2. delete members
					$this->query('DELETE FROM '.$this->gTable('memberProject').' WHERE projectId='.$this->id);
					// 3. search project tasks
					if ($objResult = $this->query('SELECT itemId FROM '.$this->gTable('item')
						.' WHERE projectId='.$this->id))
					{
						$arrIds = array();
						while($objItem = $objResult->rNext()) {
							$arrIds[] = $objItem->itemId;
						}
						if (count($arrIds)) {
							$strIds = implode(',',$arrIds);
							// 3.1. delete task comments
							$this->query('DELETE FROM '.$this->gTable('itemComment')
								.' WHERE itemId IN ('.$strIds.')');
							// 3.2 delete task files
							$this->query('DELETE FROM '.$this->gTable('itemFile')
								.' WHERE itemId IN ('.$strIds.')');
							// 3.3. delete task status
							$this->query('DELETE FROM '.$this->gTable('itemStatus')
								.' WHERE itemId IN ('.$strIds.')');
						}
						// 3.4. delete tasks
						$this->query('DELETE FROM '.$this->gTable('item').' WHERE projectId='.$this->id);
						// 4. delete status history
						$this->query('DELETE FROM '.$this->gTable('projectStatus').' WHERE projectId='.$this->id);
						return true;
					} // end search
				}
			}
		}
		return false;
	}
}

class ProjectStats extends Project
{

	function ProjectStats() {
		parent::Project();
		$this->addProperties(array(
			'projectStatus'	=> 'OBJ',
            'memberProject' => 'OBJ'
		));
	}

    function pStatus() {
		print $this->projectStatus->getStatus();
	}

    function _cleanProperties() {
        unset($this->_properties['projectStatus']);
        unset($this->_properties['memberProject']);
        unset($this->projectStatus);
        unset($this->memberProject);
    }

    function add($status,$userId) {
        $this->_cleanProperties();
        return parent::add($status,$userId);
    }
	
    function update($param='') {
        $this->_cleanProperties();
        parent::update($param);
    }

    function load($userId, $strict=true) {
        if (!$this->id) {
            return false;
        }
        if (@constant('FRK_MYSQL_VERSION_GT_4_1')) {
            // optimize for mysql > 4.1
            $sqlSelect = 'SELECT pp.*, ps.statusKey AS projectStatus_statusKey, '
                .'p1.position AS memberProject_position, ps.statusDate AS projectStatus_statusDate '
                .'FROM '.$this->gTable().' AS pp '
                .'INNER JOIN '.$this->gTable('projectStatus').' AS ps ON ps.projectId = pp.projectId '
                .(($strict)?'INNER':'LEFT').' JOIN '.$this->gTable('memberProject')
                .' AS p1 ON p1.projectId=pp.projectId AND p1.memberId='.$userId
                .' WHERE ps.statusDate=(SELECT MAX(ps2.statusDate) FROM '.$this->gTable('projectStatus')
                .' AS ps2 WHERE pp.projectId = ps2.projectId) '
                .' AND ps.projectId = '.$this->id
                .' GROUP BY ps.projectId';
        } else {
            $sqlSelect = 'SELECT pp.*, p1.position AS memberProject_position, '
                .'SUBSTRING(MAX(CONCAT(ps.statusDate,ps.statusKey)),1,19) AS projectStatus_statusDate, '
                .'SUBSTRING(MAX(CONCAT(ps.statusDate,ps.statusKey)),20) AS projectStatus_statusKey '
                .'FROM '.$this->gTable().' AS pp '
                .'INNER JOIN '.$this->gTable('projectStatus').' AS ps ON ps.projectId = pp.projectId '
                .(($strict)?'INNER':'LEFT').' JOIN '.$this->gTable('memberProject')
                    .' AS p1 ON p1.projectId=pp.projectId AND p1.memberId='.$userId
                .' WHERE ps.projectId = '.$this->id
                .' GROUP BY ps.projectId';
        }

        //echo '<div class="debug">'.$sqlSelect.'</div>'; exit;
        
        return $this->loadByQuery($sqlSelect);
    }
	
	function loadList($userId, $strict=true) {
        $sqlCommon = 'FROM '.$this->gTable().' AS pp '
            .'INNER JOIN '.$this->gTable('projectStatus').' AS ps ON ps.projectId = pp.projectId '
            .(($strict)?'INNER':'LEFT').' JOIN '.$this->gTable('memberProject')
            .' AS p1 ON p1.projectId=pp.projectId AND p1.memberId='.$userId;
        if (@constant('FRK_MYSQL_VERSION_GT_4_1')) {
            // optimize for mysql > 4.1
            $sqlCount = 'SELECT COUNT(DISTINCT pp.projectId) as rowCount, ps.statusDate AS projectStatus_statusDate, '.$sqlCommon;
            $sqlSelect = 'SELECT pp.*, ps.statusKey AS projectStatus_statusKey, '
                .'p1.position AS memberProject_position, ps.statusDate as projectStatus_statusDate '.$sqlCommon;
            $this->addWhere('ps.statusDate=(SELECT MAX(ps2.statusDate) FROM '.$this->gTable('projectStatus')
                .' AS ps2 WHERE pp.projectId = ps2.projectId)');
        } else {
            $sqlCount = 'SELECT COUNT(DISTINCT pp.projectId) as rowCount '.$sqlCommon;
            $sqlSelect = 'SELECT pp.*, p1.position AS memberProject_position, '
                .'SUBSTRING(MAX(CONCAT(ps.statusDate,ps.statusKey)),1,19) AS projectStatus_statusDate, '
                .'SUBSTRING(MAX(CONCAT(ps.statusDate,ps.statusKey)),20) AS projectStatus_statusKey '
                .$sqlCommon;
        }
        $this->addGroup('ps.projectId');

		return parent::loadList($sqlCount,$sqlSelect);
	}

}

class ProjectStatsFull extends ProjectStats
{

	function ProjectStatsFull() {
		parent::ProjectStats();
		$this->addProperties(array(
			'memberCount'	=> 'NUM',
			'itemCount'		=> 'NUM'
		));
	}

    function pStatus() {
		print $this->projectStatus->getStatus();
	}
	
	function loadList($userId, $strict=true) {
        $sqlCommon = 'FROM '.$this->gTable().' AS pp '
            .'INNER JOIN '.$this->gTable('projectStatus').' AS ps ON ps.projectId = pp.projectId '
            .(($strict)?'INNER':'LEFT').' JOIN '.$this->gTable('memberProject')
            .' AS p1 ON p1.projectId=pp.projectId AND p1.memberId='.$userId
			.' LEFT JOIN '.$this->gTable('memberProject').' AS p2 ON p2.projectId=pp.projectId'
			.' LEFT JOIN '.$this->gTable('item').' AS i1 ON i1.projectId=pp.projectId';
        if (@constant('FRK_MYSQL_VERSION_GT_4_1')) {
            // optimize for mysql > 4.1
            $sqlCount = 'SELECT COUNT(DISTINCT pp.projectId) as rowCount, ps.statusDate as projectStatus_statusDate '.$sqlCommon;
			$sqlSelect = 'SELECT pp.*, COUNT(DISTINCT p2.memberId) AS memberCount, ps.statusKey AS projectStatus_statusKey, '
				.'COUNT(DISTINCT i1.itemId) AS itemCount, '
                .'p1.position AS memberProject_position, ps.statusDate as projectStatus_statusDate '.$sqlCommon;
            $this->addWhere('ps.statusDate=(SELECT MAX(ps2.statusDate) FROM '.$this->gTable('projectStatus')
                .' AS ps2 WHERE pp.projectId = ps2.projectId)');
        } else {
            $sqlCount = 'SELECT COUNT(DISTINCT pp.projectId) as rowCount '.$sqlCommon;
			$sqlSelect = 'SELECT pp.*, COUNT(DISTINCT p2.memberId) AS memberCount, p1.position AS memberProject_position, '
				.'COUNT(DISTINCT i1.itemId) AS itemCount, '
                .'SUBSTRING(MAX(CONCAT(ps.statusDate,ps.statusKey)),1,19) AS projectStatus_statusDate, '
                .'SUBSTRING(MAX(CONCAT(ps.statusDate,ps.statusKey)),20) AS projectStatus_statusKey '
                .$sqlCommon;
        }
        $this->addGroup('ps.projectId');

		return Project::loadList($sqlCount,$sqlSelect);
	}

}

class ProjectPositionList extends TznCollection
{
    function ProjectPositionList($maxlevel=100) {
        $arrData = $GLOBALS['langProjectPosition'];
        if ($maxlevel < 100) {
            foreach($arrData as $key=>$value) {
                if ($key> $maxlevel) {
                    unset($arrData[$key]);
                }
            }
        }
		parent::TznCollection($arrData);
    }

    function pJSarray() {
        echo "\n<script>var arrPositions = new Array('";
        echo implode("','",$this->_data);
        echo "');</script>\n";
    }

}

class MemberProject extends TznDb 
{

	function MemberProject() {
		parent::TznDb('memberProject');
		$this->addProperties(array(
			'project' 			=> 'OBJ',
			'member'			=> 'OBJ',
			'position'			=> 'NUM' // 0 request, 1 member, 2 official, 3 pro, 4 leader
		));
	}
	
	function getMemberId() {
		return $this->member->id;
	}
	
	function getMemberName() {
		return $this->member->getName();
		//return $this->member->get('firstName').' '.substr($this->member->get('lastName'),0,1).'.';
	}

	function getPosition() {
		return $GLOBALS['langProjectPosition'][$this->position];
	}
	
	function pPosition() {
		echo $this->getPosition();
	}

    function checkRights($level) {
        $level--;
        return ($GLOBALS['confProjectRights'][$this->position]{$level} == '1');
    }

    function loadPosition($projectId,$memberId) {
        $table = $this->gTable('memberProject');
        return $this->loadByFilter($table.'.projectId='.$projectId
			.' AND '.$table.'.memberId='.$memberId);
    }
	
	function add() {
		if (!$this->project->id || !$this->member->id) {
			return false;
		}
		$this->getConnection();
		if ($this->loadByFilter($this->gTable('memberProject').'.projectId='.$this->project->id
			.' AND '.$this->gTable('memberProject').'.memberId='.$this->member->id)) 
		{
			// already in project
			return false;
		} else {
			return parent::add();
		}
	}
	
	function update($fields=null) {
		parent::update($fields,'projectId='.$this->project->id
			.' AND memberId='.$this->member->id);
	}
	
	function delete() {
		if ($this->project->id && $this->member->id) {
			$this->getConnection();
			return $this->query('DELETE FROM '.$this->gTable()
				.' WHERE projectId='.$this->project->id
				.' AND memberId='.$this->member->id);
		} else {
			return false;
		}
	}

}