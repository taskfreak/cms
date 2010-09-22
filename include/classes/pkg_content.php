<?php


class CmsObject extends TznDb
{

	var $_options;
	var $_join;
	var $_contentScript;
	/**
	* constructor of a class meant to be abstract
	*/
	function CmsObject($joinClass='') {
		parent::TznDb('content');
		$this->addProperties(array(
			'id'				=> 'UID',
			'pageId'			=> 'NUM',
			'handle'			=> 'STR',
			'shortcut'			=> 'STR',
			'body'				=> 'HTM',
			'options'			=> 'STR',
			'author'			=> 'OBJ,member',
			'lastChangeDate'	=> 'DTM'
		));
		$this->_options = new CmsObjectOption();
		if ($joinClass) {
			$this->_join = new $joinClass();
		}
	}
	/**
	* get content URL
	*/
	function getUrl($full=false) {
		// $objPage =& $GLOBALS['objPage']; -TOCHECK-
		$curId = -1;
		if (is_object($GLOBALS['objPage'])) {
			$objPage = $GLOBALS['objPage']->clone4();
			$curId = $GLOBALS['objPage']->id;
		}
		if ($curId != $this->pageId) {
			$objPage = new TznPage();
			$objPage->setUid($this->pageId);
			$objPage->load();
		}
		// error_log('getUrl('.(($full)?'true':'false').') : item page '.$this->pageId.'/'.$curId.' : '.substr($this->body,0,15));
		if ($this->shortcut && preg_match('/^\./',CMS_REWRITE_URL)) {
			if ($full) {
				return CMS_WWW_URL.$objPage->shortcut.'/'.$this->shortcut.'.html';
			} else {
				return CMS_WWW_URI.$objPage->shortcut.'/'.$this->shortcut.'.html';
			}
		} else {
			return TznUtils::concatUrl($objPage->getUrl($full), 'item='.$this->id);
		}
	}
	/**
	* default content : just send body
	*/
	function getContent() {
		return $this->body;
	}	
	/**
	* get icon for page list / site map
	*/
	function getIcon($module=null,$show=true,$special=false) {
		$img = CMS_WWW_URI.'assets/images/i_';
		if ($module && file_exists(CMS_WWW_PATH.'module/'.$module.'/images/i_item_on.png')
			&& file_exists(CMS_WWW_PATH.'module/'.$module.'/images/i_item_off.png'))
		{
			$img = 'module/'.$module.'/images/item';
		} else {
			$img .= 'page_';
		}
		if ($special) {
			$img .= 'hi_';
		}
		if ($show) {
			$img .= 'on';
		} else {
			$img .= 'off';
		}
		$img .= '.png';
		return $img;
	}
	
	/**
	* set properties from values received by http request (post method)
	*/
	function setHttpAuto() {
	
		// basic stuff
		$id = $this->id; // needed because submitted ID is page ID, not content ID
		
		$this->setAuto($_POST);
				
		$this->id = intval($id); // and now put it back (even if empty)
		
		// shortcut
		$shortcut = ($_POST['shortcut'])?'shortcut':'title';
		$shortbase = TznUtils::convURI($_POST[$shortcut]);	// sanitize
		
		if ($shortbase) {
			$objTmp1 = new TznDb('page');
			$objTmp2 = new TznDb('content');
			
			$sql = $i = 0;
			do {
				// check shortcut is not already used
				if ($sql) {
					$i++;
					$sql = "shortcut = '$shortbase_$i'";
				} else {
					$sql = "shortcut = '$shortbase'";
				}
			} while ($objTmp1->loadByFilter($sql) || $objTmp2->loadByFilter($sql.' AND contentId != '.$this->id));
			$this->shortcut = $shortbase.($i?('_'.$i):'');
		}
		
		// options
		$this->_options->setHttpAuto();
		
		// joined object
		if (is_object($this->_join)) {
			$this->_join->setAuto($_POST);
			$this->_join->id = $this->id;
		}

	}
	
	/**
	* parse options read from DB
	*/
	function _parseOptions() {
		$this->_options->stringToOptions($this->options);
	}
	/**
	* add options
	*/
	function addOptions($arrOpt) {
		$arrNewOpt = array();
		foreach($arrOpt as $key => $value) {
			$key = 'option_'.$key;
			$arrNewOpt[$key] = $value;
		}
		$this->_options->addProperties($arrNewOpt);
	}
	
	function setOptionDefaults($arr) {
		$this->_options->setDefaults($arr);
	}
	/**
	* set option value
	*/
	function setOption($key,$value) {
		$key = 'option_'.$key;
		$this->_options->set($key,$value);
	}
	/**
	* get option value
	*/
	function getOption($key) {
		$key = 'option_'.$key;
		if (is_numeric($this->_options->$key)) {
			return $this->_options->$key;
		} else {
			return $this->_options->get($key);
		}
	}
	/**
	* initialize load query
	*/
	function _getLoadQuery() {
		$sqlSel = 'SELECT '.$this->gTable().'.*, '
			.'author.username as author_username, author.password as author_password, author.salt as author_salt, author.autoLogin as author_autoLogin, author.timeZone as author_timeZone, author.creationDate as author_creationDate, author.expirationDate as author_expirationDate, author.lastLoginDate as author_lastLoginDate, author.lastLoginAddress as author_lastLoginAddress, author.lastChangeDate as author_lastChangeDate, author.visits as author_visits, author.badAccess as author_badAccess, author.level as author_level, author.activation as author_activation, author.enabled as author_enabled, author.email as author_email, author.title as author_title, author.firstName as author_firstName, author.middleName as author_middleName, author.lastName as author_lastName, author.nickName as author_nickName, author.avatar as author_avatar, author.companyName as author_companyName, author.address as author_address, author.city as author_city, author.zipCode as author_zipCode, author.stateCode as author_stateCode, author.countryId as author_countryId, author.cmsLanguage as author_cmsLanguage, author.authorId as author_authorId';
		$sqlFrm = ' FROM '.$this->gTable()
			.' INNER JOIN '.$this->_join->gTable().' as j1 ON '.$this->gField('contentId').' = j1.'.$this->_join->getIdKey()
			.' LEFT JOIN '.$this->gTable('member').' as author ON author.memberId='.$this->gTable().'.authorId';
		foreach($this->_join->_properties as $key => $type) {
			if (preg_match('/^OBJ/i',$type)) {
				$nKey = $key.'Id';
				if (strlen($type) > 3) {
					$nKey = substr($type,4);
				}
				$class = (strlen($type) > 3)?substr($type,4):$key;
				$obj = new $class();
				
				$sqlFrm .= ' LEFT JOIN '.$obj->gTable().' ON j1.'.$nKey.'='.$obj->gTable().'.'.$obj->getIdKey();
				// select ID
				$sqlSel .= ', j1.'.$nKey.' as j1_'.$nKey;
				// select all fields
				foreach ($obj->_properties as $nkey => $ntype) {
					if ($ntype == 'UID') {
						continue;
					}
					$sqlSel .= ', '.$obj->gTable().'.'.$nkey.' as '.$key.'_'.$nkey;
				}
			} else if ($type == 'UID') {
				$sqlSel .= ', j1.'.$this->_join->getIdKey().' as j1_'.$key;
			} else {
				$sqlSel .= ', j1.'.$key.' as j1_'.$key;
			}
		}
		return $sqlSel.$sqlFrm;
	}
	
	/*
	function _setItem($obj) {
		$ok =& parent::_setItem($obj);
		if (is_object($ok->_join)) {
			foreach($ok->_join->_properties as $key => $type) {
				$nestkey = 'j1_'.$key;
				$ok->_join->set($key, $obj->$nestkey);
			}
		}
		return $ok;
	}
	*/
	
	/**
	* load content corresponding to page
	*/
	function loadContent($pageId, $handle='') {
		$this->setNum('pageId', $pageId);
		if (!$this->pageId) {
			return false;
		}
		$handle = TznUtils::sanitize(TZN_SANITIZE_SIMPLE, $handle);
		return $this->loadByFilter('pageId='.$this->pageId.' AND handle=\''.$handle.'\'');
	}
	/**
	* load object and options from DB
	*/
	function loadByFilter($filter) {
		$ok = false;
		if (is_object($this->_join)) {
			$ok = parent::loadByQuery($this->_getLoadQuery().' WHERE '.$filter);
		} else {
			$ok = parent::loadByFilter($filter);
		}
		if ($ok) {
			$this->_parseOptions();
			return true;
		}
		return false;
	}
	/**
	* load list of objects
	*/
	function loadList() {
		if (is_object($this->_join)) {
			//$this->addGroup('j1_id');
			return parent::loadList($this->_getLoadQuery(), TZN_DB_COUNT_AUTO);
		} else {
			return parent::loadList();
		}
	}
	
	/**
	* parse options when getting object from a list
	*/
	function rNext() {
		$obj = parent::rNext();
		if ($obj) {
			$obj->_parseOptions();
		}
		return $obj;
	}
	
	/**
	* save (add or update)
	*/
	function save() {	
		$this->_saveCommon();
		return parent::save();	
	}
	/**
	* add to DB
	*/
	function add() {
		$this->_saveCommon();
		if ($ok = parent::add()) {
			if (is_object($this->_join)) {
				$this->_join->id = $this->id;
				$this->_join->add();
			}
			return $ok;
		} else {
			return false;
		}
		
	}
	/**
	* replace in DB
	*/
	function replace() {
		$this->_saveCommon();
		if ($ok = parent::replace()) {
			$this->_saveJoin();
			return $ok;
		} else {
			return false;
		}
	}
	/**
	* update to DB
	*/
	function update($fields=null) {
		$this->_saveCommon();
		if ($ok = parent::update($field)) {
			if (is_object($this->_join)) {
				$this->_join->id = $this->id;
				$this->_join->update();
			}
			return $ok;
		} else {
			return false;
		}
	}
	/**
	* delete in DB
	*/
	function delete() {
		if (is_object($this->_join)) {
			$this->_join->delete();
		}
		return parent::delete();
	}
	/** 
	* common tasks when saving
	* i.e. date of last change, options and content
	*/
	function _saveCommon() {
		$this->setDtm('lastChangeDate','NOW');
		if (!$this->author->id) {
			$this->author->id = $GLOBALS['objUser']->id;
		}
		$this->options = $this->_options->optionsToString();
	}
	/**
	* save joined object if any
	*/
	function _saveJoin() {
		if (is_object($this->_join)) {
			$this->_join->id = $this->id;
			$this->_join->replace();
		}
	}
	/**
	* load images (if content is enabled)
	*/
	function loadImgList() {
		return $this->content->loadImgList();
	}
	/**
	* load documents (if content is enabled)
	*/
	function loadDocList() {
		return $this->content->loadDocList();
	}
	
}

class CmsObjectPage extends CmsObject
{
	function CmsObjectPage($join='') {
		parent::CmsObject($join);
		$this->removeProperties('pageId');
		$this->addProperties(array(
			'page' => 'OBJ,tznPage'
		));
	}
	
	function getUrl() {
		if ($this->handle) {
			if (preg_match('/^comment\-([0-9]+)$/', $this->handle, $arrMatch)) {
				return TznUtils::concatUrl($this->page->getUrl(), 'item='.$arrMatch[1]);
			} else if ($this->shortcut && preg_match('/^\./',CMS_REWRITE_URL)) {
				return substr($this->page->getUrl(),0,-strlen(CMS_REWRITE_URL)).'/'.$this->shortcut.'.html';
			} else {
				return TznUtils::concatUrl($this->page->getUrl(), 'item='.$this->id);
			}
		} else {
			return $this->page->getUrl();
		}
	}
	
	function getSummary() {
		$str = '';
		switch ($this->handle) {
		case 'blog':
			$obj = new Blog();
			$obj->setUid($this->id);
			if ($obj->load()) {
				$str = substr($obj->title,0,50).' : ';
			}
			break;
		}
		return $str.substr(strip_tags($this->body),0,200);
	}
	
	function getType() {
		$str = TznCms::getTranslation($this->page->module, 'langModule', 'name');
		if (preg_match('/^comment\-([0-9]+)$/', $this->handle, $arrMatch)) {
			$str.=' comment';
		}
		return $str;
	}
}

class CmsObjectOption extends Tzn
{

	function CmsObjectOption() 
	{
		
	}

	function setHttpAuto() {
		if (count($this->_properties)) {
			foreach($this->_properties as $key => $type) {
	        	if ($type == 'BOL') {
	        		$this->$key = isset($_POST[$key])?((intval($_POST[$key]))?1:0):0;
	        	} else if (preg_match('/^IMG/i',$type)) {
					// upload
	        		$arrType = explode(',',$type);
	        		$arrType[0] = $key; 
	        		call_user_func_array(array(&$this,'uploadFile'),$arrType);
	        	} else {
	        		//error_log('setting option '.$key.' as '.$_POST[$key]);
	        		$this->setHttp($key,$_POST[$key]);
				}
	        }
		}
	}

	function optionsToString() {
		$arrOptions = array();
		if (count($this->_properties)) {
			foreach($this->_properties as $key => $type) {
				$value =& $this->$key;
				if (is_object($value)) {
					if (is_a($value, 'TznFile')) {
						$value->saveOptions = $this->getSaveOptions($key);
						$str = $value->saveAction();
					} else {
						$str = $value->id;
					}
					$arrOptions[$key] = $str;
					$this->$key = $str;
				} else if (isset($value)) {
					$arrOptions[$key] = $value;
				}
			}
		}
		return serialize($arrOptions);
	}

	function stringToOptions($str) {
		if (is_array($this->_properties)) {
			foreach($this->_properties as $key => $type) {
				if (!isset($this->$key)) {
					$this->$key = '';
				}
			}
		}
		if ($str) {
			$arrOptions = unserialize($str);
			foreach ($arrOptions as $key => $value) {
				$this->$key = $value;
			}
		}
	}
	
	function setDefaults($arr) {
		if (count($arr)) {
			foreach ($arr as $key => $value) {
				$this->$key = $value;
			}
		}
	}
	
	function getOptionsArray() {
		$arr = array();
		foreach($this->_properties as $key => $type) {
			$arr[$key] = $this->$key;
		}
		return $arr;
	}
	
}

/**
* complete content with linked images and documents
*/
class CmsContent extends CmsObject
{
	
	var $_mode;
	var $_upload;
	var $_imgList;
	var $_docList;
	var $_layoutList;
	
	function CmsContent($joinClass='') {
		parent::CmsObject($joinClass);
		$this->addOptions(array(
			'layout'		=> 'STR'
		));
	}
	
	function initAdmin($mode = 'default', $upload = 0) {
	
		/* MODES :
			FULL : The full stuff 
			DEFAULT : An already pretty stuffed editor
			MINI : Simplified fontwise, but still well featured
			MICRO : Simple layout options
			NANO : Very basic, No layout options
		*/
	
	
		$this->_mode = ucFirst(strtolower($mode));
		$this->_upload = intval($upload);
		
		switch ($this->_upload) {
		case 1:
			// allow customized upload
			if ($_POST['uploadmode'] == 'img') {
				$pFileType = 'img';
				include CMS_WWW_PATH.'assets/editor/php/admin_upload.php';
				exit;
			}
			
			if ($_POST['uploadmode'] == 'doc') {
				$pFileType = 'doc';
				include CMS_WWW_PATH.'assets/editor/php/admin_upload.php';
				exit;	
			}
			
			$this->_mode .= 'Upl';
			
			$GLOBALS['objHeaders']->add('css',CMS_WWW_URI.'assets/editor/css/editor.css');
			$GLOBALS['objHeaders']->add('jsScript',CMS_WWW_URI.'assets/editor/scripts/editor.js');
			
			break;
		case 2:
			// allow access to file manager
			$this->_mode .= 'Upl';
			break;
		}
		
		$GLOBALS['objHeaders']->add('jsEditor',true);
		
		if ($this->_upload == 1) {
			// --- photos ---
			$this->loadImgList();
			
			// --- documents ---
			$this->loadDocList();
			
			// ---- LOAD LAYOUTS ---		
			$this->_layoutList = new ContentLayout();
			$this->_layoutList->loadList();
		}

	}
	
	/**
	* 
	*/
	function adminSubmitNext() {
		if ($GLOBALS['objCms']->submitMode == 1) {
			// not closing
			if ($this->_upload == 1){
				// full mode : reload images and docs
				$this->loadImgList();
				$this->loadDocList();
			}
		}
		
	}
	
	function initPublic() {
		// load photos
		$this->loadImgList();
		
		// load documents
		$this->loadDocList();
		
		// add css corresponding to layout
		if ($layout = $this->getOption('layout')) {
			$GLOBALS['objHeaders']->add('css',CMS_WWW_URI.'assets/editor/layout/'.$layout.'/public.css');
		}
	}
	
	function printContent($absurl=false) {
	
		// absurl : true sets absolute URLS to images and documents
		$objContent =& $this;
		
		if ($layout = $this->getOption('layout')) {
			// layout defined
			// prepare photos
			$arrImg = array();
			if ($objContent->_imgList) {
				$i = 0;
				while ($objFile = $objContent->_imgList->rNext()) {
					if ($absurl) {
						// -TODO-
						$objFile->_absoluteUrl = true;
					}
					$arrImg[$i++] = $objFile->clone4();
				}
				$objContent->_imgList->rFree();
				unset($objContent->_imgList);
			}
			
			// prepare documents
			$arrDoc = array();
			if ($objContent->_docList) {
				$i = 0;
				while ($objFile = $objContent->_docList->rNext()) {
					$arrDoc[$i++] = $objFile->clone4();
				}
				$objContent->_docList->rFree();
				unset($objContent->_docList);
			}
			
			include CMS_WWW_PATH.'assets/editor/layout/'.$layout.'/public.php';	
			
			
		} else {
		
			// no layout chosen, just print body
			$this->p('body');
		}
		
	}
	
	function qEditArea($name='body',$height='350') {
		if ($this->_upload == 1) {
			include CMS_WWW_PATH.'assets/editor/php/admin_form.php';
		} else {
			$this->qTextarea($name,$this->body,'','style="width:99%;height:'
				.(($height)?$height:'350').'px"');
			// echo "\n".'<textarea name="'.$name.'" style="width:99%;height:'.(($height)?$height:'350').'px">';
			echo "\n".'<script type="text/javascript">CKEDITOR.replace(\''.$name.'\',{toolbar:\''.$this->_mode.'\'';
			if ($this->_upload == 2) {
				$base = CMS_WWW_URI.'assets/kcfinder/';
				echo ", filebrowserBrowseUrl : '${base}browse.php', "
					."filebrowserImageBrowseUrl : '${base}browse.php?type=images', "
					."filebrowserFlashBrowseUrl : '${base}browse.php?type=flash', "
					."filebrowserUploadUrl : '${base}upload.php', "
					."filebrowserImageUploadUrl : '${base}upload.php?type=images', "
					."filebrowserFlashUploadUrl : '${base}upload.php?type=flash'";
			}
			echo '});</script>';
		}
	}
	
	function add() {
		if (parent::add()) {
			if ($this->_upload == 1) {
				ContentImg::updateList($this->id);
				ContentDoc::updateList($this->id);
			}
			return true;
		} else {
			return false;
		}
	}
	
	function replace() {	
		if (parent::replace()) {
			if ($this->_upload == 1) {
				ContentImg::updateList($this->id);
				ContentDoc::updateList($this->id);
			}
			return true;
		} else {
			return false;
		}
	}
	
	function update() {
		if (parent::update()) {
			if ($this->_upload == 1) {
				ContentImg::updateList($this->id);
				ContentDoc::updateList($this->id);
			}
			return true;
		} else {
			return false;
		}
	}
	
	function delete() {
		// delete images
		if ($this->loadImgList()) {
			while ($obj = $this->_imgList->rNext()) {
				$obj->delete();
			}
		}
		// delete documents
		if ($this->loadDocList()) {
			while ($obj = $this->_docList->rNext()) {
				$obj->delete();
			}
		}
		// delete comments
		$this->query('DELETE FROM '.$this->gTable()
			." WHERE handle='comment-".$this->id."'");
		// delete content
		return parent::delete();
	}
		
	function loadImgList() {
		$this->_imgList = new ContentImg();
		//error_log('loading images');
		if ($this->id) {
			//error_log(' > id = '.$this->id);
			$this->_imgList->addWhere('contentId='.$this->id);
			$this->_imgList->addOrder('contentImgId ASC');
			return $this->_imgList->loadList();
		}
		return false;
	}
	
	function loadDocList() {
		$this->_docList = new ContentDoc();
		if ($this->id) {
			$this->_docList->addWhere('contentId='.$this->id);
			$this->_docList->addOrder('contentDocId ASC');
			return $this->_docList->loadList();
		}
		return false;
	}
	
	function compareContents($obj) {
		if ($this->body != $obj->body) {
			// different content (text)
			return true;
		}
		if ($this->getOption('option_layout') != $obj->getOption('option_layout')) {
			// different layout
			return true;
		}
		// check changes in images
		if ($_POST['imgnw'] || $_POST['imgdel']) {
			return true;
		}
		// check changes in documents
		if ($_POST['docnw'] || $_POST['docdel']) {
			return true;
		}
		return false;
	}
}

class ContentImg extends TznDb
{
	function ContentImg() {
		parent::TznDb('contentImg');
		$this->addProperties(array(
			'id'			=> 'UID',
			'contentId'		=> 'NUM',
			'postDate'		=> 'DTM',
			'title'			=> 'STR',
			'filename'		=> 'IMG,'
				.'(w:'.CMS_EDITOR_IMG_WDH.',h:'.CMS_EDITOR_IMG_HGT.',f:gallery/),'
				.'(w:'.CMS_EDITOR_THB_WDH.',h:'.CMS_EDITOR_THB_HGT.',f:gallery/thumbs/)',
			'filetype'		=> 'STR',
			'filesize'		=> 'STR'
		));
	}
	
	function getInfo() {
		return '<span class="imgname" title="'.dirname($this->filename).'">'.basename($this->filename).'</span> <small>('.$this->filesize.')</small>';
	}
	
	function getInfoTags() {
		return '<div class="col c60" title="'.dirname($this->filename).'">'
			.basename($this->filename).'</div>'
			.'<div class="col c30">('.$this->filesize.')</div>';
	}
	
	function setFile($params) {
		
		$objFile = new TznFile();
		
		// set properties
		$arrFile = explode(';',$params);
		$objFile->tempName = $arrFile[0];
		$objFile->origName = $arrFile[1];
		$this->filetype = $objFile->fileType = $arrFile[2];
		$this->filesize = $objFile->fileSize = $arrFile[3];
		
		// set save options
		$arrOpts = TznUtils::strToArray($this->_properties['filename']);
		if (count($arrOpts) > 1) {
			array_shift($arrOpts);
			$objFile->saveOptions = $arrOpts;
		}
		
		// set file object
		$this->filename = $objFile;

	}
	
	function add() {
		$this->setDtm('postDate','NOW');
		return parent::add();
	}
	
	function delete() {
		if (!$this->filename) {
			$this->load();
		}
		TznFile::delete($this->filename);
		return parent::delete();
	}
	
	function updateList($id) {
		// error_log('updating images... ID='.$id);
		if ($_POST['imgnw']) {
			// error_log('we have '.count($_POST['imgnw']).' new images');
			foreach($_POST['imgnw'] as $key => $params) {
				// error_log("key=$key params=$params");
				$objFile = new ContentImg();
				$objFile->setFile($params);
				$objFile->contentId = $id;
				// $objFile->dump('error_log');
				$objFile->add();
			}
		}
		
		if ($_POST['imgdel']) {
			$arrFileDel = explode(';',$_POST['imgdel']);
			foreach ($arrFileDel as $fileId) {
				if ($fileId = intval($fileId)) {
					$objFile = new ContentImg();
					$objFile->setUid($fileId);
					$objFile->delete();
				}
			}
		}
	
	}
	
}

class ContentDoc extends TznDb
{
	function ContentDoc() {
		parent::TznDb('contentDoc');
		$this->addProperties(array(
			'id'			=> 'UID',
			'contentId'		=> 'NUM',
			'postDate'		=> 'DTM',
			'title'			=> 'STR',
			'filename'		=> 'DOC,(f:documents)',
			'filetype'		=> 'STR',
			'filesize'		=> 'STR'
		));
	}
	
	function getInfo($cut=0) {
		$filename = basename($this->filename);
		if ($cut && ($cut < strlen($filename))) {
			$cut = ($cut / 2) -1;
			$filename = substr($filename,0,$cut).'..'.substr($filename,-$cut);
		}
		return $filename.' <small>('.$this->filesize.')</small>';
	}
	
	function getUrl() {
		return TZN_FILE_UPLOAD_URL.'documents/'.parent::getUrl('filename');
	}
	
	function setFile($params) {
		
		$objFile = new TznFile('document');
		
		// set properties
		$arrFile = explode(';',$params);
		$objFile->tempName = $arrFile[0];
		$objFile->origName = $arrFile[1];
		$this->filetype = $objFile->fileType = $arrFile[2];
		$this->filesize = $objFile->fileSize = $arrFile[3];
		
		// set save options
		$arrOpts = TznUtils::strToArray($this->_properties['filename']);
		if (count($arrOpts) > 1) {
			array_shift($arrOpts);
			$objFile->saveOptions = $arrOpts;
		}
		
		// set file object
		$this->filename = $objFile;

	}
	
	function add() {
		$this->setDtm('postDate','NOW');
		return parent::add();
	}
	
	function delete() {
		if (!$this->filename) {
			$this->load();
		}
		TznFile::delete($this->filename);
		return parent::delete();
	}
	
	function updateList($id) {
		if ($_POST['docnw']) {
			foreach($_POST['docnw'] as $key => $params) {
				$objFile = new ContentDoc();
				$objFile->setFile($params);
				$objFile->contentId = $id;
				$objFile->add();
			}
		}
		
		if ($_POST['docdel']) {
			$arrFileDel = explode(';',$_POST['docdel']);
			foreach ($arrFileDel as $fileId) {
				if ($fileId = intval($fileId)) {
					$objFile = new ContentDoc();
					$objFile->setUid($fileId);
					$objFile->delete();
				}
			}
		}
	}
	
}

class ContentLayout extends TznCollection
{

	function ContentLayout() {
		parent::TznCollection(null);
	}
	
	function getImg($key) {
		return $key.'/icon.png';
	}
	
	function loadList() {

		$this->_data = array();
		
		$folder = CMS_WWW_PATH.'assets/editor/layout/';

		if ($handle = opendir($folder)) {

			while (false !== ($file = readdir($handle))) {
				if (is_dir($folder.$file) && !preg_match('/^(\..*|CVS)$/',$file)) {
					$this->_data[$file] = str_replace('_',' ',substr($file,strpos($file,'_')+1));
				}
			}
			
			ksort($this->_data);

		   closedir($handle);
		}

	}
	
}

class CmsComment extends CmsObject
{

	function CmsComment() {
		parent::CmsObject();
		$this->_properties['body'] = 'BBS';
		$this->addOptions(array(
			'post_date'		=> 'DTM',
			'author_name'	=> 'STR',
			'author_email'	=> 'EML'
		));
	}
	
	function setPost($data) {
		$this->pageId = intval($data['page']);
		$this->handle = 'comment-'.intval($data['item']);
		if (!$this->id) {
			// new comment, set author details
			if ($GLOBALS['objUser']->isLoggedIn()) {
				// user is logged in
				$this->author->id = $GLOBALS['objUser']->id;
			} else {
				// user is not logged in
				$this->setOption('author_name', Tzn::getHttp($data['option_author_name']));
				$this->setOption('author_email', Tzn::getHttp($data['option_author_email']));
				$this->author->id = -1; // prevent overwrite with current user id
			}	
		}
		$this->setHttp('body', $data['body']);
	}
	
	function getPostDate() {
		return $this->_options->getDtm('option_post_date','LNX');
	}
	
	function getAuthorName() {
		if ($this->author->id) {
			return $this->author->getShortName();
		} else {
			return $this->getOption('author_name');
		}
	}
	
	function _ajaxLoad($id) {
		$objComment = new CmsComment();
		$objComment->setUid($id);
		
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
	
	function ajaxEdit() {
		// load comment
		$objComment = self::_ajaxLoad($_REQUEST['id']);
		
		// generate form and javascript
		$str = '<script type="text/javascript">'
			.'var body_edit_'.$objComment->id.'=$("comment_body_'.$objComment->id.'").get("html");'
			.'</script>';
		$str .= '<form id="comment_edit_'.$objComment->id.'" action="'.CMS_WWW_URI.'ajax.php" method="post" '
			.'onsubmit="return ajaxify_form(this,\'comment_body_'.$objComment->id.'\');">'
			.'<input type="hidden" name="module" value="comment" />'
			.'<input type="hidden" name="action" value="update" />'
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
	
	function ajaxUpdate() {
		// load comment
		$objComment = self::_ajaxLoad($_REQUEST['id']);
		
		// update database
		$objComment->setHttp('body', utf8_decode($_REQUEST['body']));
		$objComment->save();

		// return new body
		$objComment->p('body');
	}
	
	function ajaxDelete() {
		// load comment
		$objComment = self::_ajaxLoad($_REQUEST['id']);
		$objComment->delete();
		$str = '<script type="text/javascript">';
		$str .= '$("comment_'.$objComment->id.'").destroy();';
		$str .= '</script>';
		echo $str;
	}
	
	function check() {
		$GLOBALS['objPage']->checkCaptcha(!$GLOBALS['objUser']->isLoggedIn());
		if ($this->author->id < 1) {
			$this->_options->checkEmpty('option_author_name,option_author_email');
		}
		$this->checkEmpty('body');
		$this->pageId = $GLOBALS['objPage']->id;
		return (!$this->hasError() && !$this->_options->hasError() && $GLOBALS['objPage']->_captchaOk);
	}
	
	function add() {
		$this->setOption('post_date',TZN_SQL_NOW);
		return parent::add();
	}

}