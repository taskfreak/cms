<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * Tirzen Framework (TZN)
 *
 * This declares the common class from which any object from the TZN shall
 * inherit. It is compatible with PHP versions 4 and 5.
 *
 * THIS PACKAGE IS PROVIDED "AS IS" AND WITHOUT ANY EXPRESS OR IMPLIED
 * WARRANTIES, INCLUDING, WITHOUT LIMITATION, THE IMPLIED WARRANTIES OF
 * MERCHANTIBILITY AND FITNESS FOR A PARTICULAR PURPOSE.
 *
 * This package is licensed under the LGPL License
 * Copyright (C) 2005 Stan Ozier
 *
 * This library is free software; you can redistribute it and/or modify it under
 * the terms of the GNU Lesser General Public License as published by the Free
 * Software Foundation; either version 2.1 of the License, or (at your option)
 * any later version.
 * This library is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE. See the GNU Lesser General Public License for more
 * details.
 * You should have received a copy of the GNU Lesser General Public License
 * along with this library; if not, write to the Free Software Foundation, Inc.,
 * 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA 
 *
 * @package    Tirzen Framework MySQL database
 * @author     Stan Ozier <stan@tirzen.net>
 * @copyright  2009 - Stan Ozier
 * @license    http://www.gnu.org/licenses/lgpl.txt (LGPL)
 * @link       http://www.tirzen.com/tzn/
 * @version    4.0
 */
 
/**
 * TZN: Tirzen Framework (TZN) (mysql) DB class
 *
 * @package    TZN-mySQL
 * @author     Stan Ozier <stan@tirzen.net>
 * @version    4.0
 */



/* ------------------------------------------------------------------------ *\
define("TZN_USER_ID_LENGTH",8);		// length of room/user ID
define("TZN_USER_LOGIN","username");// Login mode = username OR email
define("TZN_USER_NAME_MIN",3);		// minimum length for username
define("TZN_USER_NAME_MAX",10);		// maximum length for username
define("TZN_USER_PASS_MIN",3);		// minimum length for password
define("TZN_USER_PASS_MAX",10);		// maximum length for password
define("TZN_USER_PASS_MODE",3);
	// 1 -> PHP crypt function
	// 2 -> mySQL crypt
	// 3 -> mySQL encode
	// ALGO_NAME -> PHP mCrypt_ALGO_NAME 
\* ------------------------------------------------------------------------ */

define('TZN_SANITIZE_USERNAME','/^[a-zA-Z0-9\._-]+$/');
define('TZN_SANITIZE_PASSWORD','/^[^\'"]+$/');

/**
 * User Object - Manage user tasks
 * @author   Stan Ozier <stan@tirzen.net>
 * @package tzn_user
 */

/*
-TODO-
login: check expiration date
checkautologin: add level support
isLogged: add direct checking support (if already checked then return var)
move level to generic class
*/

class TznUser extends TznDb {

	var $_isLoggedIn;
    var $_logingOut;   
		// destroying session is not effective,
    	// so have to set this to true if loggin off

    function TznUser($table) {
    	parent::TznDb($table);
    	$this->_properties = array(
    		'id'				=> 'UID',
    		'username'			=> 'STR',
    		'password'			=> 'STR',
    		'salt'				=> 'STR',
    		'autoLogin'			=> 'BOL',
    		'timeZone'			=> 'INT',
    		'creationDate'		=> 'DTM',
    		'expirationDate'	=> 'DTE',
    		'lastLoginDate'		=> 'DTM',
    		'lastLoginAddress'	=> 'STR',
			'lastChangeDate'	=> 'DTM',
    		'visits'			=> 'NUM',
    		'badAccess'			=> 'NUM',
    		'level'				=> 'LVL',
    		'activation'		=> 'STR',
    		'enabled'			=> 'BOL',
   		);
   		$this->_table=$table;
   		$this->_isLoggedIn = $this->_logingOut = false;
    }
    
	/**
	* Get the user time zone
	*/	
	function qLoginTimeZone($name='tznUserTimeZone') {
		$str = <<<EOS
<script type="text/javascript" language="javascript">
  var tzo=(new Date().getTimezoneOffset()*60)*(-1);
  document.write('<input type="hidden" name="$name" value="'+tzo+'" />');
</script>
EOS;
		print $str;
	}
	
	/**
	* default get name function
	*/
	function getName() {
		return $this->username;
	}
	
	/** 
	* Verify if a created username is correct type (length - already ised...)
	*/
	function setLogin($username='') 
    {
        switch (TZN_USER_LOGIN) {
        	case 'email':
        		// login ID is email
        		if ($username) {
	        		$this->setEml('email',$username);
	        	}
        		if (!$this->email) {
        			$this->_error['email'] = $GLOBALS["langTznUser"]["email_invalid"];
        			return false;
        		}
        		if ($this->checkUnique('email',$username)) {
        			$this->_error["email"] = 
		            	$GLOBALS["langTznUser"]["email_exists"];
		            return false;
        		}
        		// check nickname (username) is unique
        		if ($this->username) {
        			if ($this->checkUnique('username',$this->username)) {
						$this->_error["username"] = 
							$GLOBALS["langTznUser"]["user_name_exists"];
						return false;
					}
        		}
        		return true;
        		break;
        	default:
        		if (!$username) {
	        		$username = $this->username;
	        	}
        		// login ID is user name
        		if ((strlen($username) < TZN_USER_NAME_MIN) 
		        	|| (strlen($username) > TZN_USER_NAME_MAX)) {
		            $this->_error["username"] = 
		            	$GLOBALS["langTznUser"]["user_name_limit1"]
		            	.TZN_USER_NAME_MIN.$GLOBALS["langTznUser"]["user_name_limit2"]
		            	.TZN_USER_NAME_MAX.$GLOBALS["langTznUser"]["user_name_limit3"];
		            return false;
		        } else if ($this->checkUnique("username",$username)) {
		            $this->_error["username"] = 
		            	$GLOBALS["langTznUser"]["user_name_exists"];
		            return false;
		        } else if (TznUtils::sanitize(TZN_SANITIZE_USERNAME, $username)) {
		            $this->username = $username;
		            return true;
		        } else {
					$this->_error["username"] =
						$GLOBALS["langTznUser"]["user_name_invalid"];
					return false;
				}
        }
    }
	/**
	* Verify if a created password is correct type / MD5 encryption
	*/
    function setPassword($pass1, $pass2 = false, 
    	$forceEmpty = false, $noEmptyError = false)
    {
        //echo ("setpass: [ $pass1 / $pass2 ]");
        if ($pass1 || $forceEmpty) {
            // a pass has been set
            if (($pass2 !== false) && ($pass1 != $pass2)) {
                // a confirmation has been set but is different 
                $this->_error["pass"] = 
                	$GLOBALS["langTznUser"]["user_pass_mismatch"];
                return false;
            }
            $this->salt = $this->getRdm(8,
            	'abcdefghijklmnopqrstuvwxyz'
            	.'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789');
            if ($pass1) {
            	if (!TznUtils::sanitize(TZN_SANITIZE_PASSWORD,$pass1)) {
            		$this->_error["pass"] =
	                	$GLOBALS["langTznUser"]["user_pass_invalid"];
    	            return false;
            	} else if ((strlen($pass1) >= TZN_USER_PASS_MIN) 
                	&& (strlen($pass1) <= TZN_USER_PASS_MAX)) {
                    switch (TZN_USER_PASS_MODE) {
                    case 1:
                        $this->password = crypt($pass1 , $this->salt);
                        break;
                    case 2:
                        $this->password = "ENCRYPT('".$pass1."','"
                        	.$this->salt."')";
                        break;
                    case 3:
                        $this->password = "ENCODE('".$pass1."','"
                        	.$this->salt."')";
                        break;
					case 4:
					case 5:
						$this->password = "MD5('$pass1')";
						break;
                    default:
                        $iv = mcrypt_create_iv (mcrypt_get_iv_size(MCRYPT_3DES
                        	, MCRYPT_MODE_ECB), MCRYPT_RAND);
                        $crypttext = mcrypt_encrypt(TZN_USER_PASS_MODE, $this->_salt
                        	, $pass1, MCRYPT_MODE_ECB, $iv);
                        $this->password = bin2hex($crypttext);
                    }
                } else {
                    $this->_error["pass"] = 
                    	$GLOBALS["langTznUser"]["user_pass_limit1"]
                    	.TZN_USER_PASS_MIN
                    	.$GLOBALS["langTznUser"]["user_pass_limit2"]
   	                   	.TZN_USER_PASS_MAX
   	                   	.$GLOBALS["langTznUser"]["user_pass_limit3"];
                    return false;
                }
            } else {
                $this->password = "";
            }
            return true;
        } else {
            if (!$forceEmpty && !$noEmptyError) {
                $this->_error["pass"] =
                	$GLOBALS["langTznUser"]["user_pass_empty"];
                return false;
            }
            return true;
        }
    }
	/**
	* Update pass in DB
	*/
    function updatePassword() {
		$this->update("password, salt, lastChangeDate");
    }
	/**
	* coming soon
	*/
    function setLoginPassword($username, $pass1, $pass2 = false, 
    	$forceEmpty = false) 
    {
        //echo ("username = $username, pass = $pass1...");
        $step1 = $this->setPassword($pass1, $pass2, $forceEmpty, false);
        $step2 = $this->setLogin($username);
        return ($step1 && $step2);
    }
	/**
	* coming soon
	*/
    function updateLoginPassword() {
		$this->update("username, password, salt, lastChangeDate");
	}
	/**
	* coming soon
	*/
	function add() {
		$this->setDtm('creationDate','NOW');
		return parent::add();
	}
	/**
	* Update a user level
	*/	
	function updateLevel() {
        return $this->update("level,lastChangeDate");
	}
	/**
	* coming soon
	*/
    function update($fields=null) {
        $this->setDtm('lastChangeDate','NOW');
        if ($fields && (!preg_match('/lastChangeDate/',$fields))) {
        	$fields .= ",lastChangeDate";
        }
        return parent::update($fields);
    }
	
	function zBadAccess() {
        $strSql = "UPDATE ".$this->gTable()." SET"
            ." badAccess=badAccess+1"
            ." WHERE ".$this->getIdKey()." = '".$this->getUid()."'";
        $this->getConnection();
        $this->query($strSql);
    }

    function zCheckPassword($password) {
    	if ($password && !TznUtils::sanitize(TZN_SANITIZE_PASSWORD, $password)) {
    		$this->_error['login'] = 
            	$GLOBALS["langTznUser"]["user_pass_invalid"];
            $this->zBadAccess();
            return false;
    	}
        switch (TZN_USER_PASS_MODE) {
        case 1: 
            if ($this->password == "") {
                $this->password = crypt("", $this->salt);
            }    
            if (crypt($password, $this->salt) != $this->password) {
                    // password invalid
                    $this->_error['login'] = 
                    	$GLOBALS["langTznUser"]["user_pass_invalid"];
                    $this->zBadAccess();
                    return false;
            }
            break;
        case 2:
            $strSql = "SELECT ENCRYPT('$password','".$this->salt
            	."') as passHash";
            if ($result = $this->query($strSql)) {
                if ($row = $result->rNext()) {
                    if ($row->passHash == $this->password) {
                        // password OK
                        break;
                    }
                }
            }
            $this->_error['login'] = 
            	$GLOBALS["langTznUser"]["user_pass_invalid"];
            $this->zBadAccess();
            return false; // error or password mismatch
            break;
        case 3:
            $strSql = "SELECT ENCODE('$password','".$this->salt
            	."') as passHash";
            if ($result = $this->query($strSql)) {
                if ($row = $result->rNext()) {
                    if ($row->passHash == $this->password) {
                        // password OK
                        break;
                    }
                }
            }
            $this->_error['login'] = 
            	$GLOBALS["langTznUser"]["user_pass_invalid"];
            $this->zBadAccess();
            return false; // error or password mismatch
            break;
		case 4:
			if (!$this->password && !$password) {
				break;
			}
			$strSql = "SELECT MD5('$password') as passHash";
            if ($result = $this->query($strSql)) {
                if ($row = $result->rNext()) {
                    if ($row->passHash == $this->password) {
                        // password OK
                        break;
                    }
                }
            }
            $this->_error['login'] = 
            	$GLOBALS["langTznUser"]["user_pass_invalid"];
            $this->zBadAccess();
            return false; // error or password mismatch
            break;
        case 5:
        	if (!$this->password && !$password) {
				break;
			}
			if ($_SESSION['challenge']) {
				$value = md5($this->password.$_SESSION['challenge']);
				unset($_SESSION['challenge']);
				if ($value == $password) {
					break;
				}
			}
			$this->_error['login'] = 
            	$GLOBALS["langTznUser"]["user_pass_invalid"];
            $this->zBadAccess();
            return false;
            break;
        default:
            for ($i = 0; $i < strlen($this->password); $i += 2) { 
                $passBin .= chr(hexdec(substr($s,$i,2))); 
            }
            $iv = mcrypt_create_iv (mcrypt_get_iv_size (MCRYPT_3DES,
            	MCRYPT_MODE_ECB), MCRYPT_RAND);
            if (mcrypt_decrypt (MCRYPT_3DES, $this->salt, $passBin,
            	MCRYPT_MODE_ECB, $iv) == $password)
            {
                break;
            }
            $this->_error['login'] = 
            	$GLOBALS["langTznUser"]["user_pass_invalid"];
            $this->zBadAccess();
            return false;
            break;
        }
        return true;
    }
    /**
    *
    */
    function activateAccount($byUser=false) {
    	// update DB
    	$this->activation = '';
       	$this->enabled = 1;
       	if ($this->isLoaded()) {
	       	$this->update('enabled,activation');
       	}
       	
       	// send Emails
    	include CMS_INCLUDE_PATH.'language/'.$GLOBALS['objCms']->settings->get('default_language').'/system.php';
	    include_once(CMS_CLASS_PATH."pkg_com.php");
    
    	$objMessage = new EmailMessage();
    	
    	//email admin (if account activated by user)
    	if ($byUser) {
	    	if ($objMessage->loadByKey('description','sign_up_new')) {
				$bodyMessage = "\r\n\t"
					.$this->getName()."\r\n\r\n"
					.CMS_WWW_URL.'admin/member.php?id='.$this->id."\r\n";
				if ($objMessage->send($bodyMessage, $this->email)) {
					// well, ok, fine
				} else {
					$pErrorMessage = $GLOBALS['langSystemEmailStuff']['send_error'];
				}
			} else {
				$pErrorMessage = $GLOBALS['langSystemEmailStuff']['send_not_found'];
			}
    	}
	    
	    // email user
	    if ($this->email) {
		    if ($objMessage->loadByKey('description','sign_up_confirmation')) {
		    	$bodyMessage = CMS_WWW_URL.'login.php?username='.$this->username;
		    	if ($_POST['password1']) {
		    		$bodyMessage .= "\r\n\t"
		    			.$GLOBALS['langTznUser']['login_username'].': '
		    			.$this->username."\r\n\t"
		    			.$GLOBALS['langTznUser']['login_password'].': '
		    			.$_POST['password1']."\r\n";
		    	}
				if ($objMessage->send($bodyMessage, $this->email)) {
					// well, ok, fine
				} else {
					$this->_error['activation'] = $GLOBALS['langSystemEmailStuff']['send_error'];
				}
			} else {
				$this->_error['activation'] = $GLOBALS['langSystemEmailStuff']['send_not_found'];
			}
	    } else {
	    	$this->_error['activation'] = $GLOBALS['langSystemEmailStuff']['send_no_address'];
	    }
		
		return (count($this->_error) == 0);
    }
	/**
	* login and update DB
	*/
    function _activateLogin($withLevel = true) {
    	if ($tz = $_REQUEST['tznUserTimeZone']) {
    		if ($this->getInt('timeZone') != $tz) {
				$this->setInt('timeZone',$tz);
				$updTz = ',timeZone';
			}
    	}
        // register session
        $_SESSION["tznUserId"] = $this->id;
        if ($withLevel) {
            $_SESSION["tznUserLevel"] = $this->level;
        } else {
            $_SESSION["tznUserLevel"] = "0";
        }
        $_SESSION["tznUserTimeZone"] = $this->timeZone;
		$_SESSION["tznUserName"] = $this->username;
		$_SESSION["tznUserLastLogin"] = $this->lastLoginDate;
		$_SESSION["tznUserLastAddress"] = $this->lastLoginAddress;

        // update last login
        $this->setDtm('lastLoginDate','NOW');
		$this->lastLoginAddress = $_SERVER['REMOTE_ADDR'];
		$_SESSION['tznUserCurrentAddress'] = $this->lastLoginAddress;
		$this->_isLoggedIn = true;
        $this->badAccess = 0;
		$this->visits++;
        $this->update('lastLoginDate,lastLoginAddress,badAccess,visits'.$updTz);
    }
	/**
	* Verify username and password and level rights
	*/
    function login($username, $password, $level=null, $activation=false) {
        if ($username == '') {
            $this->_error['login'] = $GLOBALS["langTznUser"]["user_name_empty"];
            return false;
        }
        if (TZN_USER_LOGIN == 'username' && (!TznUtils::sanitize(TZN_SANITIZE_USERNAME, $username))) {
        	$this->_error['login'] = $GLOBALS['langTznUser']['user_name_invalid'];
        	return false;
        }
        if ($this->loadByKey(TZN_USER_LOGIN,$username)) {
            if (($level!=null) && (!$this->getLvl($level))) {
                //Insufficient rights
                $this->_error['login'] = 
                	$GLOBALS["langTznUser"]["user_forbidden"];
            }
            if (!$this->enabled) {
            	if (!$activation || $activation != $this->activation) {
	                //Account Disabled
    	            $this->_error['login'] = 	
        	        	$GLOBALS["langTznUser"]["user_disabled"];
            	}
            }
            if (!$this->zCheckPassword($password)) {
                $this->_error['login'] = 	
                	$GLOBALS["langTznUser"]["user_password_invalid"];
            } else if ($activation && $activation == $this->activation) {
            	// activate account
            	$this->activateAccount(true);
            }
			if (count($this->_error)) {
				$this->zBadAccess();
				return false;
			}
        } else {
            $this->_error['login'] = 
            	$GLOBALS["langTznUser"]["user_name_not_found"];
            return false;
        }
        
    	$this->_activateLogin();
        return true;
    }
	/**
	*coming soon
	*/
    function silentLogin($username, $password) {
        if ($username == '') {
            return false;
        }
        if ($this->loadByKey(TZN_USER_LOGIN,$username)) {
            if (!$this->enabled) {
                //Account Disabled
                $this->_error['login'] = 	
                    $GLOBALS["langTznUser"]["user_disabled"];
            }
            if (!$this->zCheckPassword($password)) {
                $this->_error['login'] = 	
                    $GLOBALS["langTznUser"]["user_password_invalid"];
            }
        } else {
            $this->_error['login'] = 
            	$GLOBALS["langTznUser"]["user_name_not_found"];
            return false;
        }
        return (count($this->_error) == 0);
    }
	/**
	* Is there a cookie for AutoLogin ??
	*/
	function checkAutoLogin($forReal=true) {
        $cookieVal = $_COOKIE['autoLogin'];
		if (empty($cookieVal)) {
			return false;
		}
        $arrVal = explode(":",$cookieVal);
		$id = $arrVal[0];
		$salt = $arrVal[1];
        if($this->loadByFilter($this->gTable().'.'.$this->getIdKey()."='".$id
        	."' AND ".$this->gTable().".salt='".$salt."' AND "
        	.$this->gTable().".autoLogin=1 AND ".$this->gTable().".enabled=1")) 
        {
			if (!$forReal) {
				return true;
			}
			setCookie('autoLogin',$this->id.":".$this->salt
				,time()+(3600*24*30));
            $this->_activateLogin();
            return true;
        } else {
            return false;
        }
	}
	/**
	* Activate AutoLogin
	*/
    function setAutoLogin() {
        if (($this->id) && ($this->salt)) {
            setCookie('autoLogin',$this->id.":".$this->salt
            	,time()+(3600*24*30));
            $this->autoLogin = '1';
            $this->update('autoLogin');
            return true;
        }
        return false;
    }
	/**
	* De-activate AutoLogin
	*/
    function resetAutoLogin() {
        if (($this->id) && ($this->salt)) {
            setCookie('autoLogin');
            if ($this->autoLogin) {
	            $this->autoLogin = "0";
    	        $this->update("autoLogin");
    	    }
            return true;
        }
        return false;
    }
	/**
	* Logout logs the user out (yes indeed)
	* Note: This will destroy the session, and not just the session data!
	*/
    function logout() {
		$_SESSION = array();
		// If it's desired to kill the session, also delete the session cookie.
		// Note: This will destroy the session, and not just the session data!
		if (isset($_COOKIE[session_name()])) {
			setcookie(session_name(), '', time()-42000, '/');
		}
		// Finally, destroy the session.
		@session_destroy();
		// while you're at it, delete auto login
		$this->resetAutoLogin();
		// set internal variable
		$this->_isLoggedIn = false;
        $this->_logingOut = true;
        $this->level = 0;
    }
	/**
	* check if user is logged in. Do not load from DB by default
	*/
    function isLoggedIn($load=false) {
    
    
    	// --- login previously checked ---
    	
    	$sUserId = $_SESSION['tznUserId'];
    
    	if ($this->_isLoggedIn && !$this->_logingOut && $this->id) {
    		// user seems already logged in
    		if ($load) {
    			// load is requested
    			if ($this->load() != $sUserId) {
    				return false;
    			}
    		} else if ($this->id != $sUserId) {
    			// ID in session and in object are different
    			return false;
    		}
    	}
    
    	// --- first login check ---
        if ($sUserId == 0 || empty($sUserId) || $this->_logingOut) {
        	// invalid ID in session or currently logging out
            // TznUtils::addMessage('ERROR:#security_expired');
            return false;
            
        } else {
        
        	// login seems OK : initialize properties
        	$this->id = $sUserId;
			$this->level = $_SESSION['tznUserLevel'];
            $this->timeZone = $_SESSION['tznUserTimeZone'];
			$this->username = $_SESSION['tznUserName'];
			$this->_isLoggedIn = true;
	        
	        // check user IP
	        if ($_SESSION['tznUserCurrentAddress'] && $_SERVER['REMOTE_ADDR'] != $_SESSION['tznUserCurrentAddress']) {
	        	TznUtils::addMessage('ERROR:#security_ip '.$_SERVER['REMOTE_ADDR'].' / '.$_SESSION['tznUserCurrentAddress']);
	        	return false;
	        }
	        
	        // check that User ID is same in cookie and in session
	        if ($load) {
	        	if ($this->load() != $_SESSION["tznUserId"]) {
	        		TznUtils::addMessage('ERROR:#security_expired');
	        		return false;
	        	}
	        }
	        
	        // reset last session stats with previous login info
	        $this->lastLoginDate = $_SESSION['tznUserLastLogin'];
			$this->lastLoginAddress = $_SESSION['tznUserLastAddress'];
			
            return true;
        }
    }

    /** 
    * All in one user login and access check function
    */
    function checkLogin($canAutoLogin=true) {
    
    	if ($this->isLoggedIn(true)) {
    		return true;
    	} else {
    		if ($canAutoLogin && $this->checkAutoLogin()) {
    			// auto logged in
    			return true;
    		} else {
    			return false;
    		}
    	}
    	
	}
		
	/**
    * forgotten password? Try to get it back or generate new one
    * type can be 'username' or 'email'
	*/
    function forgotPassword($key, $value) {
        if ($this->salt == "") {
            if (!$this->loadByKey($key,$value)) {
                // user not found
                $this->_error['forgot'] = $key." not found";
                return false;
            }
        }
        switch (TZN_USER_PASS_MODE) {
        case 1:
        	$this->_generateNewSalt();
            $newpass = $this->getRdm(6,"123456789");
            $this->password = crypt($pass1 , $this->salt);
            $this->updatePassword();
            break;
        case 2:
        	$this->_generateNewSalt();
            $newpass = $this->getRdm(6,"123456789");
            $this->password = "ENCRYPT(\"".$pass1."\",\"".$this->salt."\")";
            $this->updatePassword();
            break;
        case 3:
            $strSql = "SELECT DECODE(password, '".$this->salt
            	."') as pass FROM ".$this->_table
            	." WHERE ".$this->getIdKey()."=".$this->id;
            if ($result = $this->query($strSql)) {
                if ($row = $result->nRow()) {
                    $this->password = $row->pass;
                    return $this->password;
                }
            }
            $this->_error['forgot'] = "can not decode?";
            return false;
            break;
		case 4:
		case 5:
			$this->_generateNewSalt();
            $newpass = $this->getRdm(6,"123456789");
			$this->password = "MD5('$newpass')";
            $this->updatePassword();
            break;
        default:
            $iv = mcrypt_create_iv (mcrypt_get_iv_size (MCRYPT_3DES,
            	MCRYPT_MODE_ECB), MCRYPT_RAND);
            $this->password = mcrypt_decrypt (MCRYPT_3DES, $this->salt,
            	$passBin, MCRYPT_MODE_ECB, $iv);
            return $this->password;
            break;
        }
        return $newpass;
    }
    
    function getLoginCountry() {
    	$myIP = escapeshellarg($_SERVER['REMOTE_ADDR']);
		$pCountry = '00000'; // $_SERVER['REMOTE_ADDR'];
		if (@defined('TZN_GEOLOCATION_SCRIPT') && is_file(TZN_GEOLOCATION_SCRIPT)) {
			$arrOutput = array();
			exec(TZN_GEOLOCATION_SCRIPT.' '.$myIP, $arrOutput);
			$strOutput = implode(' ',$arrOutput);
			if (preg_match('/located in/',$strOutput)) {
				$strOutput = trim(substr($strOutput,strpos($strOutput,'in')+2));
				$pCountry = $strOutput;
			}
		}
		return $pCountry;
    }
    
    function _generateNewSalt() {
    	$this->salt = $this->getRdm(8,
        	'abcdefghijklmnopqrstuvwxyz'
        	.'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789');
    }

}

class VisitorCountry extends TznDb {

	function VisitorCountry() {
		parent::TznDb('visitorCountry');
		$this->addProperties(array(
			'country' 	=> 'STR',
			'visitors'	=> 'NUM'
		));
	}
	
	function getCountry() {
		if ($this->country == '00000') {
			return '(unknown)';
		} else {
			return ucWords(strtolower($this->country));
		}
	}
	
	function stuff($country) {
		if ($this->loadByKey('country',$country)) {
			$this->visitors += 1;
			$this->update('visitors',"country='$country'");
		} else {
			$this->country = $country;
			$this->visitors = 1;
			$this->add();
		}
	}

}
