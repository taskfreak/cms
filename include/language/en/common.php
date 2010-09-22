<?php
/****************************************************************************\
* Tirzen CMS                                                                 *
******************************************************************************
* Version: 0.1                                                               *
* Authors: Stan Ozier <stan@tirzen.com>                                      *
* License:  http://www.gnu.org/licenses/gpl.txt (GPL)                        *
\*****************************************************************************/


$GLOBALS['langTznCommon'] = array(
  'field_compulsory'	=>	'This field is compulsory',
  'saved_success'			=> 'Information successfully saved',
  'operation_successful'	=> 'Operation successful',
  'form_error'				=> "Form contains errors\nData not saved",
  'operation_failed'		=> 'Operation failed',
  'operation_denied'		=> 'Operation denied',
  'search_empty'			=> 'search produced no results',
  'list_empty'				=> 'no item to be displayed',
  'data_not_found'			=> 'Data not found',
  'data_denied'				=> 'Data access denied',
  'data_not_published'		=> 'Data not published',
  'content_not_found'		=> 'Content not found',
  'content_denied'			=> 'Content access denied',
  'content_not_published'	=> 'Content not published',
  'login_needed'			=> 'Restricted area, please login',
  'confirmation_email_sent'	=> 'Confirmation e-mail sent',
  'confirmation_email_error'	=> 'An ERROR occured when trying to send confirmation email'
);
$GLOBALS["langTznUser"] = array(
  "user_pass_mismatch" => "password mismatch",
  "user_pass_limit1" => "password must have between ",
  "user_pass_limit2" => " and ",
  "user_pass_limit3" => " characters",
  "user_pass_empty" => "password can not be empty",
  "user_pass_invalid" => "Invalid password",
  "user_name_limit1" => "username must have between ",
  "user_name_limit2" => " and ",
  "user_name_limit3" => " characters",
  "user_name_exists" => "username already exists",
  "user_name_empty" => "Please enter your user name",
  "user_name_invalid" => "Username shouldn't contain any special characters or spaces",
  "user_name_not_found" => "username not found",
  "user_disabled" => "Account disabled",
  "user_forbidden" => "You are not authorized to login",
  "user_email_empty" => "Please enter your email",
  "user_email_format" => "Please enter a valid email",
  "user_email_invalid" => "Email not found",
  "user_password_invalid" => "Wrong password",
  "email_empty" => "Please enter your e-mail address",
  "email_exists" => "an account with this e-mail address already exists",
  "email_invalid" => "please enter a valid e-mail address",
  "document_wrong_type" => "wrong file type",
  "document_empty" => "please select a file",
  "common_name_empty" => "please enter a name",
  "common_title_empty" => "please enter a title",
  "common_theme_empty" => "please select a theme",
  "common_description_empty" => "please enter description",
  "common_message_empty" => "please enter a message",
  "common_date_empty" => "please select a date",
  "common_date_invalid" => "end date is not valid",
  "common_city_empty" => "please enter city",
  "common_info_empty" => "please enter contact information",
  "common_url_empty" => "please enter a web address",
  "common_file_empty" => "please select a file by clicking the 'Browse..' button",
  "login_email" => "Your email",
  "login_username" => "Username",
  "login_password" => "Password",
  "login_error0" => "Authentification required",
  "login_error1" => "Password required",
  "login_error2" => "Access denied (restricted area)",
  "login_error3" => "Invalid username or password",
  "login_last_date" => 'Last login date',
  "login_last_address"  => 'Last login address',
  'login_activation'	=> 'Activation code',
  "login_remember"		=> 'Remember me on this computer',
  "post_user" => "user",
  "post_category" => "category",
  "post_title" => "title",
  "post_documents" => "photos",
  "post_date" => "posted on",
  "post_by" => "by",
  "doc_file_name" => "file name",
  "doc_file_size" => "file size",
  "doc_file_type" => "file type",
  "doc_remove" => "remove",
  "doc_update" => "update files",
  "post_description" => "description",
  "button_create" => "Create",
  "button_update" => "Update",
  "button_delete" => "Delete",
  "button_login" => "Login",
  "profile_update_success" => "Account sucessfully updated",
  "url" => "Website&nbsp;:&nbsp;",
  "atsign" => "(at)",
  'register_common'		=> "We received your request.",
  'register_pending'	=> "We will contact you again when your request has been validated.\r\n\r\nYour access codes:",
  'register_activation'	=> "To complete your registration, you now need to validate your account. Simply click on the link below, or use the activation code when logging in.",
  'register_activated'	=> "Congratulations, your account has been validated!",
  'register_admin_pending' => "A new registration request has been made:",
  'register_admin_confirm' => "A new registration has been confirmed:"
);

$GLOBALS["langShortDay"] = array(
  "Sun","Mon","Tur","Wed","Thu","Fri","Sat"
);

$GLOBALS["langLongMonths"] = array(
    "January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"
);

// date support
$GLOBALS['langDate'] = array (
	'today'				=> 'today',
	'tomorrow'			=> 'tomorrow',
	'day'				=> 'day',
	'week'				=> 'week',
	'month'				=> 'month',
	'year'				=> 'year',
);

$GLOBALS['langDateDay'] = array (
    'monday'            => 'monday',
    'tuesday'           => 'tuesday',
    'wednesday'         => 'wednesday',
    'thursday'          => 'thursday',
    'friday'            => 'friday',
    'saturday'          => 'saturday',
    'sunday'            => 'sunday'
);

$GLOBALS['langDateMore'] = array (
	'day'				=> 'day',
	'days'				=> 'days',
	'help'				=> 'eg. today, tomorrow, 12 apr'
);

$GLOBALS['langTznDocument'] = array(
	'document_wrong_type'	=> 'document type not accepted '
);
