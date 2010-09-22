<?php
/****************************************************************************\
* TZN CMS                                                                    *
******************************************************************************
* Version: 3.1                                                               *
* Authors: Stan Ozier <stan@tirzen.com>                                      *
* License:  http://www.gnu.org/licenses/gpl.txt (GPL)                        *
******************************************************************************
* This file is part of "TZN CMS" program.                                    *
*                                                                            *
* TZN CMS is free software; you can redistribute it and/or                   *
* modify it under the terms of the GNU General Public License as published   *
* by the Free Software Foundation; either version 2 of the License, or (at   *
* your option) any later version.                                            *
*                                                                            *
* TZN CMS is distributed in the hope that it will be                         *
* useful, but WITHOUT ANY WARRANTY; without even the implied warranty of     *
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the              *
* GNU General Public License for more details.                               *
*                                                                            *
* You should have received a copy of the GNU General Public License          *
* along with this program; if not, write to the Free Software                *
* Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA *
\****************************************************************************/

class EmailMessage extends TznDB {

	var $_bodyAlt;

    function EmailMessage() {
		parent::TznDB('emailMessage');
		$this->addProperties(
			array(
				'id'				=> 'UID',
				'direction'			=> 'BOL', // 0 -> FROM recipient (OUT), 1-> TO recipient (IN)
				'description'		=> 'STR',
				'recipientName'		=> 'STR',
				'recipientAddress'	=> 'EML',
				'recipientCc'		=> 'EML',
				'body'  			=> 'HTM',
				'html'				=> 'BOL',
				'active'			=> 'BOL'
			)
		);
		$this->_parts = array();
	}

    function getDirection() {
    	$alt = '';
    	$img = CMS_WWW_URI.'assets/images/i_email_';
        if ($this->direction) {
        	$img .= 'in';
        	$alt = $GLOBALS['langSystemEmailStuff']['dir_in'];
        } else {
            $img .= 'out';
        	$alt = $GLOBALS['langSystemEmailStuff']['dir_out'];
        }
        return '<img src="'.$img.'.png" alt="'.$alt.'" />';
    }
    
    function getIcon() {
		$img = CMS_WWW_URI.'assets/images/i_email_';
		if ($this->active) {
			$img .= 'on';
		} else {
			$img .= 'off';
		}
		$img .= '.png';
		return $img;
	}

    function getRecipient() {
        if ($this->direction) {
            return $GLOBALS['langEmail']['recipient_to'].': '.$this->getHtml("recipientAddress");
        } else {
            return $GLOBALS['langEmail']['recipient_from'].': '.$this->getHtml("recipientAddress");
        }
    }
    
    function getSubject() {
    	$str = $GLOBALS['langSystemEmailSubject'][$this->description];
    	if ($this->subject) {
    		$str = $this->subject;
    	}
    	if ($pre = $GLOBALS['objCms']->settings->get('email_prefix')) {
    		$str = $pre.' '.$str;
    	}
    	return $str;
    }
	
	function setBody($body='',$altBody='') {
		if (!$this->html) {
			$this->body = strip_tags($this->body);
		}
    	$this->_body = $this->_getInterpretedBody($this->body, $body);
    	$this->setAltBody($altBody);
	}
	
	function setAltBody($body) {
		$this->_altBody = wordwrap($this->_getInterpretedBody(strip_tags($this->body), $body));
	}

    function check() {
        if (!$this->recipientAddress) {
            $this->_error["recipientAddress"] = $GLOBALS['langEmail']['check_recipient'];
        }
        if (!$this->subject) {
            $this->_error["subject"] = $GLOBALS['langSystemEmailSubject']['check_subject'];
        }
        return (count($this->_error) == 0);
    }

   /* send mail */

    function sendForm($objData, $address, $name=null) {
        // sending data from form: every field $_REQUEST["EMXXXXXXX"]
        $str = $this->body;
        if ($this->html) {
            $str = "<table cellpadding=3 cellspacing=0 border=1>";
        }
        foreach($objData as $key => $value) {
            $pos = strpos($key, "EM");
            if (($pos === false) || ($pos > 0)) {
                continue;
            }
            $key = substr($key,2);
            if (get_magic_quotes_gpc()) {
                $value = stripslashes($value);
            }
            if ($this->html) {
                $str .="<tr><td>".$key."</td><td>".nl2br(htmlspecialchars($value))."</td></tr>";
            } else {
                $str .= ucFirst($key).":\n".$value."\n\n";
            }
        }
        if ($this->html) {
            $str .= "</table>";
        }
		if (preg_match("/(%0A|%0D|\n+|\r+)(content-type:|to:|cc:|bcc:)/i",$address)
			|| preg_match("/(%0A|%0D|\n+|\r+)(content-type:|to:|cc:|bcc:)/i",$name))
		{
				$this->_error['send'] = $GLOBALS['langEmail']['check_injection'];
				return false;
		}
        return $this->send($str, $address, $name);
	}

	function sendObject($objData, $address, $name=null) {
        $str = $this->body."\n\n";
        if ($this->html) {
            $str = "<table cellpadding=3 cellspacing=0 border=1>";
        }
		foreach($objData as $key => $value) {
			if (preg_match('/^_/',$key)) {
				continue;
			}
			if ($value && preg_match("/(%0A|%0D|\n+|\r+)(content-type:|to:|cc:|bcc:)/i",$value)) {
				$this->_error['body'] = $GLOBALS['langEmail']['check_injection'];
				return false;
			}
            if ($this->html) {
                $str .="<tr valign=\"top\"><td>".$key."</td><td>".nl2br(htmlspecialchars($value))."</td></tr>";
            } else {
                $str .= ucFirst($key).":\n".$value."\n\n";
            }
        }
        if ($this->html) {
            $str .= "</table>";
        }
		if (preg_match("/(%0A|%0D|\n+|\r+)(content-type:|to:|cc:|bcc:)/i",$address)
			|| preg_match("/(%0A|%0D|\n+|\r+)(content-type:|to:|cc:|bcc:)/i",$name))
		{
				$this->_error['send'] = $GLOBALS['langEmail']['check_injection'];
				return false;
		}

        return $this->send($str, $address, $name);
	}

    function send($message, $address, $name=null) {
    	
        $this->setBody($message);
        return $this->sendPrepared($address,$name);

    }
    
    function sendPrepared($address,$name=null) {
    	if (!$this->active) {
            $this->_error["send"] = "Email is not active";
            return false;
        }
        if (!$address) {
            $this->_error["address"] = "No recipient set";
            return false;
        }
        
        $objTransporter = new EmailTransporter();
        
        if ($this->direction) {
        	$objTransporter->setFrom($address,$name);
        	$objTransporter->setTo($this->recipientAddress, $this->recipientName);
        } else {
        	$objTransporter->setTo($address,$name);
        	$objTransporter->setFrom($this->recipientAddress, $this->recipientName);
        }
        
        if ($this->recipientCc) {
	        $objTransporter->setCc($this->recipientCc);
        }
        
        $objTransporter->setSubject($this->getSubject());

        $objTransporter->setMessage($this->_body);
        $objTransporter->setAltMessage($this->_altBody);

		//return $objTransporter->send($this->html);
		if ($objTransporter->send($this->html)) {
			return true;
		} else {
			return false;
		}
    }
    
    function rNext() {
    	$obj = parent::rNext();
    	if ($obj) {
    		if (!$obj->recipientAddress) {
	    		$obj->recipientAddress = $GLOBALS['objCms']->settings->get('default_email');
	    	}
    	}
    	return $obj;
    }
    
    function loadByFilter($filter) {
    	$res = parent::loadByFilter($filter);
    	if (!$this->recipientAddress) {
    		$this->recipientAddress = $GLOBALS['objCms']->settings->get('default_email');
    	}
    	return $res;
    }
    
    function _getInterpretedBody($template, $data) {
    	$dataOk = false;
    	$body = '';
        if (!$template) {
        	$dataOk = true; // submitted data placed
            $body = $data;
        } else if (preg_match_all('/\{[^}]*\}/',$template,$arrFound)) {
        	$body = $template;
			$arrFound = $arrFound[0];
			if (count($arrFound)) {
				$arrFound = array_unique($arrFound);
				foreach ($arrFound as $pattern) {
					// loop through each {} tag
					$value = '';
					$prop = substr($pattern,1,-1);
                    if ($prop == 'data') {
                    	// replacing tag {data}
                        $dataOk = true; // submitted data placed
                        $body = str_replace($pattern,$data,$body);
                        continue;
                    }
                    $arrProp = explode('->',$prop);
                    if (count($arrProp) > 1) {
                    	// tag represents object property
                        $varObj = $arrProp[0];
                        $prop = $arrProp[1];
                        if (is_object($GLOBALS[$varObj])) {
                        	// object exists
                        	// error_log('$'.$varObj.' = '.$GLOBALS[$varObj]->$prop());
                            $objItem =& $GLOBALS[$varObj];
                            if (in_array($prop,$objItem->_properties)) {
                                $value = $objItem->$prop;
                            } else if (method_exists($objItem,$prop)) {
                                $value = $objItem->$prop();
                            }
                        } else {
                        	// object not found, just delete tag
                        	// error_log('$'.$varObj.' object not found');
                        	$body = str_replace($pattern,'',$body);
                        	continue;
                        }
                    } else {
                    	// tag represents simple variable
                        $value = $GLOBALS[$prop];
                    }					
					/*
					echo $prop.' -&gt; ';
					echo $value.'<br />';
					*/
					if ($value) {
						$body = str_replace($pattern,$value,$body);
					}
				}
			}
		}
        if (!$dataOk) {
            // supplied data not placed
            $body .= "\r\n".$data;
        }
		//echo '<pre>'.$body.'</pre>';
		return $body;
    }
}

class EmailTransporter 
{
	
	var $_fromEmail;
	var $_fromName;
	var $_arrTo;
	var $_arrCc;
	var $_arrBcc;
	var $_subject;
	var $_message;
	var $_messageAlt;
	var $_parts;
	var $_error;
	
	function EmailTransporter() {
		$this->_arrTo = array();
		$this->_arrCc = array();
		$this->_arrBcc = array();
		$this->_error = array();
	}
	
	function _secureEmail($email) {
		if ($email && preg_match("/^[a-z0-9]([a-z0-9_\-\.]*)@([a-z0-9_\-\.]*)\.([a-z]{2,4})$/i",$email)) {
			return $email;
		} else {
			return false;
		}
	}
	
	function _secureText($str) {
		if ($str && preg_match("/(%0A|%0D|\n+|\r+)(content-type:|to:|cc:|bcc:)/i",$str)) {
			$this->_error['security'] = 'Email header injection attempt detected';
			return false;
		} else {
			return $str;
		}
	}
	
	function setFrom($email, $name='') {
		$this->_fromEmail = $this->_secureEmail($email);
		$this->_fromName = $this->_secureText($name);
		return (!empty($this->_fromEmail));
	}
	
	function _addRcpt($type, $email, $name) {
		if (strpos($email,',')) {
			// list of email addresses seperated by commas
			$arrEmails = explode(',',$email);
			foreach($arrEmails as $email) {
				$email = $this->_secureEmail(trim($email));
				if ($email) {
					$this->$type[$email] = '';
					$atLeastOne = true;
				}
			}
			return $atLeastOne;
		} else {
			$email = $this->_secureEmail($email);
			if ($email) {
				$this->$type[$email] = $this->_secureText($name); // -PHP BUG-
				if (!array_key_exists($email,$this->$type)) {
					//error_log('PHP array simple bug');
					$arr =& $this->$type;
					$arr[$email] = $this->_secureText($name);
				}
				if (!array_key_exists($email,$this->$type)) {
					error_log('PHP array double bug');
					$arr = $this->$type;
					$arr[$email] = $this->_secureText($name);
					$this->$type = $arr;
				}
				return true;
			}
		}
		return false;
	}
	
	function setTo($email, $name='') {
		$this->_arrTo = array();
		return $this->addTo($email, $name);
	}
	
	function addTo($email, $name='') {
		return $this->_addRcpt('_arrTo',$email,$name);
	}
	
	function setCc($email, $name='') {
		$this->_arrCc = array();
		return $this->addCc($email, $name);
	}
	
	function addCc($email, $name='') {
		return $this->_addRcpt('_arrCc',$email,$name);
	}
	
	function setBcc($email, $name='') {
		$this->_arrBcc = array();
		return $this->addBcc($email, $name);
	}
	
	function addBcc($email, $name='') {
		return $this->_addRcpt('_arrBcc',$email,$name);
	}
	
	function setSubject($str) {
		$this->_subject = $this->_secureText($str);
	}
	
	function setMessage($message, $messageAlt='') {
		$this->_message = $this->_secureText($message);
		if ($messageAlt) {
			$this->_messageAlt = $this->_secureText($messageAlt);
		}	
	}
	
	function setAltMessage($message) {
		if ($message) {
			$this->_messageAlt = $this->_secureText($message);
		}
	}
	
	function addAttachment($name =  "", $ctype = "application/octet-stream") {
        $this->_parts[] = array (
            "ctype" => $ctype,
            "encode" => $encode,
            "name" => $name
        );
    }

    function buildMessage($part) {
        $resume = COM_PATH_ATTACHMENT.$part["name"];
        $fp = fopen($resume, "r");
        $read = fread($fp, filesize($resume));
        $read = chunk_split(base64_encode($read));
        $encoding =  "base64";
        return  "Content-Type: ".$part[ "ctype"]
        	.($part[ "name"]? "; name = \"".$part[ "name"]."\"" :  "")
        	."\nContent-Transfer-Encoding: $encoding\n\n$read\n";
	}

    function buildMultipart($boundary) {
        
        // $multipart = "Content-Type: multipart/mixed; boundary = $boundary\n\nThis is a MIME encoded message.\n\n--$boundary";
        $multipart = "";

        for($i = sizeof($this->__parts)-1; $i >= 0; $i--) {
            $multipart .=  "\n".$this->buildMessage($this->_parts[$i])."--$boundary";
        }
        return $multipart.=  "--\n";
    }
	
	function _getHeaderStr($email,$name='') {
		$str = $email;
    	if ($name) {
    		$str = $name.' <'.$str.'>';
    	}
    	return $str;
	}
	
	function _getHeaderMulti(&$arrMail) {
		$arrRes = array();
		foreach ($arrMail as $email => $name) {
			$arrRes[] = $this->_getHeaderStr($email, $name);
        }
        return implode(', ',$arrRes);
	}
	
	function send($html=false) {
	
		if (@constant('TZN_EMAIL_DEBUG')) {
			error_log('Email requested: '.$this->_subject);
			error_log('-> From: '.$this->_fromEmail);
			error_log('-> To  : '.implode(', ',array_keys($this->_arrTo)));
			error_log('-> Cc : '.implode(', ',array_keys($this->_arrCc)));
			error_log('-> HTML: '.(($html)?'Oui':'Non'));
			error_log(substr($this->_message,0,300));
			if ($html) {
				error_log('--- ALT ---');
				error_log(substr($this->_messageAlt,0,300));
			}
			
			if (TZN_EMAIL_DEBUG == 2) {
				error_log('------ DEBUG MODE 2: email not sent ---------');
			} else {
				error_log('------ DEBUG MODE 1: trying to send email ---------');
			}
		}
		
		// === NEW PHP MAILER METHOD ====
		
		require_once(CMS_CLASS_PATH."class.phpmailer.php");

		$mail = new PHPMailer();
		$mail->CharSet = CMS_CHARSET;
		
		$lang = $GLOBALS['objCms']->settings->get('default_language');
		$mail->SetLanguage($lang, CMS_INCLUDE_PATH.'language/'.$lang.'/');

		if ($strParams = $GLOBALS['objCms']->settings->get('email_smtp')) {
			$mail->IsSMTP();
			$arrParams = explode('|',$strParams);
			$mail->Host = $arrParams[0];  // specify main and backup server
			if (trim($arrParams[1])) {
				$mail->SMTPAuth = true;     // turn on SMTP authentication
				$mail->Username = trim($arrParams[1]);  // SMTP username
				$mail->Password = trim($arrParams[2]);
				if (@constant('TZN_EMAIL_DEBUG')) {
					error_log('EMAIL sending through SMTP: '.$mail->Host
					.' ('.$mail->Username.'/'.$mail->Password.')');
				}
			}
		}
		// from
		$mail->From = $this->_fromEmail;
		$mail->FromName = $this->_fromName;
		
		// to
		if (count($this->_arrTo)) {
			foreach ($this->_arrTo as $email => $name) {
				$mail->AddAddress($email, $name);
				// error_log('sending email to '.$email);
			}
		}
		
		// cc
		if (count($this->_arrCc)) {
			foreach ($this->_arrCc as $email => $name) {
				$mail->AddCC($email, $name);
			}
		}
		
		// bcc
		if (count($this->_arrBcc)) {
			foreach ($this->_arrBcc as $email => $name) {
				$mail->AddBCC($email, $name);
			}
		}
		
		$mail->IsHTML($html);
		
		$mail->Subject = $this->_subject;
		$mail->Body    = $this->_message;
		if ($this->_messageAlt && $html) {
			$mail->AltBody = $this->_messageAlt;
		}

		if (!$mail->Send()) {
			error_log('ERROR sending email: '.$mail->ErrorInfo);
			$this->_error['send'] = $mail->ErrorInfo;
			return false;
		} else if (@constant('TZN_EMAIL_DEBUG')) {
			error_log('MAIL SENT!');
		}
		return true;
		
		/*
		
		// === OLD METHOD ====
    	
    	if (!$this->_fromEmail) {
    		$this->_error['from'] = 'email address not set or invalid';
    		return false;
    	}
    	
    	$headers = "From: "
    		.$this->_getHeaderStr($this->_fromEmail, $this->_fromName)."\r\n";
    	
    	if (count($this->_arrTo)) {
        	$rcptTo = $this->_getHeaderMulti($this->_arrTo);
        } else {
        	$this->_error['to'] = 'email address not set or invalid';
        	return false;
        }
    		
        if (count($this->_arrCc)) {
        	$headers .= "Cc: ".$this->_getHeaderMulti($this->_arrCc)."\r\n";
        }
        
		if (count($this->_arrBcc)) {
        	$headers .= "Bcc: ".$this->_getHeaderMulti($this->_arrBcc)."\r\n";
        }
        
        $message = $this->_message;
        
        if (count($this->_parts)) {
            $boundary =  "b".md5(uniqid(time()));
            $headers .= "MIME-Version: 1.0\r\n";
            $headers .= "Content-Type: multipart/mixed; boundary = $boundary\r\n";
            $body = "This is a MIME encoded message.\n\n--$boundary\r\n".$body;
            if ($html) {
                $body .= "Content-type: text/html; charset=iso-8859-1\r\n";
            } else {
                $body .= "Content-type: text/plain; charset=iso-8859-1\r\n";
            }
            $message = $body.$message;
            $message .=  "\r\n\r\n--".$boundary.$this->buildMultipart($boundary);
        } else {
            if ($html) {
                $headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
            } else {
                $headers .= "Content-type: text/plain; charset=iso-8859-1\r\n";
            }
        }
        
        if (TZN_EMAIL_FORCE_SENDER) {
			// error_log('EMAIL: sent forced email to '.$rcptTo);
            $emailSentOk = mail($rcptTo, $this->_subject, $message, $headers, "-f".$this->_fromEmail);
        } else {
			// error_log('EMAIL: sent regular email to '.$rcptTo);
            $emailSentOk = mail($rcptTo, $this->_subject, $message, $headers);
        }
        if ($emailSentOk) {
        	error_log('EMAIL sent to '.$rcptTo.' ('.substr($this->_subject,0,30).')');
        	return true;
        } else {
        	error_log('EMAIL TRANSPORTER: Error sending email to '.$rcptTo.' ('.$headers.')');
        	// error_log('-> To: '.$rcptTo);
        	// error_log('-> Subject: '.$this->subject);
        	// error_log('-> Headers: '.$headers); 
			foreach($objTransporter->_error as $key => $value) {
				error_log ('=> '.$key.': '.$value);
			}
			return false;
        }
        */
    }	
}

// --- DIRTY JOB ---

if (count($GLOBALS['langEmailAlert'])) {
	foreach ($GLOBALS['langEmailAlert'] as $key => $arrSetting) {
		$GLOBALS['langSystemEmail'][$key] = $arrSetting['description'];
		$GLOBALS['langSystemEmailSubject'][$key] = $arrSetting['subject'];
	}
}
