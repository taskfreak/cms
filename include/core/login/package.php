<?php

class LoginController extends TznController
{

	function LoginController() {
		parent::TznController('login');
	}
	
	function main() {
	
		if ($GLOBALS['objUser']->isLoggedIn()) {
			// user is logged in
			if ($GLOBALS['objUser']->hasAccess(5)) {
				TznUtils::redirect(CMS_LOGIN_REDIRECT_ADMIN);
			} else if (@constant('CMS_LOGIN_REDIRECT_MEMBER')) {
				TznUtils::redirect(CMS_LOGIN_REDIRECT_MEMBER);
			} else {
				TznUtils::redirect(CMS_WWW_URI);
			}
		} else if ($_REQUEST['forgot']) {
			Tzn::redirect(CMS_WWW_URI.'logminder.php?username='
				.urlencode(TznUtils::getHttp($_REQUEST['username'])));
		}
		
		$this->activationRequest = ($_REQUEST['activation'])?true:false;
		
		if (isset($_POST["username"])) {
			$this->activationCode = false;
			if ($GLOBALS['objCms']->settings->get('registration') == 2) {
				$this->activationCode = $_POST['activation'];
			}
			if ($GLOBALS['objUser']->login($_POST['username'],
				(@constant('TZN_USER_PASS_MODE') == 5)?$_POST['challenge']:$_POST['password'],
				null,$this->activationCode)) 
			{
		        // auto login?
		        if ($GLOBALS['objCms']->settings->get('auto_login') && $_POST['remember']) {
		            $GLOBALS['objUser']->setAutoLogin();
		        }
		        
		        // redirect to requested page
		        $req = trim(TznUtils::naturalReferrer(CMS_WWW_URL));
		        
		        if (!$req || $req == CMS_WWW_URL) {
		        	if ($GLOBALS['objUser']->hasAccess(5) && @constant('CMS_LOGIN_REDIRECT_ADMIN')) {
			        	$req = CMS_LOGIN_REDIRECT_ADMIN;
			        } else if (defined('CMS_LOGIN_REDIRECT_MEMBER')) {
			        	$req = CMS_LOGIN_REDIRECT_MEMBER;
			        }
		        }
		        
		        TznUtils::redirect($req);
				
			} else {
			
				$this->errorMessage = $GLOBALS['langError']['loginfailed'].$GLOBALS['objUser']->e('login');
				
				if ($GLOBALS['objUser']->isLoaded() && $GLOBALS['objUser']->activation 
					&& $GLOBALS['objCms']->settings->get('registration') == 2) 
				{
					// user is requested to activate account
		        	$this->activationRequest = true;    	
				}
				
				//$GLOBALS['objUser']->printErrorList();
			}
		}
		
		// set referring page
		TznUtils::addReferrer($_GET['ref'], true); // get referrer
		
		if (@constant('TZN_USER_PASS_MODE') == 5) {
			$GLOBALS['objHeaders']->add('jsScript','md5.js');
			$_SESSION['challenge'] = Tzn::getRdm(64);
		}
		
	
	}

}