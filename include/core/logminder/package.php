<?php

class LogminderController extends TznController
{

	function LogminderController() {
		parent::TznController('logminder');
	}
	
	function main() {

		// skip if user is already logged in
		
		if ($GLOBALS['objUser']->isLoggedIn()) {
			if ($GLOBALS['objUser']->hasAccess(5)) {
				TznUtils::redirect(TznCms::getUri('admin/'));
			} else {
				TznUtils::redirect(TznCms::getUri());
			}
		}
		
		if (!$GLOBALS['objCms']->settings->get('password_reminder')) {
			TznUtils::redirect('login.php','No password reminder');
		}
		
		// email reminder
		
		if (isset($_POST['email'])) {
			
		    include CMS_INCLUDE_PATH.'language/'.$GLOBALS['objCms']->settings->get('default_language').'/system.php';
		    include_once(CMS_CLASS_PATH."pkg_com.php");
		
			$objUserFound = new Member();
			$objUserFound->setEml('email',$_POST['email']);
		
			if (!$objUserFound->email || $objUserFound->e('email')) {
			
				$this->errorMessage = 'Error: '.$GLOBALS["langTznUser"]["email_invalid"];
			
			} else {
				
				if ($newpass = $objUserFound->forgotPassword("email", $objUserFound->email)) {
					
					// send email
					$bodyMessage = "\r\n\r\n\t"
						.$GLOBALS['langTznUser']['login_username']
						.' : '.$objUserFound->username."\r\n\t"
						.$GLOBALS['langTznUser']['login_password'].' : '.$newpass;
					$objMessage = new EmailMessage();
					
					if ($objMessage->loadByKey('description','members_pass_reminder')) {
						if ($objMessage->active) {
							if ($objMessage->send($bodyMessage, $objUserFound->email)) {
								$this->reminded = true;
							} else {
								// print_r($objMessage->_error);
								$this->errorMessage = $GLOBALS['langSystemEmailStuff']['send_error'];
							}
						} else {
							$this->errorMessage = $objMessage->_error['send'];
						}
					} else {
						$this->errorMessage = $GLOBALS['langSystemEmailStuff']['send_not_found'];
					}
		
				} else {
		
					$this->errorMessage = 'Error: '.$objUserFound->e("forgot");
		
				}
			}
		}
	}
	
}