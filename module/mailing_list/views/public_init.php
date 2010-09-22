<?php
/****************************************************************************\
* Tirzen CMS                                                                 *
******************************************************************************
* Version: 0.1                                                               *
* Authors: Stan Ozier <stan@tirzen.com>                                      *
* License:  http://www.gnu.org/licenses/gpl.txt (GPL)                        *
\****************************************************************************/

// init
$objModule = TznCms::newModuleObject('mailing_list');

$objModule->setUid($objPage->id);
$objModule->load();

$objSubscriber = new Member();
$objSubscriber->loadPlugins();
$objSubscriber->setPluginProperty('member_newsletter','subscribeEnable',1);

$pErrorMessage = '';
$pSuccess = 0;

if ($_SESSION['newsletter_submit']) {
	
	/* redirected on success */
	
	$pSuccess = intval($_SESSION['newsletter_submit']);
	unset($_SESSION['newsletter_submit']);
	
} else if ($_POST['save']) {

	/* subscribing / unsubscribing */
	
	include CMS_INCLUDE_PATH.'language/'.$objCms->settings->get('default_language').'/system.php';
	include_once(CMS_CLASS_PATH."pkg_com.php");
			
	$objMessage = new EmailMessage();

	$objSubscriber->setDetails($_POST);
	
	$pMode = $objSubscriber->getPluginProperty('member_newsletter','subscribeEnable');
	if ($objSubscriber->checkEmpty('firstName,lastName,email')) {
	
		$bodyMessage = "\tPrénom:\t".$objSubscriber->firstName
			."\n\tNom:\t".$objSubscriber->lastName
			."\n\tE-mail:\t".$objSubscriber->email."\n";
		if ($pMode) {
			$bodyMessage .= "\tFormat:\t"
				.(($objSubscriber->getPluginProperty('member_newsletter','subscribeHtml'))?
				'HTML':'Texte');
		}
	
		$objSearch = new Member();
		if ($objSearch->loadByFilter($objSearch->gField('email')
			.'=\''.$objSubscriber->email.'\''))
		{
			// subscriber is in DB
			if ($objSubscriber->firstName == $objSearch->firstName 
				&& $objSubscriber->lastName == $objSearch->lastName)
			{	    
				$objPlug = $objSubscriber->getPluginObject('member_newsletter');
				$objPlug->setUid($objSearch->id);
				
				if ($objPlug->update()) {
					// information is correct
					if ($pMode) {
						// subscribe
						$_SESSION['newsletter_submit'] = 2;
						// prepare email
						$objMessage->loadByKey('description','newsletter_subscribed');
					} else {
						// unsubscribe
						$_SESSION['newsletter_submit'] = 3;
						// prepare email
						$objMessage->loadByKey('description','newsletter_unsubscribed');
					}
					
					// send email
					$objMessage->send($bodyMessage, $objSubscriber->email);
					
					Tzn::redirect($objPage->getUrl());
				}
			} else {
				// different info
				$pErrorMessage = 'Un compte existe avec cette adresse email, mais les informations ne correspondent pas.'; 
			}
		} else if ($pMode) {
			// new subscriber?
			if ($objSubscriber->add()) {
				$_SESSION['newsletter_submit'] = 1;
				// prepare email
				$objMessage->loadByKey('description','newsletter_subscribed');
				$objMessage->send($bodyMessage, $objSubscriber->email);
			} else {
				$pErrorMessage = 'Votre inscription n\'a pas pu être prise en compte pour une raison inconnue';
			}
		} else {
			// trying to unsubscribe (but not found)
			$pErrorMessage = 'Nous n\'avons pu trouver de compte correspondant à cette adresse email!';
			$objSubscriber->e('email','Vérifiez votre adresse');
		}
	}
}

$objModule->content->initPublic();

// ==== HTML ===============================================================

$objCms->headers->add('cssModule','mailing_list');