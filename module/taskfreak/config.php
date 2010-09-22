<?php

$GLOBALS['confModule']['taskfreak'] = array(
	'autoload' 		=> 3,
	'comments'		=> 0,
	'editor_full'	=> 'default',
	'visitor_access'=> false
);

// === ACCESS RIGHTS ==========================================

// Change access rights settings with care.
// A switch with value 0 means right is not given
// A switch with value 1 means right is given
// Example #1: the 3rd switch on confProjectRights sets the right for a
// user to edit (modify) any comment to a task belonging to a project he's
// associated with.
// Example #2: the 10th switch in confModuleRights sets the right for a user
// to change the status of any project, whatever he's associated with it or not

// -- GLOBAL RIGHTS -------------------------------------------
// These are global rights of user over the entire application

$GLOBALS['confModuleRights']['taskfreak'] = array(
	0 => '00'.'000'.'00000'.'000'.'00', 	// visitors
	1 => '10'.'000'.'00000'.'100'.'00',	// guests
	2 => '10'.'000'.'00000'.'111'.'00',	// intern
	3 => '10'.'000'.'01001'.'111'.'00',	// manager
	4 => '11'.'111'.'11111'.'111'.'11'		// administrator
);
// misc:  1: view public tasks (access to taskfreak)
// comments 3: can comment any task
// projects: 6:see all, 7:create new, 8:edit any, 9:delete any, 10:change status
// misc #1 : 11:create own tasks, 12:view internal tasks
// misc #2 : 14:edit any task, 15:system settings

// -- PROJECT RIGHTS ------------------------------------------
// These are rights of users associated with a specific project

$GLOBALS['confProjectRights'] = array(
	0 => '00000', // user not associated to project
	1 => '10000'.'00000'.'00000',	// extern
	2 => '11000'.'00000'.'00000',	// official
	3 => '11000'.'10000'.'00000',	// member
	4 => '11110'.'11110'.'11000',	// moderator
	5 => '11111'.'11111'.'11111'	// leader
);
// comments: 1:see all, 2:add new comment, 3:edit any, 4:delete any
// tasks   : 6:create new, 7:edit any, 8:change status, 9:delete any, 10: view tasks
// project : 11:manage users, 12:change status, 13:edit info, 14:delete

// === TASKFREAK CUSTOMIZATION ================================

// number of priority levels
define('FRK_PRIORITY_LEVELS',5);	// 3, 5 or 9
define('FRK_PRIORITY_DEFAULT',3);
// how many levels to get status at 100%
define('FRK_STATUS_LEVELS',3);		// 1 to 5
// add column context
define('FRK_CONTEXT_ENABLE',FALSE);	// TRUE or FALSE
// add description to tasks / todos
define('FRK_DESCRIPTION_ENABLE',TRUE);		// TRUE OR FALSE
// default user's country
define('FRK_DEFAULT_COUNTRY','FR');

// authorize auto login
define('PRJ_AUTO_LOGIN',true);
// enable password reminder
define('PRJ_PASSWORD_REMINDER',false);
// enable registration process (0=no, 1=activation by admin, 2=user gets activation email)
define('PRJ_REGISTRATION',0);

// sort order by default (also used for RSS field)
define('FRK_DEFAULT_SORT_COLUMN','deadlineDate'); // deadlineDate, priority, project...
define('FRK_DEFAULT_SORT_ORDER',1); // 1 = Ascending, -1 = Descending
// language of the interface
define('FRK_DEFAULT_LANGUAGE','fr');	// en, fr, it, de, nl, da, zh, pl
// TaskFreak! images
define('FRK_IMAGES',CMS_WWW_URI.'module/taskfreak/images/');
// default task view
define('FRK_DEFAULT_VIEW_TYPE','recent');
// second choice task view (if task list is empty)
define('FRK_DEFAULT_VIEW_ALTERNATE','sta0,1');
// show own tasks or all users' tasks by default
define('FRK_DEFAULT_VIEW_OWN_TASKS',FALSE);
// limit number of tasks to show at once (applies to today's task view only)
define('FRK_DEFAULT_CURRENT_TASKS',0); // 0 = no limit
// includes tasks with no deadline by default or not
define('FRK_DEFAULT_NO_DEADLINE_TOO',TRUE);	// TRUE or FALSE 
// when task is marked as done, keep in task list for X days
define('FRK_DEFAULT_NO_DEADLINE_KEEP',0);	// number of days 
// deadline: displays day of the week (or tomorrow) or '1 day'
define('FRK_DEFAULT_DATEDIFF_MODE','day');	// day or diff or date
// deadline: displays 'tomorrow' for next day 
define('FRK_DEFAULT_DATEDIFF_TOMORROW',TRUE); // TRUE or FALSE
// show full text (true) or icons (false)
define('FRK_DEFAULT_CONTEXT_LONG',FALSE);	// TRUE or FALSE
// number of items in RSS file (set to 0 if you don't want to enable RSS)
define('FRK_DEFAULT_RSS_SIZE',10);
// show own tasks only, or all users' tasks in rss
define('FRK_DEFAULT_RSS_OWN_ONLY',FALSE);
// default visibility
define('FRK_DEFAULT_VISIBILITY',1); // 0 = public, 1 = internal, 2 = private
// default comment order
define('FRK_DEFAULT_COMMENT_ORDER','ASC');
// update deadline on completed
define('FRK_COMPLETE_DEADLINE',false);
// US date format mm/dd (eg. 14th may = 5/14 vs. 14/5)
define('TZN_DATE_US_FORMAT',FALSE);
// do you need confirmation when setting task as completed (from task list)
define('FRK_CONFIRM_STATUS_CLOSE',TRUE);
// do not keep user connected (disable background ajax request)
define('FRK_DISCONNECT_ON_TIMEOUT',FALSE); // false = keep connected, true = kick out when session times out
// would the page reload for real every X minutes?
define('FRK_RELOAD_FOR_REAL',5);
// hide far future tasks (number of days)
define('FRK_DEFAULT_FAR_FUTURE_HIDE',0);

// === CONTEXTS ===============================================

$GLOBALS['confContext'] = array(
    1 => '#939',
    2 => '#c33',
    3 => '#66f',
    4 => '#090',
    5 => '#963',
    6 => '#39c',
    7 => '#3c9',
    8 => '#999'
);

// === NO CHANGE BELOW THIS POINT ==============================

define('FRK_PROJECT_LEADER',count($GLOBALS['confProjectRights']) - 1);