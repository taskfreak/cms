<?php

class LogisterController extends TznController
{

	function LogisterController() {
		parent::TznController('logister');
	}
	
	function main() {

		parent::main();

		if ($GLOBALS['objUser']->isLoggedIn()) {
			// user is logged in
			if ($GLOBALS['objUser']->hasAccess(5)) {
				TznUtils::redirect(CMS_WWW_URI.'admin/');
			} else if (@constant('CMS_LOGIN_REDIRECT_MEMBER')) {
				TznUtils::redirect(CMS_LOGIN_REDIRECT_MEMBER);
			} else {
				TznUtils::redirect(CMS_WWW_URI);
			}
		} else if ($_REQUEST['forgot']) {
			Tzn::redirect(CMS_WWW_URI.'logminder.php?username='
				.urlencode(TznUtils::getHttp($_REQUEST['username'])));
		}
		
		// set referring page
		TznUtils::addReferrer(false, true); // get referrer
		
		// check settings again
		if (!$GLOBALS['objCms']->settings->get('registration')) {
			TznUtils::redirect('login.php','Sorry, accounts can be created by administrator only');
		}
		
		// initialize
		$this->objPositionList = new GlobalPositionList();
		
		/* --- LOAD USER --------------------------------------------------- */
		
		$this->objMember = new Member();
		$this->arrPlugins = array();
		
		if (count($GLOBALS['tznPlugins']['member'])) {
			foreach(array_keys($GLOBALS['tznPlugins']['member']) as $folder) {
				$class = 'Plugin'.TznUtils::strToCamel($folder,true);
				$objPlugin = new $class();
				$objPlugin->main($this->objMember);
				$this->arrPlugins[$folder] = $objPlugin;
			}
		}
		
		/* --- SAVE USER -------------------------------------------------- */
		
		if ($this->objMember->isLoaded()) {
			$GLOBALS['objCms']->initSubmitting(1);
		} else if ($GLOBALS['objUser']->isLoggedIn()) {
			$GLOBALS['objCms']->initSubmitting(2,3);
		} else {
			$GLOBALS['objCms']->initSubmitting(1);
		}
		
		if ($GLOBALS['objCms']->submitMode) {
			
			$this->objMember->setDetails($_POST);
				
			$this->objMember->level = 0;
			$this->objMember->enabled = 0;
			
			$this->callPlugins('setAuto', $this->objMember);
			
			if ($this->objMember->check($_POST['password1'],$_POST['password2'],true,true)) {
				
				if (TznPage::checkCaptcha()) {
					
					// create activation code
					$this->objMember->activation = Tzn::getRdm(16);
					
					// update DB
			        if ($this->objMember->isLoaded()) {
			    		$this->objMember->update();
			        } else {
			            $this->objMember->author->id = 1;
			            $this->objMember->add();
			        }
			        
			        include CMS_INCLUDE_PATH.'language/'.$GLOBALS['objCms']->settings->get('default_language').'/system.php';
				    include_once(CMS_CLASS_PATH."pkg_com.php");
			        
			        // send emails
			        $bodyMemberMessage = "\r\n";
						
					$objMessage = new EmailMessage();
					
					$pEmailMemberCode = 'sign_up_pending';
					switch ($GLOBALS['objCms']->settings->get('registration')) {
					case 1:
						//email to webmaster
						if ($objMessage->loadByKey('description','sign_up_new')) {
							$bodyMessage = "\r\n\t".$this->objMember->getName()."\r\n\r\n"
								.CMS_WWW_URL.'admin/member.php?id='.$this->objMember->id."\r\n";
							if ($objMessage->send($bodyMessage, $this->objMember->email)) {
								// well, ok, fine
							} else {
								$this->errorMessage = $GLOBALS['langSystemEmailStuff']['send_error'];
							}
						} else {
							$this->errorMessage = $GLOBALS['langSystemEmailStuff']['send_not_found'];
						}
						$bodyMemberMessage .= "\r\n\r\n"
							.$GLOBALS['langTznUser']['login_username']
							.' : '.$this->objMember->username."\r\n"
							.$GLOBALS['langTznUser']['login_password']
							.' : '.$_REQUEST['password1']."\r\n";
						break;
					case 2:
						// activation by user
						$bodyMemberMessage .= "\r\n"
							.CMS_WWW_URL.'login.php?username='.$this->objMember->username
							.'&activation='.$this->objMember->activation."\r\n\r\n"
							.$GLOBALS['langTznUser']['login_username']
							.' : '.$this->objMember->username."\r\n"
							.$GLOBALS['langTznUser']['login_password']
							.' : '.$_REQUEST['password1']."\r\n"
							.$GLOBALS['langTznUser']['login_activation']
							.' : '.$this->objMember->activation."\r\n";
						$pEmailMemberCode = 'sign_up_activation';
						break;
					}
					
					// email to user
					if ($objMessage->loadByKey('description',$pEmailMemberCode)) {
						if ($objMessage->send($bodyMemberMessage, $this->objMember->email)) {
							// well, ok, brillant
						} else {
							$this->errorMessage = $GLOBALS['langSystemEmailStuff']['send_error'];
							ob_start();
							$objMessage->printErrorList();
							error_log('error sending email: '.ob_get_contents());
							ob_clean();
						}
					}
					
					TznUtils::redirect('logister.php?dude='.$this->objMember->id,$this->errorMessage);
					
				} else {
					// captcha error
					$this->securityError = true;
				}
				
			} else {
				$this->errorMessage =$GLOBALS['langTznCommon']['form_error'];
			}
		
		} else if ($this->dude = intval($_REQUEST['dude'])) {
		
			// request sent
			$this->objMember->setUid($this->dude);
			$this->objMember->load();
		
			if ($this->objMember->enabled) {
				Tzn::redirect('login.php','Account already enabled, please log in'); // -TODO-TRANSLATE-
			}
		
		} else {
			
			// defaults
			$this->objMember->initObjectProperties();
			$this->objMember->country->id = $GLOBALS['objCms']->settings->get('default_country');
		}
		
		/* --- LOAD COUNTRIES ------------------------------------------------- */
		
		$this->objCountryList = new Country();
		$this->objCountryList->addOrder('name');
		$this->objCountryList->loadList();
		
		/* --- LOAD STATES ---------------------------------------------------- */
		
		$this->objStateList = new UsState();
		
		/* --- LOAD CMS LANGUAGES --------------------------------------------- */
			
		$this->objLanguageList = new CmsLanguage();
		
		$GLOBALS['objHeaders']->add('jsScript', 'mootools-1.2.4.js');
		
	}
}