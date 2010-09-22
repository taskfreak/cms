<?php
/****************************************************************************\
* Tirzen CMS                                                                 *
******************************************************************************
* Version: 0.1                                                               *
* Authors: Stan Ozier <stan@tirzen.com>                                      *
* License:  http://www.gnu.org/licenses/gpl.txt (GPL)                        *
\****************************************************************************/

error_reporting(E_ALL ^ E_NOTICE);

define('TZN_DB_HOST','localhost');
define('TZN_DB_USER','test');			// edit here
define('TZN_DB_PASS','test');			// edit here
define('TZN_DB_BASE','taskfreak_cms');	// edit here
define('TZN_DB_PREFIX','cms');
define('TZN_DB_CLASS','tzn_mysql.php');

define('TZN_DB_DEBUG',2);
define('TZN_EMAIL_DEBUG',1);

define('TZN_DB_PERMANENT',0);

define('TZN_DEBUG',0);
define('TZN_SPECIALCHARS',2);
define('TZN_HTMLMODE','html');
define('TZN_BOOL_TRUE','<img src="images/check_yes.png" width="12" height="13" border="0" />');
define('TZN_BOOL_FALSE','<img src="images/check_no.png" width="12" height="13" border="0" />');
define('TZN_TZDEFAULT','user');
define('TZN_DATEFIELD','SQL');
define('TZN_TRANS_ID',0);
define('TZN_TRANS_STATUS',1);

define('TZN_DB_ASC_OFF','images/o_asc.png');
define('TZN_DB_ASC_ON','images/o_asc_on.png');
define('TZN_DB_DESC_OFF','images/o_desc.png');
define('TZN_DB_DESC_ON','images/o_desc_on.png');
define('TZN_DB_PAGING_OFF','');
define('TZN_DB_PAGING_ON','current');
define('TZN_DB_PAGING_ENABLED','enabled');
define('TZN_DB_PAGING_DISABLED','disabled');

define('TZN_USER_ID_LENGTH',8);		// length of room/user ID
define('TZN_USER_LOGIN','username');// Login mode = username OR email
define('TZN_USER_NAME_MIN',4);		// minimum length for username
define('TZN_USER_NAME_MAX',10);		// maximum length for username
define('TZN_USER_PASS_MIN',4);		// minimum length for password
define('TZN_USER_PASS_MAX',10);		// maximum length for password
define('TZN_USER_PASS_MODE',4);

define('TZN_FILE_SLASH','/');
define('TZN_FILE_RANDOM',false);
define('TZN_FILE_FOLDER_SIZE',300); // number of files per folder
define('TZN_FILE_GD_VERSION',2);
define('TZN_FILE_GD_QUALITY',80);

define('CMS_ROOT_PATH', substr(dirname(__FILE__),0,-7));
define('CMS_INCLUDE_PATH', CMS_ROOT_PATH.'include/');
define('CMS_CLASS_PATH', CMS_INCLUDE_PATH.'classes/');
define('CMS_WWW_PATH', CMS_ROOT_PATH);

define('TZN_DB_ERROR_SCRIPT', CMS_INCLUDE_PATH.'core/error/cmserror.php');

if (!defined('CMS_WWW_URI')) {
	define('CMS_WWW_URI',preg_replace('/^\/admin/','',dirname($_SERVER['PHP_SELF']))
		.(preg_match('/\/$/',dirname($_SERVER['PHP_SELF']))?'':'/'));
}
define('CMS_WWW_URL','http://'.$_SERVER['SERVER_NAME'].CMS_WWW_URI);

// define('TZN_SITEMAP_URL',CMS_WWW_URL.'sitemap.xml');

define('CMS_REWRITE_URL','.html');
define('CMS_JOURNAL_URL_BLOG','news.html');
define('CMS_JOURNAL_URL_EVENT','events.html');

define('TZN_FILE_ICONS_PATH', CMS_WWW_PATH.'icons/');
define('TZN_FILE_ICONS_URL','icons/');
define('TZN_FILE_UPLOAD_PATH', CMS_WWW_PATH.'files/');
define('TZN_FILE_UPLOAD_URL', CMS_WWW_URI.'files/');
define('TZN_FILE_TEMP_PATH', TZN_FILE_UPLOAD_PATH.'temp/');
define('TZN_FILE_TEMP_URL', TZN_FILE_UPLOAD_URL.'temp/');

define('CMS_CACHE_PATH', TZN_FILE_UPLOAD_PATH.'cache/');


// === CUSTOMIZATION ================================

// google
define('CMS_GOOGLE_ANALYTICS','');

// Twitter RSS
define('CMS_TWITTER_RSS','http://twitter.com/statuses/user_timeline/xxxxxxxxx.rss');
define('CMS_TWITTER_TZONE', 0);

// charset
// define('CMS_CHARSET','ISO-8859-1');
define('CMS_CHARSET','UTF-8');
// use only if you have encoding/utf8 problems
define('TZN_FORCE_UTF-8',true);

// default language
define('CMS_DEFAULT_LANGUAGE','fr');

// mootools or not mootools (for site only - admin always uses it)
define('CMS_MOOTOOLS', true);

// what to show on admin recent items
define('CMS_RECENT_FILTER', "(handle='blog' OR handle='data' OR handle LIKE 'comment-%')");

// force redirect on login, whatever page has been requested
// define('CMS_LOGIN_REDIRECT_ADMIN',CMS_WWW_URI.'admin/'); // for admins
define('CMS_LOGIN_REDIRECT_ADMIN',CMS_WWW_URI.'admin'); // for admins
define('CMS_LOGIN_REDIRECT_MEMBER',CMS_WWW_URI.'taskfreak.html'); // for members

// default content image and thumb size
define('CMS_EDITOR_IMG_WDH',800);
define('CMS_EDITOR_IMG_HGT',600);
define('CMS_EDITOR_THB_WDH',240);
define('CMS_EDITOR_THB_HGT',190);

// site map
define ('CMS_TREE_DIGITS',3);
define ('CMS_TREE_LEVELS',5);

// enable teams ?
define('CMS_TEAM_ENABLE',false);

// date format
define('TZN_DATE_US_FORMAT',false);
define('CMS_DATE','%d/%m/%y');
define('CMS_DATETIME','%d/%m/%y <small>%H:%M</small>');

define('CMS_TWITTER_INTERVAL', 1440); // once a day

$GLOBALS['confLinksExternal'] = array(
 	'webmail'		=> 'http://webmail.parachutisme-bretagne.com'
//	'web statistics'   	=> 'http://'.$_SERVER['SERVER_NAME'].'/plesk-stat/webstat/'
//	'web statistics'	=> 'http://'.$_SERVER['SERVER_NAME'].':2082/tmp/tirzen/webalizer/index.html'
);

$GLOBALS['confGlobalRights'] = array(
        0 => '100'.'00'.'0000000'.'000000'.'00000000'.'000',	// guests
        1 => '100'.'00'.'0000000'.'000000'.'00000000'.'000',	// visitor
        2 => '100'.'00'.'0000000'.'111000'.'00000000'.'000',	// member
        3 => '111'.'11'.'1110100'.'110000'.'11000000'.'000',	// moderator
        4 => '111'.'11'.'1111111'.'111111'.'11111111'.'111'		// administrator
);
// content      : 1: access protected, 2: access private, 3: access unpublished
// admin		: 4: moderate comments, 5: access admin
// pages		: 6: list pages, 7: add, 8: edit, 9: delete, 
//				: 10: publish, 11: move, 12: protect
// users        : 13: list, 14: detail, 15: add, 16: edit, 17: delete, 18: enable
// teams        : 19: list, 20: detail, 21: add, 22: edit, 23: delete, 24: enable
//				: 25: manage all team pages, 26: manage all team members
// admin        : 27: email settings, 28: modules, 29: configuration

$GLOBALS['confTeamRights'] = array(
        0 => '0000000000',      // guest
        1 => '0000010000',      // member
        2 => '0011111111',      // moderator
        3 => '1111111111'       // leader
);

// 1:edit info / 2:enable / 3:add members / 4:edit members / 5: remove members
// 7: edit page contents

$GLOBALS['confModuleRights'] = array();

$GLOBALS['confLanguageCodes'] = array(
	'fr'	=> 'Fran&ccedil;ais',
	'en'	=> 'English'
);

/* --- STOP HERE - There's usually nothing to edit below this point -------- */

define('TZN_ROBOT_AGENT', 'Google|msnbot|Rambler|Yahoo|AbachoBOT|accoona|AcioRobot|ASPSeek|'
	.'CocoCrawler|Dumbot|FAST\-WebCrawler|GeonaBot|Gigabot|Lycos|MSRBOT|Scooter|'
	.'AltaVista|IDBot|eStyle|Scrubby');

define('CMS_PLUGIN_PATH',CMS_WWW_PATH.'plugin/');
define('CMS_MODULE_PATH',CMS_WWW_PATH.'module/');
define('CMS_TEMPLATE_PATH',CMS_WWW_PATH.'template/');
define('CMS_CORE_PATH',CMS_INCLUDE_PATH.'core/');