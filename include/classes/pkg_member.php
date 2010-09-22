<?php
/****************************************************************************\
* TZN CMS                                                                    *
******************************************************************************
* Version: 0.1                                                               *
* Authors: Stan Ozier <stan@tirzen.com>                                      *
* License:  http://www.gnu.org/licenses/gpl.txt (GPL)                        *
******************************************************************************
* This file is part of "TZN CMS" program.                                    *
*                                                                            *
* TZN CMS is free software; you can redistribute it and/or                   *
* modify it under the terms of the GNU General Public License as published   *
* by the Free Software Foundation; either version 2 of the License, or (at   *
* your option) any later version.                                            *
*                                                                            *
* TZN CMS is distributed in the hope that it will be                         *
* useful, but WITHOUT ANY WARRANTY; without even the implied warranty of     *
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the              *
* GNU General Public License for more details.                               *
*                                                                            *
* You should have received a copy of the GNU General Public License          *
* along with this program; if not, write to the Free Software                *
* Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA *
\****************************************************************************/

define('CMS_USER_LEVEL_ADMIN',4);
define('CMS_USER_LEVEL_MODERATOR',3);
define('CMS_USER_LEVEL_MEMBER',2);
define('CMS_USER_LEVEL_GUEST',1);
define('CMS_USER_LEVEL_NONE',0);

class GlobalPositionList extends TznCollection
{
    function GlobalPositionList($maxlevel=100) {
        $arrData = $GLOBALS['langGlobalPosition'];
        if ($maxlevel < 100) {
            foreach($arrData as $key=>$value) {
                if ($key> $maxlevel) {
                    unset($arrData[$key]);
                }
            }
        }
		parent::TznCollection($arrData);
    }

    function getPosition($level) {
        if (array_key_exists($level, $GLOBALS['confGlobalRights'])) {
            return $this->_data[$level];
        } else {
            return '???';
        }
    }

}

class TeamPositionList extends TznCollection
{
    function TeamPositionList($maxlevel=100) {
        $arrData = $GLOBALS['langTeamPosition'];
        if ($maxlevel < 100) {
            foreach($arrData as $key=>$value) {
                if ($key> $maxlevel) {
                    unset($arrData[$key]);
                }
            }
        }
		parent::TznCollection($arrData);
    }

    function getPosition($level) {
        if (array_key_exists($level, $GLOBALS['confTeamRights'])) {
            return $this->_data[$level];
        } else {
            return '???';
        }
    }
    
    function checkRights($level) {
    	$level--;
    	return ($GLOBALS['confTeamRights'][$this->position]{$level} == '1');
    }
    
    function getLeaderPosition() {
    	$len = count($this->_data);
    	return $this->_data[$len];
    }

}

class Member extends TznUser 
{
	
	var $_teamList;

	function Member() {
		parent::TznUser('member');
		$this->addProperties(array(
			'email'				=> 'EML',
			'title'				=> 'STR',
			'firstName'			=> 'STR',
			'middleName'		=> 'STR',
			'lastName'			=> 'STR',
			'nickName'			=> 'STR',
			'avatar'			=> 'IMG,(w:300,h:300,f:gallery/),(w:60,h:60,f:gallery/thumbs/)',
			'companyName'		=> 'STR',
			'address'			=> 'TXT',
			'city'				=> 'STR',
			'zipCode'			=> 'STR',
			'stateCode'			=> 'STR',
			'country'			=> 'OBJ',
			'cmsLanguage'		=> 'STR',
            'author'            => 'OBJ'
		));
        $this->_properties['level'] = 'NUM';
        $this->level = -1;
        $this->country->id = 'FR';
	}
	
	/* --- INFORMATION -------------------------------------------------------- */
	
	function getAvatar() {
		$img = '/files/user-thumb.png';
		if ($this->avatar) {
			$img = $this->getImgUrl('avatar','',2);
		}
		return '<img src="'.$img.'" alt="" class="avatar" />';
	}
    	
	function getShortName($default='') {
		$str = '';
		if ($this->nickName) {
			$str = $this->get('nickName');
		} else {
            $str = $this->get('firstName');
            if ($this->lastName) {
                $str .= ' '.substr($this->lastName,0,1).'.';
            }
		}
		return ($str)?$str:$default;
	}
	
	function getName() {
		$str .= $this->firstName;
		if ($this->middleName) {
			$str .= ' '.substr($this->middleName,0,1).'.';
		}
		$str .= ' '.$this->lastName;
		return $str;
	}
	
	function getLocation($arrCountry = null) {
		$str = $this->city;
		$str .= ($this->stateCode)?' ('.$this->stateCode.')':'';
        if ($str) {
            $str.= ', ';
        }
		if (is_array($arrCountry)) {
			$str .= $arrCountry[$this->country->id];
		} else if ($this->country->name) {
			$str .= $this->country->name;
		} else {
			$str .= $this->country->id;
		}
		return $str;
	}
	
	function getPostalAddress() {
		$str = $this->firstName;
		if ($this->middleName) {
			$str .= ' '.substr($this->middleName,0,1).'.';
		}
		$str .= ' '.$this->lastName;
		if ($this->companyName) {
			$str .= "\n".$this->companyName;
		}
		$str .= "\n".$this->address
			."\n".$this->city." ".$this->zipCode;
		
		if ($this->stateCode) {
			$str .= "\n".$this->stateCode;
		}
		
		$str .= "\n".$this->country->name;
		
		return $str;
	}

	function getIcon() {
		$img =  CMS_WWW_URI.'assets/images/i_user_';
		switch ($this->getLevelCode()) {
			case CMS_USER_LEVEL_ADMIN:
				$img .= 'admin';
				break;
			case CMS_USER_LEVEL_MODERATOR:
				$img .= 'moderator';
				break;
			case CMS_USER_LEVEL_MEMBER:
				$img .= 'member';
				break;
			case CMS_USER_LEVEL_GUEST;
				$img .= 'guest';
				break;
			default:
				$img .= 'off';
				break;
		}
		$img .= '.png';
		return $img;
	}
	
	function pIcon() {
		echo '<img src="'.$this->getIcon().'" title="'.$GLOBALS['langGlobalPosition'][$this->level].'" />';
	}
	
	/* --- SETTING USER DETAILS ----------------------------------------------- */
	
	function setDetails(&$data, $complete=true) {
		$login = $this->get(TZN_USER_LOGIN);
		parent::setAuto($data);
		if (!$complete) {
			// keep old login, just change details
			// (secure data)
			$this->set(TZN_USER_LOGIN, $login);
		}
		$this->firstName = ucWords(strtolower($this->firstName));
		$this->middleName = ucWords(strtolower($this->middleName));
		$this->lastName = ucWords(strtolower($this->lastName));
	}
	
	function checkNoAccount($complete=false, $plugins=false) {
		$arrChk[] = $this->checkEmpty('firstName,lastName'.(($complete)?',email,country':''));
		if ($this->country->id == 'US') {
			$arrChk[] = $this->checkEmpty('stateCode');
		}
		// check plugins (if any)
		if ($plugins) {
			$arrChk = array_merge($arrChk, $this->callPlugins('check', $complete));
		}
        foreach ($arrChk as $chk) {
			if (!$chk) {
				return false;
			}
		}
		return true;
	}
	
	function check($pass1, $pass2, $complete=false, $plugins=false) {
		$arrChk = array();
		// check compulsory fields
		//$check1 = $this->checkEmpty('firstName,lastName,email,country');
		$arrChk[] = $this->checkEmpty('firstName,lastName'.(($complete)?',email,country':''));
		if ($this->country->id == 'US') {
			$arrChk[] = $this->checkEmpty('stateCode');
		}
		// check unique email
        if ($this->email) {
            $chk = !$this->checkUnique('email',$this->email);
            if (!$chk) {
                $this->e('email',$GLOBALS['langTznUser']['email_exists']);
                $arrChk[] = false;
            }
        }

        if ($this->enabled || $complete) {
        	// check unique login
	        $arrChk[] = $this->setLogin();
            // check and set valid password
            $arrChk[] = $this->setPassword($pass1, $pass2, false, $this->isLoaded());
        }
        
        // check plugins
       	if ($plugins && count($this->_plugins)) {
			$arrChk = array_merge($arrChk, $this->callPlugins('check', $complete));
		}

		// var_dump($arrChk);
		
		foreach ($arrChk as $chk) {
			if (!$chk) {
				return false;
			}
		}
		return true;
	}
	
	/* --- USER ACCESS / RIGHTS ----------------------------------------------- */
	
	function getLevelCode() {
		if ($this->enabled) {
			$maxlevel = count($GLOBALS['confGlobalRights'])-1;
			switch (intval($this->level)) {
			case $maxlevel:
				return CMS_USER_LEVEL_ADMIN;
			case $maxlevel-1:
				return CMS_USER_LEVEL_MODERATOR;
			case 0:
				return CMS_USER_LEVEL_GUEST;
			default:
				return CMS_USER_LEVEL_MEMBER;
			}
		} else {
			return CMS_USER_LEVEL_NONE;
		}
		
	}
	
	function hasAccess($right, $module='', $authorId=0, $redirect=false) {
	
		if (!$right) {
			return true;
		}
		
		$level = intval($this->level);
	
		$right--;
		$check = false;
		
		if ($this->isLoggedIn() && $this->level >= 0) {
			if ($module) {
				// check module specific rights
				$check = ($GLOBALS['confModuleRights'][$module][$level]{$right} == '1');
			} else {
				// check global rights
				$check = ($GLOBALS['confGlobalRights'][$level]{$right} == '1');
			}
			if ($authorId && $this->id == $authorId) {
				// user is author of the item : permission always allowed
				$check = true;
			}
		}
		
		if (!$check && $redirect) {
			$loginUrl = CMS_WWW_URI.'login.php?ref='
				.rawurlencode($_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING']);
			//ereg('log(in|out|ister|minder)\.php',$_SERVER['PHP_SELF'])
			TznUtils::redirect($loginUrl, 'ERROR:#session_denied');
		}
		return $check;
	}

    function checkAccess($right, $module='', $authorId=0) {
        return $this->hasAccess($right, $module, $authorId, true);
    }
	
	/* --- USER PAGES AND TEAMS ----------------------------------------------- */
	
	function initUserPages() {
		if (!$this->isLoggedIn() || !$this->id || !constant('CMS_TEAM_ENABLE')) {
			// user not logged in, don't bother
			return false;
		}
		if (is_array($_SESSION['cmsUserPages'])) {
			// already initialized
			return true;
		}
		$_SESSION['cmsUserPages'] = array();
		$objUserPages = new PageTeamStats();
		if ($objUserPages->loadUserPages($this->id)) {
			while ($objTmp = $objUserPages->rNext()) {
				$_SESSION['cmsUserPages'][$objTmp->page->id] = $objTmp->position;
			}
			$objUserPages->rFree();
		}
	}
	
	function getUserPages() {
		if (is_array($_SESSION['cmsUserPages']) && count($_SESSION['cmsUserPages'])) {
			return array_keys($_SESSION['cmsUserPages']);
		} else {
			return false;
		}
	}
	
	function getUserPagesSql() {
		if ($arr = $this->getUserPages()) {
			if (count($arr) > 1) {
				return 'IN ('.implode(',',$arr).')';
			} else {
				return '= '.array_pop($arr);
			}
		}
		return false;
	}
	
	function checkUserPagesRights($pageId, $level) {
		if (is_array($_SESSION['cmsUserPages']) && count($_SESSION['cmsUserPages'])) {
			if (array_key_exists($pageId, $_SESSION['cmsUserPages'])) {
				$position = $_SESSION['cmsUserPages'][$pageId];
				$level--;
		        return ($GLOBALS['confTeamRights'][$position]{$level} == '1');
			}
		}
		return false;
	}
	
	function loadTeamIds($position=null) {
		$this->_teamList = array();
		$objTeamList = new MemberTeam();
		if ($this->id) {
			$objTeamList->addWhere($objTeamList->gTable().'.memberId='.$this->id);
			if (!is_null($position)) {
				$objTeamList->addWhere('position >= '.$position);
			}
			if ($objTeamList->loadList()) {
				$i=0;
				while ($objTeam = $objTeamList->rNext()) {
					$this->_teamList[$i++] = $objTeam->team->id;
				}
				$objTeamList->rFree();
				return $i;
			} else {
				return 0;
			}
		} else {
			return false;
		}
	}
	
	/* --- CRUDS METHODS ------------------------------------------------------ */

    function delete() {
        if ($this->id) {
			// 1. delete member
			if (parent::delete()) {
                // 2. delete group relations
				$this->query('DELETE FROM '.$this->gTable('memberTeam').' WHERE memberId='.$this->id);
                /* 3. unassociate tasks
                $this->query('UPDATE '.$this->gTable('item').' SET memberId=0 WHERE memberId='.$this->id);
                // 4. delete comments
				$this->query('DELETE FROM '.$this->gTable('itemStatus').' WHERE memberId='.$this->id);
				 */
                // 5. unassociate authors
                $this->query('UPDATE '.$this->gTable('member').' SET authorId=1 WHERE authorId='.$this->id);
                return true;
            }
        }
        return false;
    }

    function loadNonMemberList($projectId) {
        $this->addWhere('mp.memberId IS NULL');
        return parent::loadList('SELECT mm.* FROM '.$this->gTable().' as mm '
            .'LEFT JOIN '.$this->gTable('memberProject').' as mp '
            .'ON mm.memberId = mp.memberId AND mp.projectId = '.$projectId);
    }

}

class Author extends Member {

    function Author() {
        parent::Member();
    }

}

class TeamVisibility extends TznCollection
{
	function TeamVisibility() {
		parent::TznCollection(array(
			0	=> $GLOBALS['langTeam']['visi_public'],
			1	=> $GLOBALS['langTeam']['visi_protected'],
			2	=> $GLOBALS['langTeam']['visi_private']
		));
	}
}

class Team extends TznDb
{

	function Team() {
		parent::TznDb('team');
		$this->addProperties(array(
			'id'			=> 'UID',
			'name'			=> 'STR',
			'description'	=> 'BBS',
			'visibility'	=> 'NUM',
				// 0: public (everybody can see)
				// 1: protected (site member only)
				// 2: private (associated member only)
			'enabled'		=> 'BOL'
		));
	}
	
	function getIcon() {
		$img =  CMS_WWW_URI.'assets/images/i_team_';
		if ($this->enabled) {
			switch (intval($this->visibility)) {
			case 2:
				$img .= 'prvt';
				break;
			case 1:
				$img .= 'prtd';
				break;
			default:
				$img .= 'pblc';
				break;
			}
		} else {
			$img .= 'off';
		}
		$img .= '.png';
		return $img;
	}
	
	function getVisibility() {
		$str = '';
		if ($this->enabled) {
			switch (intval($this->visibility)) {
				case 2:
					$str = $GLOBALS['langTeam']['visi_private'];
					break;
				case 1:
					$str = $GLOBALS['langTeam']['visi_protected'];
					break;
				default:
					$str = $GLOBALS['langTeam']['visi_public'];
					break;
			}
		} else {
			$str = $GLOBALS['langTeam']['visi_disabled'];
		}
		return $str;
	}
	
	function check() {
		return $this->checkEmpty('name');
	}
	
	function add($leaderId) {
		// 1. add team to db
		if ($teamId = parent::add()) {
			// add leader to association table
			$objLeader = new MemberTeam();
			$objLeader->member->id = $leaderId;
			$objLeader->team->id = $teamId;
			$objLeader->position = count($GLOBALS['confTeamRights']) - 1;
			return $objLeader->add();
		}
	}
	
}

class TeamStats extends Team
{
	
	function TeamStats() {
		parent::Team();
		$this->addProperties(array(
			'memberTeamCount'	=> 'NUM',
			'memberTeam'		=> 'OBJ'
		));
	}
	
	function loadList($userId, $strict=true) {
		$this->addGroup('tt.teamId');
		return parent::loadList('SELECT tt.*, COUNT(mm.memberId) as memberTeamCount, '
			.' pp.position as memberTeam_position'
			.' FROM '.$this->gTable().' AS tt'
			.' LEFT JOIN '.$this->gTable('memberTeam').' AS mm ON mm.teamId=tt.teamId '
			.(($strict)?'INNER':'LEFT').' JOIN '.$this->gTable('memberTeam')
			.' AS pp ON pp.teamId=tt.teamId AND pp.memberId='.$userId);
	}
	
}
class MemberTeam extends TznDb
{

	function MemberTeam() {
		parent::TznDb('memberTeam');
		$this->addProperties(array(
			'member'	=> 'OBJ',
			'team'		=> 'OBJ',
			'position'	=> 'NUM'
		));
	}
	
	function getPosition() {
		if (array_key_exists($this->position,$GLOBALS['langTeamPosition'])) {
			return $GLOBALS['langTeamPosition'][$this->position];
		} else {
			return '-';
		}
	}
	
	function checkRights($level) {
		$level--;
        return ($GLOBALS['confTeamRights'][$this->position]{$level} == '1');
	}
	
	function delete() {
		return parent::delete('memberId = '.$this->member->id
			.' AND teamId = '.$this->team->id);
	}
	
}

class CmsLanguage extends TznCollection
{
	function CmsLanguage() {
		parent::TznCollection($GLOBALS['confLanguageCodes']);
	}

	function loadList() {

		$this->_data = array();

		if ($handle = opendir(CMS_INCLUDE_PATH.'language/')) {

			while (false !== ($file = readdir($handle))) {
				if ($file == '.' || $file == '..') {
					continue;
				}
				$this->_data[$file] = str_replace('_',' ',$file);
			}

		   closedir($handle);
		}

	}
}

class Country extends TznDb 
{

	function Country() {
		parent::TznDb('country');
		$this->addProperties(array(
			'id'	=> 'UID',
			'name'	=> 'STR'
		));
	}

}

class CountryUsrStats extends Country
{

	function CountryUsrStats() {
		parent::Country();
		$this->addProperties(array(
			'memberCount'	=> 'NUM'
		));
	}

}

class UsState extends TznCollection
{
	
	function UsState() {
		parent::TznCollection(array(
			'AL'=>"Alabama",  
			'AK'=>"Alaska",  
			'AZ'=>"Arizona",  
			'AR'=>"Arkansas",  
			'CA'=>"California",  
			'CO'=>"Colorado",  
			'CT'=>"Connecticut",  
			'DE'=>"Delaware",  
			'DC'=>"District Of Columbia",  
			'FL'=>"Florida",  
			'GA'=>"Georgia",  
			'HI'=>"Hawaii",  
			'ID'=>"Idaho",  
			'IL'=>"Illinois",  
			'IN'=>"Indiana",  
			'IA'=>"Iowa",  
			'KS'=>"Kansas",  
			'KY'=>"Kentucky",  
			'LA'=>"Louisiana",  
			'ME'=>"Maine",  
			'MD'=>"Maryland",  
			'MA'=>"Massachusetts",  
			'MI'=>"Michigan",  
			'MN'=>"Minnesota",  
			'MS'=>"Mississippi",  
			'MO'=>"Missouri",  
			'MT'=>"Montana",
			'NE'=>"Nebraska",
			'NV'=>"Nevada",
			'NH'=>"New Hampshire",
			'NJ'=>"New Jersey",
			'NM'=>"New Mexico",
			'NY'=>"New York",
			'NC'=>"North Carolina",
			'ND'=>"North Dakota",
			'OH'=>"Ohio",  
			'OK'=>"Oklahoma",  
			'OR'=>"Oregon",  
			'PA'=>"Pennsylvania",  
			'RI'=>"Rhode Island",  
			'SC'=>"South Carolina",  
			'SD'=>"South Dakota",
			'TN'=>"Tennessee",  
			'TX'=>"Texas",  
			'UT'=>"Utah",  
			'VT'=>"Vermont",  
			'VA'=>"Virginia",  
			'WA'=>"Washington",  
			'WV'=>"West Virginia",  
			'WI'=>"Wisconsin",  
			'WY'=>"Wyoming"
		));
	}
	
}

