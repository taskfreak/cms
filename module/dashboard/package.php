<?php

class ModuleDashboard extends TznModule
{

	function ModuleDashboard() {
		parent::TznModule('dashboard');
		
		if (!class_exists('ContentBlog')) {
			include_once(CMS_MODULE_PATH.'blog/package.php');
		}
		if (!class_exists('ContentPictureGallery')) {
			include_once(CMS_MODULE_PATH.'picture_gallery/package.php');
		}
		
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
		$this->content = new DashboardIntro();
		$this->content->loadContent($GLOBALS['objPage']->id);
				
		// load no access message
		$this->noaccess = new CmsContent();
		$this->noaccess->loadContent($GLOBALS['objPage']->id, 'noaccess');
		
		if ($GLOBALS['confModule']['dashboard']['editor_full']) {
			$this->content->initAdmin('full',2);
			$this->noaccess->initAdmin('full',2);
		} else {
			$this->content->initAdmin('Default',1);
			$this->noaccess->initAdmin('Default',1);
		}
		
		// only if on page
		$GLOBALS['objCms']->initSubmitting(1,2); // save and save and close

		$this->setView('admin');
		
	}
	
	function adminDefaultAction() {
		
		$this->content->setHttp('body',$_POST['body']);
		$this->content->_options->setHttpAuto();
		$this->content->pageId = $GLOBALS['objPage']->id;
				
		$this->noaccess->handle = 'noaccess';
		$this->noaccess->setHttp('body',$_POST['body_noaccess']);
		$this->noaccess->pageId = $GLOBALS['objPage']->id;
		
		// now do the DB work
		switch ($GLOBALS['objCms']->submitMode) {
			case 1: // save
			case 2:	// saveclose
			case 3: // saveadd
				$this->content->save();
				$this->noaccess->save();
				break;
			case 4: // delete
				$this->content->delete();
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
	
		$this->content = new DashboardIntro();
		$this->content->loadContent($GLOBALS['objPage']->id);
		
		if ($this->content->getOption('member_only') && !$GLOBALS['objUser']->isLoggedIn()) {
		
			$this->noaccess = new CmsContent();
			$this->noaccess->loadContent($GLOBALS['objPage']->id, 'noaccess');
		
		} else {
		
			// get list of comments
			$this->dataComms = new DashboardComments();
			$this->dataComms->setPagination(6,1);
			$this->dataComms->loadList();
		
			// get list of blogs
			$this->dataBlogs = new ContentBlog();
			$this->dataBlogs->addWhere('eventStart = \'9999-00-00\'');
			$this->dataBlogs->loadArticleList(14, 0, 3);
			
			// get list of events
			$this->dataEvents = new ContentBlog();
			$this->dataEvents->addWhere('((eventStop <> \'9999-00-00\' AND eventStop >= CURDATE()) OR eventStart >= CURDATE())');
			$this->dataEvents->addOrder('sticky DESC, eventStart ASC, postDate DESC, contentId DESC');
			$this->dataEvents->loadArticleList(26, 2, 3);
		
			// get list of pictures
			$this->dataPhotos = new ContentPictureGallery();
		
		}
		
		$GLOBALS['objHeaders']->add('css',array('form.css','comment.css'));
		$GLOBALS['objHeaders']->add('cssModule','dashboard');
		
		$this->setView('public');
	
	}
}

class DashboardIntro extends CmsContent
{
	function DashboardIntro() {
		parent::CmsContent();
		$this->addOptions(array(
			'member_only'	=> 'BOL',
			'page_size'		=> 'NUM'
		));
	}
}

class DashboardComments extends TznDb
{
	function DashboardComments() {
		parent::TznDb('content');
		$this->addProperties(array(
			'id'				=> 'UID',
			'body'				=> 'TXT',
			'options'			=> 'STR',
			'lastChangeDate'	=> 'DTM',
			'blog'				=> 'OBJ',
			'page'				=> 'OBJ,TznPage',
			'shortcut'			=> 'STR',
			'member'			=> 'OBJ'
		));
	}
	
	function getPostDate() {
		$opt = unserialize($this->options);
		return $this->getDtm('','LNX',TZN_TZDEFAULT,$opt['option_post_date']);
	}
	
	function getAuthorName() {
		if ($this->member->id) {
			return $this->member->getShortName();
		} else {
			$opt = unserialize($this->options);
			return $opt['option_author_name'];
		}
	}
	
	function getUrl() {
		return CMS_WWW_URI.$this->shortcut.'.html';
	}
	
	function loadList() {
		$sql = 'SELECT tcc.contentId, tcc.body, tcc.options, tcc.lastChangeDate, '
			.'tba.title AS blog_title, tba.eventStart AS blog_eventStart, tba.eventStop AS blog_eventStop, '
			."CONCAT_WS('/',tpg.shortcut,tbc.shortcut) AS shortcut, "
			.'tcc.authorId AS memberId, firstName AS member_firstName, lastName AS member_lastName, '
			.'nickName AS member_nickName, avatar AS member_avatar, email AS member_email, level AS member_level '
			.'FROM '.$this->gTable().' AS tcc '
			.'INNER JOIN '.$this->gTable('blog').' AS tba ON blogId=SUBSTRING(tcc.handle,9) '
			.'INNER JOIN '.$this->gTable().' AS tbc ON blogId=tbc.contentId '
			.'INNER JOIN '.$this->gTable('page').' AS tpg ON tpg.pageId=tbc.pageId '
			.'LEFT JOIN '.$this->gTable('member').' AS tmm ON tcc.authorId = tmm.memberId ';
		$this->addWhere("tcc.handle LIKE 'comment-%'");
		$this->addOrder('tcc.lastChangeDate DESC');
		return parent::loadList($sql);
	}
}
