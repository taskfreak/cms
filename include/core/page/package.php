<?php

class PageController extends TznController
{

	function PageController() {
		parent::TznController('page');
	}
	
	function main() {
	
		$url = TznCms::getAdminRef();
		
		$pAccessError = false;
		
		// if (count($_POST)) { echo '<pre>'; print_r($_POST); echo '</pre>'; }
		
		if ($id = intval($_REQUEST['id'])) {
		    $this->page = new TznPage();
		    $this->page->setUid($id);
		    if ($this->page->load()) {
		        switch($_REQUEST['mode']) {
		        case 'copy':
		        	if ($this->page->canHeader()) {
		        		$this->actionCopy();
	        			return true;
		        	} else {
		        		$pAccessError = true;
		        	}
		        case 'header':
		        	if ($this->page->canHeader()) {
			        	$this->actionHeader();
			        	return true; // squeezed popup : don't go any further, just display the view
			        } else {
						$pAccessError = true;
					}
		        	break;
		        case 'add':
		        	if ($this->page->canAdd()) {
			        	$this->actionHeader();
			        	return true; // squeezed popup : don't go any further, just display the view
			        } else {
						$pAccessError = true;
					}
		        	break;
		        case 'up':
		        	if ($this->page->canMove()) {
						$this->page->moveOutlineUp();
						TznUtils::redirect(TznUtils::getReferrer(false),'Page moved!');
					} else {
						$pAccessError = true;
					}
		            break;
		        case 'delete':
					if ($this->page->canDelete()) {
						$this->page->deleteTree();
					} else {
						$pAccessError = true;
					}
		            break;
		        default:
		        	if ($this->page->canEdit()) {
			        	if (isset($_REQUEST['backtopage'])) {        	
			        		TznUtils::setReferrer($this->page->getUrl());
			        	} else {
			        		TznUtils::addReferrer($url);
			        	}
			        	$this->actionEdit();
			        } else {
						$pAccessError = true;
					}
		        	break;
		        }
		    }
		
		} else if ($_REQUEST['mode'] == 'addsection') {
		
			if ($GLOBALS['objUser']->hasAccess(7)) {
				// new section
				$objNewPage = new TznPage();
				$objNewPage->setOutlineNextRoot();
				// echo 'new root outline: '.$objNewPage->getOutline(); exit;
				$objNewPage->setTitle('section'.rand(111,999));
				$objNewPage->add();
				unset($objNewPage);
				TznUtils::redirect(TznUtils::getReferrer(false),'Page added!');
			} else {
				$pAccessError = true;
			}
			
		}
		
		// on access denied, redirect
		if ($pAccessError) {
			TznUtils::redirect(CMS_WWW_URI.'admin/page.php', $GLOBALS['langMessage']['denied']);
		}
		
		
		TznCms::setAdminRef($url);
		
		// load page list
		$this->pageList = new TznPage();
		/*
		if (!$GLOBALS['objUser']->hasAccess(3)) {
			$this->pageList->addWhere('display=1');
		}
		*/
		$this->pageList->loadTree();
		
		// load template list
		$this->templateList = new TznTemplateList();
		$this->templateList->loadList();
		
		/* === PREPARE HTML ======================================================== */
		
		// force including CSS from assets directory by passing full path
		$GLOBALS['objHeaders']->add('css',CMS_WWW_URI.'assets/css/squeezebox.css');
		
		$GLOBALS['objHeaders']->add('jsScript','squeezebox.js');
	
	}
	
	function actionList() {
	
	}
	
	function actionCopy() {

		// create new page 
		$objNewPage = $this->page->clone4();
		$objNewPage->id = 0;
		$objNewPage->set($objNewPage->_outlineField,'');
		
		// copy first page as the next brother
		$objParent = $this->page->getParent();
		if ($objParent->id) {
			// has a parent : get next childish outline from it
			$objNewPage->set($objNewPage->_outlineField,$objParent->getOutlineNextChild());
		} else {
			// root page : get the next root page outline
			$objNewPage->setOutlineNextRoot();
		}
		
		// create the page
		$objNewPage->setAlternativeShortcut();
		$objNewPage->add();
		
		// get outline roots to be updated
		$outOrg = $this->page->getOutlineRoot(false);
		$outDst = $objNewPage->getOutlineRoot(false, $objNewPage->position);
		
		// copy children
		$objChildrenList = new TznPage();
		if ($objChildrenList->loadChildren($this->page, $objChildrenList->_outlineLevels)) {
			while ($objChild = $objChildrenList->rNext()) {
				$objChild->id = 0;
				$objChild->setAlternativeShortcut();
				// update outline
				$outCur = $outOld = $objChild->get($objChild->_outlineField);
				$outCur = preg_replace('/^'.$outOrg.'/', $outDst, $outCur);
				$objChild->set($objChild->_outlineField, $outCur);
				// copy to DB
				$objChild->add();
			}
		}
		
		TznUtils::redirect(CMS_WWW_URL.'admin/page.php','Pages copiées');
		
	}
	
	function actionHeader() {
	
		$GLOBALS['objCms']->initSubmitting(1); // save only (actually, will close the window too)

		// check action
		$this->mode = 'add';
		switch($_REQUEST['mode']) {
			case 'add':
				// create new page
				$this->pageParent = $this->page->clone4();
				$this->page->resetProperties();
				$this->page->template = $this->pageParent->template;
				$this->page->module = $this->pageParent->module;
				break;
			case 'header':
			default:
				$this->pageParent = $this->page->getParent();
				$this->mode = 'header';
				break;
		}
		
		/* === SUBMIT CHANGES ====================================================== */
		
		if ($GLOBALS['objCms']->submitMode) {
			switch ($this->mode) {
				case 'add':
					$this->page->set($this->page->_outlineField,$this->pageParent->getOutlineNextChild());
					$this->page->setTitle($_REQUEST['title'],$_REQUEST['menu'],$_REQUEST['shortcut']);
					$this->page->inheritProperties($this->pageParent);
					$this->page->set('template',$_REQUEST['template']);
					$this->page->set('module',$_REQUEST['module']);
					$this->page->add();
					if ($this->page->module) {
						TznUtils::redirect(CMS_WWW_URI.'admin/page.php?id='.$this->page->id,'Page ajout&eacute;e'); // -TODO-TRANSLATE-
					} else {
						TznUtils::redirect(CMS_WWW_URI.'admin/page.php','Page ajout&eacute;e'); // -TODO-TRANSLATE-
					}
					break;
				case 'header':
					$canMoveRightNow = ($this->page->getLvl('protected',4))?false:true; // save this for later
					$this->page->setAuto($_POST);
					$this->page->setTitle($_POST['title'],$_POST['menu'],$_POST['shortcut']);
					if ($GLOBALS['objUser']->hasAccess(10)) {
						$this->page->setBol('display',$_POST['display']);
						$this->page->setNum('private',$_POST['private']);
						$this->page->setBol('showInMenu',$_POST['showInMenu']);
					}
					if ($GLOBALS['objUser']->hasAccess(12)) {
						// error_log('SETTING protection : '.implode(',',$_REQUEST['protected']));
						$this->page->resetProtection();
						$this->page->setLvlAuto('protected',$_REQUEST['protected']);
					}
					
					// -TODO- check
					$this->page->update();
				
					// move?
					if ($canMoveRightNow && $_REQUEST['parent'] && $this->pageParent->id != $_REQUEST['parent']) {
						// page not protected against moving
						// and parent id submitted is different
						$objNewParent = new TznPage();
						$objNewParent->setUid($_REQUEST['parent']);
						if ($objNewParent->load()) {
							$this->page->moveOutlineParent($objNewParent->position);
							$this->pageParent =& $objNewParent;
						} else if (!$_REQUEST['parent']) {
							// move to root
							$this->page->moveOutlineParent($this->page->_outlineSample);
							$this->pageParent =& $objNewParent;
						}
					}
					TznUtils::redirect(TznUtils::getReferrer(false, true),'Page updated'); // -TODO-TRANSLATE-
					break;
			}
			
		}
		
		/* === PREPARE DATA ======================================================== */
		
		$this->pageList = new TznPage();
		$this->pageList->loadTree();
		
		$this->moduleList = new TznModuleList();
		$this->moduleList->loadList();
		
		$this->templateList = new TznTemplateList();
		$this->templateList->loadList();
		
		/* === PREPARE HTML ======================================================== */
		
		$this->jsUpdAuto = '';
		if (!$this->page->shortcut) {
			$this->jsUpdAuto .= 'this.form.shortcut.value=cms_shortcut(this.value);';
		}
		if (!$this->page->menu) {
			$this->jsUpdAuto .= 'this.form.menu.value=this.value;';
		}
		if ($this->jsUpdAuto) {
			$this->jsUpdAuto = 'onblur="'.$this->jsUpdAuto.'"';
		}
	
		$this->setView('view_header');
			
	}
	
	function actionEdit() {
		
		/* === MODULE STUFF ======================================================== */
		
		//TznUtils::addReferrer();
		$GLOBALS['objPage'] =& $this->page;
		
		if ($this->page->module) {
			
			$this->module = $GLOBALS['objCms']->getModuleObject($this->page->module);
			
			$action = 'adminDefault';
			if (TznUtils::sanitize(TZN_SANITIZE_SIMPLE,$_REQUEST['action'])) {
				$action = 'admin'.ucfirst($_REQUEST['action']);
			}
			
			$this->module->$action();
			
			if ($_POST['forcePublish'] == 1) {
				// force publish
				$this->page->display = 1;
				$this->page->update('display');
			}
			
			if ($GLOBALS['objCms']->submitMode) {
				// error_log('PAGE submit : '.$GLOBALS['objCms']->submitMode);
				$action .= 'Action';
				if (method_exists($this->module, $action)) {
					$this->module->$action();
				} else {
					$this->module->adminDefaultAction();
				}
			}
			
		}
		
		/* === SET VIEW ============================================================== */
		
		$this->setView('view_edit');

	}
}