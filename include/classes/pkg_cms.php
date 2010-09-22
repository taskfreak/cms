<?php
/****************************************************************************\
* TZN CMS                                                                    *
******************************************************************************
* Version: 2.0                                                               *
* Authors: Stan Ozier <stan@tirzen.com>                                      *
* License:  http://www.gnu.org/licenses/gpl.txt (GPL)                        *
******************************************************************************
* This file is part of "TZN CMS" program.                                    *
*                                                                            *
* TZN CMS is free software; you can redistribute it and/or                   *
* modify it under the terms of the GNU General Public License as published   *
* by the Free Software Foundation; either version 2 of the License, or (at   *
* your option) any later version.                                            *
*                                                                            *
* TZN CMS is distributed in the hope that it will be                         *
* useful, but WITHOUT ANY WARRANTY; without even the implied warranty of     *
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the              *
* GNU General Public License for more details.                               *
*                                                                            *
* You should have received a copy of the GNU General Public License          *
* along with this program; if not, write to the Free Software                *
* Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA *
\****************************************************************************/
/**
 * 
 * @author   Stan Ozier <stan@tirzen.net>
 * @package pkg_cms
 */

define('CMS_PROTECTION_INIT','00000');

class CmsLanguageList extends TznCollection
{
    function CmsLanguageList() {
		parent::TznCollection($GLOBALS['confLanguageCodes']);
    }
}

class TznCollectable {
	/**
	* coming soon
	*/	
	function _init($key,$reset=false) {
		if (!is_array($this->$key) || $reset) {
			$this->$key = array();
		}
	}
	/**
	* set item (reset)
	*/	
	function set($key, $value) {
		$this->add($key,$value,true);
	}
	/**
	* add item (bottom)
	*/
	function add($key,$value,$reset=false) {
		$this->_init($key,$reset);
		if (!$reset) {
			$skip = $key.'Skip';
			if (is_array($this->$skip) && in_array($value, $this->$skip)) {
				return false;
			}
		}
		if (is_array($value)) {
			$this->$key = array_unique(array_merge($this->$key, $value));
		} else if (!in_array($value,$this->$key)) {
			$this->{$key}[] = $value;
		}
		return true;
	}
	/**
	* add item (top)
	*/
	function ins($key,$value,$reset=false) {
		$this->_init($key,$reset);
		if (!$reset) {
			$skip = $key.'Skip';
			if (is_array($this->$skip) && in_array($value, $this->$skip)) {
				return false;
			}
		}
		if (is_array($value)) {
			$this->$key = array_unique(array_merge($this->$key, $value));
		} else if (!in_array($value,$this->$key)) {
			array_unshift($this->$key, $value);
		}
		return true;
	}
	/**
	* remove included file from header
	*/
	function remove($key, $value) {
		if (is_array($this->$key) && in_array($value,$this->$key)) {
			$newArray = array();
			foreach ($this->$key as $val) {
				if ($val == $value) {
					continue;
				}
				$newArray[] = $val;
			}
			$this->$key = $newArray;
		} else {
			$key = $key.'Skip';
			$this->{$key}[] = $value;
		}
	}

}

class TznCms extends TznCollectable
{

	var $accessLevel;
	var $announcement;
	var $menus;
	var $modules;
	var $settings;
	var $includePage;
	var $submitOptions; // array of submit options: 1:save, 2:saveclose, 3:saveadd, 4:delete
	var $submitMode; // submit mode corresponding to submit options
	var $submitScript;

	/**
	* constructor
	* initialize plugins, messaging system, referrers filo
	*/
	function TznCms() {
		$this->modules = array();
		
		if ($arr = TznUtils::initPlugins()) {
			foreach($arr as $plugin) {
				$this->initPlugin($plugin);
			}
		}
		
		TznUtils::initMessaging();
		TznUtils::initReferring();
	}
	
	/**
	* initialize Cms objects
	* @param int $right required right to access page
	* @param int $autoload will load all modules if settings say so
	*/
	
	function init($right, $autoload=true) {
		
		$GLOBALS['objCms'] = new TznCms();
		$GLOBALS['objUser'] = new Member();
		
		$GLOBALS['objHeaders'] = new TznHeaders();

		// load settings : check modules
		// and include module file if needed
		$GLOBALS['objCms']->settings = new TznSetting();
		$GLOBALS['objCms']->settings->loadSettings(($right >= 5)?true:false); // force module autoload in admin section
		
		// check session
		@header("Content-type: text/html; charset=".CMS_CHARSET);
		@session_start();
		
		// check user login (or auto login if possible)
		$GLOBALS['objUser']->checkLogin(true); // and load user
				
		// check access level
		if ($right) {
			// level check is requested (redirect if access denied)
			$GLOBALS['objUser']->checkAccess($right);
			define('CMS_SECURITY', true);
		}
		
		// get system language pref
		$lang = $GLOBALS['objCms']->settings->get('default_language','fr');
		
		// get user language pref
		// -TODO-
		
		// get page language pref
		// -TODO-
		
		define('CMS_LANGUAGE', $lang);
		
		// include language files
		include CMS_INCLUDE_PATH.'language/'.CMS_LANGUAGE.'/common.php';
		include CMS_INCLUDE_PATH.'language/'.CMS_LANGUAGE.'/config.php';
		include CMS_INCLUDE_PATH.'language/'.CMS_LANGUAGE.'/cms.php';
		
		// default data format
		$dft = 'EUR';
		if ($GLOBALS['objCms']->settings->get('date_us_format')) {
			$dft = 'USA';
		}
		define('CMS_DEFAULT_DATEFORMAT',$dft);
		
		// admin page stuff
		if ($right >= 5) {
			$GLOBALS['objCms']->adminCore();
		}

		if ($autoload) {
		
			// autoload modules (and reload session object if necessary)
			$GLOBALS['objCms']->autoLoadModules();

		}
				
		// load user pages
		$GLOBALS['objUser']->initUserPages();
		
	}
	
	/* --- PLUGIN STUFF ------------------------------------------------------- */
	
	function initPlugin($plugin) {
		$plugin = 'Plugin'.TznUtils::strToCamel($plugin, true);
		$objPlugin = new $plugin();
		// error_log("--> initialize $plugin for : ".$objPlugin->super.' in '.$objPlugin->folder);
		$this->plugins[$objPlugin->super][$objPlugin->folder] = $objPlugin;
	}
	
	function getPluginObject($key) {
		foreach ($this->plugins as $class => $arr) {
			if (array_key_exists($key, $arr)) {
				return $arr[$key];
			}
		}
		return false;
	}
	
	
	/* --- MODULE MANAGEMENT -------------------------------------------------- */
	
	function initModule($module, $option) {
		// add module to list of available modules
		$this->modules[$module] = $option;
	}
	
	function autoLoadModules($force=false) {
		// load module automagically
		foreach($this->modules as $module => $setting) {
			if ($setting == 3 || $force) {
				$this->loadModule($module);
				if (method_exists($this->modules[$module], 'reloadSession')) {
					$this->modules[$module]->reloadSession();
					// call_user_func(array($className, 'reloadSession'));
				}
			}
		}
	}
	
	function loadModule($module) {
	
		if (array_key_exists($module, $this->modules) && is_object($this->modules[$module])) {
			return true;
		}
	
		// initialize module
		if (!is_file(CMS_MODULE_PATH.$module.'/package.php')) {
			TznUtils::log_warn("package.php not found for module '$module'");
			return false;
		}
		include_once(CMS_MODULE_PATH.$module.'/package.php');
		$className = 'Module'.str_replace(' ','',ucWords(str_replace('_',' ',$module))); // -TODO- call TznUtils::strToCamel
		// error_log("initializing $module (class $className)");
		if (class_exists($className)) {
			// module exists and class is defined
			{
				$this->modules[$module] = new $className();
				return true;
			}
		}
		TznUtils::log_warn("class '$className' not defined in package.php for module '$module'");
		return false;
	}
		
	function getModuleObject($module) {
		// instantiate module object (initialize if needded)
		//if (!array_key_exists($module, $this->modules) || !is_object($this->modules[$module])) {
			if (!$this->loadModule($module)) {
				// -TODO- add to list of stuff
				return false;
			}
		//}
		return $this->modules[$module];
	}
	
	/* --- SUBMIT STUFF (for admin) ------------------------------------------- */

	/**
	* initialize submit method
	* @param int submit mode (as many as wished)
	*/	
	function initSubmitting() {
		$this->submitOptions = func_get_args();
		if ($_POST['save']) {
			$this->submitMode = 1;
		} else if ($_POST['saveclose']) {
			$this->submitMode = 2;
		} else if ($_POST['saveadd']) {
			$this->submitMode = 3;
		} else if ($_POST['delete']) {
			$this->submitMode = 4;
		} else {
			$this->submitMode = 0;
		}
		// echo 'submit mode: '.$this->submitMode; 
		// if ($this->submitMode) exit;
	}
	
	/**
	* print save buttons
	*/
	function adminSubmitButtons() {
		if (is_array($this->submitOptions)) {
			foreach($this->submitOptions as $subopt) {
				switch ($subopt) {
					case 1: // save
						echo '<button type="submit" name="save" value="1" class="save">'
							.TznCms::getTranslation('save','langSubmit').'</button>';
						break;
					case 2: // save and close
						echo ' <button type="submit" name="saveclose" value="2" class="saveclose">'
							.TznCms::getTranslation('saveclose','langSubmit').'</button>';
						break;
					case 3: // save and add
						echo ' <button type="submit" name="saveadd" value="3" class="saveadd">'
							.TznCms::getTranslation('saveadd','langSubmit').'</button>';
						break;
					case 4:	// delete
						echo ' <button type="submit" name="delete" value="4" class="delete">'
							.TznCms::getTranslation('delete','langSubmit').'</button>';
						break;
				}
			}
		}
	}
	
	
	/**
	* what to do after submit
	*/
	function adminSubmitNext() {
		/* error_log('so what now : '.$this->submitMode);
		error_log('-> '.implode(', ',$_SESSION['tznReferrers'])); */
		switch ($this->submitMode) {
			case 1: // save
				// just saved, do nothing, just show again
				break;
			case 2: // saveclose
				// redirect to previous page in referrer filo
				TznUtils::redirect(TznUtils::getReferrer(true, true));
				break;
			case 3: // saveadd
				// just saved, redirect to this page with empty form
				TznUtils::redirect(TznUtils::getReferrer(false, false));
				break;
			case 4: // delete
				// redirect to previous page in referrer filo
				TznUtils::redirect(TznUtils::getReferrer(true, true));
				break;
		}
	}
	
	/* --- MISCELANEOUS ------------------------------------------------------- */
	
	function adminCore() {
	
		include CMS_INCLUDE_PATH.'language/'.CMS_LANGUAGE.'/admin.php';

		$GLOBALS['confAdminMenu'] = array(
			'content' => array(
				'pages'	=> 'admin/page.php'
			),
			// 'newsletter'...
			'administration' => array(
				'members'	=> 'admin/member.php'
			)
		);
		$canEmails = $GLOBALS['objUser']->hasAccess(27);
		$canModule = $GLOBALS['objUser']->hasAccess(28);
		$canConfig = $GLOBALS['objUser']->hasAccess(29);
		if ($canEmails || $canModule || $canConfig) {
			$GLOBALS['confAdminMenu']['system'] = array();
			if ($canEmails) {
				$GLOBALS['confAdminMenu']['system']['email'] = 'admin/email.php';
			}
			if ($canModule) {
				$GLOBALS['confAdminMenu']['system']['modules'] = 'admin/module.php';
				// $GLOBALS['confAdminMenu']['system']['plugins'] = 'admin/plugin.php';
			}
			if ($canConfig) {
				$GLOBALS['confAdminMenu']['system']['settings'] = 'admin/setting.php';
			}
		}
		
		if (CMS_TEAM_ENABLE) {
			$GLOBALS['confAdminMenu']['administration']['teams'] = 'admin/team.php';
		}
		
		// force including CSS from assets directory by passing full path
		
		$GLOBALS['objHeaders']->add('css',array(
			CMS_WWW_URI.'assets/css/common.css',
			CMS_WWW_URI.'assets/css/admin.css',
			CMS_WWW_URI.'assets/css/form.css'
		));
		
		$strCss = "<!--[if IE]>\n"
		.'<link rel="stylesheet" type="text/css" href="../assets/css/admin.ie.css" />'
		."\n<![endif]-->\n";
		
		$GLOBALS['objHeaders']->add('cssCode', $strCss);
		
		$GLOBALS['objHeaders']->add('jsScript',array('mootools-1.2.4.js','common.js', 'admin.js'));
	}
	
	function adminMenu() {
		if ($GLOBALS['objUser']->hasAccess(5)) {
			include CMS_INCLUDE_PATH.'html/floating_menu.php';
		}
	}
	
	function getAdminRef() {
		$url = $_SERVER['REQUEST_URI'];
		if ($_POST['id']) {
			$url = TznUtils::concatUrl($url, 'id='.$_POST['id']);
		}
		if ($_POST['module']) {
			$url = TznUtils::concatUrl($url, 'module='.$_POST['module']);
		}
		if ($_POST['mode']) {
			$url = TznUtils::concatUrl($url, 'mode='.$_POST['mode']);
		}
		if ($_POST['action']) {
			$url = TznUtils::concatUrl($url, 'action='.$_POST['action']);
		}
		if ($_POST['item']) {
			$url = TznUtils::concatUrl($url, 'item='.$_POST['item']);
		}
		if (isset($_REQUEST['backtopage'])) {
			$url = str_replace('&backtopage','',$url);
		}
		return str_replace('&amp;','&',$url);
	}
	
	function setAdminRef($ref='') {
		if (!$ref) {
			$ref = TznCms::getAdminRef();
		}
		if (!strpos($ref,'?') || preg_match('/special\.php\?module=([a-z0-9_-]+)$/', $ref)) {
			TznUtils::setReferrer(CMS_WWW_URI.'admin/');
		}
		TznUtils::addReferrer($ref);
	}
	
	function errorFatal($message) {
		TznUtils::addMessage('ERROR:'.$message);
		TznCms::errorPage('cmserror');
	}
	
	function errorPage($code) {
		switch ($code) {
			case '404':
				if (preg_match('/'.TZN_ROBOT_AGENT.'/', $_SERVER['HTTP_USER_AGENT'])) {
					header("Status : 404 Not Found");
					header("HTTP/1.1 404 Not Found");
				}
			case '403':
			case 'unpublished':
			case 'cmserror':
			case 'maintenance':
				$pToInclude = $code;
				break;
			default:
				$pToInclude = '500'; // server error
				break;
		
		}
		
		if (is_file(CMS_WWW_PATH.'template/_error/'.$pToInclude.'.php')) {
			include CMS_WWW_PATH.'template/_error/'.$pToInclude.'.php';
		} else {
			include CMS_INCLUDE_PATH.'core/error/'.$pToInclude.'.php';
		}
		
		exit;
		
	}
	
	function getHeader($admin=false) {
		include CMS_INCLUDE_PATH.'html/header.php';
		if ($admin) {
			include CMS_INCLUDE_PATH.'html/admin_header.php';
		}
	}
	
	function getFooter($admin=false) {
		if ($admin) {
			if (is_array($GLOBALS['confAdminMenu'])) {
				include CMS_INCLUDE_PATH.'html/admin_menu.php';
			}
			include CMS_INCLUDE_PATH.'html/admin_footer.php';
		}
		include CMS_INCLUDE_PATH.'html/footer.php';
	}
	
	function getUri($url='') {
		return CMS_WWW_URI.$url;
	}
	
	function getTranslation($label, $arr, $field='') {
		if (!is_array($GLOBALS[$arr])) {
			return $label;
		}
		if (array_key_exists($label, $GLOBALS[$arr])) {
			if (is_array($GLOBALS[$arr][$label])) {
				return $GLOBALS[$arr][$label][$field];
			} else {
				return $GLOBALS[$arr][$label];
			}
		} else {
			return $label;
		}
	}
	
	function getLogo($logo='logo-cms.png') {
		if (file_exists(CMS_WWW_PATH.'template/default/images/'.$logo)) {
			return CMS_WWW_URI.'template/default/images/'.$logo;
		} else {
			return CMS_WWW_URI.'assets/images/'.$logo;
		}
	}
	
	function getCopyright($withVersion=false) {
		return '<a href="http://cms.tirzen.com"><img src="'
		.$this->getLogo('logo-cms-mini.png')
		.'" border="0" alt="Tirzen Content Management System" />TZN CMS'
		.(($withVersion)?' v'.($this->settings->get('cms_version').' GNU General Public License'):'')
		.'</a>';
	}
	
}

class TznHeaders extends TznCollectable
{
	var $css;		// link to css file
		// location: current template, default template, /css folder
	var $cssModule;	// link to module specific css file (name of module)
		// location: current template, default template, module/css
	var $cssCode;	// direct style in HTML's head section
	var $jsScript;	// link to js file
		// location: current template, default template, /js folder
	var $jsEditor;  // include CK editor
	var $jsScriptCode; // direct js code in HTML's head section
	var $jsOnLoad;	// body on load js calls
	var $jsOnDown;	// body on down js calls
	var $jsCalendar;	// date fields
	var $rss;		// rss link
	
	/**
	* HTML's head section ($objCms->head)
	*/	
	function TznHeaders() {
	}
	
	/**
	* Print Page Headers - include js, css, xml-rss and javascript direct code
	*/	
	function printHead() {
	
		if (count($this->jsCalendar)) {
			$this->add('css',CMS_WWW_URI.'assets/css/calendar.css');
			$this->add('jsScript', CMS_WWW_URI.'assets/js/calendar.js');
			foreach($this->jsCalendar as $it) {
				if (is_string($it)) {
					$it=trim($it);
					$this->add('jsOnLoad',"new Calendar({ '$it': 'd/m/y' })");
				}
			}
        }
       
        // css
        if (count($this->css)) {
			foreach($this->css as $it) {
				if (strrpos($it,'/')) {
					echo '<link rel="stylesheet" type="text/css" href="'.$it.'" />'."\n";
				} else if (file_exists(CMS_WWW_PATH.'template/'.CMS_TEMPLATE.'/css/'.$it)) {
					echo '<link rel="stylesheet" type="text/css" href="'.CMS_WWW_URI.'template/'.CMS_TEMPLATE.'/css/'.$it.'" />'."\n";
				} else if (file_exists(CMS_WWW_PATH.'template/default/css/'.$it)) {
					echo '<link rel="stylesheet" type="text/css" href="'.CMS_WWW_URI.'template/default/css/'.$it.'" />'."\n";
				} else if (file_exists(CMS_WWW_PATH.'assets/css/'.$it)) {
					echo '<link rel="stylesheet" type="text/css" href="'.CMS_WWW_URI.'assets/css/'.$it.'" />'."\n";
				}
			}
		}
		
		// module specific css
        if (count($this->cssModule)) {
			foreach($this->cssModule as $it) {
				$jt = $it;
				if (!preg_match('/\.css$/',$jt)) {
					$jt .= '.css';
				} else {
					$it = substr($it,0,-4);
				}
				if ($pos = strpos($it,'.')) {
					$it = substr($it,0,$pos);
				}
				if (strrpos($jt,'/')) {
					echo '<link rel="stylesheet" type="text/css" href="'.$jt.'" />'."\n";
				} else if (file_exists(CMS_WWW_PATH.'template/'.CMS_TEMPLATE.'/css/'.$jt)) {
					echo '<link rel="stylesheet" type="text/css" href="'.CMS_WWW_URI.'template/'.CMS_TEMPLATE.'/css/'.$jt.'" />'."\n";
				} else if (file_exists(CMS_WWW_PATH.'template/default/css/'.$jt)) {
					echo '<link rel="stylesheet" type="text/css" href="'.CMS_WWW_URI.'template/default/css/'.$jt.'" />'."\n";
				} else if (file_exists(CMS_WWW_PATH.'module/'.$it.'/css/'.$jt)) {
					echo '<link rel="stylesheet" type="text/css" href="'.CMS_WWW_URI.'module/'.$it.'/css/'.$jt.'" />'."\n";
				} else if (file_exists(CMS_WWW_PATH.'assets/css/'.$jt)) {
					echo '<link rel="stylesheet" type="text/css" href="'.CMS_WWW_URI.'assets/css/'.$jt.'" />'."\n";
				}
			}
		}
		
		// css code (ie tests)
		if (count($this->cssCode)) {
            echo implode("\n",$this->cssCode)."\n";        
        }
        
        // javascript CK editor
        if ($this->jsEditor) {
        	echo '<script type="text/javascript" src="'.CMS_WWW_URI.'assets/ckeditor/ckeditor.js" language="javascript"></script>';
        }
		
		// javascrpt direct code
		if (count($this->jsScriptCode)) {
            echo '<script type="text/javascript">'."\n";
            echo implode("\n",$this->jsScriptCode);
            echo "\n</script>\n";           
        }
        
		// xml/rss
        if (count($this->rss)) {
			foreach($this->rss as $it) {
				echo '<link rel="alternate" type="application/rss+xml" title="RSS Feed" href="'
					.CMS_WWW_URL.$it.'" />'."\n";
			}
		}
		
		// google analytics
		if (
			@constant('CMS_GOOGLE_ANALYTICS') // Google code defined
			&& ($_SERVER['REMOTE_ADDR'] != '127.0.0.1') // not local copy (dev version)
			&& (!preg_match('/^\/admin\//',$_SERVER['REQUEST_URI'])) // not accessing admin page
		) {
		?>
		<script type="text/javascript">		
		var _gaq = _gaq || [];
		_gaq.push(['_setAccount', '<?php echo CMS_GOOGLE_ANALYTICS; ?>']);
		_gaq.push(['_trackPageview']);
		
		(function() {
		var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
		ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
		var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
		})();
		</script>
		<?php
		}
		
	}
	/**
	* Print to <body> tag
	*/
	function printBodyJs() {
		$str = '';
	    if (count($this->jsOnLoad)) {
	    	$str .= ' onload="'.implode(';',$this->jsOnLoad).'"';
	    }
	    if (count($this->jsOnDown)) {
	    	$str .= ' onmousedown="'.implode(';',$this->jsOnDown).'"';
	    }
		echo $str;
	}
	
	/**
	 * Print at end of <body> tag
	 */
	function printFoot() {
		// javascript (include)
		if (count($this->jsScript)) {
			echo "\n";
			foreach($this->jsScript as $it) {
				$it = trim($it);
				if (strrpos($it,'/')) {
					echo '<script type="text/javascript" src="'.$it.'" language="javascript"></script>'."\n";
				} else if (file_exists(CMS_WWW_PATH.'/template/'.CMS_TEMPLATE.'/js/'.$it)) {
					echo '<script type="text/javascript" src="'.CMS_WWW_URI.'template/'
						.CMS_TEMPLATE.'/js/'.trim($it).'" language="javascript"></script>'."\n";
				} else if (file_exists(CMS_WWW_PATH.'template/default/js/'.$it)) {
					echo '<script type="text/javascript" src="'.CMS_WWW_URI.'template/'
						.'default/js/'.trim($it).'" language="javascript"></script>'."\n";
				} else if (file_exists(CMS_WWW_PATH.'assets/js/'.$it)) {
					echo '<script type="text/javascript" src="'.CMS_WWW_URI.'assets/js/'
						.trim($it).'" language="javascript"></script>'."\n";
				}
			}
		}
	}
}

class TznSetting extends TznDb
{
	
	var $_arrValues;
	var $_arrModules;
	/**
	* constructor
	*/	
	function TznSetting()
	{
		parent::TznDb('setting');
		$this->addProperties(array(
			'settingKey' 			=> 'STR',
			'settingValue'			=> 'STR'
		));
		$this->_arrValues = array();
	}
	/**
	* get setting
	*/
	function get($key,$default='') {
		return ($this->_arrValues[$key])?$this->_arrValues[$key]:$default;
	}
	/**
	* set setting
	*/	
	function set($key, $value) {
		if (!$value) {
			$value = 0;
		}
		$this->_arrValues[$key] = $value;
	}
	/**
	* save settings
	*/	
	function saveValue($key,$value) {
		$this->set($key, $value);
		$this->getConnection();
		return $this->query('REPLACE INTO '.$this->gTable()
			.' SET settingKey=\''.$key.'\''
			.', settingValue=\''.addslashes($this->get($key)).'\'');
	}
	/**
	* load settings from DB
	* @param bool $forceautoload if true, will autoload all modules (used to set up admin)
	*/	
	function loadSettings($forceautoload=false) {
		if ($this->loadList()) {
			while ($objSet = $this->rNext()) {
			
				if (preg_match('/^module_([a-z\_-]*)/i', $objSet->settingKey, $arr)) {
				
					// preload modules
					$module = $arr[1];
					
					// check module exists
					if (file_exists(CMS_WWW_PATH.'module/'.$module.'/package.php')) {
						
						// add to list of installed modules
						$this->_arrModules[] = $module;
						
						$mode = ($forceautoload)?3:$objSet->settingValue;
					
						switch ($mode) // autoload module
						{
							case 3: // auto load
							case 2: // auto include
								// plugin included ?
								if (file_exists(CMS_WWW_PATH.'module/'.$module.'/plugin.php')) {
									include_once(CMS_MODULE_PATH.$module.'/plugin.php');
									$GLOBALS['objCms']->initPlugin($module);
								}
								
								// load module package file
								include_once(CMS_MODULE_PATH.$module.'/package.php');
								
							case 1: // enable module
								$GLOBALS['objCms']->initModule($module, $mode);
								break;
						}
					}
					
				} else {
					// other general settings
					$this->_arrValues[$objSet->settingKey] = $objSet->settingValue;
				}
			}
			return true;
		}
		return false;
	}
	/**
	* -TODO-
	*/	
	function saveSettings($what='') {
		if ($what) {
			$arrWhat = explode(',',$what);
			foreach($arrWhat as $key) {
				$key = trim($key);
				$this->saveValue($key,$this->_arrValues[$key]);
			}
		} else {
			foreach($this->_arrValues as $key => $value) {
				$this->saveValue($key,$value);
			}
		}
	}
	/**
	* -TODO-
	*/	
	function postSettings() {
		
		// check posted values
       	foreach ($_POST as $key => $value) {
       		// skip not real settings
       		if ($key == 'submit' || $key == 'action' || preg_match('/toggle$/',$key)) {
       			continue;
       		}
       		// check checkboxes
       		if (preg_match('/old$/',$key)) {
       			$tmp = substr($key,0,-4);
       			if (array_key_exists($tmp,$_POST)
       				|| array_key_exists($tmp.'_toggle',$_POST))
       			{
       				continue;
       			} else {
       				// unchecked -> set to 0
       				$key = $tmp;
       				$value = 0;
       			}
       		}
       		// check complex form
       		else if (preg_match('/value$/',$key)) {
       			$tmp = substr($key,0,-6);
       			if (array_key_exists($tmp.'_toggle',$_POST)) {
       				$key = $tmp;
       			} else {
       				continue;
       			}
       		}
       		// check if value is different
       		if ($this->get($key) == $value) {
       			continue;
       		}
       		// save new value
       		$this->saveValue($key,$value);
       	}
	}
	/**
	* add module to be used
	*/
	function installModule($module, $autoload=0) {
		$this->getConnection();
		return $this->query('REPLACE INTO '.$this->gTable()
			." SET settingKey='module_${module}', "
			."settingValue='$autoload'");
	}
	
	/**
	* remove setting from DB
	*/
	function removeSetting($key) {
		$this->getConnection();
		return $this->query(
			'DELETE FROM '.$this->gTable()
			." WHERE settingKey='${key}'"
		);
	}
}

class TznController extends TznPluginable
{

	var $folder;
	var $view;

	function TznController($folder) {
		parent::TznPluginable();
		$this->folder = $folder;
	}
	
	/**
	* static function to be called to call controller corresponding to a page
	* not used by modules
	*/
	function factory($folder) {
		// called to customize login, logout, logister, logminder

		if (is_file(CMS_WWW_PATH.'template/_'.$folder.'/package.php')) {
			include CMS_WWW_PATH.'template/_'.$folder.'/package.php';
		} else {
			include CMS_CORE_PATH.$folder.'/package.php';
		}
		
		$controller = ucFirst($folder).'Controller';
		$objController = new $controller();
		$objController->setView('view');
		
		return $objController;
	}
	
	function main() {
		$this->callPlugins('main');
	}
	
	function setView($view) {
		$this->view = $view;
	}
	
	function view() {
		if (is_file(CMS_WWW_PATH.'template/_module_'.$this->folder.'/'.$this->view.'.php')) {
			include CMS_WWW_PATH.'template/_module_'.$this->folder.'/'.$this->view.'.php';
		} else {
			include CMS_CORE_PATH.$this->folder.'/'.$this->view.'.php';
		}
	}
}

class TznPlugin extends TznController
{

	function TznPlugin($folder, $super, $class='') {
		parent::TznController($folder);
		$this->super = $super;
		if (is_array($super)) {
			foreach($super as $sup => $cl) {
				$GLOBALS['tznPlugins'][$sup][$folder] = $cl;
			}
		} else if ($class) {
			// error_log('-> add '.$class." in tznPlugins[$super]");
			$GLOBALS['tznPlugins'][$super][$folder] = $class;
		}
	}
	
	function main() {
		$this->includeLanguage();
		return false; // do not include in logister form
	}
	
	/**
	* include file language (can be called as static)
	*/
	function includeLanguage($folder='', $lang=CMS_LANGUAGE, $langdefault=CMS_DEFAULT_LANGUAGE) {
		if (!$folder) {
			$folder = $this->folder;
		}
		if (is_file(CMS_PLUGIN_PATH.$folder.'/language/'.$lang.'.php')) 
		{
			include_once(CMS_PLUGIN_PATH.$folder.'/language/'.$lang.'.php');
		} 
		else if (is_file(CMS_MODULE_PATH.$folder.'/language/'.$lang.'.php'))
		{
			include_once(CMS_MODULE_PATH.$folder.'/language/'.$lang.'.php');
		} 
		else if (is_file(CMS_PLUGIN_PATH.$folder.'/language/'.$langdefault.'.php'))
		{
			include_once(CMS_PLUGIN_PATH.$folder.'/language/'.$langdefault.'.php');
		}
		else if (is_file(CMS_MODULE_PATH.$folder.'/language/'.$langdefault.'.php'))
		{
			include_once(CMS_MODULE_PATH.$folder.'/language/'.$langdefault.'.php');
		}
	}
	
	function getTranslation($key) {
		if (is_array($GLOBALS['langPlugin'][$this->folder])) {
			if ($val = $GLOBALS['langPlugin'][$this->folder][$key]) {
				return $val;
			}	
		}
		return $key;
	}
	
	function view() {
		if (is_file(CMS_WWW_PATH.'template/_plugin_'.$this->folder.'/views/'.$this->view.'.php')) {
			include CMS_WWW_PATH.'template/_plugin_'.$this->folder.'/views/'.$this->view.'.php';
		} else {
			include CMS_MODULE_PATH.$this->folder.'/views/'.$this->view.'.php';
		}
	} 
}

class TznModule extends TznController
{

	var $content; // objCms content object
	var $includeScript; // script to include when displaying content (public) or form (admin)

	/**
	* 
	*/
	function TznModule($folder, $langdefault=CMS_DEFAULT_LANGUAGE) {
	
		parent::TznController($folder);
	
		if (!$folder) {
			// keep it abstract
			return false;
		}
		
		// include language file
		$lang = defined('CMS_LANGUAGE')?CMS_LANGUAGE:CMS_DEFAULT_LANGUAGE;
		$this->includeLanguage($folder, $lang, $langdefault);
		
		// include config file
		if (is_file($this->filePath('config.php'))) {
			include_once($this->filePath('config.php'));
		}
		
	}
	
	/**
	* include file language (can be called as static)
	*/
	function includeLanguage($folder, $lang=CMS_LANGUAGE, $langdefault=CMS_DEFAULT_LANGUAGE) {
		if (is_file(CMS_MODULE_PATH.$folder.'/language/'.$lang.'.php')) 
		{
			include_once(CMS_MODULE_PATH.$folder.'/language/'.$lang.'.php');
		}
		else if (is_file(CMS_MODULE_PATH.$folder.'/language/'.$langdefault.'.php'))
		{
			include_once(CMS_MODULE_PATH.$folder.'/language/'.$langdefault.'.php');
		}
	}
	
	function getTranslation($key) {
		if (is_array($_GLOBALS['langModule'][$this->folder])) {
			if ($val = $_GLOBALS['langModule'][$this->folder][$key]) {
				return $val;
			}	
		}
		return $key;
	}
	
	function filePath($file) {
		return CMS_WWW_PATH.'module/'.$this->folder.'/'.$file;
	}
	
	function fileUrl($file) {
		return CMS_WWW_URI.'module/'.$this->folder.'/'.$file;
	}
	
	/**
	* enable module
	* @param int $autoload 1:enable, 2:autoload file, 3: instanciate automatically
	*/
	function installEnable($autoload=1) {
		$GLOBALS['objCms']->settings->installModule($this->folder, $autoload);
	}
	
	/**
	* disable module
	*/
	function installDisable() {
		// -TODO- check if any page use the module
		$GLOBALS['objCms']->settings->removeSetting('module_'.$this->folder);
	}
	
	/**
	* add email alerts
	*/
	function addEmailAlert($param1, $param2=null) {
		if (is_array($param1)) {
			foreach($param1 as $key => $dir) {
				$this->addEmailAlert($key, $dir);
			}
		} else {
			include_once(CMS_CLASS_PATH.'pkg_com.php');
			$objMess = new EmailMessage();
			$objMess->set('direction', $param2);
			$objMess->set('description', $param1);
			$objMess->set('body', '{data}');
			$objMess->set('html', 0);
			$objMess->set('active', 0);
			$objMess->add();
		}
	}
	
	/**
	* remove email alerts (on disable module)
	*/
	function removeEmailAlert($param) {
		if (is_array($param)) {
			foreach($param as $key) {
				$this->removeEmailAlert($key);
			}
		} else {
			include_once(CMS_CLASS_PATH.'pkg_com.php');
			$objMess = new EmailMessage();
			$objMess->loadByFilter("description ='$param'");
			$objMess->delete();
		}
	}
	
	/**
	* add section in admin menu
	*/
	function addSection($section) {
		// get translation
		$transl = $section;
		if (isset($GLOBALS['langModuleMenu'][$this->folder][$section])) {
			$transl = $GLOBALS['langModuleMenu'][$this->folder][$section];
		}
		// check and create
		if (!is_array($GLOBALS['confAdminMenu'][$section])) {
			$GLOBALS['confAdminMenu'][$section] = array(
				'_translation' => $transl
			);
		} else {
			TznUtils::log_warn("TznModule::addSection : section '$section' already defined");
		}
	}
	
	/**
	* add menu in admin menu
	* @param string $section name of section
	* @param string $label label of menu item
	* @param string $callback method name to be called
	*/
	function addMenu($section, $label, $callback) {
		if (is_array($GLOBALS['confAdminMenu'][$section])) {
			$transl = $label;
			if (isset($GLOBALS['langModuleMenu'][$this->folder][$label])) {
				$transl = $GLOBALS['langModuleMenu'][$this->folder][$label];
			}
			$GLOBALS['arrAdminMenu'][$section][$transl] = $callback;
		} else {
			TznUtils::log_error("TznModule::addMenu : section '$section' not defined");
		}
	}
	
	/**
	* print out content from content object
	*/
	function view() {
		if ($this->view) {
			include $this->filePath('views/'.$this->view.'.php');
		} else if (is_object($this->content)) {
			if (method_exists($this->content,'printContent')) {
				echo $this->content->printContent();
			} else {
				echo $this->content->getContent();
			}
		} else {
			TznUtils::log_warn('TznModule::view : no include script or content object not implemented');
			include CMS_CORE_PATH.'error/nocontent.php';
		}
	}
	
	/**
	* print out content from content object
	*/
	function ajaxView() {
		if ($this->view) {
			include $this->filePath('views/'.$this->view.'.php');
		} else if (is_object($this->content)) {
			if (method_exists($this->content,'printContent')) {
				echo $this->content->printContent();
			} else {
				echo $this->content->getContent();
			}
		}
	}
	
	/**
	* abstract method called when editing page content
	* initilizations meant to be implemented
	*/
	function adminDefault() {
		// nothing
		echo 'TznModule::adminDefault : default admin action not implemented';
	}
	
	/**
	* default submit action
	*/
	function adminDefaultAction() {
	
		if (is_object($this->content)) {
			$this->content->setHttpAuto();
	
			// try to force page ID
			$this->content->pageId = intval($_POST['pageId']);
		
			// set page ID if empty
			if (!$this->content->pageId) {
				// called when editing page
				$this->content->pageId = $GLOBALS['objPage']->id;
			}
			
			// now do the DB work
			switch ($GLOBALS['objCms']->submitMode) {
				case 1: // save
				case 2:	// saveclose
				case 3: // saveadd
					$this->content->save();
					break;
				case 4: // delete
					$this->content->delete();
					break;
			}
			
			if (method_exists($this->content, 'adminSubmitNext')) {
				$this->content->adminSubmitNext();
			}
		}
		
		if (method_exists($this, 'adminSubmitNext')) {
			$this->adminSubmitNext();
		}
		
		// and redirect user
		$GLOBALS['objCms']->adminSubmitNext();
	}
	
	/**
	* default method called when editing page content
	*/
	function adminView() {
		if ($this->view) {
			include $this->filePath('views/'.$this->view.'.php');
		} else if (is_object($this->content)) {
			if (method_exists($this->content,'adminContent')) {
				echo $this->content->adminContent();
			} else {
				echo 'TznModule::adminContent : content object needs to implement method adminContent';
			}
		} else {
			TznUtils::log_warn('TznModule::adminContent : no include script or content object not implemented');
		}
	}
	
	/**
	* load comments if enabled
	*/
	function initComments($id, $postAllowed=true) {
		if (!$GLOBALS['confModule'][$this->folder]['comments']) {
			$this->comments = false; // not enabled
			return false;
		}
		// allowing new comments ?
		if ($postAllowed) {
			$this->postcomment = new CmsComment();
			if ($this->postComment($_POST)) {
				TznUtils::redirect($this->content->getUrl().'#com-'.$this->postcomment->id);
			}
			$GLOBALS['objHeaders']->add('css','form.css');
		}
		// load comments
		$GLOBALS['objHeaders']->add('css','comment.css');
		$this->comments = new CmsComment();
		$this->comments->addWhere("handle='comment-$id'");
		$this->comments->addOrder('contentId ASC'); // can not use date as it can change if modified
		if ($this->comments->loadList()) {
			return true;
		} else {
			return false;
		}
	}
	
	/**
	* add new comment
	*/
	function postComment($data) {
		if (!array_key_exists('saveComment', $data)) {
			return false;
		}
		$this->postcomment->setPost($data);
		if ($this->postcomment->check()) {
			return $this->postcomment->add();
		}
		return false;
	}
	
	/**
	* show comments in web page
	*/
	function commentsView() {
		if (!$this->comments) {
			return false;
		}
		
		include CMS_CORE_PATH.'comments/list.php';
		
		if ($this->postcomment) {
			include CMS_CORE_PATH.'comments/form.php';
		}
	}
	
	/**
	* abstract method called when editing module options
	* initilizations meant to be implemented
	*/
	function installOptions() {
		// nothing
		echo 'TznModule::adminOptions : no options for this module';
		
		$this->includeScript = CMS_INCLUDE_PATH.'language/'.CMS_LANGUAGE.'/module_no_options.php';
	}
}

class TznModuleList extends TznCollection
{
	/**
	* coming soon
	*/
	function TznModuleList() {
		parent::TznCollection(null);
	}
	/**
	* Load Module list in Admin section
	* @param str mode filter: installed, uninstalled, all
	*/
	function loadList($mode='installed') {

		$this->_data = array();

		if ($handle = opendir(CMS_MODULE_PATH)) {

			while (false !== ($file = readdir($handle))) {
				if (is_dir(CMS_MODULE_PATH.$file) && $file != '.' && $file != '..' 
					&& $file != 'CVS' && !preg_match('/^_/',$file)) 
				{
					if ($mode == 'installed') {
						if (!array_key_exists($file, $GLOBALS['objCms']->modules)) {
							continue;
						}
					} else if ($mode == 'uninstalled') {
						if (array_key_exists($file, $GLOBALS['objCms']->modules)) {
							continue;
						}
					}
					
					$name = $GLOBALS['langModule'][$file]['name'];
					if (!$name) {
						$name = str_replace('_',' ',$file);
					}
					$this->_data[$file] = $name;
				}
			}
			
			ksort($this->_data);

		   closedir($handle);
		}

	}
}

class TznModuleOption extends TznDb
{
	/**
	* Verify module options in db
	*/
	function TznModuleOption() {
		parent::TznDb('moduleOption');
		$this->addProperties(array(
			'module'	=> 'STR',
			'field'		=> 'STR',
			'value'		=> 'STR'
		));
	}
	/**
	* Load Module list with option
	*/
	function loadModule($module) {
		$arrValues = array();
		$this->addWhere("module = '$module'");
		$this->addOrder('field');
		if ($this->loadList()) {
			while ($objItem = $this->rNext()) {
				$arrValues[$objItem->field] = $objItem->value;
			}
		}
		return $arrValues;
	}
	/**
	* Saving if option change
	*/
	function save() {
		$this->setConnectionVerbose(FALSE);
		$sql = 'REPLACE INTO '.$this->gTable().' SET '
			.'module = \''.$this->module.'\', '
			.'field = \''.$this->field.'\', '
			.'value = \''.$this->value.'\'';
		return $this->query($sql); 
	}
	/**
	* Delete module options
	*/
	function reset() {
		$this->getConnection();
		$this->setConnectionVerbose(FALSE);
		return $this->query('DELETE FROM '.$this->gTable().' WHERE module=\''
			.$this->module.'\'');
	}
}

class TznTemplateList extends TznCollection
{
	/**
	* list of templates
	*/
	function TznTemplateList() {
		parent::TznCollection(null);
	}
	/**
	* Load Template list from template
	*/
	function loadList() {

		$this->_data = array();

		if ($handle = opendir(CMS_WWW_PATH.'template/')) {

			while (false !== ($file = readdir($handle))) {
				if (is_dir(CMS_WWW_PATH.'template/'.$file) && $file != '.' 
					&& $file != '..' && $file != 'CVS' 
					&& !preg_match('/^_/',$file))
				{
					$this->_data[$file] = str_replace('_',' ',$file);
				}
				
			}
			
			ksort($this->_data);

		   closedir($handle);
		}

	}
}

class TznMenus
{
	var $_arrMenus;
	function TznMenus() {
		$this->_arrMenus = array();
	}
	function add() {
		$arrArgs = func_get_args();
		$id = array_shift($arrArgs);
		$menu = array_shift($arrArgs);
		if (!$id || !$menu) {
			echo 'DEVLPT ERROR: adding menu, needs parameters ID and MENU TYPE';
			exit;
		}
		$menu = strtolower($menu);
		include_once(CMS_WWW_PATH.'template/_menu/'.$menu.'/pkg_menu.php');
		$className = str_replace(' ','',ucWords(str_replace('_',' ',$menu)));
		$this->_arrMenus[$id] = new $className($arrArgs);
	}
	function p($id, $menu='') {
		if (array_key_exists($id, $this->_arrMenus)) {
			$this->_arrMenus[$id]->p($menu);
		}
	}
	function rPages($id) {
		if (!array_key_exists($id, $this->_arrMenus)) {
			return false;
		}
		return $this->_arrMenus[$id]->_pageList->rCount();
	}
}

class TznPage extends TznOutline
{
	var $_captchaOk;
	var $_isTeamAdmin;
	
	/**
	* Load page properties form db
	*/
	function TznPage() {
		$digits = (@constant('CMS_TREE_DIGITS'))?CMS_TREE_DIGITS:3;
		$levels = (@constant('CMS_TREE_LEVELS'))?CMS_TREE_LEVELS:4;
		parent::TznOutline('page', 'position', $digits, $levels);
		$this->addProperties(array(
			'id'				=> 'UID',
			'title'				=> 'STR',
			'menu'				=> 'STR',
			'shortcut'			=> 'STR',
			'template'			=> 'STR',
			'description'		=> 'STR',
			'keyword'			=> 'STR',
			'encoding'			=> 'STR',
			'module'			=> 'STR',
			'display'			=> 'BOL',
			'private'			=> 'NUM',
				// 0 = public, 1 = protected, 2 = private
			'showInMenu'		=> 'BOL', // 0 not in menu, 1 in menu
			'protected'			=> 'LVL',
			'lastChangeDate'	=> 'DTM'
		));
		$this->protected = CMS_PROTECTION_INIT;
		$this->_isTeamAdmin = false;
	}

	/**
	* Page title used in drop down list of pages
	*/
	function getOutlineTitle($field='title') {
		$str = '';
		$level = $this->getOutlineLevel();
		if ($level > 1) {
			for ($i=1; $i<$level; $i++) {
				$str .= '&nbsp; ';
			}
			$str .= '- ';
		}
		return $str.$this->get($field);
	}
	
	/**
	* get publish status
	*/
	function getStatus() {
		if ($this->display) {
			$str = $GLOBALS['langSetHeaders']['status_published'].' ';
			switch ($this->private) {
				case 0: // public
					$str .= '('.$GLOBALS['langSetHeaders']['status_public'].')';
					break;
				case 1: // protected
					$str .= '('.$GLOBALS['langSetHeaders']['status_protected'].')';
					break;
				case 2: // private
					$str .= '('.$GLOBALS['langSetHeaders']['status_private'].')';
					break;
			}
			return $str;
		} else {
			return $GLOBALS['langSetHeaders']['status_unpublished'];
		}
	}
	
	function getTemplate() {
		return str_replace('_',' ',$this->template);
	}
	
	function resetProtection() {
		$this->protected = CMS_PROTECTION_INIT;
	}
	
	/**
	* check team member admin rights
	*/
	function setTeamAdminRights($arr) {
		$reg = '/^('.implode('|',$arr).')/';
		if (preg_match($reg,$this->position)) {
			$this->_isTeamAdmin = true;
		}
	}
	
	function checkTeamAdmin($arr=null) {
		if (is_array($arr)) {
			$this->setTeamAdminRights($arr);
		}
		return $this->_isTeamAdmin;
	}
		
	/**
	* check rights to read page
	*/
	function canAccess() {
	
		if (!$this->private) {
			// public page always accessible
			return true;
		}
		
		// can not access prive page (by default)
		$canAccess = false;
		if ($GLOBALS['objUser']->isLoggedIn()) {
			// is team admin
			if ($this->_isTeamAdmin) {
				return true;
			}
			// ok first step, user is logged in and may have a chance to see this
			if (($this->private == 1) && $GLOBALS['objUser']->hasAccess(1))
			{
				//protected content which user has access to
				$canAccess = true;
				
			} else if ($this->private == 2) {
				// page is private
				if ($GLOBALS['objUser']->hasAccess(2)) {
					// user has full access (admin)
					$canAccess = true;
				} else if (@constant('CMS_TEAM_ENABLE')) {
					// check if user is associated with this page
					if ($arr = $GLOBALS['objCms']->getUserPages()) {
						$canAccess = in_array($this->id, $arr);
					}
				}
			}
		}
		return $canAccess;
	}
	
	/**
	 * check if a subpage can be added
	 */
	function canAdd() {
		return ($GLOBALS['objUser']->hasAccess(7) && $this->canOutlineAddChild() && (!$this->getLvl('protected',3)));
	}
	
	/**
	 * check if page can be copied
	 */
	function canCopy() {
		return ($GLOBALS['objUser']->hasAccess(7) && (!$this->getLvl('protected',3)));
	}
	
	/**
	 * check if page headers can be edited
	 */
	function canHeader() {
		return $GLOBALS['objUser']->hasAccess(10);
	}
	
	/**
	 * check if page content can be edited
	 */
	function canEdit() {
	
		// first, user has definetly has to be logged in
		if (!$GLOBALS['objUser']->isLoggedIn()) {
			return false;
		}
	
		// then, check if page allows edit
		if (empty($this->module) || $this->getLvl('protected',1)) {
			// page is a section OR is protected
			return false;
		}
		
		// now check admin global access
		if ($GLOBALS['objUser']->hasAccess(8)) {
			// user can edit any page
			return true;
		}
		
		// try checking module special rights
		/*
		if ($this->page->module) {
			if ($GLOBALS['objUser']->hasAccess(2, $this->page->module)) { // -TODO- also if author
				return true;
			}
		}
		*/
		
		// check if page is associated to team and team member is allowed to edit page		
		if ($GLOBALS['objUser']->checkUserPagesRights($this->id, 7)) {
			return true;
		}
		
		// none gone through ? bye bye
		return false;
	}
	
	/**
	 * check if page can be moved
	 */
	function canMove() {
		return ($GLOBALS['objUser']->hasAccess(11) && !$this->getLvl('protected',4) && $this->canOutlineUp());
	}
	
	/**
	* verify the rights to delete a page
	*/
	function canDelete() {
		if (preg_match('/^[0]+$/',$this->position)) {
			// root page can not be deleted
			return false;
		}
		return ($GLOBALS['objUser']->hasAccess(9) && ($this->getLvl('protected',5) == 0));
	}
	
	/**
	* Load first page
	*/
	function loadFirstPage() {
		return $this->loadByQuery('SELECT * FROM '.$this->gTable()
			." WHERE module <> ''"
			.' AND display=1'
			.' ORDER BY position LIMIT 0,1');
	}
	/**
	* load parents
	*/
	function loadLocation($minlevel = 1) {
		
		$outline = $this->getOutline();
		$arrOut = $this->getOutlineArray();
		// echo '<pre>';
		// print_r($arrOut);
		$arrCpl = array();
		$nuline = str_pad('',$this->_outlineDigits,'0');
		
		$cbline = '';
		for($i=count($arrOut); $i > $minlevel; $i--) {
			$curline = array_pop($arrOut);
			// echo "\nloop $i : $curline\n";
			// print_r($arrOut);
			if ($curline == $nuline) {
				$cbline .= $nuline;
				continue;
			}
			// echo "\n  adding: $curline".implode('',$arrOut).$cbline;
			$arrCpl[] = implode('',$arrOut).$curline.$cbline;
			$cbline .= $nuline;
		}
		$objParentList = new TznPage();
		$objParentList->addWhere($this->_outlineField."='"
			.implode("' OR ".$this->_outlineField."='",$arrCpl)
			."'");
		// echo "\n".$objParentList->sqlWhere(); exit;
		$objParentList->addOrder($this->_outlineField.' ASC');
		if ($objParentList->loadList(TZN_DB_COUNT_OFF,$sql)) {
			return $objParentList;
		} else {
			return false;
		}
	}
	/**
	* load children pages
	*/
	function loadChildWithModule($private=false) {
		$objChildrenList = new TznPage();
		if ($objChildrenList->loadChildren($this->id)) {
			while ($objChild = $objChildrenList->rNext()) {
				if ($objChild->module && ($private || $objChild->display)) {
					return $objChild;
				} else {
					return $objChild->loadChildWithModule();
				}
			}
		} else {
			return false;
		}
	}
	/**
	* get page URL
	*/
	function getUrl($full=false) {
		$url = ($full)?CMS_WWW_URL:CMS_WWW_URI;
		if (@constant('CMS_REWRITE_URL')) {
			if (preg_match('/^\./',CMS_REWRITE_URL)) {
				return $url.$this->get('shortcut').CMS_REWRITE_URL;
			} else {
				return $url.CMS_REWRITE_URL.$this->get('shortcut').'_'.$this->id;
			}
		} else {
			return $url.'index.php?page='.$this->id;
		}
	}
	/**
	* Get icon for active or inactive page
	*/
	function getIcon() {
		$img = CMS_WWW_URI.'assets/images/i_';
		if ($this->module) {
			if (file_exists(CMS_WWW_PATH.'module/'.$this->module.'/images/i_module_on.png')
				&& file_exists(CMS_WWW_PATH.'module/'.$this->module.'/images/i_module_off.png'))
			{
				$img = CMS_WWW_URI.'module/'.$this->module.'/images/i_module_';
			} else {
				$img .= 'page_';
			}
		} else {
			$img .= 'section_';
		}
		if ($this->display) {
			$img .= 'on';
			if (!$this->showInMenu) {
				$img .= 'o';
			}
		} else {
			$img .= 'off';
		}
		$img .= '.png';
		return $img;
	}
	
	/**
	* set title of the page
	*/	
	function setTitle($title,$menu=null,$shortcut=null) {
		if ($title) {
			$this->setHttp('title',$title);
		} else {
			$this->title='---';
		}
		$this->setHttp('menu',($menu)?$menu:$title);
		$this->shortcut = $shortbase = TznUtils::convURI($shortcut?$this->getHttp($shortcut):$this->menu);
		
		// check if shortcut is unique
		$this->setAlternativeShortcut($shortbase);
		
	}
	
	/**
	* check shortcut and set alternative one if it already exists
	*/
	function setAlternativeShortcut($shortbase='') {
		if (!$shortbase) {
			if ($this->shortcut) {
				$shortbase = $this->shortcut;
			} else {
				return false;
			}
		}
	
		$sql2 = '';
		if ($this->id) {
			$sql2 = ' AND pageId != '.$this->id;
		}
		$sql = "shortcut='".$this->shortcut."'$sql2";
		$objTmp = new TznPage();
		$i = 1;
		while ($objTmp->loadByFilter($sql)) {
			$this->shortcut = $shortbase.'_'.$i++;
			$sql = "shortcut='".$this->shortcut."'$sql2";
		}
		return $this->shortcut;
	}
	
	/**
	* security image
	*/
	function getCaptcha($required=true, $tag='div', $isPost=false) {
		if (!$isPost) {
			$isPost = ($_REQUEST['send'])?true:false;
		}
		$str = '';
		if ($required) {
			$str = '<'.$tag.' class="form_captcha compulsory">';
			$str .= '<label for="cms_security">'.TznCms::getTranslation('security_label','langForm').'&nbsp;:</label>';
			if ($isPost && (!$this->_captchaOk)) {
				$str .= '<span class="tznError">'.TznCms::getTranslation('security_image_error','langForm').'.</span>';
			} else {
				$str .= '<span>'.TznCms::getTranslation('security_image','langForm').'.</span>';
			}
			$str .= '<span class="cms_captcha"><img src="'.CMS_WWW_URI.'security_image.php?rdm='.rand(10000,99999)
				.'&amp;width=190" width="190" height="40" alt="Captcha" class="secuimg" onclick="$(\'cms_security\').focus()" />'
				.'<input type="text" id="cms_security" name="cms_security" value="" /></span></'.$tag.'>';
		}
		return $str;
	}

	function checkCaptcha($required=true) {
		$ok = false;
		if (!$required) {
			$ok = true;
		} else {
			$ok = false;
			if ($_SESSION['cms_security'] && strtoupper($_POST['cms_security']) == $_SESSION['cms_security']) {
				$ok = true;
			}
			$_SESSION['cms_security'] = Tzn::getRdm(16); //don't ask
			unset($_SESSION['cms_security']);
		}
		if (is_object($this)) {
			$this->_captchaOk = $ok;
		}
		return $ok;
	}
	
	/**
	* print the content of page
	*/
	function view() {
		if ($this->module) {
			$objModule = $GLOBALS['objCms']->modules[$this->module];
			if (is_object($objModule)) {
				$objModule->view();
			} else {
				log_error('TznPage::view : object module '.$this->module.' not implemented');
				include CMS_CORE_PATH.'core/nocontent.php';
			}
		} else {
			log_warn('TznPage::view : no module defined');
			include CMS_CORE_PATH.'core/nocontent.php';
		}
	}
	
	function call($func) {
		if ($this->module) {
			$objModule = $GLOBALS['objCms']->modules[$this->module];
			if (is_object($objModule)) {
				return $objModule->$func();
			} else {
				log_error('TznPage::view : object module '.$this->module.' not implemented');
				include CMS_CORE_PATH.'core/nocontent.php';
			}
		} else {
			log_warn('TznPage::view : no module defined');
			include CMS_CORE_PATH.'core/nocontent.php';
		}
	}
	/**
	* Print the admin menu bar
	*/
	function printAdminMenu($adminOnly=true) {
		if ($GLOBALS['objUser']->isLoggedIn() && 
			(!$adminOnly || $GLOBALS['objUser']->checkAccess(24)))
		{
			include CMS_WWW_PATH.'template/admin/'.CMS_TEMPLATE_ADMIN.'/menu_top.php';
		}
	}
	/**
	* Get the properties of a page
	*/
	function inheritProperties($objParent) {
		if ($objParent->template) {
			$this->set('template',$objParent->template);
		} else {
			$this->set('template',CMS_TEMPLATE_DEFAULT);
		}
		$this->set('description',$objParent->description);
		$this->set('keyword',$objParent->keyword);
		$this->set('encoding',$objParent->encoding);
		$this->set('private',$objParent->private);
		$this->set('showInMenu',$objParent->showInMenu);
	}
	/**
	* Add a page
	*/
	function add() {
		$this->set('showInMenu',1);
		$this->setDtm('lastChangeDate','NOW');
		parent::add();
	}
	/**
	* Update a page
	*/
	function update() {
		$this->setDtm('lastChangeDate','NOW');
		parent::update();
	}
	
	function deletePage() {
		if ($this->module) {
			$objModule = $GLOBALS['objCms']->getModuleObject($this->module);
			if (method_exists($objModule, 'adminDeletePage')) {
				$objModule->adminDeletePage($this->id);
			}
		}
	}
	
	function deleteTree() {
		if ($this->loadChildren($this)) {
			while ($objChild = $this->rNext()) {
				$objChild->deletePage();
			}
		}
		$this->deletePage();
		return parent::deleteOutlineTree();
	}
}

class PageTeam extends TznDb
{
	function PageTeam()
	{
		parent::TznDb('pageTeam');
		$this->addProperties(array(
			'pageId' => 'NUM',
			'teamId' => 'NUM'
		));
	}
	
	function _delete($pageId, $teamId) {
		return parent::delete('pageId = '.$pageId.' AND teamId = '.$teamId);
	}
	
	function delete() {
		return $this->_delete($this->pageId, $this->teamId);
	}
}

class PageTeamStats extends PageTeam
{
	
	function PageTeamStats()
	{
		parent::PageTeam();
		$this->removeProperties('pageId');
		$this->addProperties(array(
			'page'		=> 'OBJ,tznPage',
			'position'	=> 'NUM'
		));
	}
	
	function checkUserRights($level) {
		$level--;
        return ($GLOBALS['confTeamRights'][$this->position]{$level} == '1');
	}
	
	function checkUserPage($userId,$pageId) {
		$this->addWhere('pp.pageId='.$pageId);
		if ($this->loadUserPages($userId)) {
			return true;
		} else {
			return false;
		}
	}
	
	function loadUserPages($userId) {
		$this->addWhere('mt.memberId='.$userId);
		$sql = 'SELECT pt.teamId, pt.pageId, '
			.'pp.position AS page_position, pp.title AS page_title, '
			.'pp.menu AS page_menu, pp.shortcut AS page_shortcut, '
			.'pp.template AS page_template, pp.description AS page_description, '
			.'pp.description AS page_description, pp.keyword AS page_keyword, '
			.'pp.encoding AS page_encoding, pp.module AS page_module, '
			.'pp.display as page_display, pp.private AS page_private, '
			.'pp.protected AS page_protected, '
			.'pp.lastChangeDate AS page_lastChangeDate, mt.position AS position '
			.'FROM '.$this->gTable().' AS pt '
			.'INNER JOIN '.$this->gTable('page').' AS pp ON pt.pageId=pp.pageId '
			.'INNER JOIN '.$this->gTable('team').' AS tt ON pt.teamId=tt.teamId '
			.'INNER JOIN '.$this->gTable('memberTeam').' AS mt ON tt.teamId=mt.teamId';
		return $this->loadList($sql);
	}
	
	function delete() {
		return $this->_delete($this->page->id, $this->teamId);
	}
}

class Tag extends TznDb
{
	/**
	* tags
	*/
	function Tag() {
		parent::TznDb('tag');
		$this->addProperties(array(
			'id'		=> 'STR',
			'category'	=> 'BOL',
			'language'	=> 'STR'
		));
	}
}
