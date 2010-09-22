<?php

class ModuleBlog extends TznModule
{

	function ModuleBlog() {
		parent::TznModule('blog');
	}
	
	/**
	* install/enable module
	*/
	function installEnable() {
		// enable as 1: basic, 2: autoload file, 3: instanciate automatically
		parent::installEnable($GLOBALS['confModule']['blog']['autoload']);
		$objDb = new TznDb('blog');
		$objDb->query(
			'CREATE TABLE IF NOT EXISTS '.$objDb->gTable().' ('
			.'`blogId` mediumint(8) unsigned NOT NULL, '
			.'`postDate` date NOT NULL, '
			.'`title` varchar(255) NOT NULL, '
			.'`eventStart` date NOT NULL, '
			.'`eventStop` date NOT NULL, '
			.'`summary` text NOT NULL, '
			.'`sticky` tinyint(1) unsigned NOT NULL, '
			.'`publish` tinyint(1) unsigned NOT NULL, '
			.'PRIMARY KEY `blogId` (`blogId`)'
			.') ENGINE=MyISAM '
		);
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
	*/
	function adminDefault() {
		
		$order = 0; // show latest posts by default
		
		// decide submit modes
		if ($GLOBALS['objPage']->id) {
			// load intro and options
			$this->content = new BlogIntro();
			$this->content->loadContent($GLOBALS['objPage']->id);
			
			$order = $this->content->getOption('order_type');
			
			$this->content->initAdmin('Full',2);
			
			// only if on page
			$GLOBALS['objCms']->initSubmitting(1,2); // save and save and close
		}
	
		// initialize object content
		$this->data = new ContentBlog();
		
		// load list corresponding to the page
		$this->data->loadArticleList($GLOBALS['objPage']->id, $order, 12, intval($_REQUEST['pg']));
	
		// set script for form
		$this->baseLink = CMS_WWW_URI.'admin/page.php?id='.$GLOBALS['objPage']->id;
		$this->setView('admin_page');
		
	}
	
	/**
	* default action from left menu (special)
	*/
	function adminSpecial() {
		$this->adminDefault();
		$this->baseLink = CMS_WWW_URI.'admin/special.php?module=blog';
		$this->setView('admin_list');
	}
	
	function adminEdit() {
	
		// decide submit modes
		$GLOBALS['objCms']->initSubmitting(1,2); // save and save and close
	
		// initialize object content
		$this->content = new ContentBlog();
		
		if ($pItemId = intval($_REQUEST['item'])) {
			// load item if editing
			$this->content->loadByFilter('contentId='.$pItemId);
		} else {
			// set defaults when creating new item
			$this->content->_join->set('publish',$GLOBALS['confModule']['blog']['defaults']['publish']);
			$this->content->_join->set('private',$GLOBALS['confModule']['blog']['defaults']['private']);
			if ($GLOBALS['confModule']['blog']['defaults']['layout']) {
				$this->content->setOption('layout',$GLOBALS['confModule']['blog']['defaults']['layout']);
			}
		}
		
		if (!$GLOBALS['objPage']->id) {
			$this->objPageList = new TznPage();
			$this->objPageList->addWhere("module = 'blog'");
			$this->objPageList->addOrder('position');
			$this->objPageList->loadList();
		}
		
		// initialize editor
		switch ($GLOBALS['confModule']['blog']['editor_full']) {
		case 'full':
			$this->content->initAdmin('Full',2);
			break;
		case 'mini':
			$this->content->initAdmin('Mini',1);
			break;
		default:
			$this->content->initAdmin('Default',1);
			break;
		}
	
		// set script for form
		$GLOBALS['objHeaders']->add('jsCalendar',array('cms_date','cms_begin','cms_end'));
		
		$this->setView('admin_item');
	}
	
	function adminDelete() {
		$this->content = new ContentBlog();
		if ($this->content->loadByFilter('contentId='.intval($_REQUEST['item']))) {
			$this->content->delete();
		}
		TznUtils::redirect(TznUtils::getReferrer(true,true));
	}
	
	function adminDeletePage($pageId) {
		$objItemList = new ContentBlog();
		if ($objItemList->loadList('pageId='.$pageId)) {
			while ($objItem = $objItemList->rNext()) {
				$objItem->delete();
			}
		}
	}
		
	/**
	* called on public page (basically sending content)
	*/
	function publicDefault() {
	
		$this->intro = new BlogIntro();
		$this->intro->loadContent($GLOBALS['objPage']->id);
		
		$this->content = new ContentBlog();
		
		// load content corresponding to page
		if ($pItemId = TznUtils::sanitize(TZN_SANITIZE_SIMPLE,$_REQUEST['item'])) {
		
			// load article
			$filter = 'pageId='.$GLOBALS['objPage']->id.' AND contentId='.$pItemId;
			if (!$GLOBALS['objUser']->isLoggedIn()) {
				$filter .= ' AND publish=1 AND private=0';
			} else if (!$GLOBALS['objUser']->hasAccess(1, $this->folder)) {
				$filter .= ' AND publish=1';
			}
			$this->content->loadByFilter($filter);
			$this->content->initPublic();
			
			if ($this->content->getOption('comments_allow')) {
			
				// load comments only if allowed within the post
				// and allow post only if allowed for everyone or user is logged in
				$this->initComments(
					$pItemId, 
					!$this->content->getOption('comments_private') || $GLOBALS['objUser']->isLoggedIn()
				);
			}
			
			$GLOBALS['objPage']->title = $this->content->_join->title;
				// .' | '.$GLOBALS['objPage']->title;
			
			$GLOBALS['objPage']->description = $this->content->getSummary().'. '.$GLOBALS['objPage']->description;
			
			$this->setView('public_item');
			
		} else {
		
			$pid = 0;
			if ($this->content->getOption('page_only')) {
				$pid = $GLOBALS['objPage']->id;
			}
		
			// load list of articles
			$this->content->loadArticleList($pid, 
				$this->intro->getOption('order_type'),
				$this->intro->getOption('pagination'), intval($_REQUEST['pg']));
				
			$this->setView('public_list');
			
		}
		
		$GLOBALS['objHeaders']->add('cssModule','blog');
		
	}
	
	/* --- Newsletter module hacks ----------------------------------- */
	
	function newsletterInit() {
		// load articles to show in admin when creating / editing newsletter
		$this->_itemList = new ContentBlog();
		// load news blogs and coming events only
		$this->_itemList->addWhere('('
			."(eventStart='9999-00-00' AND postDate <= CURDATE() AND postDate > DATE_SUB(CURDATE(), INTERVAL 3 MONTH))"
			." OR ((eventStart >= CURDATE() OR (eventStop <> '9999-00-00' AND eventStop >= CURDATE())) AND eventStart < DATE_ADD(CURDATE(), INTERVAL 3 MONTH))"
			.')');
		// $this->_itemList->setDbDebug(3);
		$this->_itemList->loadNewsletterList();
	}
	
	function newsletterSelect($label) {
		// show list of items
		$this->_itemList->qSelect($label,'getTitle()','','- Selectionner pour ajouter -','wxl','onchange="cms_news_add(this)"');
	}
	
	function newsletterItem($id) {
		$this->_item = new ContentBlog();
		if ($this->_item->loadByFilter('contentId='.$id)) {
			return $this->_item;
		} else {
			return false;
		}
	}
	
	function newsletterView() {
		// show item
		echo '-TODO- BlogModule::newsletterView()'; exit;
	}
	
}

class BlogIntro extends CmsContent
{
	function BlogIntro() {
		parent::CmsContent();
		$this->addOptions(array(
			'date_in_list'		=> 'BOL',
			'author_in_list'	=> 'BOL',
			'intro_in_item'		=> 'BOL',
			'date_in_item'		=> 'BOL',
			'author_in_item'	=> 'BOL',
			'order_type'		=> 'NUM',
			'page_only'			=> 'BOL',
			'pagination'		=> 'NUM'
		));
	}
}

class Blog extends TznDb
{

	var $_cleanSticky;
	
	function Blog() {
		parent::TznDb('blog');
		$this->addProperties(array(
			'id'			=> 'UID',
			'postDate'		=> 'DTE',
			'eventStart'	=> 'DTE',
			'eventStop'		=> 'DTE',
			'title'			=> 'STR',
			'sticky'		=> 'BOL',
			'summary'		=> 'BBS',
			'publish'		=> 'BOL',
			'private'		=> 'NUM'
				// 0 = public, 1 = protected, 2 = private
		));
		$this->_cleanSticky = false;
		if ($GLOBALS['confModule']['blog']['db_prefix']) {
			$this->_db_prefix = $GLOBALS['confModule']['blog']['db_prefix'];
		}
	}
	
	function setAuto($data, $nested='') {
		$pSticky = $this->sticky;
		parent::setAuto($data, $nested);
		if ($this->sticky && !$pSticky) {
			// marked as sticky (and was not sticky before)
			// remove all other sticky marked (when saving)
			$this->_cleanSticky = true;
		}
	}
	
	function cleanSticky() {
		if ($this->_cleanSticky && @constant('CMS_BLOG_SINGLE_STICKY')) {
			$this->getConnection();
			$this->query('UPDATE '.$this->gTable().' SET sticky=0 WHERE sticky=1');
			$this->_cleanSticky = false;
		}
	}
	
	function checkDates() {
		if (!$this->hasValue('eventStart')) {
			$this->eventStart = '9999-00-00';
			$this->eventStop = '9999-00-00';
			return false;
		} else if (!$this->hasValue('eventStop')) {
			$this->eventStop = $this->eventStart;
		}
		return true;
	}
	
	function add() {
		$this->cleanSticky();
		return parent::add();
	}
	
	function replace() {
		$this->cleanSticky();
		return parent::replace();
	}
	
	function update($fields='') {
		$this->cleanSticky();
		return parent::update($fields);
	}
	
	function save() {
		$this->cleanSticky();
		return parent::save();
	}
	
}

class ContentBlog extends CmsContent
{

	function ContentBlog() {
		parent::CmsContent('Blog');
		$this->addOptions(array(
			'comments_allow'	=> 'BOL',
			'comments_private'	=> 'BOL',
			'is_event'			=> 'BOL'
		));
		$this->handle = 'blog';
		if ($GLOBALS['confModule']['blog']['db_prefix']) {
			$this->_db_prefix = $GLOBALS['confModule']['blog']['db_prefix'];
		}
	}
	
	function newsletterTitle() {
		$str = '<em>';
		if ($this->getOption('is_event')) {
			$str .= 'Evenement';
		} else {
			$str .= 'Article';
		}
		return $str.'</em> : '.$this->getTitle();
	}
	
	function newsletterHtml() {
		return '<h4>'.$this->getTitle()."</h4>\n"
			.'<p>'.$this->getSummary()."<br />\n"
			.'<a href="'.$this->getUrl(true).'">lire l\'article</a>'
			."</p>\n";
	}
	
	function newsletterText() {
		return '* '.$this->getTitle()." *\n"
			.$this->getSummary()."\n"
			.$this->getUrl(true)."\n\n";
	}
	
	function isEvent() {
		return $this->_join->hasValue('eventStart');
	}
	
	function getDates($format='SQL', $format2='') {
		if (empty($format2)) {
			$format2 = $format;
		}
		if ($this->_join->hasValue('eventStop') && ($this->_join->get('eventStop') != $this->_join->get('eventStart'))) {
			return 'du '.$this->_join->get('eventStart',$format).' au '.$this->_join->get('eventStop',$format2);
		} else {
			return 'le '.$this->_join->get('eventStart',$format2);
		}
	}

	function getTitle() {
		$str = $this->_join->get('title');
		/*
		if ($this->_join->hasValue('eventStart')) {
			$str = $this->_join->getDte('eventStart',CMS_DATE).' : '.$str;
		}
		*/
		return $str;
	}
	
	function getSummary($cut=210) 
	{
		if ($this->_join->summary) {
			return $this->_join->getStr('summary',$cut);
		} else if ($this->body) {
			return substr(str_replace(array("\n","\r"),array(" ",""),strip_tags($this->body)),0,$cut).'...';
		} else {
			return false;
		}
	}
	
	function getCommentCount() {
		return $this->loadCount("SELECT COUNT('contentId') AS rowCount FROM "
			.$this->gTable()
			." WHERE handle='comment-".$this->id."'");
	}
	
	function getLinkMore() {
		$str = '<a href="'.$this->getUrl().'">lire l\'article</a>';
		if ($this->getOption('comments_allow') && $GLOBALS['confModule']['blog']['comments']) {
			$str .= '&nbsp; <span class="comments">'.$this->getCommentCount().' commentaire'.(($comcnt>1)?'s':'').'</span>';
		}
		return $str;
	}
	
	function check() {
		if ($this->_join->checkDates() || $this->getOption('is_event')) {
			$this->setOption('is_event',1);
			return $this->_join->checkEmpty('eventStart,title');
		} else {
			return $this->_join->checkEmpty('title');
		}
	}

	function loadLast($pageId=0)
	{
		$objItemList = new ContentBlog();
		if ($pageId) {
			$objItemList->addWhere('pageId = '.$pageId);
		}
		$objItemList->addWhere('postDate <= CURDATE()');
		$objItemList->addWhere('publish = 1');
		if (!$GLOBALS['objUser']->isLoaded()) {
			// can only see public posts
			$objItemList->addWhere('private=0');
		}
		$objItemList->addOrder('sticky DESC, postDate DESC, contentId DESC');
		$objItemList->setPagination(1);
		// $objItemList->setDbDebug(3);
		if ($objItemList->loadList(TZN_DB_COUNT_OFF)) {
			if ($objItem = $objItemList->rNext()) {
				return $objItem;
			}
		} 
		return false;
	}
	
	function loadArticleList($pageId=0, $order=0, $pagination=0, $page=1) {
		if ($pageId) {
			$this->addWhere($this->gField('pageId').'='.$pageId);
		}
		
		if ($GLOBALS['objUser']->isLoggedIn()) {
			if (!$GLOBALS['objUser']->hasAccess(1,'blog')) {	
				// load published posts only
				$this->addWhere('publish=1');
			}
			// -TODO- private not only user's posts but also team's
			$this->addWhere('(private=0 OR private=1 OR '
				.'(private=2 AND '.$this->gField('authorId','content').'='.$GLOBALS['objUser']->id.'))');
		} else {
			// can only see public posts
			$this->addWhere('private=0');
			$this->addWhere('publish=1');
		}
		if (!$this->_sqlOrder) {
			switch ($order) {
			case 1: // blog archives
				$this->addWhere("eventStart = '9999-00-00' AND postDate < NOW()");
				$this->addOrder('sticky DESC, postDate ASC, contentId ASC');
				break;
			case 2: // events
				$this->addWhere("eventStop > NOW() AND eventStart <> '9999-00-00'");
				$this->addOrder('sticky DESC, eventStart ASC, contentId ASC');
				break;
			case 3: // event archives
				$this->addWhere('eventStart < NOW()');
				$this->addOrder('sticky DESC, eventStop DESC, contentId DESC');
				break;
			default: // blog news
				$this->addWhere("eventStart = '9999-00-00' AND postDate < NOW()");
				$this->addOrder('sticky DESC, postDate DESC, contentId DESC');
				break;
			}
			// $this->addOrder('sticky DESC, postDate DESC, contentId DESC');
		}
		
		if ($pagination) {
			$this->setPagination($pagination,$page);
		}
		return $this->loadList();
	}
	
	function loadNewsletterList() {
		$this->addWhere('private=0');
		$this->addWhere('publish=1');
		$this->addOrder('eventStart ASC, postDate DESC, contentId DESC');
		return $this->loadList();
	}
	
}