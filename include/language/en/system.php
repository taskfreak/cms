<?php
/****************************************************************************\
* TaskFreak!                                                                 *
* multi user                                                                 *
******************************************************************************
* Version: 0.5.1                                                             *
* Authors: Stan Ozier <taskfreak@gmail.com>                                  *
* License:  http://www.gnu.org/licenses/gpl.txt (GPL)                        *
\****************************************************************************/


// system menu
$GLOBALS['langSystemMenu'] = array (
	'general'   		=> 'General',
	'email_alerts'		=> 'Email Alerts',
	'contexts'			=> 'Contexts'
);

$GLOBALS['langSystemStuff'] = array(
	'site_legend'		=> 'Site preferences',
	'site_title'		=> 'Site title',
	'site_footer'		=> 'Page footer',
	'site_offline'		=> 'Set the site under maintenance (off-line)',
	'interface_legend'	=> 'User interface',
	'interface_lang'	=> 'Language',
	'interface_date_format'	=> 'Date format',
	'interface_date_eur'	=> 'European (dd/mm/yy)',
	'interface_date_usa'	=> 'American (mm/dd/yy)',
	'options_legend'	=> 'Members options',
	'options_auto_login'	=> 'Allow members to have their password remembered',
	'options_pass_reminder'	=> 'Allow members to request a new password if forgotten',
	'options_register'	=> 'Allow visitors to register as members',
	'options_register_man'	=> 'Accounts are activated by admin only',
	'options_register_auto'	=> 'Accounts are activated by new members',
	'settings_saved'	=> 'System preferences saved'
);

// email description
$GLOBALS['langSystemEmail'] = array(
    'sign_up_new'			=> 'Sign-up: new request notification',
    'sign_up_pending'		=> 'Sign-up: request pending',
    'sign_up_activation'	=> 'Sign-up: activation email',
    'sign_up_confirmation'	=> 'Sign-up: confirmation email',
    'members_pass_reminder'	=> 'Members: Password reminder'
);

// email subjects
$GLOBALS['langSystemEmailSubject'] = array(
	'sign_up_new'			=> 'TaskFreak! New account requested',
	'sign_up_pending'		=> 'TaskFreak! Your account (pending)',
	'sign_up_activation'	=> 'TaskFreak! Your account needs activation',
	'sign_up_confirmation'	=> 'TaskFreak! Your account is now ready',
	'members_pass_reminder' => 'TaskFreak! Your new password'
);

// email stuff
$GLOBALS['langSystemEmailStuff'] = array(
	'setup_prefix'	=> 'Subject prefix',
	'setup_address'	=> 'Default email address',
	'setup_smtp'	=> 'Send emails through SMTP instead of default PHP mail() function',
	'setup_server'	=> 'Serveur',
    'from'          => 'From',
    'to'            => 'To',
    'cc'            => 'Cc',
    'dir'           => 'Dir.',
    'dir_in'        => 'IN',
    'dir_out'       => 'OUT',
    'alert'         => 'Alert',
    'name'          => 'Name',
    'email'         => 'Email',
    'subject'       => 'Subject',
    'body_template' => 'Body template',
    'enabled'       => 'Enabled',
    'enable_label'  => 'Enable email alert',
    'disabled'      => 'Disabled',
    'disable_label' => 'Disable email alert',
    'link_edit'     => 'Edit email alert settings',
    'check_recipient'	=> 'Please enter recipient address',
	'check_subject'		=> 'Please enter subject',
	'check_injection'	=> 'Email header injection attempt detected',
	'check_active'		=> 'Email alert is not active',
	'send_ok'			=> 'Email sent',
	'send_not_found'	=> 'Error sending email: settings not found',
	'send_no_address'	=> 'Error sending email: no address specified',
	'send_error'		=> 'Error sending email: can not send'
);
