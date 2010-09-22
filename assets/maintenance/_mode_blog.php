<?php

class Std {
	function Std() {}
}

class Data {
	var $_db;
	var $_table;
	var $_tableId;
	var $data;
	
	function Data($table, $tableId='') {
		$this->_table = $table;
		if (empty($tableId)) {
			$tableId = $table.'Id';
		}
		$this->_tableId = $tableId;
		$this->_db = new MiniDb();
		$this->_db->connect();
		$this->data = new Std();
	}
	function id($val=null) {
		$id = $this->_tableId;
		if (isset($val)) {
			$this->data->$id = $val;
		}
		return $this->data->$id;
	}
	function load($filter='', $autoload=false) {
		$sql = 'SELECT * FROM '.$this->_table;
		if ($filter) {
			if (preg_match('/^\d+$/',$filter)) {
				$filter = $this->_tableId.' = '.$filter;
			}
			$sql .= ' WHERE '.$filter;
		}
		$sql .= ' ORDER BY '.$this->_tableId;
		// echo '<pre>'.$sql.'</pre>';
		if ($r = $this->_db->query($sql)) {
			if ($autoload) {
				$this->next();
			}
			return $r;
		} else {
			return false;
		}
	}
	function next() {
		if ($this->data = $this->_db->rNext()) {
			return true;
		}
		return false;
	}
	function insert($dbg=false) {
		$sql = '';
		$arr = get_object_vars($this->data);
		foreach ($arr as $key => $val) {
			if ($sql) {
				$sql .= ', ';
			}
			$sql .= "`$key`='".mysql_escape_string($val)."'";
		}
		$sql = 'INSERT INTO `'.$this->_table.'` SET '.$sql;
		if ($dbg) {
			echo '<pre>'.$sql.'</pre>';
		}
		if ($this->_db->query($sql, $dbg)) {
			if ($id = $this->_db->getInsertId()) {
				$this->id($id);
				return $this->id();
			} else {
				return true;
			}
		} else if ($db >= 2) {
			exit;
		} else {
			return false;
		}
	}
	function free() {
		$this->data = new Std();
		$this->_db->rFree();
	}
}

echo '<ul>';
$i = $s = $c = $d = $f = $m = $e = 0;

$oJournal = new Data('tzn_journal', 'journalId');
$oJournal->load();

$oContent = new Data('tzn_content', 'contentId');

$nContent = new Data('lig_content', 'contentId');
$nBlog = new Data('lig_blog','blogId');

while ($oJournal->next()) {
	if ($i) {
		echo '</li>';
	}

	$i++;
	// temporize
	if ($i % 20 == 0) {
		echo '<script>window.scrollTo(0, document.body.scrollHeight);</script>';
		ob_flush();
		flush();
	}
	if ($i % 50 == 0) sleep(1);
	
	// clean old data
	$nId = 0;
	$oContent->free();
	$nContent->free();

	// get blog content
	$jid = $oJournal->id();
	echo '<li>Id='.$jid;
	
	$cid = $oJournal->data->contentId;
	if ($oContent->load($cid, true)) {
		echo ':';
	} else {
		echo 'FATAL'; exit;
	}
	
	// get options
	$options = unserialize($oJournal->data->options);
	$opt2 = unserialize($oContent->data->options);
	if (is_array($opt2)) {
		$options = array_merge($options, $opt2);
		echo '+';
	}
	unset($opt1, $opt2);
	echo ' ';
	
	// insert content in new DB
	// $pid = $oJournal->data->pageId;
	$pid = 0;
	if (!$pid) {
		if ($options['option_is_event']) {
			$pid = 48;
		} else {
			$options['option_is_event'] = 0;
			$pid = 13;
		}
	}
	switch ($pid) {
		case 13: // blog
			echo '[blog] ';
			$nContent->data->pageId = 14;
			break;
		case 48: // event
			echo '[event] ';
			$nContent->data->pageId = 26;
			break;
		default:
			echo '[unkown '.$pid.'] ';
			$s++;
			break;
	}
	if (!$nContent->data->pageId) {
		continue;
	}
	
	$nContent->data->handle='blog';
	$str = preg_replace(
		array('/[יטךכ]/','/[אבגה]/','/[לםמן]/','/[שתûü]/','/[צפער]/','/[ח‡]/','/[ \'\?\/\\&"]/'),
		array('e','a','i','u','o','c','-'),
		strtolower($oJournal->data->title));
	$str = preg_replace('/[^a-z0-9\-]/','_',$str);
	$nContent->data->shortcut = trim(trim(str_replace('---','-',$str),'_'),'-');
	$short = $nContent->data->shortcut;
	$sql = 'SELECT contentId FROM lig_content WHERE shortcut=';
	$z = 0;
	while ($db->query($sql."'$short'")) {
		$short = $nContent->data->shortcut.'-'.$z++;
	}
	$nContent->data->shortcut = $short;
	$nContent->data->body = $oContent->data->body;
	$nContent->data->options = serialize($options);
	$nContent->data->authorId = $oJournal->data->memberId;
	$nContent->data->lastChangeDate = $oJournal->data->lastChangeDate;
	
	echo $nContent->data->shortcut;
	
	$nId = $nContent->insert();
	if (!$nId) {
		echo '<span class="error">Can not insert content data</span>';
		$f++;
		continue;
	}
	
	// insert blog in new DB
	$nBlog->free();
	$nBlog->data->blogId = $nId;
	$nBlog->data->postDate = $oJournal->data->postDate;
	$nBlog->data->title = $oJournal->data->title;
	$nBlog->data->eventStart = $oJournal->data->beginDate;
	$nBlog->data->eventStop = $oJournal->data->endDate;
	$nBlog->data->summary = $oJournal->data->summary;
	$nBlog->data->publish = $oJournal->data->publish;
	$nBlog->data->private = $oJournal->data->private;
	
	if (!$nBlog->insert()) {
		echo '<span class="error">Can not insert blog data</span>';
		$f++;
		continue;
	}
	
	echo ' <span class="ok">OK</span>, ';
	
	// copy images in new DB
	$oImg = new Data('tzn_contentImg', 'contentImgId');
	$oImg->load('contentId = '.$cid);
	while ($oImg->next()) {
		$nImg = new Data('lig_contentImg', 'contentImgId');
		$nImg->data->contentId = $nId;
		$nImg->data->postDate = $oImg->data->postDate;
		$nImg->data->title = $oImg->data->title;
		$nImg->data->filename = $oImg->data->filename;
		$nImg->data->filetype = $oImg->data->filetype;
		$nImg->data->filesize = $oImg->data->filesize;
		if ($nImg->insert()) {
			@copy(TZN_FILE_SOURCE_PATH.'gallery/'.$oImg->data->filename, TZN_FILE_UPLOAD_PATH.'gallery/'.$oImg->data->filename);
			@copy(TZN_FILE_SOURCE_PATH.'gallery/thumbs/'.$oImg->data->filename, TZN_FILE_UPLOAD_PATH.'gallery/thumbs/'.$oImg->data->filename);
		}
	}
	
	// copy documents in new DB
	$oImg = new Data('tzn_contentDoc', 'contentDocId');
	$oImg->load('contentId = '.$cid);
	while ($oImg->next()) {
		$nImg = new Data('lig_contentDoc', 'contentDocId');
		$nImg->data->contentId = $nId;
		$nImg->data->postDate = $oImg->data->postDate;
		$nImg->data->title = $oImg->data->title;
		$nImg->data->filename = $oImg->data->filename;
		$nImg->data->filetype = $oImg->data->filetype;
		$nImg->data->filesize = $oImg->data->filesize;
		if ($nImg->insert()) {
			@copy(TZN_FILE_SOURCE_PATH.'documents/'.$oImg->data->filename, TZN_FILE_UPLOAD_PATH.'documents/'.$oImg->data->filename);
		}
	}
	
	// load comments
	$nComment = new Data('lig_content','contentId');
	$oComment = new Data('tzn_journalComment','journalCommentId');
	$oComment->load('journalId='.$jid);
	
	// insert comments
	$b = 0;
	while ($oComment->next()) {
		$nComment->data->pageId = $nContent->data->pageId;
		$nComment->data->handle = 'comment-'.$nId;
		$nComment->data->body = $oComment->data->body;
		$nComment->data->lastChangeDate = $oComment->data->lastChangeDate;
		
		// options
		$options = array();
		$options['option_post_date'] = $oComment->data->postDate;
		$options['option_ip_address'] = $oComment->data->ipAddress;
		if ($oComment->data->memberId) {
			$nComment->data->authorId = $oComment->data->memberId;
		} else {
			$options['option_author_name'] = $oComment->data->authorName;
			$options['option_author_email'] = $oComment->data->autorEmail;
		}
		$nComment->data->options = serialize($options);
		
		// insert
		if ($nComment->insert()) {
			$b++;
			$c++;
		} else {
			$d++;
		}
		
		$nComment->free();
	}
	
	echo $b.' comments';
	
	// free results
	$oContent->free();
	
	$m++;
	
}

echo '</li></ul>';
echo "<p>$i articles: $m success, $s skipped, $f failed. $c comments inserted ($d failed)</p>";
	
