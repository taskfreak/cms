<?php

class ModuleTaskfreak extends TznModule
{

	function ModuleTaskfreak() {
		parent::TznModule('taskfreak');
		
		include_once(CMS_MODULE_PATH.'taskfreak/language/fr.php');
		
		include_once(CMS_MODULE_PATH.'taskfreak/include/classes.php');
		
		define('FRK_LANGUAGE',CMS_DEFAULT_LANGUAGE);
		define('FRK_SKIN_FOLDER',FRK_DEFAULT_SKIN_FOLDER);
		define('FRK_NO_DEADLINE_TOO',FRK_DEFAULT_NO_DEADLINE_TOO);
		define('FRK_NO_DEADLINE_KEEP',FRK_DEFAULT_NO_DEADLINE_KEEP);
		define('FRK_DATEDIFF_MODE',FRK_DEFAULT_DATEDIFF_MODE);
		define('FRK_DATEDIFF_TOMORROW',FRK_DEFAULT_DATEDIFF_TOMORROW);
		define('FRK_CONTEXT_LONG',FRK_DEFAULT_CONTEXT_LONG);
		define('FRK_RSS_SIZE',FRK_DEFAULT_RSS_SIZE);
		define('FRK_SORT_COLUMN',FRK_DEFAULT_SORT_COLUMN);
		define('FRK_SORT_ORDER',FRK_DEFAULT_SORT_ORDER);
	}
	
	/**
	* install/enable module
	*/
	function installEnable() {
		// enable as 1: basic, 2: autoload file, 3: instanciate automatically
		parent::installEnable($GLOBALS['confModule']['taskfreak']['autoload']);
		
		// -TODO-
		// create database tables
	}
		
	/**
	* called when uninstalling
	*
	function installDisable() {
		parent::installDisable();
	}
	*/
	
	/**
	* called when giving options
	function installOptions() {
	} 
	*/
	
	/**
	* default call when editing page with this module
	* page intro and list projects
	*/
	function adminDefault() {
		
		$order = 0; // show latest posts by default
		
		// decide submit modes
		if (!$GLOBALS['objPage']->id) {
			echo 'Taskfreak Error : page ID not specified';
			exit;
		}
		// load intro and options
		$this->content = new TaskfreakIntro();
		$this->content->loadContent($GLOBALS['objPage']->id);
				
		$this->content->initAdmin('Full',2);
		
		// only if on page
		$GLOBALS['objCms']->initSubmitting(1,2); // save and save and close
	
		// initialize object content
		$this->data = new ProjectStatsFull();
		
		// --- search criterions ---
		if ($sName = $_REQUEST['name']) {
			if (preg_match('/^".*"$/',$sName)) {
				$hParam = str_replace('"','',$sName);
			} else if (ereg('\*',$iName)) {
				$hParam = str_replace('*','%',$sName);
			} else {
				$hParam = '%'.str_replace(' ','%',$sName).'%';
			}
			$this->data->addWhere("pp.name LIKE '$hParam'");
		}

		if ($_REQUEST['complete']) {
			$this->data->addWhere("statusKey >= 40");
		}

		// order
		$this->data->addOrder('ps.statusKey ASC, name ASC');

		// load (all or own only)
		$this->data->loadList($GLOBALS['objUser']->id, !$GLOBALS['objUser']->hasAccess(6, $this->folder));
	
		// set script for form
		$this->baseLink = CMS_WWW_URI.'admin/page.php?id='.$GLOBALS['objPage']->id;
		
		$this->setView('admin_list');
		
	}
	
	/**
	 * edit project
	 */
	function adminEdit() {
	
		// decide submit modes
		$GLOBALS['objCms']->initSubmitting(1,2); // save and save and close
	
		if ($this->_loadProject()) {
			$this->title = 'Modifier projet';
		} else {
			$this->title = 'Cr&eacute;er un nouveau projet';
		}
		
		/* --- SUMBIT CHANGES ? -------------------------------------- */
		
		if ($GLOBALS['objCms']->submitMode) {
			// error_log('SUBMIT ['.$GLOBALS['objCms']->submitMode.']');
			$this->_adminEditProject();
			// user will be redirected if necessary
			// on error, script goes on and page is reloaded
		} else if (!empty($_REQUEST['remove'])) {
			$this->_adminEditProjectRemoveUser($_REQUEST['remove']);
		}
		
		/* --- LOAD MEMBER LIST -------------------------------------- */
		
		$this->memberList = new MemberProject();
		if ($this->content->isLoaded()) {
		    $this->memberList->addWhere($this->memberList->gTable().'.projectId = '.$this->content->id);
		    $this->memberList->addOrder('position DESC, firstName ASC');
		    $this->memberList->loadList();
		}

		/* --- LOAD STATUS CHANGE HISTORY ------------------------------------- */
		
		$this->statusHistory = new ProjectStatus();
		if ($this->content->id) {
		    $this->statusHistory->addWhere('projectId = '.$this->content->id);
		    $this->statusHistory->addOrder('statusDate');
		    $this->statusHistory->loadList();
		}
		
		/* --- LOAD OTHER USERS ----------------------------------------------- */
		
		$this->otherUserList = new Member();
		if ($this->content->isLoaded()) {
			$this->otherUserList->addWhere('level >= 1');
		    $this->otherUserList->loadNonMemberList($this->content->id);
		}
		
		/* --- LOAD PROJECT STATUS -------------------------------------------- */
		
		$this->statusList = new ProjectStatusList();
		
		/* --- LOAD PROJECT POSITIONS ----------------------------------------- */
		
		$this->positionList = new ProjectPositionList($this->userPosition);
	
		/* --- PREPARE HTML VIEW ---------------------------------------------- */
		
		$this->baseLink = CMS_WWW_URI.'admin/page.php?id='.$GLOBALS['objPage']->id.'&amp;action=edit&amp;item='.intval($this->content->id);
		$GLOBALS['objHeaders']->add('jsScript',CMS_WWW_URI.'module/taskfreak/js/freak.admin.js');
		$this->setView('admin_edit');
	}
	
	/**
	 * load project and set rights
	 */
	function _loadProject() {
	
		$this->content = new ProjectStats();
		$this->content->initObjectProperties();
		
		$this->rights = array();
		$this->rights['userCanManage'] = false;    // can(not) manage project's member
		$this->rights['userCanStatus'] = false;    // can(not) change project status
		$this->rights['userCanEdit'] = false;      // can(not) edit project details
		$this->rights['userCanDelete'] = false;    // can(not) delete project
		
		$this->userPosition = 0;         // current user's position
		
		
		/* --- LOAD PROJECT --------------------------------------------------- */
		
		if (isset($_REQUEST['item'])) {
		
			// admin loads specific project
			$this->content->setUid($_REQUEST['item']);
			if (!$this->content->load($GLOBALS['objUser']->id,false)) {
				Tzn::redirect('project_list.php','ERROR:'.$langMessage['not_found_or_denied'].' (error #424)');
			} else if ($GLOBALS['objUser']->hasAccess(8, $this->folder)) {
		        // user can edit/manage any project (admin)
		        $this->rights['userCanManage'] = true;
		        $this->rights['userCanStatus'] = true;
		        $this->rights['userCanEdit'] = true;
		        $this->rights['userCanDelete'] = true;
		        $this->userPosition = FRK_PROJECT_LEADER+1;
		    } else {
				// check that user is leader or moderator
				$this->member = new MemberProject();
				if ($this->member->loadPosition($this->content->id,$GLOBALS['objUser']->id))
		        {
		            $this->rights['userCanManage'] = $this->member->checkRights(11);
		            $this->rights['userCanStatus'] = $this->member->checkRights(12);
		            $this->rights['userCanEdit'] = $this->member->checkRights(13);
		            $this->rights['userCanDelete'] = $this->member->checkRights(14);
		            $this->userPosition = $this->member->position;
		        } else if (!$GLOBALS['objUser']->hasAccess(10, $this->folder)) {
		            Tzn::redirect(CMS_WWW_URI,'ERROR:'.$langMessage['not_found_or_denied']);
		        }
			}
			
			return true;
			
		} else {
		
		    $this->rights['userCanEdit']=true;
		    $this->rights['userCanStatus']=true;
		    
		    return false;
		
		}
	}
	
	/**
	 * save project info and users
	 */
	function _adminEditProject() {
	
		// --- 1. manage users ------------------------------------------------------
				
		if ($this->rights['userCanManage']) {
		
		    // --- add new user to project ---
		
		    if (isset($_POST['invite']) || !empty($_REQUEST['nuser'])) {
		        $objMemberTeam = new MemberProject();
		        $objMemberTeam->initObjectProperties();
				$objMemberTeam->project->id = $this->content->id;
				$objMemberTeam->member->setUid($_REQUEST['nuser']);
		        $objMemberTeam->set('position',$_REQUEST['nposition']);
		        if ($objMemberTeam->add()) {
		            $pMessageEditStatus = $GLOBALS['langProject']['user_added_ok'];
		        } else {
		            $this->error = $GLOBALS['langProject']['user_added_err'];
		        }
		    }
			
			// --- update position ---
			
			foreach ($_REQUEST as $key => $value) {
				if (preg_match('/^position-/',$key)) {
					$id = intval(substr($key,9));
					if ($id != $GLOBALS['objUser']->id) {
						// can not change own position
						$objMemberTeam = new MemberProject();
						if ($objMemberTeam->loadPosition($this->content->id,$id))
						{
							if ($objMemberTeam->position != $value) {
								// update database
								$objMemberTeam->position = $value;
								$objMemberTeam->getConnection();
								$objMemberTeam->update('position');
		                        $pMessageEditStatus = $GLOBALS['langProject']['user_position_ok'];
							}
						}
					}
				}
			}
			
			// redirect if necessary
			if ($pMessageEditStatus) {
				// error_log('-> changes in users');
				switch ($GLOBALS['objCms']->submitMode) {
				case 1: // save
					// just saved, do nothing, just show again
					break;
				case 2: // saveclose
					// redirect to previous page in referrer filo
					TznUtils::redirect(TznUtils::getReferrer(true, true), $pMessageEditStatus);
					break;
				}
		    }
		    
		} // end manage users
		
		// --- 2. save project info -------------------------------------------------
		
	    if ($this->rights['userCanEdit']) {
	    
	    	// error_log('-> Lets edit the project');
	    
	        $this->content->setHttpAuto();
	        if ($this->content->check()) { // register form is valid
	            if ($this->content->isLoaded()) {
	                // --- save project status ---
	                if ($this->rights['userCanStatus'] && !$this->error && $_POST['status'] != $this->content->projectStatus->statusKey) {
	                    $this->content->setStatus($_POST['status'],$GLOBALS['objUser']->id);
	                    $pMessageStatus = $GLOBALS['langProject']['action_status_ok'];
	                } else {
	                    $pMessageStatus = $GLOBALS['langProject']['action_save_ok'];
	                }
	                $this->content->update();
	            } else if ($this->content->add($_POST['status'],$GLOBALS['objUser']->id)) { // add in DB
	                $pMessageStatus = $GLOBALS['langProject']['action_added_ok'];
	            }
	        } else {
	    		$this->error = $GLOBALS['langTznCommon']['form_error'];
	        }
	        
		} // end saving project info
		

		// redirect on successful update
		if ($pMessageStatus) {
			// error_log('--> changes in project');
			switch ($GLOBALS['objCms']->submitMode) {
			case 1: // save
				// just saved, do nothing, just show again
				break;
			case 2: // saveclose
				// redirect to previous page in referrer filo
				TznUtils::redirect(TznUtils::getReferrer(true, true), $pMessageStatus);
				break;
			}
		}
		
	}
	
	/**
	 * remove user from project
	 */
	function _adminEditProjectRemoveUser($uid) {
		if ($this->rights['userCanManage']) {
			$objMemberTeam = new MemberProject();
			$objMemberTeam->initObjectProperties();
			$objMemberTeam->project->id = $this->content->id;
			$objMemberTeam->member->setUid($_REQUEST['remove']);
			if ($objMemberTeam->delete()) {
				$pMessageEditStatus = $GLOBALS['langProject']['user_removed_ok'];
				return true;
			} else {
				$this->error = $GLOBALS['langProject']['user_removed_err'];
			}
		}
		return false;
	}
	
	/**
	 * override edit default reaction
	 */
	function adminEditAction() {
		// ignore default action on edit
		// previously taken care of by _adminEditProject
	}
	
	function adminDelete() {
		if ($this->_loadProject() && $this->rights['userCanDelete']) {
			$this->content->delete();
		}
		TznUtils::redirect(TznUtils::getReferrer(true,true));
	}
	
	function adminDeletePage($pageId) {
		/*
		$objItemList = new ContentBlog();
		if ($objItemList->loadList('pageId='.$pageId)) {
			while ($objItem = $objItemList->rNext()) {
				$objItem->delete();
			}
		}
		*/
	}
		
	/**
	* list tasks
	*/
	function publicDefault() {
	
		$this->_checkUserAccess();
	
		// --- Get Filters / Parameters ----------------
		
		$this->_initFilterRequest();
		
		/* --- LOAD INTRO -------------------------------------------------------- */
		
		$this->intro = new TaskfreakIntro();
		$this->intro->loadContent($GLOBALS['objPage']->id);
		
		/* --- LOAD USER's PROJECT LIST ------------------------------------------ */
		
		$this->_loadProjectList();
		
		// error_log('publicDefault : project list loaded');
		
		/* --- LOAD TASKS ----------------------------------------------------- */
		
		$this->data = new ItemStats();
		
		// --- Filter : Projects -----------------------
		
		// check access to requested project
		if ($this->req['project']) {
			
		    if (!$GLOBALS['objUser']->hasAccess(6, $this->folder)) {
		        // user can not see all projects
		        $objProjectCheck = new MemberProject();
		        if (!$objProjectCheck->loadPosition($this->req['project'],$GLOBALS['objUser']->id)) 
		        {
		            // user is not a member this project
		            $this->req['project'] = 0;
		            $pMessageStatus = 'ERROR:No access to this project';
		        }
		        unset($objProjectCheck);
		    }
		    
		}
		
		// filter by user's projects
		$sqlFilter = '';
		if ($this->req['project']) {
			// apply single project filter
			$sqlFilter = 'ii.projectId = \''.$this->req['project'].'\'';
		    $this->req['link']=TznUtils::concatUrl($this->req['link'],'project='.$this->req['project']);
		    
		    while($objTmp = $this->projects->rNext()) {
		    	if ($objTmp->id == $this->req['project']) {
		    		$this->project = $objTmp;
		    		break;
		    	}
		    }
		    
		    $this->projects->rReset();
		    		    
		} else {
			// filter by all available projects
		    $arrProject = array();
		    
		    // user can only access his own projects
		    if ($this->projects->rMore()) {
		        while($objTmp = $this->projects->rNext()) {
		            $arrProject[] = $objTmp->id;
		        }
		        if ($GLOBALS['objUser']->hasAccess(1,'taskfreak')) {
		        	$arrProject[] = '0';
		        }
		        $sqlFilter = 'ii.projectId IN ('.implode(',',$arrProject).')';
		        
		        $this->projects->rReset();
		    } else {
		    	$sqlFilter = 'ii.projectId = 0';
		    }
		    
		    // members of "comité d'administration" can access public tasks too
		    // -TODO- add to settings
		    if (in_array(3,$arrProject)) {	
			    $sqlFilter = '('.$sqlFilter.' OR showPrivate = 0)';
			}
			unset($arrProject);
		}
		 
		$this->data->addWhere($sqlFilter);
		
		// --- LOAD ------------------------------------
		
		// error_log('publicDefault : loading list of tasks');
		
		$this->data->loadTaskList($this->req);
		
		// --- REDIRECT if needed ----------------------
		// if just logged in and no recently changed tasks, show current tasks
		if (@constant('FRK_DEFAULT_VIEW_ALTERNATE') && empty($_GET['show']) && $this->req['show'] == 'recent' && !$this->data->rCount()) {
			TznUtils::redirect(TznUtils::concatUrl($GLOBALS['objPage']->getUrl(),'show='.FRK_DEFAULT_VIEW_ALTERNATE));
		}
		
		/* --- PREPARE HTML VIEW ---------------------------------------------- */
		
		$GLOBALS['objHeaders']->add('cssModule','taskfreak');
		$GLOBALS['objHeaders']->add('cssModule','taskfreak.prio'.FRK_PRIORITY_LEVELS);
		$GLOBALS['objHeaders']->add('cssCode',"<style>@import url('/module/taskfreak/css/taskfreak.print.css') print;</style>");
		$GLOBALS['objHeaders']->add('jsScript',CMS_WWW_URI.'module/taskfreak/js/freak.js');
		
		// error_log('publicDefault : show me the good stuff');
		
		$this->setView('public_list');
	}
	
	/**
	 * view task
	 */
	function publicView() {
	
		// --- Get Filters / Parameters ----------------
		
		$this->_initFilterRequest();
		
		// --- Load task and check rights --------------
	
		$this->_checkTaskAccess($_REQUEST['item'], 'view');
		
		// --- Prepare / Post new Comment ---------------
		
		if ($this->task->checkRights($GLOBALS['objUser']->id,2,true,true)) {
		
			$this->postcomment = new ItemComment();
			$GLOBALS['objHeaders']->add('css','form.css');
			
			if (!empty($_POST['comment_body']) || is_array($_POST['uplfile'])) {
				// post new comment
				$this->postcomment->setHttp('body', $_POST['comment_body']);
				$this->postcomment->itemId = $this->task->id;
				$this->postcomment->member->id = $GLOBALS['objUser']->id;
				
				// add attachments
				if ($arr = $this->task->addUpdateFiles()) {
					$this->postcomment->body .= "\n";
					foreach($arr as $fname => $ftitle) {
						$this->postcomment->body .= "\nfichier ajouté : <a href=\"$fname\" target=\"_blank\">$ftitle</a>";
					}
				}
				
				if ($this->postcomment->add()) {
					$this->task->set('lastChangeDate','NOW');
					$this->task->set('lastChangeAuthorId', $objUser->id);
					$this->task->update('lastChangeDate,lastChangeAuthorId');
					$this->message = $GLOBALS['langMessage']['done_comment_added'];
					TznUtils::redirect(TznUtils::concatUrl($GLOBALS['objPage']->getUrl(),'action=view&item='
						.$this->task->id).'#comment_'.$this->postcomment->id);
				}
			}
		}
		
		// --- Load task history -----------------------
		
		$this->taskStatus = new ItemStatus();
		$this->taskStatus->addWhere('itemId='.$this->task->id);
		$this->taskStatus->addOrder('statusDate ASC');
		$this->taskStatus->loadList();
		
		// --- Load task author and creation info -------
		
		$this->taskOrigin = $this->taskStatus->rNext();
		$this->taskStatus->rReset();
		
		// --- Load task files --------------------------
		
		$this->files = new ItemFile();
		$this->files->addWhere('itemId='.$this->task->id);
		$this->files->loadList();
		
		// --- Load comments ----------------------------
		
		$this->comments = new ItemComment();
		
		if ($this->task->checkRights($GLOBALS['objUser']->id,1,true,true)) {
			$this->comments->addWhere('itemId='.$this->task->id);
			$this->comments->addOrder('postDate '.(@defined('FRK_DEFAULT_COMMENT_ORDER')?FRK_DEFAULT_COMMENT_ORDER:'ASC'));
			$this->comments->loadList();
		}
		
		// --- Change user's view status ----------------
		
		$this->task->setViewStatus();
	
		/* --- PREPARE HTML VIEW ---------------------------------------------- */
		
		$GLOBALS['objHeaders']->add('css','comment.css');
		$GLOBALS['objHeaders']->add('cssModule','taskfreak');
		$GLOBALS['objHeaders']->add('cssModule','taskfreak.prio'.FRK_PRIORITY_LEVELS);
		$GLOBALS['objHeaders']->add('cssCode',"<style>@import url('/module/taskfreak/css/taskfreak.print.css') print;</style>");
		$GLOBALS['objHeaders']->add('jsScript',CMS_WWW_URI.'module/taskfreak/js/freak.js');
		
		$this->setView('public_view');
	}
	
	/**
	 * edit task
	 */
	function publicEdit() {
	
		// --- Get Filters / Parameters ----------------
		
		$this->_initFilterRequest();
		
		// --- Load task and check rights --------------
	
		$this->_checkTaskAccess($_REQUEST['item'], 'edit');
		
		/* --- SAVE TASK --------------------------------------------------------- */
		
		if ($_POST['save']) {
		
			$newStatus = intval($_POST['status']);
		    $oldStatus = ($this->task->id)?$this->task->itemStatus->statusKey:-1;
			
			$this->task->setAuto($_POST);
			
			// project
		    $objProject = new Project();
		
		    if ($GLOBALS['objUser']->hasAccess(7, 'taskfreak') && $_POST['project2']) {
		        
		        // create new project on the fly
		        $objProject->set('name',$_POST['project2']);
		        $objProject->add(0,$GLOBALS['objUser']->id);
		        //$objResponse->addScript('freak_rld()');
		        
		        // associate task to new project
		        $this->task->project =& $objProject;
		
		    } else if ($tmpId = intval($_POST['project'])) {
		
		        $pCanAddTask = false;
		        if ($GLOBALS['objUser']->hasAccess(8, 'taskfreak')) {
		            // user is administrator
		            $objProject->setUid($tmpId);
		            if ($objProject->load()) {
		                $pCanAddTask = true;
		            }
		        } else {
		            // user is not administrator
		            // need to check rights to access project
		            $objMemberProject = new MemberProject();
		            if ($objMemberProject->loadPosition($tmpId,$GLOBALS['objUser']->id))
		            {
		                $pCanAddTask = $objMemberProject->checkRights(6);
		                $objProject =& $objMemberProject->project;
		            }
		        }
		
		        if ($pCanAddTask) {
		            // associate to existing project
		            $this->task->project =& $objProject;
		        }
		
		    } else {
		
		        // no project
		        $this->task->project->name = '-';
		        $this->task->project->id = 0;
		    }
		
		    // user(s)
		    $objMember = new Member();
		    $this->task->member =& $objMember;
		    if ($tmpId = intval($_POST['user'])) {
		        $objMember->setUid($tmpId);
		        $objMember->load();
		    }
			
			if ($this->task->check()) {
			
				if ($this->task->isLoaded()) {
				
		            // set last change date
		            $this->task->setDtm('lastChangeDate','NOW');
		            $this->task->set('lastChangeAuthorId', $GLOBALS['objUser']->id);
	            
		            // update database
		            $this->task->update();
	            
	            	// update files
	            	$this->task->addUpdateFiles();
	            
		            $pMessage = $GLOBALS['langMessage']['done_updated'];
	            
		        } else {
	
		            $this->task->set('authorId', $GLOBALS['objUser']->id);
		            
		            $this->task->setDtm('creationDate','NOW');
		            $this->task->setDtm('lastChangeDate','NOW');
		
		            // add item to DB (if user has rights to do it)
		            $this->task->add();
		            
		            // add / update files
		            $this->task->addUpdateFiles();
		            
		            // change user's view status
		            $this->task->setViewStatus();
		            
		            $pMessage = $GLOBALS['langMessage']['done_added'];
		            
				}
				
				// update task status
				if ($oldStatus != $newStatus) {
					$this->task->setStatus($newStatus,$GLOBALS['objUser']->id);
				}
				
				// redirect to task list
				TznUtils::redirect($this->req['link'], $pMessage);
				
			}
			
		}
				
		/* --- LOAD USER's PROJECT LIST ------------------------------------------ */
		
		$this->_loadProjectList();
		
		/* --- LOAD PROJECT's USERS and FILES (if editing) ----------------------- */
		
		$this->users = new MemberProject();
		
		$projectId = 0;
		if ($this->task->isLoaded()) {
			// project
			$projectId = $this->task->project->id;
			// files
			$this->files = new ItemFile();
			$this->files->addWhere('itemId='.$this->task->id);
			$this->files->loadList();
		} else if ($obj = $this->projects->rNext()) {
			// get default project
			$projectId = $obj->id;
			$this->projects->rReset();
			unset($obj);
			// 
			$this->files = false;
		}
		
		if ($projectId) {
			$this->users->addWhere($this->users->gTable().'.projectId = '.$projectId);
		    $this->users->addOrder('firstName ASC');
		    $this->users->loadList();
		}
		
		/* --- PREPARE HTML VIEW ------------------------------------------------- */
		
		$GLOBALS['objHeaders']->add('css','form.css');
		$GLOBALS['objHeaders']->add('cssModule','taskfreak');
		$GLOBALS['objHeaders']->add('cssModule','taskfreak.prio'.FRK_PRIORITY_LEVELS);
		$GLOBALS['objHeaders']->add('jsScript',CMS_WWW_URI.'module/taskfreak/js/freak.js');
		
		$GLOBALS['objHeaders']->add('jsCalendar','cms_date');
		
		$this->setView('public_edit');
	}
	
	/**
	 * delete task
	 */
	function publicDelete() {
	
		// --- Get Filters / Parameters ----------------
		
		$this->_initFilterRequest();
		
		// --- Load task and check rights --------------
	
		$this->_checkTaskAccess($_REQUEST['item'], 'delete');
		
		$this->task->delete();
		
		TznUtils::redirect($this->getUrl(), $GLOBALS['langTaskMessage']['done_deleted']);
		
	}
	
	/**
	 * get link (public pages)
	 */
	function getUrl($action='', $item=0) {
		$link = TznUtils::concatUrl($GLOBALS['objPage']->getUrl(), 'action='.$action);
		switch($action) {
		case 'view':
			return TznUtils::concatUrl($link, 'item='.$item);
			break;
		case 'edit':
		case 'delete':
		case 'status':
			if ($item) {
				return TznUtils::concatUrl($link, 'item='.$item);
			} else {
				return $link;
			}
			break;
		case 'list':
			$this->req['link'] = $GLOBALS['objPage']->getUrl();
		default:
			return $this->req['link'];
		}
	}
	
	/**
	 * get Filter / Request parameters
	 */
	function _initFilterRequest() {
	
		$this->req = array();
		$this->req['link'] = $GLOBALS['objPage']->getUrl();
	
		// Projects
		$this->req['project'] = TznUtils::getHttpParameter('get','project',true,true,'INT'); // all projects by default
		
		// User
		$this->req['user'] = TznUtils::getHttpParameter('get','user',true,true, 'INT');
		
		// Search
		$this->req['search'] = TznUtils::getHttpParameter('get','search',true,true);
		
		// Context
		$this->req['context'] = TznUtils::getHttpParameter('get','context',true,true);
		
		// Date / View
		$this->req['show'] = TznUtils::getHttpParameter('get','show',true,true);
	}
	
	/**
	 * check user access rights
	 */
	function _checkUserAccess() {
		if (
			(!$GLOBALS['objUser']->isLoggedIn() && $GLOBALS['confModule']['taskfreak']['visitor_access'])
			|| $GLOBALS['objUser']->hasAccess(1,'taskfreak')
		) {
			// error_log('_checkUserAccess : is OK');
			return true;
		}
		// error_log('_checkUserAccess : DENIED');
		TznUtils::redirect(CMS_WWW_URL);
	}
	
	/**
	 * load and check task access
	 */
	function _checkTaskAccess($id, $mode) {
	
		$this->_checkUserAccess(); // check access to TaskFreak!
		
		$this->task = new ItemStats();
		$id = intval($id);
		
		$this->rights = array();
		
		if (!$id) {
			if ($mode == 'edit') {
				// create new task
				if ($GLOBALS['objUser']->hasAccess(11, 'taskfreak')) {
					return true;
				} else {
					TznUtils::redirect($this->req['link'], 'User not allowed to create task');
				}
			} else {
				TznUtils::redirect($this->req['link'], 'Task ID not specified');
			}
		}
		
		$this->task->setUid($id);
		if ($this->task->load($GLOBALS['objUser']->id)) {
		
			// error_log('task loaded:'.$id.', user '.$objUser->id.' has position '.$objTask->position);

			$this->rights['userCanView'] = ($GLOBALS['objUser']->hasAccess(1, 'taskfreak') || $this->task->checkRights($GLOBALS['objUser']->id,0,false));
			$this->rights['userCanComment'] = ($GLOBALS['objUser']->hasAccess(3, 'taskfreak') || $this->task->checkRights($GLOBALS['objUser']->id,2,true));
			$this->rights['userCanEdit'] = ($GLOBALS['objUser']->hasAccess(14, 'taskfreak') || $this->task->checkRights($GLOBALS['objUser']->id,7,false));
			$this->rights['userCanStatus'] = ($GLOBALS['objUser']->hasAccess(14, 'taskfreak') || $this->task->checkRights($GLOBALS['objUser']->id,8, true));
			$this->rights['userCanDelete'] = ($GLOBALS['objUser']->hasAccess(14, 'taskfreak') || $this->task->checkRights($GLOBALS['objUser']->id,9, false));
		
			$check = false;
			switch($mode) {
			case 'view':
				$check = $this->rights['userCanView'];
				break;
			case 'comment':
				$check = $this->rights['userCanComment'];
				break;
			case 'edit':
				$check = $this->rights['userCanEdit'];
				break;
			case 'status':
				$check = $this->rights['userCanStatus'];
				break;
			case 'delete':
				$check = $this->rights['userCanDelete'];
				break;
			default:
				TznUtils::redirect($this->req['link'], 'Load task mode unknown');
				break;
			}
		
	        if ($check) 
	        {
	            return true;
	        }
	    }
	    
		unset($this->task);
	    TznUtils::redirect($this->req['link'], $GLOBALS['langTznCommon']['data_denied']);
	}
	
	function _loadProjectList() {
		$this->projects = new ProjectStats();
		$this->projects->addOrder('statusKey ASC, name ASC');
		$this->projects->addHaving('projectStatus_statusKey < 40');
		$this->projects->loadList($GLOBALS['objUser']->id,(!$GLOBALS['objUser']->hasAccess(6, $this->folder)));
	}
	
	/* ---------- AJAX METHODS ------------------------------------------------ */
	
	/**
	 * Load list of users assigned to a project
	 */
	function ajaxUserlist() {
		$pid = intval($_REQUEST['item']);
		$js = '';
		
		if ($pid) {
			// load project's members
			$objlist = new MemberProject();
			$objlist->addWhere($objlist->gTable().'.projectId = '.$pid);
		    $objlist->addOrder('firstName ASC');
			$objlist->loadList();
			
			// reset select's list
			$js = "e = $('i_user'); e.empty();\n";
			$js .= "e.adopt(new Element('option',{'text':'-'}));\n";
			
			// add users to select
			while($obj = $objlist->rNext()) {
				$js .= "e.adopt(new Element('option',{'value':'".$obj->getMemberId()."','text':'".$obj->getMemberName()."'}));\n";
			}
			
			$js .= "e.setProperty('disabled',false);\n";
		    
		} else {
			// not a valid project ID
			$js = "alert('Erreur lors du chargement de la liste des utilisateurs du projet');";
		}
		
		echo '<script type="text/javascript">';
		echo $js;
		echo '</script>';
		
	}
	
	/**
	 * Change task status
	 */
	function ajaxStatus() {
	
		$this->_checkTaskAccess($_REQUEST['item'], 'status');
		
        $newStatus = intval($_REQUEST['status']);
        
        $js = '';
        
        if ($newStatus == $this->task->itemStatus->statusKey) {
        	$newStatus--;
        }
        
        if ($newStatus != $this->task->itemStatus->statusKey) {
            $this->task->setStatus($newStatus,$GLOBALS['objUser']->id);
            $this->task->setDtm('lastChangeDate','NOW');
            $this->task->set('lastChangeAuthorId', $GLOBALS['objUser']->id);
            $upfields = 'lastChangeDate,lastChangeAuthorId';
			if ($newStatus == FRK_STATUS_LEVELS) {
				// task is completed
				if (@constant('FRK_COMPLETE_DEADLINE') == TRUE) {
					// update deadline to current date
					$this->task->setDte('deadlineDate','NOW');
					$upfields .= ',deadlineDate';
				}
			}
			$this->task->update($upfields);
			
			if (isset($_POST['status'])) {
			
				// --- Show new status in task details ----------------
				echo $GLOBALS['langItemStatus'][$newStatus];
				$js = "tf_new_status('".$this->task->getDtm('lastChangeDate','SHT',$GLOBALS['objUser']->timeZone)."','"
					.$GLOBALS['objUser']->getName()."','"
					.$GLOBALS['langItemStatus'][$newStatus]."');";
				
			} else {
			     
                // --- Show new status in list ------------------------
                
                for ($i = 0; $i < FRK_STATUS_LEVELS; $i++) {
                    $j = ($i < $newStatus)?(FRK_STATUS_LEVELS - $i):0;
                    $js .= "$('est".($i+1)."-".$this->task->id."').set('class','sts$j');\n";
                }
                // update status
                $js .= "$('estx-".$this->task->id."').set('text','".$GLOBALS['langItemStatus'][$newStatus]."');\n";
                
                sleep(1); // wait one second so status doesn't get updated twice in a row
            }
            
        } else {
        	$js = 'alert("rien à mettre à jour");';
        }
		
		echo '<script type="text/javascript">';
		echo $js;
		echo '</script>';
	}
			
	/**
	 * Post comment
	 */
	function ajaxCompost() {
		error_log('post new comment');
	}
	
	/**
	 * Edit comment
	 */
	function ajaxComedit() {
		$objComment = self::_ajaxComLoad($_REQUEST['id'], 3, $objTask);
		
		// generate form and javascript
		$str = '<script type="text/javascript">'
			.'var body_edit_'.$objComment->id.'=$("comment_body_'.$objComment->id.'").get("html");'
			.'</script>';
		$str .= '<form id="comment_edit_'.$objComment->id.'" action="'.CMS_WWW_URI.'ajax.php" method="post" '
			.'onsubmit="return ajaxify_form(this,\'comment_body_'.$objComment->id.'\');">'
			.'<input type="hidden" name="module" value="taskfreak" />'
			.'<input type="hidden" name="action" value="comupdate" />'
			.'<input type="hidden" name="id" value="'.$objComment->id.'">';
		ob_start();
		$objComment->qTextArea('body','','wxxl hm');
		$str .= ob_get_contents();
		ob_clean();
		$str .= '<br /><button type="submit" name="save" value="1">Enregistrer</button> '
			.'<button type="button" onclick="$(\'comment_body_'.$objComment->id.'\').set(\'html\',body_edit_'.$objComment->id.')">Annuler</button>'
			.'</form>';

		echo $str;
	}
	
	/**
	 * Update comment
	 */
	function ajaxComupdate() {
		// load comment
		$objComment = self::_ajaxComLoad($_REQUEST['id'], 3, $objTask);
		
		// update database
		$objComment->setHttp('body', $_REQUEST['body']);
		$objComment->update('body');

		// return new body
		$objComment->p('body');
	}
	
	/**
	 * Delete comment
	 */
	function ajaxComdelete() {
		$objComment = self::_ajaxComLoad($_REQUEST['id'], 4, $objTask);
		$objComment->delete();
		$str = '<script type="text/javascript">';
		$str .= '$("comment_'.$objComment->id.'").destroy();';
		$str .= '</script>';
		echo $str;
	}
	
	/**
	 * load task (private method)
	 */
	function _ajaxComLoad($id, $level, &$objTask) {
	
		$objComment = new ItemComment();
		$objComment->setUid($id);
		
		if ($objComment->load()) {
			$objTask = new ItemStats();
			$objTask->setUid($objComment->itemId);
			if ($objTask->load($GLOBALS['objUser']->id)) {
				if ($objComment->checkRights($GLOBALS['objUser']->id,$level,$objTask)
					|| $GLOBALS['objUser']->hasAccess(14, 'taskfreak')
					|| $objTask->checkRights($GLOBALS['objUser']->id,0)) 
				{
					return $objComment;
				}
			}
		}
		
		// load comment
		if (!$objComment->load()) {
			// comment not found
			$str = '<script type="text/javascript">'
				.'alert("can not load comment #'.$_REQUEST['id'].' !");'
				.'</script>';
			echo $str;
			exit;
		}
		
		// check access rights
		if (!$GLOBALS['objUser']->hasAccess(6,'blog',$objComment->author->id)) {
			// no access
			$str = '<script type="text/javascript">'
				.'alert("can not access comment #'.$objComment->id.' !");'
				.'</script>';
			echo $str;
			exit;
		}
		return $objComment;
	}
	
	function pSortTh($label, $width, $key) {
		echo '<th width="'.$width.'" class="easyclick';
		if ($key == $this->req['sort']) {
			if ($what == 'deadline' || $what == 'lastChangeDate') {
				$this->req['dir'] = -$this->req['dir'];
			}
			if ($this->req['dir'] == 1) {
				echo ' sortup';
			} else {
				echo ' sortdn';
			}
		}
		echo '"><a href="'.TznUtils::concatUrl($this->req['link'],'sort='.$key).'">'.$label.'</a></th>';
	}
}

class TaskfreakIntro extends CmsContent
{
	function TaskfreakIntro() {
		parent::CmsContent();
		$this->addOptions(array(
			'pagination'		=> 'NUM'
		));
	}
}

/*
RENAME TABLE  `eps_web`.`tzn_item` TO  `eps_web`.`lig_item` ;
RENAME TABLE  `eps_web`.`tzn_itemAlert` TO  `eps_web`.`lig_itemAlert` ;
RENAME TABLE  `eps_web`.`tzn_itemComment` TO  `eps_web`.`lig_itemComment` ;
RENAME TABLE  `eps_web`.`tzn_itemContext` TO  `eps_web`.`lig_itemContext` ;
RENAME TABLE  `eps_web`.`tzn_itemFile` TO  `eps_web`.`lig_itemFile` ;
RENAME TABLE  `eps_web`.`tzn_itemStatus` TO  `eps_web`.`lig_itemStatus` ;
RENAME TABLE  `eps_web`.`tzn_memberProject` TO  `eps_web`.`lig_memberProject` ;
RENAME TABLE  `eps_web`.`tzn_project` TO  `eps_web`.`lig_project` ;
RENAME TABLE  `eps_web`.`tzn_projectStatus` TO  `eps_web`.`lig_projectStatus` ;
*/