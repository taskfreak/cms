<?php
class TwitterRss {
	var $url;
	var $username;
	var $count;
	var $fname;
	var $_xml;
	var $_rss;
	
	function TwitterRss($url='', $tz=0) {
		if (empty($url)) {
			if (defined('CMS_TWITTER_RSS')) {
				$url = CMS_TWITTER_RSS;
			} else {
				die ('Unable to fetch Twitter Feed : No URL supplied');
			}
		}
		if (empty($tz)) {
			if (defined('CMS_TWITTER_TZONE')) {
				$tz = CMS_TWITTER_TZONE;
			} else if (defined('TZN_TZSERVER')) {
				$tz = TZN_TZSERVER;
			}
		}
		$this->url = $url;
		$this->tz = $tz;
		$this->fname = CMS_CACHE_PATH.'twitter.xml';
	}
	
	function _loadRss() {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $this->url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
		$result = curl_exec($ch);
		curl_close($ch);
		if ($result) {
			return $result;
		} else {
			$this->_xml = '';
			return false;
		}
	}
	
	function _parseRss($subwrap, $wrap) {
	
		$this->_rss = simplexml_load_string($this->_xml);
	
		if (!$this->_rss) {
			return false;
		}
	
		$this->username = str_ireplace('Twitter / ', '', $this->_rss->channel->title);
	
		$i = $this->count;
		if ($wrap) {
			$this->_html = '<'.$wrap.'>';
		}
		foreach ($this->_rss->channel->item as $item) {
			if ($i-- <= 0) {
				break;
			}
			if ($subwrap) {
				$this->_html .= '<'.$subwrap.' id="twit-'.($this->count - $i).'">';
			}
			
			$this->_html .= $this->_getPost($item);
			
			if ($subwrap) {
				$this->_html .= '</'.$subwrap.'>';
			}
		}
		if ($wrap) {
			$this->_html .= '</'.$wrap.'>';
		}
	}
	
	function _parseDate($date) {
		$time = strtotime($date);
		$time += $this->tz;
		$curr = time();
		$diff = $curr - $time;
		$str = strftime('le %d/%m/%y &agrave; %H:%M', $time);
		if ($diff <= 60) {
			$str = 'il y a 1 minute';
		} else if ($diff <= 3540) {
			$str = 'il y a '.ceil($diff/60).' minutes';
		} else if ($diff <= 3660) {
			$str = 'il y a 1 heure';
		} else if ($diff <= 64800) {
			$str = 'il y a '.ceil($diff/3600).' heures';
		}
		return $str;
		///' :: $diff." ($curr, $time)";
	}
	
	function _getPost($item) {
		$full = utf8_decode($item->title);
		$full = substr($full, strlen($this->username)+2);
		return htmlspecialchars($full).' <small>'.$this->_parseDate($item->pubDate).'</small>';
	}
	
	function checkCache($autoload=true) {
		
		if (is_file($this->fname)) {
			$ctime = filemtime($this->fname);
			if (time()-$ctime < (60*CMS_TWITTER_INTERVAL)) {
				// error_log('RSS is recent, load cache');
				$this->loadCache();
				return true;
			} else {
				// error_log('RSS is too old');
			}
		}
		if ($autoload) {
			// error_log('-> autoloading ON');
			if (!$this->saveCache()) {
				// error_log('--> can not retreive RSS');
				return $this->loadCache();
			} else {
				// error_log('--> cache is renewed');
				return true;
			}
		}
		return false;
	}
	
	function loadCache() {
		if (is_file($this->fname)) {
			// error_log('---> loading cache');
			$this->_xml = file_get_contents($this->fname);
			return true;
		} else {
			// error_log('no cache');
		}
		return false;
	}
	
	function saveCache() {
		if ($this->_xml = $this->_loadRss()) {
			// error_log('-> RSS retreived, saving new cache');
			file_put_contents($this->fname, $this->_xml);
			return true;
		} else {
			// error_log('-> RSS not retreived');
			return false;
		}
	}
	
	function phtml($count = 3, $subwrap='p', $wrap='') {
		$this->count = $count;
		if ($this->checkCache()) {
			$this->_parseRss($subwrap, $wrap);
		} else {
			$this->_html = '<p>RSS feed invalide</p>';
		}
		echo $this->_html;
	}
	
}
