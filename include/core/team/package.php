<?php

class TeamController extends TznController
{

	function TeamController() {
		parent::TznController('team');
	}
	
	function main() {
	
		// set referring page
		if (!$_REQUEST['mode']) {
			TznCms::setAdminRef();
		}
		
		if ($_REQUEST['id']) {
		
			switch ($_REQUEST['mode']) {
			case 'member_add':
				$this->actionMemberAdd();
				break;
			case 'member_remove':
				$this->actionMemberRemove();
				break;
			default:
				// load user account
				$this->actionEdit();
			}
			
		} else {
			
			// load member list
			$this->actionList();
			
		}
	
	}
	
	function actionList() {
	
		$this->teamList = new TeamStats();
		$this->teamList->addOrder('name');
		$this->teamList->loadList($GLOBALS['objUser']->id,!$GLOBALS['objUser']->checkAccess(16));
		
		// $GLOBALS['objHeaders']->add('jsScript','admin_team.js');
		
		$this->positionList = new TeamPositionList($GLOBALS['objUser']->level);
	
	}
		
	function actionEdit() {

		$this->team = new Team();
	    $this->team->setUid(intval($_REQUEST['id']));
	    
	    $this->memberTeam = new MemberTeam();
	
	    // try to load tean
	    if ($this->team->load()) {
	    	
	    // === EDIT ===========================================================
	    	
	    	// --- LOAD TEAM MEMBERS ------------------------------------------
	    	
	    	$this->memberList = new MemberTeam();
	    	$this->memberList->addWhere($this->memberList->gTable().'.teamId='.$this->team->id);
	    	$this->memberList->addOrder('position DESC, firstName ASC');
	    	$this->memberList->loadList();
	    	
	    	// load current's user's position
	    	$this->memberTeam->loadByFilter($this->memberList->gTable().'.teamId='.$this->team->id
	    		.' AND '.$this->memberList->gTable().'.memberId='.$GLOBALS['objUser']->id);
	   	
	    	// --- CHECK ACCESS RIGHTS -------
	    	
	    	if (!$GLOBALS['objUser']->hasAccess(16)) {
	    		// user is not administrator
		    	if (!$this->memberTeam->isLoaded() // user is not associated to team
		    		|| !($this->memberTeam->checkRights(1) // user has the right to edit
	    			|| $this->memberTeam->checkRights(3) // user has the right to add members
	    			|| $this->memberTeam->checkRights(4) // user has the right to edit members
	    		)) {
		    		TznUtils::redirect(CMS_WWW_URI.'admin/team.php','ERROR:'
		    			.$GLOBALS['langMessage']['denied']);
		    	}
	    	}
	    	
	    	// rights to edit associated pages
	    	$pUserCanEditPages = $GLOBALS['objUser']->hasAccess(20);
	    	
	    	// --- DELETE TEAM -------------------------------------------------
	    	
	    	if ($_REQUEST['mode'] == 'delete') {
				if ($GLOBALS['objUser']->hasAccess(21) || $this->memberTeam->checkRights(1)) {
					// user is admin or team leader
		            $this->team->delete();
		            TznUtils::redirect(CMS_WWW_URI.'admin/team.php',
		            	$GLOBALS['langMessage']['data_deleted']);
				} else {
					TznUtils::redirect(CMS_WWW_URI.'admin/team.php',
						$GLOBALS['langMessage']['denied']);
				}
	        }
	        
	        // --- LOAD TEAM PAGES LIST -----------------------------------------
	        
	        if ($pUserCanEditPages) {
	        	
	        	$this->pageList = new TznPage();
				$this->pageList->loadTree();
	        	
	        	$this->teamPageList = new PageTeamStats();
	        	$this->teamPageList->addWhere($this->teamPageList->gTable()
	        		.'.teamId=\''.$this->team->id.'\'');
	        	$this->teamPageList->loadList();
	        }
	        
	    } else {
	    
	    // === NOT LOADED: NEW OR ERROR =========================================
	    	
	    	if (intval($_REQUEST['id'])) {
				// team id supplied but not found	
				$GLOBALS['objCms']->message = 'ERROR:'.$GLOBALS['langMessage']['not_found_or_denied'];
	    	}
	    	
	    	if ($GLOBALS['objUser']->hasAccess(18)) {
		        // user is administrator and tries to create new team
		        $this->team->enabled = 1;
		        $this->team->initObjectProperties();
		        $this->team->id = 'new';
		    } else {
		        // something went wrong
		        TznUtils::redirect(CMS_WWW_URI.'admin/team.php','ERROR:'
		    		.$GLOBALS['langMessage']['not_found_or_denied']);
		    }
		    
	    }
	    
	    // --- PREPARE FORM STUFF -----------------------------------------------
		
		if ($this->team->isLoaded()) {
			$GLOBALS['objCms']->initSubmitting(1,2);
		} else if ($GLOBALS['objUser']->isLoggedIn()) {
			$GLOBALS['objCms']->initSubmitting(2,3);
		} else {
			$GLOBALS['objCms']->initSubmitting(1);
		}
	    
	    // --- SUBMIT TEAM DATA -------------------------------------------------
	        
		if ($GLOBALS['objCms']->submitMode) {
		
		    $pLevel = $this->team->level;
		    
		    // --- team info ----------
		    if (!$this->team->isLoaded()
		    	|| $this->memberTeam->checkRights(1)
		    	|| $GLOBALS['objUser']->hasAccess(19))
		    {
		    	// can update info only if creating new 
		    	// or edited by leader or administrator
				$this->team->setAuto($_POST);
		    }
		    
		    if (!$this->team->isLoaded()
		    	|| $this->memberTeam->checkRights(2)
		    	|| $GLOBALS['objUser']->hasAccess(22))
		    {
		    	// enable / disable (same conditions, almost) 
				if ($_POST['enabled']) {
					$this->team->enabled = 1;
				} else {
					$this->team->enabled = 0;
				}
		    }
			
			// --- save to database ---
			if ($this->team->check()) {
				
				// --- team's members -----
				$objEditMember = new MemberTeam();
				$objEditMember->initObjectProperties();
				$objEditMember->team->id = $this->team->id;
				
				// 1. add members and change positions
				if (!empty($_POST['mbp'])) {
					foreach ($_POST['mbp'] as $key => $pos) {
						if (($this->memberTeam->checkRights(4)
							&& $this->memberTeam->position > $pos)
							|| $GLOBALS['objUser']->hasAccess(19))
						{
							// can only be edit by team leader and moderators,
							// if member has higher position,
							// or if user is administrator
							$objEditMember->member->id = $key;
							$objEditMember->position = $pos;
							$objEditMember->replace();
						}
					}
				}
				
				// 2. remove members
				if ($_POST['deletedItems']) {
					if ($this->memberTeam->checkRights(5)
						|| $GLOBALS['objUser']->hasAccess(19))
					{
						// can only be removed by team leaders and moderators
						// or if user is administrator
						$arrRem = explode(';',$_POST['deletedItems']);
						//error_log('about to remove '.$_POST['deletedItems']);
						foreach ($arrRem as $key) {
							//error_log('removing item #'.$key);
							if ($key = intval($key)) {
								$objEditMember->member->id = $key;
								$objEditMember->delete();
							}
						}
					}
				}
				
				// --- team's pages --------
				if ($pUserCanEditPages) {
					$objEditPage = new PageTeam();
					$objEditPage->teamId = $this->team->id;
					
					// 1. add pages
					if (!empty($_POST['pbp'])) {
						foreach ($_POST['pbp'] as $key => $pos) {
							$objEditPage->pageId = $key;
							$objEditPage->replace();
						}
					}
					
					// 2. remove pages
					if ($_POST['deletedPages']) {
						$arrRem = explode(';',$_POST['deletedPages']);
						//error_log('about to remove '.$_POST['deletedPages']);
						foreach ($arrRem as $key) {
							//error_log('removing item #'.$key);
							if ($key = intval($key)) {
								$objEditPage->pageId = $key;
								$objEditPage->delete();
							}
						}
					}
				}
	
				// save team info/details
				if ($GLOBALS['objUser']->checkAccess(19) || $this->memberTeam->checkRights(1)) {
					$pMessage = $GLOBALS['langTznCommon']['operation_failed'];
			        if ($this->team->isLoaded()) {
			    		if ($this->team->update()) {
			    			$pMessage = $GLOBALS['langTeamContent']['infosaved'];
			    		}
			        } else {
			            if ($this->team->add($GLOBALS['objUser']->id)) {
			            	$pMessage = $GLOBALS['langTeamContent']['infocreated'];
			            }
			        }
			        
			        switch ($GLOBALS['objCms']->submitMode) {
			        	case 3:
							TznUtils::redirect(TznUtils::getReferrer(false),$pMessage);
			        	default:
							TznUtils::redirect(TznUtils::getReferrer(true,true),$pMessage);
					}
			        
				} else {
					TznUtils::redirect(CMS_WWW_URI.'admin/team.php?',$GLOBALS['langTznCommon']['operation_denied']);
				}
				
			} else {
				
				$GLOBALS['objCms']->message = 'ERROR:'.$GLOBALS['langTznCommon']['form_error'];
				
			}
			
		}
	    
	    // --- LOAD VISIBILITY --------------
	
		$this->visibilityList = new TeamVisibility();	
		
		// --- PREPARE HTML -----------------
		
		$GLOBALS['objHeaders']->add('css',CMS_WWW_URI.'assets/css/squeezebox.css');
		$GLOBALS['objHeaders']->add('jsScript','squeezebox.js');
		
		$this->setView('view_edit');
		
	}
	
	function actionMemberAdd() {
	
		$GLOBALS['objCms']->initSubmitting(1); // save only (actually, will close the window too)

		// load team and check rights
		$this->_loadAndCheckTeam();
	    
		// load list of all positions
		$this->positionList = new TeamPositionList($GLOBALS['objUser']->level);
		
		/* === SUBMIT CHANGES ====================================================== */
		
		$this->arrMembersAdded = array();
		
		if (isset($_REQUEST['position']) && is_array($_REQUEST['member'])) {
		
			foreach ($_REQUEST['member'] as $memberId) {
				$objMember = new Member();
				$objMember->setUid($memberId);
				if ($objMember->load()) {
					// member's position list
					$objMemberTeam = new MemberTeam();
					$objMemberTeam->initObjectProperties();
					$objMemberTeam->member = $objMember;
					$objMemberTeam->team->setUid($_REQUEST['id']);
					$objMemberTeam->position = intval($_REQUEST['position']);
					$objMemberTeam->add();
					
					$this->arrMembersAdded[] = $objMemberTeam->clone4();
					
				}
			}
			
		}
		
		/* === SEARCH MEMBERS ===================================================== */
		
		if (isset($_REQUEST['searchMember'])) {
		
			// load up list of current members
			$objMemberList = new MemberTeam();
	    	$objMemberList->addWhere($objMemberList->gTable().'.teamId='.$this->team->id);
	    	$objMemberList->addOrder('position DESC, firstName ASC');
	    	$objMemberList->loadList();
	    	
	    	$this->arrMembers = array();
	    	while ($objTmp = $objMemberList->rNext()) {
	    		$this->arrMembers[$objTmp->member->id] = $objTmp->getPosition();
	    	}
		
			// search member
			$this->memberSearchList = new Member();
			$tbl = $this->memberSearchList->gTable();
			
			if ($str = Tzn::getHttp($_REQUEST['searchMember'])) {
				if (preg_match('/^".*"$/',$str)) {
					$str = str_replace('"','',$str);
				} else if (preg_match('/\*/',$str)) {
					$str = str_replace('*','%',$str);
				} else {
					$str = '%'.str_replace(' ','%',$str).'%';
				}
				$str = str_replace('\'','\'\'',$str); // double quotes for SQL
				$this->memberSearchList->addWhere("$tbl.firstName LIKE '$str' "
					."OR $tbl.lastName LIKE '$str' OR $tbl.email LIKE '$str'");
			}
			// avoid administrator
			if (!$GLOBALS['objUser']->checkAccess(25)) {
				$this->memberSearchList->addWhere($tbl.'.memberId > 1');
			}
			
			$this->memberSearchList->loadList();
		
			$this->setView('view_member_search');
			return false; // stop here
		}
		
		$this->setView('view_member');
	
	}
	
	function actionMemberRemove() {
	
		// load team and check rights
		$this->_loadAndCheckTeam();
		
		if ($this->memberTeam->checkRights(5) || $GLOBALS['objUser']->checkAccess(19))
		{
			// can only be removed by team leaders and moderators
			// or if user is administrator
			
			$objEditItem = new MemberTeam();
			$objEditItem->initObjectProperties();
			$objEditItem->team->id = $this->team->id;

			if ($memberId = intval($_REQUEST['member'])) {
				$objEditItem->member->id = $memberId;
				$objEditItem->delete();
				
				echo '<script type="text/javascript">';
				echo "$('mbt_".$objEditItem->member->id."').destroy();";
				echo '</script>';
				
			}
		}
		exit;
	}
	
	function _loadAndCheckTeam() {
	
		$this->team = new Team();
	    $this->team->setUid(intval($_REQUEST['id']));
	
	    // try to load tean
	    if ($this->team->load()) {
	    		    	
	    	// load current's user's position
	    	$this->memberTeam = new MemberTeam();
	    	$this->memberTeam->loadByFilter($this->memberTeam->gTable().'.teamId='.$this->team->id
	    		.' AND '.$this->memberTeam->gTable().'.memberId='.$GLOBALS['objUser']->id);
	   	
	    	// --- CHECK ACCESS RIGHTS -------
	    	
	    	if (!$GLOBALS['objUser']->hasAccess(16)) {
	    		// user is not administrator
		    	if (!$this->memberTeam->isLoaded() // user is not associated to team
		    		|| !($this->memberTeam->checkRights(1) // user has the right to edit
	    			|| $this->memberTeam->checkRights(3) // user has the right to add members
	    			|| $this->memberTeam->checkRights(4) // user has the right to edit members
	    		)) {
		    		TznUtils::redirect(CMS_WWW_URI.'admin/team.php','ERROR:'
		    			.$GLOBALS['langMessage']['denied']);
		    	}
	    	}
	    	
	    }
	}
}