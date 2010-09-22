<?php

define('PRJ_MAILING_LIST_PAGE_SIZE',10);
define('PRJ_NEWS_SEND_BACKGROUND', false);
define('PRJ_NEWS_SEND_PACK_SIZE',2);
define('PRJ_NEWS_SEND_PACK_DELAY_TYPE','sec'); // sec or micro
define('PRJ_NEWS_SEND_PACK_DELAY_TIME',15);

// include_once(CMS_MODULE_PATH.'mailing_list/plugin.php');

class ModuleMailingList extends TznModule
{

	function ModuleMailingList() {
		parent::TznModule('mailing_list');
		$this->_module = array();
	}
	
	function installEnable() {
		// enable as 1: basic, 2: autoload file, 3: instanciate automatically
		parent::installEnable($GLOBALS['confModule']['mailing_list']['autoload']);
		$objDb = new TznDb('memberNewsletter');
		$objDb->query(
			'CREATE TABLE IF NOT EXISTS '.$objDb->gTable().' ('
			."`memberNewsletterId` mediumint(8) unsigned NOT NULL default '0',"
			."`subscribeDate` datetime NOT NULL default '0000-00-00 00:00:00',"
			."`subscribeEnable` tinyint(1) unsigned NOT NULL default '0',"
			."`subscribeHtml` tinyint(1) unsigned NOT NULL default '0',"
			."`newsletterId` mediumint(8) unsigned NOT NULL default '0',"
			."`newsletterDate` datetime NOT NULL default '0000-00-00 00:00:00',"
			."PRIMARY KEY  (`memberNewsletterId`),"
			."KEY `subscribeEnable` (`subscribeEnable`)"
			.') ENGINE=MyISAM'
		);
		
		$objDb->query(
			"CREATE TABLE IF NOT EXISTS `".$objDb->gTable('newsletter')."` ("
			."`newsletterId` mediumint(8) unsigned NOT NULL auto_increment,"
			."`title` varchar(255) collate latin1_general_ci NOT NULL default '',"
			."`creationDate` datetime NOT NULL default '0000-00-00 00:00:00',"
			."`deliveryDate` datetime NOT NULL default '0000-00-00 00:00:00',"
			." PRIMARY KEY  (`newsletterId`)"
			.") ENGINE=MyISAM"
		);
		
		$objDb->query(
			"CREATE TABLE IF NOT EXISTS `".$objDb->gTable('newsletterContent')."` ("
			."`newsletterId` mediumint(8) unsigned NOT NULL default '0',"
			."`moduleName` varchar(127) collate latin1_general_ci NOT NULL default '',"
			."`moduleId` int(10) unsigned NOT NULL default '0',"
			."`position` tinyint(3) unsigned NOT NULL default '0',"
			."PRIMARY KEY  (`newsletterId`,`moduleName`,`moduleId`)"
			.") ENGINE=MyISAM"
		);
		
		// add email alerts
		$this->addEmailAlert(array(
			'mailing_list_subscribed' 	=> 0,
			'mailing_list_unsubscribed'	=> 0,
			'mailing_list_send'			=> 0
		));
	}
	
	/* ----- Common private methods ---------------- */
	
	function _loadNewsletter($id) {
		
		$this->letter = new ContentNewsletter();
	    if ($id) {
	    	$this->letter->setUid($id);
			if ($this->letter->load()) {
				return true;
			}
		}
		return false;
		
	}
	
	function _loadStuff() {
	
		// --- LOAD NEWSLETTERS ------------
        
        $this->letters = new ContentNewsletter();
        $this->letters->addKeywordFilter($_SESSION['mailingLetterKeyword']);
        $this->letters->addOrder('j1_creationDate DESC');
        $this->letters->setPageAuto();
        $this->letters->loadList();
        
        // ---- LOAD SUBSCRIBERS -----------
        
        $this->subscribers = new MailingListSubscriber();
        $this->subscribers->addKeywordFilter($_SESSION['mailingSubscriberKeyword']);
        $this->subscribers->addWhere('subscribeEnable=1');
        $this->subscribers->addOrder('subscribeDate DESC');
        $this->subscribers->setPageAuto();
        $this->subscribers->loadList();
	}
	
	/* ----- Admin Actions ------------------------- */
	
	function adminDefault() {
	
		// main screen : list newsletters and subscribers
		
		$this->content = new CmsContent();
		$this->content->loadContent($GLOBALS['objPage']->id);
		
		$this->content->initAdmin('Full',2);
		
		$GLOBALS['objCms']->initSubmitting(1,2);
		
        $this->_loadStuff();
        
		// set view
		$this->baseLink = CMS_WWW_URI.'admin/page.php?id='.$GLOBALS['objPage']->id;
		$GLOBALS['objHeaders']->add('cssModule','mailing_list.admin');
		$GLOBALS['objHeaders']->add('jsScript',CMS_WWW_URI.'module/mailing_list/js/admin.js');
		$this->setView('admin_main');

	}
	
	/**
	* default action from left menu (special)
	*/
	function adminSpecial() {
		$this->_loadStuff();
		$this->baseLink = CMS_WWW_URI.'admin/special.php?module=mailing_list';
		$GLOBALS['objHeaders']->add('cssModule','mailing_list.admin');
		$GLOBALS['objHeaders']->add('jsScript',CMS_WWW_URI.'module/mailing_list/js/admin.js');
		$this->setView('admin_special');
	}
	
	function adminRead() {
		// view newsletter and links to test/send it
		
		if (!$this->_loadNewsletter($_REQUEST['item'])) {
			TznUtils::redirect(CMS_WWW_URI.'admin/page.php?id='.$_REQUEST['id']);
		}
		
		$this->content =& $this->letter;
		
		$this->member = new Member();
        $this->member->firstName = '[NOM';
        $this->member->lastName = 'INSCRIT]';
        
        $this->letter->initNewsLetter();
        //$this->content->initModules();
        //$this->content->loadModules();  
              
        // check
        $this->recipients = new MemberNewsletter();
        $this->recipients->addWhere('subscribeEnable=1');
        $this->recipients->addWhere('newsletterId <> 0');
        $this->recipients->addWhere('newsletterDate = \'0000-00-00 00:00:00\'');
        $this->someReady = $this->recipients->loadCount();
        if ($this->someReady) {
        	$this->recipients->setPagination(1);
        	$this->recipients->loadList();
			if ($this->subscriber = $this->recipients->rNext()) {
				$this->someId = $this->subscriber->newsletterId;
			}
        }
        
        $GLOBALS['objHeaders']->add('cssModule','mailing_list.admin');
		$this->setView('admin_read');
	}
		
	function adminEdit() {
		// add/edit newsletter
		
		$GLOBALS['objCms']->initSubmitting(1,2); // save and save and close
		
		$pLoaded = $this->_loadNewsletter($_REQUEST['item']);
		$this->content =& $this->letter;
		
		/* if ($objCms->admin->submit) {
            // set body and items
            $this->letter->setHttpAuto();
            // update DB
            if ($pLoaded) {
                $this->letter->update();
            } else {
                $this->letter->add();
            }
            
        } */
        
        // load lists of items to be added
        $this->content->initModules();
        $this->content->loadModules();
        
        // initialize wysiwyg editor
		$this->content->initAdmin('Mini',2);
		
        // add javascript to sort items by drag and drop
        // $GLOBALS['objCms']->headers->add('jsOnLoad',"new Sortables($('news_items'));");

        // prepare view
        $GLOBALS['objHeaders']->add('cssModule','mailing_list.admin');
        $GLOBALS['objHeaders']->add('jsScript',CMS_WWW_URI.'module/mailing_list/js/admin.js');
        $this->setView('admin_edit');
	}
	
	function adminDelete() {
		// delete newsletter
		$this->_loadNewsletter($_REQUEST['item']);
		$this->letter->delete();
		TznUtils::redirect(CMS_WWW_URI.'admin/page.php?id='.$_REQUEST['id'],'Lettre effacée');
	}
	
	/* ----- Public Actions ------------------------ */
	
	function publicDefault() {
	
		$this->content = new CmsContent();
		$this->content->loadContent($GLOBALS['objPage']->id);
		
		// error_log('-> create subscriber');
		
		$this->subscriber = new Member();
		$this->userIsLoggedIn = $GLOBALS['objUser']->isLoggedIn();
		if ($this->userIsLoggedIn) {
			$this->subscriber =& $GLOBALS['objUser'];
		}
		
		$this->success = intval($_GET['success']);
		
		$this->mailing = $this->subscriber->getPlugin('member_news_letter');
		
		/*
		echo '<pre>';
		var_dump($this->subscriber);
		// var_dump($this->mailing);
		echo '</pre>';
		*/
		
		if ($_POST['send']) {
		
			//error_log('setting details...');
			$this->subscriber->setDetails($_POST, false);
			
			//error_log('-> set and we have : '.$GLOBALS['objCms']->_plugins['member']['mailing_list']->subscribeEnable);
			//error_log('-> still '.$this->mailing->subscribeEnable);
			
			if ($this->subscriber->checkEmpty('firstName,lastName,email')) {
				
				// if user is logged in, change his preferences
				if ($this->userIsLoggedIn) {
				
					$this->mailing->update();
					$this->success = ($this->mailing->subscribeEnable)?2:3;
				
				} else {
					// user is not logged in., search for email in DB
					
					$objSearch = new Member();
					if ($objSearch->loadByFilter($objSearch->gField('email')
						.'=\''.$this->subscriber->email.'\''))
					{
					
						// subscriber is in DB
						if ($this->subscriber->firstName == $objSearch->firstName 
							&& $this->subscriber->lastName == $objSearch->lastName)
						{
							//$this->subscriber =& $objSearch;
							//$this->mailing = $this->subscriber->getPlugin('member_news_letter');
							
							$this->mailing->setUid($objSearch->id);
							
							/*
							echo '<pre>';
							var_dump($objSearch);
							echo '</pre><hr /><pre>';
							var_dump($this->mailing);
							exit;
							*/
							
							if ($this->mailing->update('subscribeEnable')) {
								// information is correct
								$this->success = ($this->mailing->subscribeEnable)?2:3;
							}
						} else {
							// different info
							$this->errorMesage = 'Un compte existe avec cette adresse email, mais les informations ne correspondent pas.'; 
						}
					} else if ($this->mailing->subscribeEnable) {
						// new subscriber?
						if ($this->subscriber->add()) {
							$this->success = 1;
						} else {
							$this->errorMessage = 'Votre inscription n\'a pas pu être prise en compte pour une raison inconnue';
						}
					} else {
						// trying to unsubscribe (but not found)
						$this->errorMessage = 'Nous n\'avons pu trouver de compte correspondant à cette adresse email!';
						$this->subscriber->e('email','Vérifiez votre adresse');
					}
					
				
				}
				
				// prepare email
				if ($this->success) {
				
					include_once(CMS_INCLUDE_PATH.'language/'.CMS_LANGUAGE.'/system.php');
					include_once(CMS_CLASS_PATH."pkg_com.php");
			
					$objMessage = new EmailMessage();
				
					$bodyMessage = "\tPrénom:\t".$this->subscriber->firstName
						."\n\tNom:\t".$this->subscriber->lastName
						."\n\tE-mail:\t".$this->subscriber->email."\n";
					if ($this->mailing->subscribeEnable) {
						$bodyMessage .= "\tFormat:\t"
							.(($this->mailing->subscribeHtml)?'HTML':'Texte');
					}
					
					switch ($this->success) {
						case 1:
						case 2:
							// subscribe
							$objMessage->loadByKey('description','mailing_list_subscribed');
							break;
						case 3:
							// unsubscribe
							$objMessage->loadByKey('description','mailing_list_unsubscribed');
							break;
						default:
							echo 'Something went wrong here : wron success code';
							exit;
					}
					
					// send email
					$objMessage->send($bodyMessage, $this->subscriber->email);
				
					// redirect
					TznUtils::redirect(TznUtils::concatUrl($GLOBALS['objPage']->getUrl(),'success='.$this->success));
				}
			
			} else {
			
				// compulsory field is missing
				$this->mailing->subscribeEnable = ($this->mailing->subscribeEnable)?0:1;
				
			}
			
		}
		
		$GLOBALS['objHeaders']->add('css','form.css');
		$GLOBALS['objHeaders']->add('cssModule','mailing_list');
		
		$this->setView('public_view');
		
	}
	
	/* ----- Ajax Features ------------------------- */
	
	function ajaxLetters() {
		
		// back to first page
		$_SESSION['mailingLetterPage'] = 1;
		
		// init object listing newsletters
		$objItemList = new ContentNewsletter();
		
		// filter by keyword
		$keyword = Tzn::getHttp($_REQUEST['lettersKeyword']);
        if ($keyword) {
            $objItemList->addKeywordFilter($keyword);
            $_SESSION['mailingLetterKeyword'] = $keyword;
        } else {
            $_SESSION['mailingLetterKeyword'] = '';
        }
        
        // load list
        $objItemList->addOrder('j1_creationDate DESC');
        $objItemList->setPageAuto(0,true);
        $objItemList->loadList();
        
		// render data grid in HTML
		if ($objItemList->rMore()) {
		
			$objItemList->renderList();
		
			//$objSubscriberList->getPaginationNav()
			
		} else {
			echo '<p class="empty ctr">Aucune lettre ne correspond aux crit&egrave;res de recherche</p>';
		}
		
		// update pagination links
		// $str = $objItemList->getPaginationNav();
				
	}
	
	function ajaxLettersPaging($diff=0) {
		
		// init object listing newsletters
		$objItemList = new ContentNewsletter();
		
		// search by keyword?
		if ($pKeyword = $_SESSION['mailingLetterKeyword']) {
            $objItemList->addKeywordFilter($pKeyword);
		}
		
		// load list
        $objItemList->addOrder('creationDate DESC');
        $objItemList->setPageAuto($diff);
        $objItemList->loadList();
        
		// render data grid in HTML
		$objItemList->renderList();
		
		// set ajax response
		$objResponse->addAssign('mailing_list_letters','innerHTML',$str);
		
		// update pagination links
		//$str = $objItemList->getPaginationNav();
	}
	
	function ajaxSubscribers() {
		
		// init object
        $objItemList = new MailingListSubscriber();
        
        // filter by keyword
        $keyword = Tzn::getHttp($_REQUEST['subscribersKeyword']);
        if ($keyword) {
            $objItemList->addKeywordFilter($keyword);
            $_SESSION['mailingSubscriberKeyword'] = $keyword;
        } else {
            $_SESSION['mailingSubscriberKeyword'] = '';
        }
        
        // load list
        $objItemList->addWhere('subscribeEnable=1');
        $objItemList->addOrder('subscribeDate DESC');
        $objItemList->setPageAuto(0,true);
        $objItemList->loadList();
        
		// render data grid in HTML
		if ($objItemList->rMore()) {
		
			$objItemList->renderList();
		
			//$objSubscriberList->getPaginationNav()
			
		} else {
			echo '<p class="empty ctr">Aucun inscrit &agrave; la newsletter</p>';
		}				
		
	}
	
	function ajaxSubscribersPaging($diff=0) {
		$objResponse = new xajaxResponse();
		
		// init object listing subscribers
		$this->subscriberList = new MailingListSubscriber();
        
        // search by keyword?
		if ($pKeyword = $_SESSION['mailingSubscriberKeyword']) {
            $this->subscriberList->addKeywordFilter($pKeyword);
		}
		
        $this->subscriberList->addWhere('subscribeEnable=1');
        $this->subscriberList->addOrder('subscribeDate DESC');
        $this->subscriberList->setPageAuto($diff);
        $this->subscriberList->loadList();
		
		// render data grid in HTML
		ob_start();
		$this->subscriberList->renderList();
		$str = ob_get_contents();
		ob_clean();
		
		// set ajax response
		$objResponse->addAssign('mailing_list_subscribers','innerHTML',$str);
		
		// update pagination links
		$str = $this->subscriberList->getPaginationNav();
				
		// set ajax response
		$objResponse->addAssign('mailing_list_sub_page','innerHTML',$str);
		
        return $objResponse->getXML();
	}
	
	function ajaxSendTestInit() {
		if ($GLOBALS['objUser']->hasAccess(2,'mailing_list')) {
		?>
		  <div class="ctr">
			<h1>Envoi exemplaire test</h1>
			<p>Voulez-vous envoyer un exemplaire vers</p>
			<p><?php echo $GLOBALS['objUser']->email ?></p>
			<p>
				<a href="javascript:ajaxify_request('<?php 
					echo CMS_WWW_URI.'ajax.php?module=mailing_list&amp;action=sendTestStart&amp;id='.$_REQUEST['id'];
				?>','sbox-content')" class="button">Oui, Envoyer!</a> &nbsp;
				<a href="javascript:SqueezeBox.close()">Non, Annuler</a>
			</p>
		  </div>
		<?php
		} else {	
			echo '<p>droits insuffisants. Operation annulee</p>';
		}
	}
	
	function ajaxSendTestStart() {
		$id = intval($_REQUEST['id']);
		if (!$id) {
			echo 'Wrong newsletter ID'; exit;
		}
		if ($GLOBALS['objUser']->hasAccess(2,'mailing_list')) {
			// ok, move
			$objMember = new MemberNewsletter();
			$objMember->setUid($GLOBALS['objUser']->id);
			$objMember->load();
			// error_log('prepare newsletter');
			$objMember->initNewsletterTest($id);
			// start sending
			// error_log('send : call');
			return $this->ajaxSendEmail($id);
		} else {
			echo '<p>droits insuffisants. Operation annulee</p>';
		}
	}
	
	function ajaxSendMassInit() {
		$id = intval($_REQUEST['id']);
		if (!$id) {
			echo 'Wrong newsletter ID'; exit;
		}
		if ($GLOBALS['objUser']->hasAccess(3,'mailing_list')) {
			$this->subscriberList = new MailingListSubscriber();
			// $this->subscriberList->addKeywordFilter($_SESSION['mailingSubscriberKeyword']);
			$this->subscriberList->addWhere('subscribeEnable=1');
			$tot = $this->subscriberList->loadCount();
			$tot = $this->subscriberList->rTotal();
			?>
			<div class="ctr">
				<h1>Envoi de la lettre n&deg;<?php echo $id; ?></h1>
				<p><?php echo 'Souhaitez-vous envoyer &agrave; '.$tot.' destinataire'.(($tot>1)?'s':''); ?></p>
				<p>
					<a href="javascript:ajaxify_request('<?php 
						echo CMS_WWW_URI.'ajax.php?module=mailing_list&amp;action=sendMassStart&amp;id='.$_REQUEST['id'];
					?>','sbox-content')" class="button">Oui, Envoyer!</a> &nbsp;
					<a href="javascript:SqueezeBox.close()">Non, Annuler</a>
				</p>
			</div>
			<?php
		} else {	
			echo '<p>droits insuffisants. Operation annulee</p>';
		}
		
	}
	
	function ajaxSendMassStart() {
		$id = intval($_REQUEST['id']);
		if (!$id) {
			echo 'Wrong newsletter ID'; exit;
		}
		if ($GLOBALS['objUser']->hasAccess(3,'mailing_list')) {
			// ok, move
			$objMember = new MemberNewsletter();
			$objMember->initNewsletterMass($id);
			// start sending
			if (PRJ_NEWS_SEND_BACKGROUND) {
				// -TODO- send in the background
				// -> write file in cache folder with Newsletter ID
				// -> leave cron do the job during the night
			} else {
				return $this->ajaxSendEmail($id);
			}
		} else {
			echo '<p>droits insuffisants. Operation annulee</p>';
			return false;
		}
	}
	
	function ajaxSendEmail($id=0) {
	
		// error_log('send : init news ID='.$id); sleep(5);
		if (!$id) {
			$id = intval($_REQUEST['id']);
		}
		if (!$id) {
			echo 'Wrong newsletter ID'; exit;
		}
		
		$num = intval($_REQUEST['num']); // number of emails sent so far
		$tot = intval($_REQUEST['tot']); // total number of emails to send
		
		$this->subscriberList = new MailingListSubscriber();
        // $this->subscriberList->addKeywordFilter($_SESSION['mailingSubscriberKeyword']);
        // $this->subscriberList->addWhere('subscribeEnable=1');
        $this->subscriberList->addWhere($this->subscriberList->gField('newsletterId').'='.$id);
        $this->subscriberList->addWhere('newsletterDate=\'0000-00-00 00:00:00\'');
        $this->subscriberList->addOrder('subscribeDate DESC');
        $this->subscriberList->setPagination(($num)?PRJ_NEWS_SEND_PACK_SIZE:1);
        $this->subscriberList->loadList();
        
        if (!$num) {
        	$tot = $this->subscriberList->rTotal();
        	// error_log('start sending '.$this->subscriberList->rCount().' sur '.$tot.' dest.');
        } else {
        	// error_log('continue sending '.$num.' ('.$this->subscriberList->rCount().' sur '.$tot.')');
        }
        
        // error_log('send : loop');
        
        if ($this->subscriberList->rMore()) {
        
        	// error_log('send : load');
        	$objLetter = new ContentNewsletter();
			$objLetter->setUid($id);
			if (!$objLetter->load()) {
				echo 'Newsletter does not exist';
				echo '<script type="text/javascript">window.location.reload()</script>';
				exit;
			}
			
			// error_log('send : prepare');
			$objLetter->initNewsLetter();
        
	        while ($objItem = $this->subscriberList->rNext()) {
	        
	        	// error_log('-> send : send mail');
	        	$objLetter->send($objItem);
	        	// error_log('-> send : update DB');
				$objItem->set('newsletterDate','NOW');
				$objItem->update('newsletterDate'); // ok, sent
				$num++;
				// error_log('-> sent #'.$num.'/'.$tot);
				if ($num < $tot) {
					// be nice with CPU
					if (PRJ_NEWS_SEND_PACK_DELAY_TYPE == 'micro') {
						usleep(PRJ_NEWS_SEND_PACK_DELAY_TIME);
					} else {
						sleep(PRJ_NEWS_SEND_PACK_DELAY_TIME);
					}
				}
				
			}
			
			// error_log('send : report');
		
			$str = '<div class="ctr">'."\n";
			$strJs = '';
			if ($num < $tot) {
				$p = round($num * 100 / $tot);
				$str .= '<h4>Envoi lettre  n&deg;'.$id.' en cours...</h4>';
				$str .= '<p>'.$num.' sur '.$tot.' envoy&eacute;s ('.$p.'%)</p>';
				$str .= '<div class="cms_percent"><div style="width:'.$p.'%"></div></div>';
				//$str .= '<table class="cms_percent"><tr><td width="'.$p.'%"></td><td width="'.(100-$p).'%"></tr></table>';

				$strJs = "ajaxify_request('"
					.CMS_WWW_URI.'ajax.php?module=mailing_list&action=sendEmail&id='.$id
					.'&num='.$num.'&tot='.$tot
					."','sbox-content')";
			} else {
				if ($tot > 1) {
					// ok this is cheap. We're guessing this is a test only if only one is sent
					$objLetter->setDelivered();
				}
				$str .= '<p><strong>Envoi lettre n&deg;'.$id.' termin&eacute;!</strong></p>'."\n";
				$str .= '<p>Envoy&eacute;e &agrave; '.$tot.' destinataire'.(($tot>1)?'s':'').'.</p>'."\n";
				$str .= '<p><a href="javascript:SqueezeBox.close();window.location.reload()">Fermer</a></p>'."\n";
			}
		} else {
			// no user found?
			$str .= '<p>Erreur survenue &agrave '.$num.'/'.$tot.'.</p>'."\n";
			$str .= '<p><a href="javascript:SqueezeBox.close()" class="button">Fermer</a></p>'."\n";
		}
		$str .= '</div>'."\n";
		
		// error_log('send : response = '.$str);
		
		echo $str;
		echo '<script type="text/javascript">'.$strJs.'</script>';

	}
	
	function ajaxSendCancel() {
	
		$id = intval($_REQUEST['id']);
		if (!$id) {
			echo 'Wrong newsletter ID'; exit;
		}
		
		if (isset($_REQUEST['confirm'])) {
			$this->subscriberList = new MemberNewsletter();
			// $this->subscriberList->addKeywordFilter($_SESSION['mailingSubscriberKeyword']);
			
			$sql = 'UPDATE '.$this->subscriberList->gTable()
				.' SET newsletterDate = \'0000-00-00 00:00:00\''
				.', newsletterId = 0'
				.' WHERE subscribeEnable=1'
				.' AND newsletterId = '.$id
				.' AND newsletterDate = \'0000-00-00 00:00:00\'';
			
			$this->subscriberList->getConnection();
			$this->subscriberList->query($sql);
		
			echo '<div class="ctr">'."\n";
			echo '<h1>Envoi annul&eacute;!</h1>'."\n";
			echo '<p><a href="javascript:SqueezeBox.close();window.location.reload()">Fermer</a></p>'."\n";
			echo '</div>';
			
		} else {
			// ask for confirmation
			?>
			<div class="ctr">
				<p>Voulez-vous annuler l'envoi en cours?</p>
				<p>
					<a href="javascript:ajaxify_request('<?php 
						echo CMS_WWW_URI.'ajax.php?module=mailing_list&amp;action=sendCancel&amp;id='.$id.'&amp;confirm=go';
					?>','sbox-content')" class="button">Oui, Annuler</a> &nbsp;
					<a href="javascript:SqueezeBox.close()">Non !</a>
				</p>
			</div>
			<?php
		}

	}
}

/**
	List of Subscribers
*/

if (class_exists('MemberNewsletter')) {

class MailingListSubscriber extends MemberNewsletter {

	function MailingListSubscriber() {
		parent::MemberNewsletter();
		$this->removeProperties('newsletterId');
		$this->addProperties(array(
		  'newsletter' => 'OBJ',
		  'memberNewsletter'   => 'OBJ,member'
        ));
	}
	
	function addKeywordFilter($keyword) {
		$time = Tzn::_dteValue($keyword);
		if (preg_match('/[0-2]{2}[0-9]{2}\-[0-9]{2}\-[0-9]{2}/',$time)) {
		    $date1 = $time.' 00:00:00';
		    $date2 = $time.' 23:59:59';
		    $this->addWhere("((subscribeDate >= '$date1' AND subscribeDate <= '$date2')"
		        ." OR (newsletterDate >= '$date1' AND newsletterDate <= '$date2'))");
		} else {
		    $str = '%'.str_replace(' ','%',$keyword).'%';
		    $this->addWhere("(CONCAT(firstName,' ',lastName) LIKE '$str'"
		        ." OR firstName LIKE '$str' OR lastName LIKE '$str'"
		        ." OR email LIKE '$str')");
		}
	}
	
	function renderList() {
        $r = 0;
	    while ($obj = $this->rNext()) {
	        $url = CMS_WWW_URI.'admin/member.php?id='.$obj->memberNewsletter->id;
			echo '<div class="row">';
			echo '<div class="col c40"><a href="'.$url.'">'.$obj->memberNewsletter->getName().'</a></div>';
			echo '<div class="col c30">'.$obj->memberNewsletter->get('email').'</div>';
			echo '<div class="col c10">'.$obj->getFormat().'</div>';
			echo '<div class="col c20">'.$obj->get('subscribeDate','SHT').'</div>';
			echo '</div>';
		}
	}
	
	function getPaginationNav() {
	   $str = '<div class="paging_nav_lft">';
	   if ($this->hasPrevious()) {
	       $str .= '<a href="javascript:cms_custom_call(\'mailing_list\',\'subscriberPaging\',-1)" class="button">&lt;</a>';
	   } else {
	       $str .= '<span class="button">&lt;</span>';
	   }
	   $str .= '</div>'
	       .'<div class="paging_nav_ctr">'.$this->getPaginationStats().'</div>'
	       .'<div class="paging_nav_rgt">';
	   if ($this->hasNext()) {
	       $str .= '<a href="javascript:cms_custom_call(\'mailing_list\',\'subscriberPaging\',1)" class="button">&gt;</a>';
	   } else {
	       $str .= '<span class="button">&gt;</span>';
	   }
	   $str .= '</div>';
	   return $str;
	}
	
	function getPaginationStats() {
	   $tmp = (($this->_page - 1) * $this->_pageSize)+1;
	   return 'Inscrits '.$tmp.' &agrave; '.(($tmp-1) + $this->rCount()).' sur '.$this->rTotal().' au total';
	}
	
	function setPageAuto($diff=0, $reset=false) {

        $page = intval($_SESSION['mailingSubscriberPage']);
        
        if (!$page || $page < 0 || $reset) {
            $page = 1;
        }
        
        $page += intval($diff);
		
		$_SESSION['mailingSubscriberPage'] = $page;
		
		parent::setPagination(PRJ_MAILING_LIST_PAGE_SIZE,$page);
	}

}

/** 
	Newsletter object
*/

class ContentNewsletter extends CmsContent
{

	var $_module;
	var $_itemList;
	var $_bodyHtml;
	var $_bodyText;
	var $_objMailing;


	function ContentNewsletter() {
		parent::CmsContent('Newsletter');
		$this->handle = 'newsletter';
	}

	function setHttpAuto() {
		
		parent::setHttpAuto();
		
		$i = 1;
		$this->initModules(false);
		$this->_itemList = new TZNStaticResult();
		
		// add new items
		$objItem = new NewsletterContent();
		$objItem->newsletterId = $this->id;
		foreach ($this->_module as $module => $obj) {
			$objItem->moduleName = $module;
			if (is_array($_POST[$module])) {
				foreach($_POST[$module] as $id) {
					$objItem->moduleId = intval($id);
					if (!$objItem->moduleId) {
						continue;
					}
					$objItem->position = $i++;
					// error_log('set item '.$objItem->position.'. '.$objItem->moduleId.' ('.$this->id.')');
					$this->_itemList->addItem($objItem->clone4()); 
				}
			}
		}
	}
	
	function setDelivered() {
		$this->_join->setDtm('deliveryDate','NOW');
		return $this->_join->update('deliveryDate');
	}
	
	function getDeliveryDate() {
	   if ($this->_join->deliveryDate && !preg_match('/^(0000|9999)/',$this->_join->deliveryDate)) {
	       return $this->_join->get('deliveryDate','SHT');
	   } else {
	       return '- non envoy&eacute;e -';
	   }
	}
	
	function addKeywordFilter($keyword) {
	   $time = Tzn::_dteValue($keyword);
        if (preg_match('/[0-2]{2}[0-9]{2}\-[0-9]{2}\-[0-9]{2}/',$time)) {
            $date1 = $time.' 00:00:00';
            $date2 = $time.' 23:59:59';
            $this->addWhere("(creationDate >= '$date1' AND creationDate <= '$date2')");
            $this->addWhere("(deliveryDate >= '$date1' AND deliveryDate <= '$date2')",'OR');
        } else if ($keyword) {
            $str = '%'.str_replace(' ','%',$keyword).'%';
            $this->addWhere("(j1.title LIKE '$str' OR body LIKE '$str')");
        }
	}
	
	function renderList() {
	    while ($obj = $this->rNext()) {
			$url = CMS_WWW_URI.'admin/page.php?id='.$obj->pageId.'&amp;action=read&amp;item='.$obj->id;
        ?>
        <div class="row">
        	<div class="col c50"><a href="<?php echo $url; ?>"><?php echo $obj->_join->get('title'); ?></a></div>
        	<div class="col c15"><?php echo $obj->_join->get('creationDate','SHT'); ?></div>
        	<div class="col c15"><?php echo $obj->getDeliveryDate(); ?></div>
        	<div class="col c20 action">
        		<a href="<?php 
        			echo CMS_WWW_URI.'admin/page.php?id='.$obj->pageId.'&amp;action=delete&amp;item='.$obj->id; 
        		?>" onclick="return confirm('<?php echo $GLOBALS['langAdmin']['del_confirm']; ?>')">supprimer</a>
        		<a href="<?php echo CMS_WWW_URI.'admin/page.php?id='.$obj->pageId.'&amp;action=edit&amp;item='.$obj->id; ?>">modifier</a>
        		<a href="<?php echo CMS_WWW_URI.'admin/page.php?id='.$obj->pageId.'&amp;action=read&amp;item='.$obj->id; ?>" rel="clickme">voir</a>
        	</div>
        </div>
        <?php
		}
	}
	
	function getPaginationNav() {
	   $str = '<div class="paging_nav_lft">';
	   if ($this->hasPrevious()) {
	       $str .= '<a href="javascript:cms_custom_call(\'mailing_list\',\'lettersPaging\',-1)" class="button">&lt;</a>';
	   } else {
	       $str .= '<span class="button">&lt;</span>';
	   }
	   $str .= '</div>'
	       .'<div class="paging_nav_ctr">'.$this->getPaginationStats().'</div>'
	       .'<div class="paging_nav_rgt">';
	   if ($this->hasNext()) {
	       $str .= '<a href="javascript:cms_custom_call(\'mailing_list\',\'lettersPaging\',1)" class="button">&gt;</a>';
	   } else {
	       $str .= '<span class="button">&gt;</span>';
	   }
	   $str .= '</div>';
	   return $str;
	}
	
	function getPaginationStats() {
	   $tmp = (($this->_page - 1) * $this->_pageSize)+1;
	   return 'Lettres '.$tmp.' &agrave; '.(($tmp-1) + $this->rCount()).' sur '.$this->rTotal().' au total';
	}
	
	function setPageAuto($diff=0, $reset=false) {
        $page = intval($_SESSION['mailingLetterPage']);
        
        if (!$page || $page < 0 || $reset) {
            $page = 1;
        }
        
        $page += intval($diff);
		
		$_SESSION['mailingLetterPage'] = $page;
		
		parent::setPagination(PRJ_MAILING_LIST_PAGE_SIZE,$page);
	}
	
	/* --- DATABASE OPERATIONS ------------- */
	
	function add() {
		// error_log('adding newsletter');
        $this->_join->set('creationDate','NOW');
        $this->set('lastChangeDate','NOW');
        if (parent::add()) {
        	// add new items
        	while ($objItem = $this->_itemList->rNext()) {
        		$objItem->newsletterId = $this->id;
        		// error_log('add item '.$objItem->position.'. '.$objItem->moduleId.' ('.$this->id.')');
				$objItem->replace(); // true: ignore duplicates
			}
			// delete removed items
			$this->_deleteRemovedItems();
        }
	}
	
	function update() {
        $this->set('lastChangeDate','NOW');
        parent::update();
		// add new items
		if (!is_object($this->_itemList)) {
			return true;
		}
		while ($objItem = $this->_itemList->rNext()) {
			// error_log('upd item '.$objItem->position.'. '.$objItem->moduleId.' ('.$this->id.')');
			$objItem->newsletterId = $this->id;
			$objItem->replace(); // true: ignore duplicates
		}
		// delete removed items
		$this->_deleteRemovedItems();
		return true;
	}
	
	function updateDelivery() {
		return parent::update('');
	}
	
	function _deleteRemovedItems() {
		$objItem = new NewsletterContent();
		$objItem->newsletterId = $this->id;
		if ($_POST['items2delete']) {
			// error_log('deleting... '.$_POST['items2delete']);
			$arr = explode(',',$_POST['items2delete']);
			foreach($arr as $id) {
				// error_log('deleting NOW: '.$id);
				$objItem->delete($id);
			}
		}
	}
	
	/* ------ NEWSLETTER ITEMS (MODULES) ----------- */
	
	function initModules($load=true) {
	
		// load all installed modules
		$GLOBALS['objCms']->autoLoadModules(true);
		
		foreach ($GLOBALS['objCms']->modules as $module => $obj) {
			if (method_exists($obj, 'newsletterInit')) {
				$obj->newsletterInit();
				$this->_module[$module] = $obj;
			}
		}
		
	}
	
	function loadModules() {
		$this->_itemList = new NewsletterContent();
		if ($this->id) {
			$this->_itemList->addWhere('newsletterId = '.$this->id);
			$this->_itemList->addOrder('position');
			$this->_itemList->loadList();
		}
	}
		
	function viewModuleSelect() {
		if (count($this->_module)) {
			echo '<ul>';
			foreach($this->_module as $label => $obj) {
				echo '<li><label>'.$GLOBALS['langModule'][$label]['name'].':</label>';
				$obj->newsletterSelect($label);
				echo '</li>';
			}
			echo '</ul>';
		}
	}
	
	function remModule() {
		// -TODO-
	}
	
	/* --- RENDERING AND SENDING NEWSLETTER ---------- */
	
	function initNewsLetter() {
	
		$f = CMS_CACHE_PATH.'newsletter_'.$this->id;
		$t = strtotime($this->lastChangeDate);
		
		$this->loadModules();
		
		$this->_bodyHtml = $this->_bodyText = '';
		
		// HTML version
		if (is_file($f.'.html')
			&& (filemtime($f.'.html')-TZN_TZSERVER) >= $t)
		{
			// get content from cache file
			$this->_bodyHtml = file_get_contents($f.'.html');
		} else {
			// generate content on the fly
			
			$this->_bodyHtml = '</div>'
				.'<h3>'.$this->_join->get('title').'</h3>'
				.'<div>'.$this->get('body').'</div>'
				.'<div>'
				.$this->getNewsModulesHtml()
				.'</div>'
				.'<div style="font-size:.8em">';
				
			// save in cache file
			@file_put_contents($f.'.html',$this->_bodyHtml);
		}	
			
		// Text version
		if (is_file($f.'.txt')
			&& (filemtime($f.'.txt')-TZN_TZSERVER) >= $t)
		{
			// cache file is older than
			$this->_bodyText = file_get_contents($f.'.txt');
		} else {
		
			$arrFind = array('</h1>','</h2>','</h3>','<br />','</p>','<li>','</li>');
			$arrReplace = array(
				"\r\n---------------------------------------------------------------------------\r\n\r\n",
				"\r\n----------------------------------------\r\n\r\n",
				"\r\n\r\n", "\r\n", "\r\n\r\n", "\r\n- ",
				"\r\n");
			
			$this->_bodyText .= '* '.$this->_join->title." *\n"
				."===========================================================================\n\n"
				.strip_tags(str_replace($arrFind, $arrReplace, $this->body));
			
			$this->_bodyText .= $this->getNewsModulesText();
			
			// save in cache file
			@file_put_contents($f.'.txt',$this->_bodyText);
		}
		
		// Initialize object
		include_once(CMS_CLASS_PATH.'pkg_com.php');
		$this->_objMailing = new EmailMessage();
		$this->_objMailing->loadByKey('description','mailing_list_send');
	}
	
	function viewModuleList() {
		echo '<ul id="news_items" class="form_order">';
		while ($objItem = $this->_itemList->rNext()) {
			$objItem->loadItem();
			$id = $objItem->moduleName.'_'.$objItem->moduleId;
			echo '<li id="'.$id.'">';
			echo '<a href="javascript:cms_news_del(\''.$id.'\',true)" class="button">X</a>';
			echo '<input type="hidden" name="'.$objItem->moduleName
				.'[]" value="'.$objItem->moduleId.'" />';
			echo $objItem->getTitle().'</li>';
		}
		echo "</ul>\n";
	}
	
	function getNewsletterHtml() {
		$this->_objMailing->setBody($this->_bodyHtml);
		return '<div>'.$this->_objMailing->_body."</div>\n";
	}
	
	function getNewsletterText() {
		$this->_objMailing->setAltBody($this->_bodyText);
		return $this->_objMailing->_altBody;
	}
	
	function getNewsModulesHtml() {
		$str = '';
		$this->_itemList->rReset();
		if ($this->_itemList->rMore()) {
			while ($objItem = $this->_itemList->rNext()) {
				$str .= "<hr />\n";
				$objItem->loadItem();
				$str .= $objItem->getNewsItemHtml();
			}
		}
		return $str;
	}
	
	function getNewsModulesText() {
		$str = '';
		$this->_itemList->rReset();
		if ($this->_itemList->rMore()) {
			while ($objItem = $this->_itemList->rNext()) {
				$str .= "\n";
				$objItem->loadItem();
				$str .= $objItem->getNewsItemText();
			}
		}
		return $str;
	}
	
	function send($objSubscriber) {
		$GLOBALS['objMember'] = $objSubscriber->memberNewsletter;
		$objEmail = $this->_objMailing->clone4();
		$objEmail->html = $objSubscriber->subscribeHtml;
		if ($objEmail->html) {
			$objEmail->setBody($this->_bodyHtml);
			$objEmail->setAltBody($this->_bodyText);
		} else {
			$objEmail->setBody($this->_bodyText);
		}
		// error_log('==> sending email to '.$objSubscriber->memberNewsletter->email);
		return $objEmail->sendPrepared(
			$objSubscriber->memberNewsletter->email,
			$objSubscriber->memberNewsletter->getName()
		);
	}
		
}

/*
class NewsletterPage extends Page
{
    function NewsletterPage() {
        parent::Page();
		$this->addProperties(array(
            'newsletter' => 'OBJ'
        ));
    }

    function loadList() {
        $sql = 'SELECT p1.*, c1.body as content_body, '
            ."SUBSTRING(MAX(CONCAT(n1.creationDate,LPAD(n1.newsletterId,10,'0'))),20) AS newsletterId, "
            ."SUBSTRING(MAX(CONCAT(n1.creationDate,LPAD(n1.newsletterId,10,'0'))),1,19) AS newsletter_creationDate, "
            ."SUBSTRING(MAX(CONCAT(n1.creationDate,LPAD(n1.newsletterId,10,'0'),n1.title)),30) AS newsletter_title, "
            ."SUBSTRING(MAX(CONCAT(n1.creationDate,LPAD(n1.newsletterId,10,'0'),n1.deliveryDate)),30) AS newsletter_deliveryDate "
            .'FROM '.$this->gTable().' AS p1 '
            .'LEFT JOIN '.$this->gTable('newsletter').' AS n1 ON n1.pageId = p1.pageId '
            .'LEFT JOIN '.$this->gTable('content').' AS c1 ON c1.contentId = n1.contentId';
        //$this->addWhere('n1.newsletterId IS NOT NULL');
        $this->addWhere('p1.module=\'mailing_list\'');
        $this->addOrder('creationDate DESC');
        $this->addGroup('n1.pageId');
        return TznDb::loadList($sql);
    }
}
 */
 
class Newsletter extends TznDb
{
	
	function Newsletter()
	{
		parent::TznDb('newsletter');
		$this->addProperties(array(
			'title'			=> 'STR',
			'creationDate'	=> 'DTM',
			'deliveryDate'	=> 'DTM'
		));
	}
	
}
 
class NewsletterContent extends TznDb
{
	var $_item;

	function NewsletterContent() {
		parent::TznDb('newsletterContent');
		$this->addProperties(array(
			//'id'			=> 'UID',
			'newsletterId'	=> 'NUM',
			'moduleName'	=> 'STR',
			'moduleId'		=> 'NUM',
			'position'		=> 'NUM'
		));
	}
	
	function loadItem() {
		if ($obj = $GLOBALS['objCms']->getModuleObject($this->moduleName)) {
			//error_log('item loaded');
			$this->_item = $obj->newsletterItem($this->moduleId);
		}
	}
		
	function getTitle() {
		return $this->_item->newsletterTitle();
	}
	
	function delete($key) {
		if ($this->newsletterId && $key) {
			$idx = strrpos($key,'_');
			$name = substr($key,0,$idx);
			$id = substr($key,$idx+1);
			//error_log('deleting item '.$name.' / '.$id);
			return parent::delete('newsletterId='
				.$this->newsletterId
				.' AND moduleName=\''.$name
				.'\' AND moduleId='.$id);
		} else {
			return false;
		}
	}
	
	function getNewsItemHtml() {
		return $this->_item->newsletterHtml();
	}
	
	function getNewsItemText() {
		return $this->_item->newsletterText();
	}
}

}