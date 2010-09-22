<?php
/****************************************************************************\
* Tirzen CMS                                                                 *
******************************************************************************
* Version: 3.0                                                               *
* Authors: Stan Ozier <stan@tirzen.com>                                      *
* License:  http://www.gnu.org/licenses/gpl.txt (GPL)                        *
\****************************************************************************/

// comments
$GLOBALS['langComment'] = array(
	'comments'			=> 'Comments',
	'posted_on'			=> 'Posted on',
	'posted_by'			=> 'by',
	'none'				=> 'No comment so far',
	'edit'				=> 'edit',
	'delete'			=> 'delete',
	'delete_confirm'	=> 'Really delete this comment ?',
	'post'				=> 'Add a comment',
	'post_body'			=> 'Comment',
	'post_submit'		=> 'Post comment'
);

// top menu / left menu
$GLOBALS['langMenu'] = array (
	'administration'	=> 'administration',
	'admin_summary'		=> 'Activity summary',
	'admin_help'		=> 'Here\'s the dashboard of the admin section, displaying latest activity on the website.<br />To make changes on the website pages, go to the <a href="admin_page_list.php">site map</a>.',
	'admin_pages'		=> 'Site map (pages)',
	'admin_users'		=> 'Manage users',
	'admin_teams'		=> 'Manage groups',
	'system'			=> 'Configuration',
	'system_emails'		=> 'Email alerts',
	'system_options'	=> 'Modules &amp; options',
	'system_config'		=> 'System preferences',
	'home'				=> 'Home',
	'sitemap'			=> 'site map',
	'edit'				=> 'edit this page',
	'prefs'				=> 'Prefs',
	'pages'				=> 'Pages',	
	'logged'			=> 'Logged in as',
	'exterlinks'		=> 'External Links',
	'menu'				=> 'Menu',
	'homepage'			=> 'Home Page',
	'logout'			=> 'logout'
);
//login_info.php /  login page info if error
$GLOBALS['langLoginInfo'] = array (
	'notamember'		=> '<b>If you are not a member</b> but would like to become one,',
	'notamember1'		=> 'request an account here',
	'problemlogin'		=> 'If you have problems login in</b>, it might be because of one of the following reasons:',
	'problemlogin1'		=> 'You are not logged in or your session has expired',
	'problemlogin2'		=> 'You enter the wrong password?',
	'problemlogin3'		=> 'Request a new one by email',
	'problemlogin4'		=> 'Your browser does not support javascript',
	'problemlogin5'		=> 'Your browser does not have cookies enabled',
	'problemlogin6'		=> 'You do not have sufficient access rights to access the requested page',
);
//logout.php / logout page
$GLOBALS['langLogout'] = array (
	'nowlogout'			=> 'You are now logged out. Goodbye.',
	'lastlogin'			=> 'Last login',
	'from'				=> 'From',
	'backhome'			=> 'back to home page',
	'logagain'			=> 'login again',
);

//footer
$GLOBALS['langFooter'] = array (
	'homepage'			=> 'Home Page',
);
//admin.php / content in administration section
$GLOBALS['langAdmin'] = array (
	'sitemap'			=> 'Site map',
	'help'				=> 'Help',
	'help_legend'		=> 'Help &amp; Legend',
	'addsection'		=> 'add new section (top level)',
	'section'			=> 'section',
	'sectionactive'		=> 'active section',
	'sectionhidden'		=> 'unpublished section',
	'page'				=> 'page',
	'pageactive'		=> 'active page',
	'pagenomenu'		=> 'hidden page',
	'pagehidden'		=> 'unpublished page',
	'view'				=> 'view',
	'edit'				=> 'edit',
	'setup'				=> 'setup',
	'up'				=> 'up',
	'add'				=> 'add',
	'install'			=> 'install',
	'delete'			=> 'delete',
	'remove'			=> 'remove',
	'confirmdel'		=> 'Really delete this page and every page under it?',
	'create_page'		=> 'Create new page',
	'page_title'		=> 'page title',
	'menu_title'		=> 'menu title',
	'add_image'			=> 'Add image',
	'pic_gallery'		=> 'Picture gallery',
	'date'				=> 'Date',
	'pic_title'			=> 'Picture title',
	'pic'				=> 'Picture',
	'desc'				=> 'Description',
	'publish'			=> 'publish picture',
	'add_img_to'		=> 'Add a picture to',
	'del_confirm'		=> 'Are you sure you want to delete this item',
	'del_confirm_user'	=> 'Do you really want to delete this user?',
	'help_module_opts'	=> 'Change module options'
);
// system
$GLOBALS['langSystem'] = array(
	'email_legend'			=> 'Email alerts',
	'email_list_help_on'	=> 'Alert enabled',
	'email_list_help_off'	=> 'Alert disabled',
	'email_list_help_in'	=> 'Incoming e-mail',
	'email_list_help_out'	=> 'Outgoing e-mail',
);
//admin_module.php / content in Modules & Options section
$GLOBALS['langModule'] = array (
	'list'				=> 'List of modules',
	'options'			=> 'options',
	'saveoptions'		=> 'Save Options',
);
//admin_page_content.php / content in edit page
$GLOBALS['langEditContent'] = array (
	'contentup'			=> 'Content updated!',
	'pageup'			=> 'page updated!',
	'page_no_change'	=> 'No changes need to be saved',
	'page_published'	=> 'Page successfully published',
	'pageprotect'		=> 'page is protected'
);
//admin_page_hearder.php/ content in the setup page
$GLOBALS['langSetHeaders'] = array (
	'newpage'			=> 'New page',
	'duplicate'			=> 'Duplicate',
	'duplicate_confirm'	=> 'Really duplicate this page and all its subpages ?',
	'headersup'			=> 'page headers updated!',
	'errornotfound'		=> 'ERROR:Page not found',
	'basic'				=> 'Basic information',
	'title'				=> 'Title',
	'menu'				=> 'Menu',
	'status'			=> 'Status',
	'parent'			=> 'Parent',
	'description'		=> 'Description',
	'publish'			=> 'publish page',
	'publish_auto'		=> 'publish directly',
	'display'			=> 'Visibility',
	'display_ok'		=> 'content published',
	'display_public'	=> 'public content',
	'display_protected'	=> 'protected content',
	'display_private'	=> 'private content',
	'display_menu'		=> 'show in navigation menu',
	'advanced'			=> 'Advanced settings',
	'showhide'			=> 'Show/Hide',
	'shortcut'			=> 'Shortcut',
	'template'			=> 'Template',
	'module'			=> 'Module',
	'keywords'			=> 'Keywords',
	'charset'			=> 'Charset',
	'protection'		=> 'Protection',
	'nocontent'			=> 'can not edit contents',
	'nosetup'			=> 'can not edit setup',
	'noadd'				=> 'can not add sub pages',
	'nomove'			=> 'can not move',
	'nodelete'			=> 'can not delete',
	'apply_to_children'	=> 'Update description in all sub pages',
	'status_published'	=> 'Published',
	'status_unpublished'=> 'Unpublished',
	'status_public'		=> 'public',
	'status_protected'	=> 'protected',
	'status_private'	=> 'private'
);
//admin-user.php / users administration section
$GLOBALS['langUserAdmin'] = array (
	'noaccess'			=> 'access denied',
	'users'				=> 'Users',
	'newuser'			=> 'create new user',
	'newregister'		=> 'New member request',
	'administrator'		=> 'administrator',
	'moderator'			=> 'moderator',
	'member'			=> 'member',
	'guest'				=> 'guest',
	'contact'			=> 'contact (no access)',
	'search_name'		=> 'Enter a name',
	'order_creation'	=> 'Rights, Creation',
	'order_rights'		=> 'Rights, Name',
	'order_visit'		=> 'Last visit',
	'info_creation'		=> 'Account created on',
	'info_login'		=> 'Last visit on',
	'info_visits'		=> 'Number of visits',
	'info_failed'		=> 'Failed login attempts'
);
//admin_user_edit.php / user edit section
$GLOBALS['langUserContent'] = array (
	'infosaved'			=> 'Information successfully saved',
	'infocreated'			=> 'User successfully created',
	'errorform'			=> 'ERROR:There are some errors in the form - Information not saved!',
);

//Submit label
$GLOBALS['langSubmit'] = array (
	'close'				=> 'Close',
	'closenosave'		=> 'Close without saving',
	'closeconfirm'		=> 'Drop all modifications?',
	'backtoprevious'	=> 'Back to previous page',
	'create'			=> 'Create',
	'save'				=> 'Save changes',
	'publish'			=> 'Save &amp; Publish',
	'saveclose'			=> 'Save &amp; Close',
	'savecontinue'		=> 'Save &amp; Continue',
	'saveadd'			=> 'Save &amp; Create new one',
	'cancel'			=> 'Cancel',
	'update'			=> 'Update',
	'updatecontinue'	=> 'Update &amp; Continue',
	'delete'			=> 'Delete'
);
//error.php / error page
$GLOBALS['langError'] = array (
	'errorpagetitle'	=> 'TZN CMS error',
	'houston'		=> 'Houston, there\'s a problem...!',
	'checkinstall'		=> 'click here to check installation',
	'nofolder'		=> '(and installation folder can not be found)',
	'backtry'		=> 'click here to go back and try again',
	'tryagain'		=> 'click here to try again',
	'homeback'		=> 'click here to return to home page',
	'upwebsite'		=> 'We\'re updating our website',
	'bebacksoon'		=> ' will be back very sOOn',
	'pagereserved'		=> 'Page reserved to members',
	'pleaselogin'		=> 'please log in',
	'cominsoon'		=> 'Coming soon...',
	'loginfailed'		=> 'Login Failed: ',
	'usercannotenable'	=> 'Can not enable user (no username supplied)'
);
//form_to_mail module / public_content.php
$GLOBALS['langForm_content'] = array (
	'sent'			=> 'message sent!',
	'error_sent'		=> 'error occured while sending email',
);
//html_content & straigth content module / admin_options.php
$GLOBALS['langHTML'] = array (
	'height'			=> 'Height of textarea',
);
// fields and column labels
$GLOBALS['langForm'] = array (
	'label'				=> 'Label',
	'name'				=> 'Name',
	'type'				=> 'Type',
	'compulsory'		=> 'Compulsory',
	'addfield'			=> 'add new field',
	'language'			=> 'Language',
	'options'			=> 'Options',
	'introduction'		=> 'Introduction',
	'email_subject'		=> 'Email Subject',
	'send_to'			=> 'Send to',
	'copy'				=> 'Copy to',
	'submit_button'		=> 'Submit button',
	'response'			=> 'Response',
	'footer'		=> 'Page footer',
	'secu_img'			=> 'Security image',
	'enable_contact'	=> 'Enable contact form (really send email)',
	'style'				=> 'Style',
	'large'				=> 'large',
	'medium'			=> 'medium',
	'small'				=> 'small',
	'tiny'				=> 'tiny',
	'same_line'			=> 'Same line as previous',
	'text'				=> 'Text',
	'choice'			=> 'Choice',
	'other'				=> 'Other',
	'single_line'		=> 'Single line',
	'default_value'		=> 'Default Value',
	'multi_line'		=> 'Multi line',
	'rows'				=> 'rows',
	'valid_data'		=> 'Valid data',
	'alphanumeric'		=> 'alphanumeric',
	'integer'			=> 'integer',
	'decimal'			=> 'decimal',
	'email'				=> 'email',
	'checkbox'			=> 'Multi choice (checkbox)',
	'radio'				=> 'Single choice (radio)',
	'select'			=> 'Drop down (select)',
	'choice'			=> 'Choices',
	'hidden'			=> 'Hidden field',
	'value'				=> 'Value',
	'yes'				=> 'Yes',
	'no'				=> 'No',
	'formnot'			=> 'Form not designed',
	'wait'				=> 'Please wait...',
	'disable'			=> 'Form temporary disable',
	'compulsory_legend' 		=> 'Fields in <span class="compulsory">red</span> are compulsory.',
	'security_label'			=> 'Anti&nbsp;Spam System',
	'security_image'			=> 'Please type the characters your see in image',
	'security_image_error' 		=> 'Wrong security code, please try again (cookies must be enabled)'
);

$GLOBALS['langTaskDetails'] = array (
    'history_date'      => 'date',
    'history_user'      => 'user',
    'history_what'      => 'action'

);
// project related
$GLOBALS['langTeam'] = array(
    'team'           	=> 'Group',
    'teams'          	=> 'Groups',
    'teams_legend'	=> 'User groups',
    'tab_info'		=> 'Group info',
    'tab_members'	=> 'Members',
    'tab_pages'		=> 'Pages',
    'name'              => 'Name',
    'description'       => 'Description',
    'position'          => 'Position',
    'members'           => 'Members',
    'members_legend'    => 'Project members',
    'pages_legend'	=> 'Group pages',
    'status'            => 'Status',
    'action'            => 'Action',
    'new_team'			=> 'create a new group',
    'project_history'   => 'View status change history',
    'remove_confirm'    => 'really remove from the project?',
    'new_member'   => 'Add a member to this group',
    'search_member'		=> 'Search a member',
    'new_page'   => 'Add page to group',
    'page'		=> 'Page',
    'user_no_team'   => 'Belongs to no group',
    'visibility'		=> 'Access',
    'visi_public'		=> 'Public',
    'visi_protected'	=> 'Protected',
    'visi_private'		=> 'Private',
    'visi_disabled'	=> 'Disabled',
    'enabled_label'		=> 'Enable group',
    'help_no_edit'		=> 'General information on the group'
);
$GLOBALS['langTeamContent'] = array (
	'infosaved'			=> 'Group information saved',
	'infocreated'		=> 'Group successfully saved',
	'errorform'			=> 'ERROR:Form contains erros<br />Data not saved!',
);
// user related
$GLOBALS['langUser'] = array(
    'information'       => 'Personal information',
    'user'              => 'User',
    'name'              => 'Name',
    'title'             => 'Title',
    'first_name'        => 'First name',
    'middle_name'       => 'Middle name',
    'last_name'         => 'Last name',
    'company'		=> 'Company',
    'address'           => 'Address',
    'location'          => 'Location',
    'city'              => 'City',
    'state'             => 'State',
    'zip_code'		=> 'Zip Code',
	'country'           => 'Country',
	'language'			=> 'Language',
	'phone'		=> 'Phone',
	'mobile'	=> 'Mobile',
	'fax'		=> 'Fax',
    'email'             => 'Email',
    'email_address'		=> 'Email address',
    'position'          => 'Level',
    'last_login'        => 'Last login',
    'action'            => 'Action',
    'delete_confirm'    => 'really delete this user?',
    'enable_confirm'    => 'really enable this user?',
    'disable_confirm'   => 'really disable this user?',
    'account'           => 'Account credentials',
    'account_legend'    => 'Please choose a username and password to gain access to the system',
    'username'          => 'Username',
    'password'          => 'Password',
    'password_confirm'  => '(confirm)',
    'auto_login'        => 'remember me on this computer',
    'password_legend'   => 'Enter a password (and confirm) only if you want to change it.',
    'password_reminder'	=> 'password lost ?',
    'password_intro'	=> "<strong>If you are already a member</strong>, but have forgotten your password, please fill the form below :",
    'password_footer'	=> 'A message will be sent to this email address with your password',
    'password_email'	=> 'Please check your emails',
    'password_reminded'	=> 'A message containing your access codes<br />has just been sent to your email address',
    'back_to_login'		=> 'back to login form',
    'back_to_home'		=> 'back to home page',
    'go_to_login_again'	=> 'login again',
    'teams_legend'	=> 'Team legend',
    'enabled_label'     => 'Account is enabled',
    'nickname'		=> 'Nickname',
    'nickname_legend'	=> 'Nickname is displayed instead of your real name',
);

// buttons
$GLOBALS['langButton'] = array(
	'send'		=> 'Send',
	'submit'	=> 'Submit request',
	'cancel'	=> 'Annuler',
    'add'           => 'Create',
    'add_account'	=> 'Create an account',
    'save'			=> 'Save',
    'update'        => 'Save changes',
    'reset'			=> 'Reset form',
    'back'			=> 'Back to list',
    'ignore'        => 'Cancel changes',
    'back'          => 'Back to list',
    'search'		=> 'Search',
    'associate'		=> 'Associate'
);


// error and information messages
$GLOBALS['langMessage'] = array (
    'not_found'			=> 'Data not found',
    'not_found_or_denied'       => 'Data not found or access denied',
    'denied'                    => 'Access denied!',
    'search_no_result'		=> 'Search didn\'t return any result',
    'data_updated'				=> 'Data successfully updated',
    'data_deleted'				=> 'Data successfully deleted!',
    'data_created'				=> 'Data successfully created',
	'close_window'			    => 'close this window',
    'session_expired'           => 'Session has expired'
);

$GLOBALS['langRss'] = array (
    'no_task'       => 'No task for today',
    'error_login'   => 'Authentication failed'
);

