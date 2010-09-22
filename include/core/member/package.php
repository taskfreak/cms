<?php

class MemberController extends TznController
{

	function MemberController() {
		parent::TznController('member');
	}
	
	function main() {
	
		// set referring page
		TznCms::setAdminRef();
		
		/* === PREPARE DATA ======================================================= */
		
		$GLOBALS['objCms']->submitScript = 'admin/member.php';
		
		if ($_REQUEST['id']) {
		
			// load user account
			$this->actionEdit();
			
		} else {
			
			// load member list
			$this->actionList();
			
		}
	
	}
	
	function actionList() {
	
		$this->objMemberList = new Member();
		
		// search by keyword
		
		if ($pKeyword = TznUtils::getHttpParameter('request','userSearch',true,true)) {
			$pKeyword = '%'.str_replace(' ','%',$pKeyword).'%';
			$this->objMemberList->addWhere("(CONCAT(".$this->objMemberList->gField('firstName')
				.",' ',".$this->objMemberList->gField('lastName')
				.") LIKE '$pKeyword' OR ".$this->objMemberList->gField('username')
				." LIKE '$pKeyword')");
		}
		
		// order list
		
		$this->memberDate = 'lastLoginDate';
		$pSort = intval(TznUtils::getHttpParameter('request','userOrder',true,true));
		switch($pSort) {
			case 2:
				$this->memberDate = 'creationDate';
				$this->objMemberList->addOrder('creationDate DESC');
				break;
			case 1:
				$this->objMemberList->addOrder('level DESC, lastName ASC');
				break;
			default:
				$this->objMemberList->addOrder('lastLoginDate DESC');
				break;
		}
		
		// page list
		
		$pPage = intval(TznUtils::getHttpParameter('request','userPage',true,true));
		
		// pagination and load
		
		$this->objMemberList->setPagination(20,$pPage);
		$this->objMemberList->loadList();
	
	}
	
	function actionEdit() {

		$this->objMember = new Member();
		$this->arrPlugins = array();
		
		$this->objMember->setUid(intval($_REQUEST['id']));
		
		// try to load user
		if ($this->objMember->load()) {
			
			// --- CHECK ACCESS RIGHTS -------
			
			if ((!$GLOBALS['objUser']->hasAccess(14))
				&& ($GLOBALS['objUser']->id != $this->objMember->author->id)
				&& $GLOBALS['objUser']->level < $this->objMember->level) 
			{
		        // only admin or author and if of higher level
		        // can load specific user
		        // otherwise user edits its own profile
		        $this->objMember =& $GLOBALS['objUser'];
		        
		    } else // can delete only if authorized
			
			// --- DELETE USER -------------------------------------------------
			
			if ($_REQUEST['mode'] == 'delete') {
				if ($GLOBALS['objUser']->hasAccess(14) && $GLOBALS['objUser']->level > $this->objMember->level) {
		            $this->objMember->delete();
				}
				TznUtils::redirect(TznUtils::getReferrer(true,true),$GLOBALS['langMessage']['data_deleted']);
		    }
		    
		} else {
			
			if (intval($_REQUEST['id'])) {
				// user id supplied but user not found	
				$GLOBALS['objCms']->message = 'ERROR:'.$GLOBALS['langMessage']['not_found_or_denied'];
			}
			
			if ($GLOBALS['objUser']->hasAccess(15)) {
		        // user is administrator and tries to create new user
		        $this->objMember->initObjectProperties();
		        $this->objMember->country->id = $GLOBALS['objCms']->settings->get('default_country');
		        $this->objMember->activation = '0123456789ABCDEF'; // needed to send confirmation email
		    } else {
		        // user is editing himself
		        $this->objMember =& $GLOBALS['objUser'];
		    }
		    
		}
		
		// --- PREPARE FORM STUFF -----------------------------------------------
		
		if ($this->objMember->isLoaded()) {
			$GLOBALS['objCms']->initSubmitting(1,2);
		} else if ($GLOBALS['objUser']->isLoggedIn()) {
			$GLOBALS['objCms']->initSubmitting(2,3);
		} else {
			$GLOBALS['objCms']->initSubmitting(1);
		}
		
		
		// --- SUBMIT USER DATA -------------------------------------------------
		    
		if ($GLOBALS['objCms']->submitMode) {
		
		    $pActivated = $pActivationNeeded = false;
		    $pLevel = $this->objMember->level;
		    
			$this->objMember->setDetails($_POST);
			$this->objMember->setUid(intval($_REQUEST['id']));
			
		    if (($this->objMember->id != $GLOBALS['objUser']->id) 
		    	&& ($GLOBALS['objUser']->hasAccess(12))) 
		    {
		        // check requested level is not higher than user's own level
		        $pLevel = min($GLOBALS['objUser']->level,$_POST['level']);
		    }
		
		    $this->objMember->level = $pLevel;
		
			if ($_POST['enabled'] && $GLOBALS['objUser']->hasAccess(15)) {
				// Enable account
				$this->objMember->enabled = 1;
				
				// activate account (if necessary)
				if ($this->objMember->activation && $this->objMember->email) {
					$pActivationNeeded = true;
				}
				
			} else if ($this->objMember->id != $GLOBALS['objUser']->id) {
			
				$this->objMember->enabled = 0;
				
			}
			
			if ($this->objMember->check($_POST['password1'],$_POST['password2'],false,true)) {
				
				$pMessage = '';
				
		        if ($this->objMember->isLoaded()) {
		    		if ($this->objMember->update()) {
		    			$pMessage = $GLOBALS['langUserContent']['infosaved'];
		    		}
		        } else {
		        	$this->objMember->author->id = $GLOBALS['objUser']->id;
		            if ($this->objMember->add($GLOBALS['objUser']->id)) {
		            	$pMessage = $GLOBALS['langUserContent']['infocreated'];
		            }
		        }
		        
		        if (!$pMessage) {
		        	// looks like no message confirming saving in DB has been set
		        	// which means something went wrong
		        	$pMessage = $GLOBALS['langTznCommon']['operation_failed'];
		        	
		        } else if ($pActivationNeeded) {
		        
		        	// activate and send confirmation email
					if ($this->objMember->activateAccount()) {
						// message sent successfully
						$pActivated = true;
						$pMessage .= "<br />".$GLOBALS['langTznCommon']['confirmation_email_sent'];
					} else {
						// error sending email
						$pMessage .= "<br />".$GLOBALS['langTznCommon']['confirmation_email_error'];
					}
		        	
		        }
		        
		        switch ($GLOBALS['objCms']->submitMode) {
		        	case 3:
						TznUtils::redirect(TznUtils::getReferrer(false),$pMessage);
		        	default:
						TznUtils::redirect(TznUtils::getReferrer(true,true),$pMessage);
				}
				
			} else {
				
				TznUtils::addMessage('ERROR:'.$GLOBALS['langTznCommon']['form_error']);
				
				//$this->objMember->printErrorList();
				
			}
			
		}
		
		// --- LOAD TEAM LIST --------------
		
		$this->objTeamList = new TeamStats();
		if ($this->objMember->id) {
			$this->objTeamList->loadList($this->objMember->id,true);
		}
		
		// --- LOAD COUNTRIES --------------
		
		$this->objCountryList = new Country();
		$this->objCountryList->addOrder('name');
		$this->objCountryList->loadList();
		
		// --- LOAD STATES ------------------
		
		$this->objStateList = new UsState();
		
		// --- LOAD CMS LANGUAGES -----------
		
		$this->objLanguageList = new CmsLanguage();
		
		// --- LOAD POSITIONS / RIGHTS ------
		
		$this->objPositionList = new GlobalPositionList($GLOBALS['objUser']->level);
		
		// --- GET PLUGINS DATA -------------------------------------------------
		
		if (count($GLOBALS['tznPlugins']['member'])) {
			foreach(array_keys($GLOBALS['tznPlugins']['member']) as $folder) {
				$class = 'Plugin'.TznUtils::strToCamel($folder,true);
				$objPlugin = new $class();
				$objPlugin->main($this->objMember);
				$this->arrPlugins[$folder] = $objPlugin;
			}
		}
		
		$this->setView('view_edit');
		
	}
}