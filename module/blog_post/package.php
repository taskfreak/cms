<?php

if (!class_exists('ContentBlog')) {
	include_once(CMS_MODULE_PATH.'blog/package.php');
}

class ModuleBlogPost extends TznModule
{

	function ModuleBlogPost() {
		parent::TznModule('blog_post');
	}
	
	/**
	* install/enable module
	*/
	function installEnable() {
		// enable as 1: basic, 2: autoload file, 3: instanciate automatically
		parent::installEnable(1);
	}
		
	/**
	* default call when editing page with this module
	*/
	function adminDefault() {
		
		// load intro and options
		$this->content = new BlogPostIntro();
		$this->content->loadContent($GLOBALS['objPage']->id);
		
		// load confirmation
		$this->confirm = new CmsContent();
		$this->confirm->loadContent($GLOBALS['objPage']->id, 'confirm');
		
		// load no access message
		$this->noaccess = new CmsContent();
		$this->noaccess->loadContent($GLOBALS['objPage']->id, 'noaccess');
		
		if ($GLOBALS['confModule']['blog_post']['editor_full']) {
			$this->content->initAdmin('full',2);
			$this->confirm->initAdmin('full',2);
			$this->noaccess->initAdmin('full',2);
		} else {
			$this->content->initAdmin('Default',1);
			$this->confirm->initAdmin('Default',1);
			$this->noaccess->initAdmin('Default',1);
		}
		
		$this->data = new ContentBlogPost();
		$this->data->loadPostsList(true);
		
		// only if on page
		$GLOBALS['objCms']->initSubmitting(1,2); // save and save and close

		$this->baseLink = CMS_WWW_URI.'admin/special.php?module=blog';
		$this->setView('admin');
		
	}
	
	function adminDefaultAction() {
		
		$this->content->setHttp('body',$_POST['body']);
		$this->content->_options->setHttpAuto();
		$this->content->pageId = $GLOBALS['objPage']->id;
		
		$this->confirm->handle = 'confirm';
		$this->confirm->setHttp('body',$_POST['body_confirm']);
		$this->confirm->pageId = $GLOBALS['objPage']->id;
		
		$this->noaccess->handle = 'noaccess';
		$this->noaccess->setHttp('body',$_POST['body_noaccess']);
		$this->noaccess->pageId = $GLOBALS['objPage']->id;
		
		// now do the DB work
		switch ($GLOBALS['objCms']->submitMode) {
			case 1: // save
			case 2:	// saveclose
			case 3: // saveadd
				$this->content->save();
				$this->confirm->save();
				$this->noaccess->save();
				break;
			case 4: // delete
				$this->content->delete();
				$this->confirm->delete();
				$this->noaccess->delete();
				break;
		}
		
		// and redirect user
		$GLOBALS['objCms']->adminSubmitNext();
	}
	
	/**
	* called on public page (basically sending content)
	*/
	function publicDefault() {
	
		// if admin rights, redirect to admin page
		if ($GLOBALS['objUser']->hasAccess(5)) {
			TznUtils::redirect(CMS_WWW_URI.'admin/special.php?module=blog&action=edit&item='.intval($_REQUEST['item']));
		}
	
		$this->intro = new BlogPostIntro();
		$this->intro->loadContent($GLOBALS['objPage']->id);
		
		if ($this->intro->getOption('member_only') && !$GLOBALS['objUser']->isLoggedIn()) {
		
			$this->more = new CmsContent();
			$this->more->loadContent($GLOBALS['objPage']->id, 'noaccess');
		
		} else {
		
			// decide submit modes
			$GLOBALS['objCms']->initSubmitting(1,2); // save and save and close
		
			// initialize object content
			$this->content = new ContentBlog();
			
			// load item if editing
			if ($pItemId = intval($_REQUEST['item'])) {
				// error_log('editing #'.$pItemId);
				$this->content->loadByFilter('contentId='.$pItemId);
			}
			
			// initialize editor
			if ($GLOBALS['confModule']['blog_post']['post_full']) {
				$this->content->initAdmin('Full',2);
			} else {
				$this->content->initAdmin('Default',1);
			}
			
			$this->success = false;
			
			if ($GLOBALS['objCms']->submitMode) {
			
				$this->content->setHttpAuto();
				if ($this->content->check()) {
					$this->content->pageId = $GLOBALS['objPage']->id;
					$this->content->_join->setDtm('postDate','NOW');
					$this->content->save();
					$this->more = new CmsContent();
					$this->more->loadContent($GLOBALS['objPage']->id, 'confirm');
					$this->success = true;
				}
				
			}
			
			if (!$this->success) {
				// get form labels translations
				//include CMS_MODULE_PATH.'blog/language/fr.php';
				
				// set script for form
				$GLOBALS['objHeaders']->add('css','form.css');
				$GLOBALS['objHeaders']->add('jsScript','common.js');
				$GLOBALS['objHeaders']->add('jsScript','admin.js');
				$GLOBALS['objHeaders']->add('jsCalendar',array('cms_begin','cms_end'));
			}
		
		}
		
		$this->setView('public');
	
	}
}

class BlogPostIntro extends CmsContent
{
	function BlogPostIntro() {
		parent::CmsContent();
		$this->addOptions(array(
			'member_only'		=> 'BOL',
			'allowed_types'		=> 'NUM'
		));
	}
}

class ContentBlogPost extends ContentBlog
{

	function ContentBlogPost() {
		parent::ContentBlog();
	}
	
	function loadPostsList($admin=false, $pagination=0, $page=1) {
		
		if ($admin) {
			$this->addWhere('publish=0');
		} else {
			$this->addWhere('private=0');
			$this->addWhere('publish=1');
		}
		
		if (!$this->_sqlOrder) {
			$this->addOrder('sticky DESC, postDate DESC, contentId DESC'); // -TODO- order option
		}
		
		if ($pagination) {
			$this->setPagination($pagination,$page);
		}
		
		return $this->loadList();
	}

}