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
 * Copyright (C) 2009 Stan Ozier
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
 * @package    Tirzen Framework
 * @author     Stan Ozier <stan@tirzen.net>
 * @copyright  2009 - Stan Ozier
 * @license    http://www.gnu.org/licenses/lgpl.txt (LGPL)
 * @link       http://www.tirzen.net/tzn/
 * @version    4.0
 */
 
/**
 * TZN: Tirzen Framework (TZN) common/generic class
 *
 * @package    TZN
 * @author     Stan Ozier <stan@tirzen.com>
 * @version    4.0
 */



/* ------------------------------------------------------------------------ *\
define("TZN_DEBUG",0);
	// 0 no debug, 1 on error, 2 show most, 3 show all
define("TZN_SPECIALCHARS",1);
	// 0 = none, 1 = custom > & ", 2 = htmlspecialchars, 3 = htmlentities
define("TZN_HTMLMODE","html");
	// no = no HTML, bbs = BBS [b]style[/b], html = HTML accepted
define("TZN_BOOL_TRUE","Y");
define("TZN_BOOL_FALSE","N");
	// image or HTML for booleans
define("TZN_TZDEFAULT","user");
define("TZN_DATEFIELD","SQL");
\* ------------------------------------------------------------------------ */
define("TZN_DATE_SQL","%Y-%m-%d");
//define("TZN_DATE_FRM","%d %b %y");
define("TZN_DATE_FRM","%d/%m/%Y"); // EUR
// define("TZN_DATE_FRM","%m/%d/%y"); // USA
define("TZN_DATE_SHT","%d %b %y"); // -TODO- %e bug
define("TZN_DATE_SHX","%a %e %b %y");
define("TZN_DATE_LNG","%d %B %Y");
define("TZN_DATE_LNX","%A %d %B %Y");
define("TZN_DATETIME_SQL","%Y-%m-%d %H:%M:%S");
define("TZN_DATETIME_EUR","%d/%m/%y %H:%M");
define("TZN_DATETIME_USA","%m/%d/%y %I:%M%p");
define("TZN_DATETIME_FRM",TZN_DATETIME_EUR);
define("TZN_DATETIME_SHT","%d %b %y, %H:%M");
define("TZN_DATETIME_SHX","%a %d %b %y, %H:%M");
define("TZN_DATETIME_LNG","%d %B %Y, %H:%M");
define("TZN_DATETIME_LNX","%A %d %B %Y, %H:%M");
define("TZN_DATETIME_HRS","%H:%M");

define("TZN_KEY_LENGTH",8);
define("TZN_KEY_STRING","ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890");

if (@constant('TZN_DATE_US_FORMAT')) {
    define("TZN_DATE_FRM","%m/%d/%y");	// US format 
} else {
    define("TZN_DATE_FRM","%d/%m/%y");	// rest of the world dd/mm
}

define('TZN_SANITIZE_SIMPLE','/^[a-z0-9_-]+$/i');

define('TZN_SQL_NOW', gmdate('Y-m-d H:i:s'));
define('TZN_SQL_TODAY',substr(TZN_SQL_NOW,0,10));
define("TZN_NOW",strtotime(TZN_SQL_NOW));
define("TZN_TZSERVER",intval(date('Z')));

if (@constant('TZN_TRANS_ID')) {
	ini_set("session.use_trans_sid",1);
} else {
	ini_set('session.use_trans_sid', 0);
	ini_set('session.use_only_cookies', 1);
}

class TznPluginable {

	var $_plugins;
	
	function TznPluginable() {
		$this->_plugins = array();
		$class = strtolower(get_class($this)); // get class name of implemented object (not the super class name)
		if (is_array($GLOBALS['tznPlugins']) && count($GLOBALS['tznPlugins'][$class])) {
			foreach ($GLOBALS['tznPlugins'][$class] as $key) {
				// error_log("INIT plugin for $class : $key");
				$this->initPlugin($key);
			}
		}
	}
	
	function initPlugin($key, $obj=null) {
		if (is_null($obj)) {
			$class = TznUtils::strToCamel($key, true);
			$this->_plugins[$key] = new $class();
		} else {
			$this->_plugins[$key] = $obj;
		}
	}
	
	function getPlugin($key) {
		if (!is_array($this->_plugins)) {
			return false;
		}
		if (array_key_exists($key, $this->_plugins)) {
			return $this->_plugins[$key];
		} else {
			return false;
		}
	}
	
	function setPlugin($key, $obj) {
		if (!is_array($this->_plugins)) {
			return false;
		}
		if (array_key_exists($key, $this->_plugins)) {
			$this->_plugins[$key] = $obj;
			return true;
		} else {
			return false;
		}
	}
	
	function callPlugin($key, $method) {
		$args = func_get_args();
		$obj = $this->getPlugin($key);
		if (!$obj) {
			return false;
		}
		$obj = $this->_plugins[$key];
		switch (count($args)) {
			case 2:
				return $obj->$method();
			case 3:
				return $obj->$method($args[2]);
			case 4:
				return $obj->$method($args[2], $args[3]);
			case 5:
				return $obj->$method($args[2], $args[3], $args[4]);
			case 6:
				return $obj->$method($args[2], $args[3], $args[4], $args[5]);
			case 7:
				return $obj->$method($args[2], $args[3], $args[4], $args[5], $args[6]);
			case 8:
				return $obj->$method($args[2], $args[3], $args[4], $args[5], $args[6], $args[7]);
		}
	}
	
	function callPlugins($method) {
		if (!count($this->_plugins)) {
			return false;
		}
		$arr = array();
		$args = func_get_args();
		foreach ($this->_plugins as $key => $obj) {
			if (!method_exists($obj, $method)) {
				continue;
			}
			if ($this->id) {
				$obj->id = $this->id;
			}
			switch (count($args)) {
				case 1:
					$r = $obj->$method();
					break;
				case 2:
					$r = $obj->$method($args[1]);
					break;
				case 3:
					$r = $obj->$method($args[1], $args[2]);
					break;
				case 4:
					$r = $obj->$method($args[1], $args[2], $args[3]);
					break;
				case 5:
					$r = $obj->$method($args[1], $args[2], $args[3], $args[4]);
					break;
				case 6:
					$r = $obj->$method($args[1], $args[2], $args[3], $args[4], $args[5]);
					break;
				case 7:
					$r = $obj->$method($args[1], $args[2], $args[3], $args[4], $args[5], $args[6]);
					break;
			}
			$arr[$key] = $r;
		}
		return $arr;
	}

}

class Tzn extends TznPluginable {

	var $_error;
	var $_properties;
	
	function Tzn() {
		parent::TznPluginable();
        $this->_error = array();
    }
    
    /**
	 * addProperties : generic function
	 * add property(ies) to class/object
	 * @param prm1 array or name of property
	 * @param prm2 if first parameter is, second is type (else: not needed)
	 */
    function addProperties($prm1, $prm2=null) {
    	if (is_array($prm1)) {
			if (is_array($this->_properties)) {
				$this->_properties = array_merge($this->_properties,$prm1);
			} else {
				$this->_properties = $prm1;
			}
		} else if ($prm2) {
			if (!is_array($this->_properties)) {
				$this->_properties = array();
			}
			$this->_properties[$prm1] = $prm2;
		}
    }
    
    /**
	 * removeProperties : generic function
	 * remove property(ies) to class/object
	 * @param prm1 array or name of property
	 * @param prm2 if first parameter is, second is type (else: not needed)
	 */
    function removeProperties($prm1) {
    	if (is_array($this->_properties)) {
    		if (is_array($prm1)) {
    			foreach($prm1 as $key) {
    				unset($this->_properties[$key]);
    			}
    		} else {
    			unset($this->_properties[$prm1]);
    		}
    	}
    }

	
	/* --- GET  -------------------------------------------------- */
	
	/**
	 * get : generic function
	 * check property type and call corresponding method
	 * variable parameters
	 */
	function get($key)
	{
		if (!$key) {
			return 'Error: Tzn::get (empty 1st parameter)';
		}
		if ($dfn = $this->_properties[$key]) {
			if (is_array($dfn)) {
				return call_user_func_array(array(&$this,'getLst'),$dfn);
			}
			$arrType = explode(',',$dfn);
			if (preg_match('/^OBJ/',$arrType[0])) {
				// indirect call to object
				$arrArgs = func_get_args();
				array_shift($arrArgs[0]); // remove first parameter (key)
				$obj = $this->$key;
				if (is_object($obj)) {
					return call_user_func_array(array(&$obj,'get'), $arrArgs);
				}
			} else if (func_num_args() > 1) {;
				$arrArgs = func_get_args();
				return call_user_func_array(array(
					&$this,'get'.$arrType[0]),$arrArgs);
			} else {
				$type = $arrType[0];
				$arrType[0] = $key;
				// echo "calling get$type with "; print_r($arrType);
				return call_user_func_array(array(&$this,'get'.$type),$arrType);
			}
		}
	}
	
	function getUid() {
		return $this->id;
	}
	
	function getValue($key) {
        if ($this->$key) {
            return str_replace('"','&quot;',$this->$key);
        } else {
            return '';
        }
    }
	
	function getRdm($len = TZN_KEY_LENGTH, $strChars = TZN_KEY_STRING)
	{
		$strCode = "";
		$intLenChars = strlen($strChars);
		for ( $i = 0; $i < $len; $i++ )	{
			$n = mt_rand(1, $intLenChars);
			$strCode .= substr($strChars, ($n-1), 1);
		}
		return $strCode;
	}
	
	function getInt($keyval,$default=0)
	{
		$value = Tzn::_value($keyval,$default);
		return number_format(round($value),0);
	}

	function getNum($keyval,$default=0)
	{
		$value = Tzn::_value($keyval,$default);
		$value = number_format(abs(round($value)),0);
		if (!$value) {
			$value = $default;
		}
		return $value;
	}
	
	/**
		get decimal value (key/val,[decimal],[default])
		@param keyval field or value
		@param decimal precision
		@param default value
	*/
	function getDec($keyval)
	{
		$arrArgs = func_get_args();
		$i = 1;
		$dec = null;
		if (intval($arrArgs[1]) == $arrArgs[1]) {
			// number of digits after decimal point
			$dec = $arrArgs[1];
			$i++;
		}
		$value = Tzn::_value($keyval,$arrArgs[$i]);
		if ($dec) {
			return number_format($value,$dec);
		} else {
			if (round($value) != $value) {
				$dec = 2;
				if (is_object($this)) {
					$arrType = explode(',',$this->_properties[$key]);
					if (count($arrType) > 1) {
						$dec = $arrType[1];
					}
				}
				return number_format($value,$dec);
			}
			return number_format($value);
		}
	}

	/**
		get decimal value, form field format (key/val,[decimal],[default])
		@param keyval field or value
		@param decimal precision
		@param default value
	*/
	function getDec2($keyval)
	{
		$arrArgs = func_get_args();
		$i = 1;
		$dec = null;
		if (intval($arrArgs[1]) == $arrArgs[1]) {
			// number of digits after decimal point
			$dec = $arrArgs[1];
			$i++;
		}
		$value = Tzn::_value($keyval,$arrArgs[$i]);
		if ($dec) {
			return number_format($value,$dec,'.','');
		} else {
			if (round($value) != $value) {
				$dec = 2;
				if (is_object($this)) {
					$arrType = explode(',',$this->_properties[$key]);
					if (count($arrType) > 1) {
						$dec = $arrType[1];
					}
				}
				return number_format($value,$dec,'.','');
			}
			return number_format($value,null,'.','');
		}
	}
	
	/**
	 * getStr (key/val,[cut],[default])
	 */
	function getStr($keyval)
	{
		return Tzn::_strValue(func_get_args());
	}
	
	function getUrl($keyval,$default='') {
		return Tzn::_value($keyval,$default);
	}
	
	function getEml($keyval,$default='') {
		return Tzn::_value($keyval,$default);
	}
	
	function getTxt($keyval)
	{
		return Tzn::_strValue(func_get_args());
	}
	
	function getBbs($keyval,$default='')
	{
		return Tzn::_value($keyval,$default);
	}
	
	function getHtm($keyval,$default='', $cut=0)
	{
		return Tzn::_value($keyval,$default, $cut);
	}
	
	function getXml($keyval, $default='') {
		if ($this->$keyval) {
			return $this->$keyval;
		} else {
			return $default;
		}
	}

	function getImg($keyval,$default='')
	{
		return Tzn::_value($keyval,$default);
	}
	
	/**
		get image URL
		@param keyval field or value
		@param default default image filename
		@param opt parameter number (1st one by default)
	*/
	function getImgUrl($keyval, $default='', $opt=1)
	{
		if (is_object($this)) {
			$value = $this->_value($keyval);
			if ($value) {
				if (is_object($value)) {
					$value = $value->folder.$value->fileName;
				}
				$arrOpts = TznUtils::strToArray($this->_properties[$keyval]);
				while ($opt > count($arrOpts)) {
					$opt--;
				}
				if (count($arrOpts) >= $opt) {
					$arrOpts = $arrOpts[$opt];
					if ($folder = $arrOpts['f']) {
						$value = TznFile::checkTrailingSlash($folder).$value;
					}
				}
			} else {
				$value = $default;
			}
		} else if ($keyval) {
			$value = $keyval;
		} else {
			$value = $default;
		}
		$value = TZN_FILE_UPLOAD_URL.$value;
		if ($this->_absoluteUrl) {
			$value = substr(CMS_WWW_URL,0,-1).$value;
		}
		return $value;
	}
	
	function getSaveOptions($field) {
		$arrOptions = TznUtils::strToArray($this->_properties[$field]);
		array_shift($arrOptions); // remove 2nd argument (field type)
		return $arrOptions;
	}
	
	function getBol($keyval,$default=null)
	{
		if (is_object($this)) {
			// $value = Tzn::_value($keyval,$default);
			if (is_null($this->$keyval)) {
				$value = $default;
			} else {
				$value = $this->$keyval;
			}
		} else {
			$value = $default;
		}
		return $value?true:false;
	}
	
	function getDte($keyval,$format='SQL',$default='')
	{
		$value = Tzn::_value($keyval,$default);
		if (!$value || preg_match('/^(0000|9999)/',$value)) {
			return $default;
		} else if (($ts = strtotime($value)) > 0) {
			$const = 'TZN_DATE_'.$format;
			if (defined($const)) {
				$format = constant($const);
			} else if (!preg_match('/%/',$format)) {
				$format = TZN_DATE_SQL;
            }
            $value = strftime($format,$ts);
            if (@constant('TZN_FORCE_UTF-8')) {
            	return utf8_encode($value);
            } else {
	            return $value;
            }
		} else {
			// error
			return false;
		}
	}
	
	function getDtm($keyval,$format='SQL',$tz=TZN_TZDEFAULT,$default='')
	{
		$value = Tzn::_value($keyval,$default);
		if ($value == 'now' || $value == 'NOW') {
			$value = TZN_NOW;
		} else if ((preg_match('/^(0000|9999)/',$value)) 
			|| (($dt = @strtotime($value)) === -1)) 
		{
			if (!preg_match('/^[0-9]{4}\-[0-9]{2}\-[0-9]{2}/',$default)) {
				return $default;
			} else {
				$value = $default;
			}
		} else {
			$value = $dt;
		}
		if (!$value) {
			return '/!\\';
		}
		$tz = $this->_getUserTZ($tz);
		$const = 'TZN_DATETIME_'.$format;
		if (defined($const)) {
			$format = constant($const);
		} else if (!preg_match('/%/',$format)) {
			$format = TZN_DATETIME_SQL;
		}
		$value = strftime($format,$value+$tz);
		if (@constant('TZN_FORCE_UTF-8')) {
        	return utf8_encode($value);
        } else {
         return $value;
        }
	}

    function getLvl($keyval, $level) {
        if (is_object($this)) {
            $data =& $this->$keyval;
        } else {
        	$data =& $keyval;
        }
        $level = $level - 1;
        return $data{$level};
	}
	
	function getPrm($keyval, $param) {
		$arrParams =& $keyval;
		if (is_object($this)) {
			if (array_key_exists($keyval, $this->_properties)) {
				$varname = '_prm_'.$keyval;
				if (!is_array($this->$varname)) {
					if ($this->$keyval) {
						$this->$varname = unserialize($this->$keyval);
					} else {
						$this->$varname = array();
					}
				}
				$arrParams =& $this->$varname;
			}
		}
		return $arrParams[$param];
	}
	
	function getPrmString($keyval) {
		$arrParams =& $keyval;
		if (is_object($this)) {
			if (array_key_exists($keyval, $this->_properties)) {
				$varname = '_prm_'.$keyval;
				if (is_array($this->$varname)) {
					$this->$keyval = serialize($this->$varname);
				}
				return $this->$keyval;
			}
		}
		return serialize($keyval);
	}
	
	function getLst($keyval,$default='') {
		//error_log('getting '.$keyval.' > '.$this->$keyval);
		if (is_object($this) && array_key_exists($keyval,$this->_properties)) {
			return $this->$keyval;
		} else {
			return $default;
		}
	}

	function hasValue($keyval) {
		$value = $keyval;
		if (is_object($this) && property_exists($this, $keyval)) {
			$value = $this->$keyval;
		}
		return (!empty($value) && !preg_match('/00\-00/', $value));
	}
	
	/* -- Private methods ---- */
	
	/**
	 * z : static function to retreive data (object or static)
	 */
	
	function _value($keyval,$default='', $cut=0)
	{
		if (is_object($this)) {
			if (preg_match("/^(.*)\((.*)\)$/i",$keyval,$m)) {
				if (method_exists($this, $m[1])) {
					$keyval = $m[1];
					if ($m[2]) {
						// error_log('calling '.$m[1].'('.$m[2].')');
						$value = call_user_func_array(array(&$this,$keyval),
							explode(',',$m[2]));
					} else {
						$value = $this->$keyval();
					}
					return (empty($value))?$default:$value;
				}
			}
			if (is_numeric($keyval)) {
				$value = $keyval;
			} else if (is_array($this->_properties) && array_key_exists($keyval, $this->_properties)) {
				$value = $this->$keyval;
            } else {
               	$value = $default; // -CHANGED- $value = $keyval;
               	$default = $keyval;
			}
			if (TZN_DEBUG == 3) {
				echo '['.$keyval.']=['.$value.']';
            } else {
            	// error_log('['.$keyval.']=['.$value.']');
            }
		} else {
			$value = $keyval;
		}
		if ($cut) {
			$value = strip_tags($value);
			if (strlen($value) > $cut) {
				$value = substr($value, 0, $cut).'...';
			}
		}
		return (empty($value))?$default:$value;
	}
	
	function _strValue($arg)
	{
		$cut = 0; $pos = 1;
		if (is_int($arg[1])) {
			$cut = $arg[1];
			$pos++;
		}
		$default = $arg[$pos++];
		$style = $arg[$pos];
		$value = Tzn::_value($arg[0],$default);
		$value = strip_tags($value);
		if ($cut) {
			$value = str_replace("\r\n"," ",$value);
			if (($cut > 2) && (strlen($value) > $cut)) {
				$value = trim(substr($value,0,($cut-2))).".."; 
			}
		}
		switch (TZN_SPECIALCHARS)
		{
			case 1:
				$spe = array('&','<','>','"');
				$sfe = array('&amp;','&lt;','&gt;','&quot;');
				$value = str_replace($spe,$sfe,$value);
				break;
			case 2:
				$value = htmlspecialchars($value);
				break;
			case 3:
				$value = htmlentities($value);
				break;
		}
		return $value;
	}

	function _getUserTZ($tz=TZN_TZDEFAULT) {
		if (is_numeric($tz)) {
			$tz = intval($tz);
		} else if ($tz == 'server') {
			$tz = TZN_TZSERVER;
		} else if ($tz == 'user') {
			$tz = intval($_SESSION['tznUserTimeZone']);
		} else if (is_object($this) && isset($this->$tz)) {
			$tz = intval($this->$tz);
		} else {
			$tz = 0;
		}
		// error_log('tz = '.$tz);
		return $tz;
	}
	
	function _dteValue($value,$format='SQL',$default='')
	{
		$const = 'TZN_DATE_'.$format;
		if (defined($const)) {
			$format = constant($const);
		} else if (!preg_match('/%/',$format)) {
			$format = TZN_DATE_SQL;
        }
        if (is_null($value)) {
            $value = $default;
        }
		if (!$value || preg_match('/^(0000|9999)/',$value)) {
            // case 1: no date set
            return '9999-00-00';
		} else if ($value == 'now' || $value == 'NOW') {
			// case 2: now
            $value = time() - TZN_TZSERVER + $this->_getUserTZ('user');
        } else if (strpos($value,'/')) {
	        // dd/mm or mm/dd format
	        $arrValue = explode('/',$value);
	        if (@constant('TZN_DATE_US_FORMAT')) {
	            // US date format mm/dd
	            $month = $arrValue[0];
	            $day = $arrValue[1];
	        } else {
	            // common date format dd/mm
	            $day = $arrValue[0];
	            $month = $arrValue[1];
	        }
	        $year = (count($arrValue)>2)?$arrValue[2]:gmdate('Y');
	        // error_log("we have $day/$month/$year here");
	        $value = mktime (0,0,0, $month, $day, $year);
        } else {
            // try to parse english sentence
            $tmp = @strtotime($value);
            // error_log('check '.$value.': '.$tmp);
            if ($tmp == -1 || $tmp === false) {
                // invalid date format (non english language?)
                // try to translate
                /* $value = str_replace(
                    array_values($GLOBALS['langTznDate']),
                    array_keys($GLOBALS['langTznDate']),
                    $value); */
                // error_log('unknown date value: '.$value.' / '.$GLOBALS['langTznDate']['august']);
                if (is_array($GLOBALS['langTznDate'])) {
                    $value = strtr($value, array_flip($GLOBALS['langTznDate']));
                }
                // error_log(' &gt; '.$value);
                $tmp = strtotime($value);
            }
            if ($tmp == -1 || $tmp === false) {
                // still not valid?
                return false;
            }
        	$value = $tmp - TZN_TZSERVER + $this->_getUserTZ('user') ;
        }
        return strftime($format,$value);
    }
	
	function _dtmValue($value,$format='SQL',$tz=TZN_TZDEFAULT,$default='')
	{
		if ($value == 'now' || $value == 'NOW') {
			$value = TZN_NOW;
			$tz = 0;
		} else if ((preg_match('/^(0000|9999)/',$value)) 
			|| (($dt = @strtotime($value)) === -1)) 
		{
			if (!preg_match('/^[0-9]{4}\-[0-9]{2}\-[0-9]{2}/',$default)) {
				return $default;
			} else {
				$value = $default;
			}
		} else {
			$value = $dt;
		}
		if (!$value) {
			return '0000-00-00';
		}
		$tz = $this->_getUserTZ($tz);
		$const = 'TZN_DATETIME_'.$format;
		if (defined($const)) {
			$format = constant($const);
		} else if (!preg_match('/%/',$format)) {
			$format = TZN_DATETIME_SQL;
		}
		return strftime($format,$value-$tz);
	}
	
	function _isArrayIndexed($arr) {
		if (!is_array($arr) && is_object($this)) {
			$arr = $this->$arr;
		}
		$keys = array_keys($arr);
		foreach ($keys as $key => $val) {
			if ($key !== $val) return true;
		}
		return false;
	}


	function dump($mode='print', $nested = '')
	{
       	// setting data coming from an array (GET, POST, SESSION or mySQL)
        foreach($this->_properties as $key => $type) {
        	if (preg_match('/^OBJ/',$type)) {
        		if ($mode == 'print') {
        			echo $nested.$key." [$type] {\r\n";
        		} else {
        			$mode($nested.$key." [$type] {\r\n");
        		}
				$this->$key->dump($mode, $nested.'  ');
				if ($mode == 'print') {
        			echo $nested."} \r\n";
        		} else {
        			$mode($nested."} \r\n");
        		}
        	} else {
        		if ($mode == 'print') {
        			echo $nested.$key." [$type] => "
						.((is_null($this->$key))?'NULL':$this->$key)."\r\n";
        		} else {
					$mode($nested.$key." [$type] => "
						.((is_null($this->$key))?'NULL':$this->$key)."\r\n");
				}
			}
        }
    }
	
	/* --- SET  -------------------------------------------------- */

	function initObjectProperties($nested = false) {
		foreach($this->_properties as $key => $type) {
			if (preg_match('/^OBJ/i',$type)) {
				$class = (strlen($type) > 3)?substr($type,4):$key;
				if ($nested) {
					$this->$key = new $class();
				} else {
					if (!is_object($this->$key) || strtolower(get_class($this->$key)) != strtolower($class)) {
						$this->$key = new $class();
					}
					$this->$key->initObjectProperties(true);
				}
			}
		}
		$this->callPlugins('initObjectProperties', $nested);
	}
	
	function resetProperties($nested=false) {
		foreach($this->_properties as $key => $type) {
			if (preg_match('/^OBJ/i',$type)) {
				$class = (strlen($type) > 3)?substr($type,4):$key;
				$this->$key = new $class();
				if (!$nested) {
					$this->$key->resetProperties(true);
				}
			} else {
				unset($this->$key);
			}
		}
		$this->callPlugins('resetProperties', $nested);
	}
	
	function setAuto(&$data, $nested = '', $method='_setFromHttp')
	{
        if (is_object($data)) {
        	$method = '_setFromObject';
        }
        if (!count($this->_properties)) {
        	return false;
        }
        foreach($this->_properties as $key => $type) {
        	// if ($method == '_setFromHttp') error_log('-> '.$key.' ('.$type.')');
        	if (($type == 'PRM' || $type == 'LVL') && ($method == '_setFromHttp')) {
        		continue;
        	} else if (is_array($type)) {
        		// list of choices
        		$this->$method($data,$key,($nested)?($nested.'_'.$key):$key);
        	} else if ($key == 'id') {
        		if ($nested) {
					$this->$method($data,'id',$nested.'Id');
				} else if (is_object($data) || @constant('TZN_PHP4_SQLITE')) {
					$this->$method($data,'id',$this->getIdKey());
        		} else {
					$this->$method($data,'id','id');
				}
        	} else if (preg_match('/^OBJ/',$type)) {
        		$class = (strlen($type) > 3)?substr($type,4):$key;
        		if ($nested) {
        		// set only id of 2nd level nested object
					$obj = new $class();
					$obj->$method($data,'id',$nested.'_'.$key.'Id');
					// $nid = $nested.'_'.$key.'Id';
					// echo "setting $key ID=".$data->$nid." to $key by $method <br/>";
					$this->$key = $obj;
					unset($obj);
				} else {
					if (!is_object($this->$key) || strtolower(get_class($this->$key)) != strtolower($class)) {
						$class = ucfirst($class);
						// error_log('=> '.$key.' as '.$class);
						$this->$key = new $class();
					}
					$this->$key->setAuto($data, $key, $method);
				}
        	} else if (preg_match('/^(img|doc)/i',$type) && ($method == '_setFromHttp')) {
        		if ($data[$key.'_del']) {
        			// remove single pic requested
        			TznDb::_deleteFile($key, $type);
        			$this->$key = '';
        		} else {
		    		// upload
		    		$arrType = TznUtils::strToArray($type); // get params into array
		    		array_unshift($arrType,$key); // prepend prop/field name
		    		call_user_func_array(array(&$this,'uploadFile'),$arrType);
		    	}
        	} else {
        		$nKey = $nested?$nested.'_'.$key:$key;
        		/*
        		if ($nested) {
	        		error_log('-> setting '.$nested.'->'.$key.' with '.$data->$nKey);
	        	}
	        	*/
				$this->$method($data,$key,$nKey);
				/*
				if ($nested && ($method == '_setFromHttp')) {
					echo "$nested: setting $key as ".$this->$key." ($nKey) <br/>";
				}
				*/
			}
        }
        // joined stuff
        if (is_object($this->_join)) {
			foreach($this->_join->_properties as $key => $type) {
				if (preg_match('/^OBJ/',$type)) {
					$class = (strlen($type) > 3)?substr($type,4):$key;
					$obj = new $class();
					$obj->setAuto($data,$key,$method);
					$obj->$method($data,'id','j1_'.$key.'Id');
					$this->_join->$key = $obj;
					unset($obj);
				} else if (preg_match('/^(img|doc)/i',$type) && ($method == '_setFromHttp')) {
					// skip : delegated to CmsObject setauto method
				} else {
					$nestkey = 'j1_'.$key;
					$this->_join->$method($data, $key, $nestkey);
				}
			}
			if (method_exists($this->_join, 'setPostDb')) {
				$this->_join->setPostDb($data);
			}
		}
		// plugins
		$this->callPlugins('setAuto', $data, $nested, $method);
    }

    function _setDirect(&$data,$key,$dkey) {
        if (isset($data[$dkey])) {
			$this->set($key,$data[$dkey]);
		}
    }
    
    function _setFromHttp(&$data,$key,$dkey) {
    	if (isset($data[$dkey])) {
    		$value = $data[$dkey];
			if (get_magic_quotes_gpc() && $value) {
				// remove fucking magic quotes
				$value=stripslashes($value);
			}
			$this->set($key,$value);
		}
    }
    
    function _setFromObject(&$data,$key,$dkey) {
    	if (is_array($this->_properties[$key]) && strpos('|',$data->$dkey)) {
	    	$this->$key = explode('|',$data->$dkey);
	    } else {
	    	$this->$key = $data->$dkey;
	    }
    }

	function getHttp($value,$sanitize=false) {
    	if ($value && (get_magic_quotes_gpc() || @constant('TZN_MAGIC_FIX'))) {
			// remove fucking magic quotes
			$value=stripslashes($value);
		}
		if ($sanitize) {
			if (is_bool($sanitize)) {
				$value = htmlspecialchars($value);
			} else if ($value) {
				$sanitize = 'get'.ucFirst(strtolower($sanitize));
				$value = Tzn::$sanitize('xxxxx',$value);
			}
		}
		return $value;
    }

	function setHttp($key,$value) {
		if (!is_null($value)) {
			$this->set($key, $this->getHttp($value));
			/*
			$arrType = explode(',',$this->_properties[$key]);
			if ($key == 'id') {
				$this->setUid($value);
			} else if ((!is_null($value)) || ($arrType[0] == 'IMG')) {
				$arrArgs = func_get_args();
				echo "calling... set".$arrType[0]." with <pre>";
				print_r($arrArgs);
				echo "</pre>";
				exit;
			}
			*/
		}
	}
    
    function set($key,$value)
	{
		if (is_array($this->_properties[$key])) {
			$arrArgs = func_get_args();
			return call_user_func_array(array(
				&$this,'setLst'),$arrArgs);
		}
		$arrType = explode(',',$this->_properties[$key]);
		if ($key == 'id') {
			$this->setUid($value);
		} else if ((!is_null($value)) || ($arrType[0] == 'IMG')) {
			$arrArgs = func_get_args();
			if ($arrType[0]) {
				return call_user_func_array(array(
					&$this,'set'.$arrType[0]),$arrArgs);
			} else {
				echo "OOPS: error calling set() (no type defined for $key)";
				exit;
			}
		}
	}
    
    function setUid($value) {
    	if (preg_match('/^[0-9a-zA-Z]*$/',$value) && $value != 'new') {
	    	$this->id = $value;
	    }
    }
	
	function setInt($key, $value)
	{
		$value = preg_replace(array('/[a-zA-Z]/','/ /','/,/'),'',$value);
		$this->$key = intval($value);
	}
	
	function setNum($key, $value)
	{
		$value = preg_replace(array('/[a-zA-Z]/','/ /','/,/'),'',$value);
		$this->$key = abs(intval($value));
	}
	
	function setDec($key, $value)
	{
		$value = preg_replace(array('/[a-zA-Z]/','/ /','/,/'),'',$value);
		//echo '{'.$value.'}'.floatval($value);
		if (preg_match('/[0-9]*(\.[0-9]+)?/',$value)) {
			$this->$key = $value;
		} else {
			$this->$key = 0;
		}
	}
	
	function setStr($key, $value)
	{
		$this->$key = strip_tags($value);
	}
	
	function setTxt($key, $value)
	{
		$this->$key = 
			preg_replace("/<script[^>]*>[^<]+<\/script[^>]*>/is","", $value);
	}
	
	function setEml($key, $value, $keep=true)
	{
		$value = ($value)?trim($value):$value;
		if ($value) {
			if (preg_match("/^[a-z0-9]([a-z0-9_\-\.\+]*)@([a-z0-9_\-\.]*)\.([a-z]{2,4})$/i",$value))
			{
				$this->$key = $value;
			} else {
				if ($keep) {
					$this->$key = $value;
				}
				$this->e($key,"invalid email");
			}
		} else if (!is_null($value)) {
			$this->$key = '';
		}
	}
	
	function setUrl($key, $value)
	{
		$value = ($value)?trim($value):$value;
		if ($value && (!preg_match("/^(http|https|ftp)?:\/\//i",$value))) {
			$value = "http://".$value;
		}
		if ($value == "http://") {
			$value = "";
		}
		$this->$key = $value;
	}
	
	function setBbs($key, $value)
	{
		$value = preg_replace("/<script[^>]*>[^<]+<\/script[^>]*>/is"
			,"", $value); 
		$value = preg_replace("/<\/?(div|span|iframe|frame|input|"
			."textarea|script|style|applet|object|param|embed|form)[^>]*>/is"
			,"", $value);
		$this->$key = $value;
	}
	
	function setHtm($key, $value)
	{
		// scary
		$this->$key = $value;
	}
	
	function setXml($key, $value)
	{
		// scary
		$this->$key = $value;
	}

	function setImg($key,$value)
	{
		// sImg (key, ([width,height], [folder])* )
		// DATABASE
		$this->$key = $value;
	}
	
	function uploadFile($key)
	{
		// error_log("uploading $key : ".$this->_properties[$key]." : ".$_FILES[$key]['name']);
		// HTTP upload
		$objFile = new TznFile();		
		// set old file name
		$objFile->oldFile = $this->$key;
		// upload and set arguments
		$arrArgs = func_get_args();
		if (count($arrArgs) == 1) {
			// if no specific parameter for size and folder, gets default from properties
			$arrArgs = TznUtils::strToArray($this->_properties[$key]);
       		array_unshift($arrArgs,$key);
		}
		$ok = call_user_func_array(array(
			&$objFile,'upload'),$arrArgs);
		// set value
		$tmpKey = $key.'_tmp';
        if ($ok) {
			// set value from upload
			$this->$key = $objFile;
			$_SESSION[$tmpKey] = $objFile;
			return true;
		} else if ($value = $_SESSION[$tmpKey]) {
			// set value from session
			if ($value->tempName && file_exists(TZN_FILE_TEMP_PATH.$value->tempName)) {
				$this->$key = $value;
				return true;
			} else {
				unset($_SESSION[$tmpKey]);
			}
		}
		return false;
	}
	
	function setBol($key, $value)
	{
		$this->$key = $value?1:0;
	}
	
	function setDte($key, $value)
	{
		$this->$key = Tzn::_dteValue($value);
	}
	
	function setDtm($key, $value, $tz=TZN_TZSERVER)
	{
		$this->$key = Tzn::_dtmValue($value,'SQL',$tz);
	}
	
	function setObj($key, $value)
	{
		$class = ucFirst($key);
		$obj = new $class;
		$obj->setAuto($value,$key); // nested
		$this->$key = $obj;
	}

    function setLvl($keyval, $param1, $param2=null) {
		 if (is_object($this)) {
            $data =& $this->$keyval;
        } else {
        	$data =& $keyval;
        }
		if (is_null($param1)) {
			return false;
		} else if (is_array($param1)) {
			$this->setLvlAuto($name,$param1);
		} else {
			$level = $param1 - 1;
			if ($param2) {	
				$data{$level} = $param2;
			} else {
				$data{$level} = '0';
			}
		}
    }
    
    function setLvlAuto($name,$values) {
    	$data =& $this->$name;
    	if (is_array($values)) {
			for ($i=0, $j=strlen($data); $i < $j; $i++) {
				if ($values[$i]) {
					$data{$i} = $values[$i];
				} else {
					$data{$i} = '0';
				}
			}
    	} else {
    		for ($i=0, $j=strlen($data); $i < $j; $i++) {
    			$data{$i} = '0';
    		}
    	}
    }
    
    function setPrm($keyval, $param, $value) {
    	$arrParams =& $keyval;
		if (is_object($this)) {
			$varname = '_prm_'.$keyval;
			if (array_key_exists($keyval, $this->_properties)) {
				if (is_array($this->$varname)) {
					$arrParams = $this->$varname;
				} else if ($this->$keyval) {
					$arrParams = unserialize($this->$keyval);
				}
			}
		}
		if (!is_array($arrParams)) {
			$arrParams = array();
		}
		$arrParams[$param] = $value;
		if (is_object($this)) {
			$this->$varname = $arrParams;
		}
		return $arrParams;
	}
	
	function setLst($key, $value) {
		$result = '';
		if ($this->_isArrayIndexed($this->_properties[$key])) {
			if (array_key_exists($value,$this->_properties[$key])) {
				// error_log('setting idx '.$key.'['.$value.']='.$this->_properties[$key][$value]);
				$result = $value; //array_search($value,$this->_properties[$key]);
			}
		} else if (in_array($value, $this->_properties[$key])) {
			// error_log('setting straight '.$key.'='.$value);
			$result = $value;
		} else if (is_array($value)) {
			// -- multiple choice --
			// -TODO- check all values
			$result = $value;
		} else {
			// error_log('illegal value for '.$key.' ('.$value.')');
		}
		$this->$key = $result;
	}
    
	/* --- PRINT  ------------------------------------------------ */
	
	function f($key)
    {
        ob_start();
        $this->p($key);
        $str = ob_get_contents();
        ob_clean();
        return $str;
    }
	
	function p($key)
	{
		$arrArgs = func_get_args();
		if ($dfn = $this->_properties[$key]) {
			if (is_array($dfn)) {
				return call_user_func_array(array(&$this,'pLst'),$arrArgs);
			}
			$arrType = explode(',',$dfn);
			if (preg_match('/^OBJ/',$arrType[0])) {
				// indirect call to object
				array_shift($arrArgs[0]); // remove first parameter (key)
				$obj = $this->$key;
				if (is_object($obj)) {
					return call_user_func_array(array(&$obj,'p'), $arrArgs);
				}
			} else if (func_num_args() > 1) {;
				return call_user_func_array(array(
					&$this,'p'.$arrType[0]),$arrArgs);
			} else {
				$type = $arrType[0];
				$arrType[0] = $key;
				// echo "calling p$type with "; print_r($arrType);
				return call_user_func_array(array(&$this,'p'.$type),$arrType);
			}
		}
		
		// print call_user_func_array(array(&$this,'get'), $arrArgs);
	}
		
	function pStr($keyval)
	{
		print Tzn::_strValue(func_get_args());		
	}

	function pNum($keyval,$default='')
	{
		print Tzn::getNum($keyval,$default);
	}
	
	function pInt($keyval,$default='')
	{
		print Tzn::getInt($keyval,$default);
	}

	function pDec($keyval,$param1='',$param2='')
	{
		print Tzn::getDec($keyval,$param1,$param2);
	}
	
	function pEml($keyval,$default='',$style='')
	{
		$value = Tzn::_value($keyval);
		if ($value) {
			print '<a href="mailto:'.$value.'"'.Tzn::_style($style)
				.'>'.$value.'</a>';
		} else {
			print $default;
		}
	}
	
	function pUrl($keyval,$target='',$default='',$style='',$xtra='')
	{
		$value = Tzn::_value($keyval);
		if ($value) {
			$str = '<a href="'.$value.'" '.Tzn::_style($style);
			if ($target) {
				$str .= 'target="'.$target.'" ';
			}
			if ($xtra) {
				$str .= $xtra;
			}
			$str .= '>'.preg_replace("/^(http[s]?|ftp):\/\//i",''
				,$value).'</a>';
			print $str;
		} else {
			print $default;
		}
	}
	
	function pTxt($keyval)
	{
		print nl2br(Tzn::_strValue(func_get_args()));
	}
	
	function pBbs($keyval,$default='')
	{
		$value = Tzn::_value($keyval,$default);
		$value = preg_replace("/(?<!\")((http|ftp)+(s)?"
			.":\/\/[^<>\s]+)/i", "<a href=\"\\0\" target=\"_blank\">\\0</a>", $value);
		print nl2br($value);
	}
	
	function pHtm($keyval,$default='&nbsp;')
	{
		print Tzn::_value($keyval,$default);
	}

	function pImg($keyval,$default='&nbsp;',$width=0,$height=0,$extra='',$mode='real') {
		// pImg (key, [default, [width,height]])
		if (is_object($this)) {
			$value = $this->getImgUrl($keyval, $default);
		} else {
			$value = $keyval;
		}
		if (!$value) {
			print $default;
		} else {
			if (is_object($value)) {
				$value = $value->tempName;
			}
			$objThumb = new TznThumbnail($value, $width, $height);
			$str = $objThumb->getTag($mode);
			if (!$extra) {
				print $str;
			} else {
				print str_replace('<img','<img '.$extra,$str);
			}
		}
	}
	
	function pImgThb($keyval,$default='&nbsp;',$width=0,$height=0,$extra='',$mode='real') {
		// pImg (key, [default, [width,height]])
		if (is_object($this)) {
			$value = $this->getImgUrl($keyval, $default,2);
		} else {
			$value = $keyval;
		}
		if (!$value) {
			print $default;
		} else {
			if (is_object($value)) {
				$value = $value->tempName;
			}
			$objThumb = new TznThumbnail($value, $width, $height);
			$str = $objThumb->getTag($mode);
			if (!$extra) {
				print $str;
			} else {
				print str_replace('<img','<img '.$extra,$str);
			}
		}
	}
	
	function pBol($keyval,$default='',$yes=TZN_BOOL_TRUE,$no=TZN_BOOL_FALSE)
	{
		$value = Tzn::getBol($keyval,$default);
		print $value?$yes:$no;
	}
	
	function pLst($keyval,$separator='\r\n',$default='&nbsp;')
	{
		$value = Tzn::getLst($keyval,$default,$separator);
		if ($separator == '\r\n') {
			$value = nl2br($value);
		}
		print $value;
	}
	
	function pDct($keyval,$favsep='\r\n',$default=null)
	{
		$value = Tzn::getDct($keyval,$favsep,$default);
		if ($favsep == '\r\n') {
			$value = nl2br($value);
		}
		print $value;
	}
	
	function pDte($keyval,$format='SQL',$default='')
	{
		print Tzn::getDte($keyval,$format,$default);
	}
	
	function pDtm($keyval,$format='SQL',$tz=TZN_TZDEFAULT,$default='-')
	{
		if (preg_match('/^(0000|9999)/',$value)) {
			$value = $default;
		} else {
			$value = Tzn::getDtm($keyval,$format,$tz,$default);
		}
		print $value;
	}
	
	function pTmz($keyval,$default='') 
	{
		$value = $keyval;
		if (is_object($this)) {
			$value = $this->_value($keyval);
		}
		print Tzn::_tmz($value);
	}

    function pLvl($name,$level,$default=null,$yes=TZN_BOOL_TRUE,$no=TZN_BOOL_FALSE)
	{
		if (is_object($this)) {
            $data =& $this->$name;
        } else {
        	$data = '';
        }
        $level--;
		if ($level < strlen($data) && $data{$level}) {
			echo $yes;
		} else {
			echo $no;
		}
	}
		
	function _tmz($tz)
	{
		$str = "GMT";
        if ($tz != null) {
            $tza = abs($tz);
            $tzHour = ($tza / 3600);
            $tzMin = ($tza % 3600);
            if ($tz >= 0) {
                $str .= "+";
            } else {
                $str .= "-";
            }
            if ($tzHour < 10) {
                $str .= "0";
            }
            $str .= $tzHour;
            if ($tzMin < 10) {
                $str .= "0";
            }
            $str .= $tzMin;
        }
        return $str;
	}

	/* --- FORM  -------------------------------------------------- */
		
	function qHidden($keyval,$default='')
	{
		if (is_object($this)) {
			$value = Tzn::_value($keyval,$default);
		} else {
			$value = $default;
		}
		$form = '<input type="hidden" name="'.$keyval.'" value="'.$value.'" />';
		print $form;
	}
	
	function qText($keyval,$default='',$style='',$xtra='')
	{	
		if (is_int($style)) {
			$len = $style;
			$style = '';
		}
		if (is_object($this) && ($this->_properties[$keyval])) {
			$value = Tzn::_value($keyval,$default);
			if (!$style) {
				$arrType = explode(',',$this->_properties[$key]);
				switch ($arrType[0]) {
				case 'INT':	
					$style= 'tznFormInt';
					break;
				case 'NUM':	
					$style= 'tznFormNum';
					break;
				case 'DEC':	
					$style= 'tznFormDec';
					break;
				case 'STR':
				case 'TXT':
				case 'BBS':
				case 'URL':
				case 'EML':
					$style= 'tznFormStr';
					break;
				case 'INT':	
					$style= 'tznFormInt';
					break;
				}
			}
		} else /* if ($_REQUEST[$keyval]) {
			$value = $_REQUEST[$keyval];
		} else */ {
			$value = $default;
		}
		/*
		if ($value == $keyval) {
			$value = '';
		}
		*/
		$value = str_replace('"','&quot;',$value);
		$form = '<input type="text" id="i_'.TznUtils::convXHTML($keyval).'" name="'.$keyval.'" value="'.$value.'" ';
		if ($len) {
			$form .= 'size="'.$len.'" ';
		}
		if ($style) {
			$form .= Tzn::_style($style);
		}
		if ($xtra) {
			$form .= $xtra.' ';
		}
		$form .= '/>';
		print $form;
		Tzn::pError($keyval);
	}
	
	function qPassword($keyval,$default='',$style='',$xtra='')
	{
		if (is_object($this)) {
			$value = Tzn::_value($keyval,$default);
		} else {
			$value = $default;
		}
		$form = '<input type="password" id="i_'.TznUtils::convXHTML($keyval).'" name="'.$keyval.'" ';
		if (is_int($style)) {
			$form .= 'size="'.$style.'" ';
		} else {
			$form .= Tzn::_style($style);
		}
		if ($xtra) {
			$form .= $xtra.' ';
		}
		$form .= '/>';
		print $form;
		Tzn::pError($keyval);
	}
	
	function qTextArea($keyval,$default='',$style='tznFormTxt',$xtra='')
	{
		if (is_object($this) && property_exists($this, $keyval)) {
			$value = htmlspecialchars(Tzn::_value($keyval, $default));
			//$value = $this->get($keyval,$default);
		} else {
			$value = htmlspecialchars($default);
		}
		$form = '<textarea id="i_'.TznUtils::convXHTML($keyval).'" name="'.$keyval.'" ';
		if (strpos($style,',')) {
			// cols,rows
			$arr = explode(',',$style);
			$form .= 'cols="'.$arr[0].'" rows="'.$arr[1].'" ';
		} else {
			$form .= Tzn::_style($style);
		}
		if ($xtra) {
			$form .= $xtra.' ';
		}
		$form .= '>'.$value.'</textarea>';
		print $form;
		Tzn::pError($keyval);
	}

	function qBbs($keyval,$default='',$style='tznFormBbs',$xtra='')
	{
		if (is_object($this)) {
			$value = Tzn::_value($keyval,$default);
		} else {
			$value = $default;
		}
		$form = '<textarea id="i_'.TznUtils::convXHTML($keyval).'" name="'.$keyval.'" ';
		if (strpos($style,',')) {
			// cols,rows
			$arr = explode(',',$style);
			$form .= 'cols="'.$arr[0].'" rows="'.$arr[1].'" ';
		} else {
			$form .= Tzn::_style($style);
		}
		if ($xtra) {
			$form .= $xtra.' ';
		}
		$form .= '>'.$value.'</textarea>';
		print $form;
		Tzn::pError($keyval);
	}
	
	function qHtml($keyval,$default='',$style='tznFormHtml',$xtra='')
	{
		if (is_object($this)) {
			$value = Tzn::getHtm($keyval,$default);
		} else {
			$value = $default;
		}
		$form = '<textarea id="i_'.TznUtils::convXHTML($keyval).'" name="'.$keyval.'" ';
		if (strpos($style,',')) {
			// cols,rows
			$arr = explode(',',$style);
			$form .= 'cols="'.$arr[0].'" rows="'.$arr[1].'" ';
		} else {
			$form .= Tzn::_style($style);
		}
		if ($xtra) {
			$form .= $xtra.' ';
		}
		$form .= '>'.$value.'</textarea>';
		print $form;
		Tzn::pError($keyval);
	}
	
	function qImage($keyval,$default='',$style='tznFile',$xtra='') {
		if (is_object($this)) {
			$value = Tzn::_value($keyval,$default);
		} else {
			$value = $default;
		}
		$form = '';
		if ($value) {
			Tzn::pImg($keyval,'',90,60);
			$form = '<br />';
		}
		$form .= '<input id="i_'.TznUtils::convXHTML($keyval).'" type="file" name="'.$keyval.'" value="" ';
		$form .= Tzn::_style($style);
		if ($xtra) {
			$form .= $xtra.' ';
		}
		$form .= '/>';
		print $form;
		Tzn::pError($keyval);
	}
	
	function qImageOptional($keyval,$default='',$style='tznFile',$xtra='') {
		if (is_object($this)) {
			$value = Tzn::_value($keyval,$default);
		} else {
			$value = $default;
		}
		$form = '';
		if ($value) {
			Tzn::pImg($keyval,'',90,60);
			echo '<p><input id="f_'
				.TznUtils::convXHTML($keyval).'_del'.'" type="checkbox" name="'.$keyval.'_del" value="1" />'
				.' <label for="f_'.$keyval.'_del'.'">'.$GLOBALS['langAdmin']['delete'].'</label></p>';
		}
		$form .= '<input type="file" name="'.$keyval.'" value="" ';
		$form .= Tzn::_style($style);
		if ($xtra) {
			$form .= $xtra.' ';
		}
		$form .= '/>';
		print $form;
		Tzn::pError($keyval);
		
	}

	function qFile($keyval,$default='',$style='tznFile',$xtra='') {
		$form .= '<input id="f_'.TznUtils::convXHTML($keyval).'" type="file" name="'.$keyval.'" value="" ';
		$form .= Tzn::_style($style);
		if ($xtra) {
			$form .= $xtra.' ';
		}
		$form .= '/>';
		print $form;
		Tzn::pError($keyval);
	}
	
	function qMultiFile($name, $field, $objList = null, $incSize=false, $incThumb=false) {
		echo '<div id="f_'.TznUtils::convXHTML($name).'" class="multifilelist">';
		if (is_object($objList)) {
			while ($objItem = $objList->rNext()) {
				echo '<div id="f_'.TznUtils::convXHTML($name).'_'.$objItem->id.'" class="multifileitem ';
				if ($incThumb) {
					echo 'cms_tip" title="&lt;img src=\'';
					echo $objItem->getImgUrl($incThumb,'',2);
					echo '\' /&gt;';
				}
				echo '">';
				echo '<a class="del frgt" href="javascript:{}" onclick="cms_multifile_del(\''.$name.'\',\''.$objItem->id.'\')">X</a>'
					.$objItem->get($field);
				if ($incSize) {
					echo ' <small>('.TznFile::getShortSize($objItem->$incSize).')</small>';
				}
				echo '</div>';
			}
		}
		echo '</div>';
		echo '<a href="javascript:{}" onclick="cms_multifile_add(\''.$name.'\')" class="puce multifileadd">ajouter fichier</a>';
		echo '<iframe name="multifileframe_'.$name.'" style="width:0px; height: 0px; border: 0px;"></iframe>';
		echo '<input type="hidden" id="'.TznUtils::convXHTML($name).'2delete" name="'.$name.'2delete" value="" />'; // list of deleted files
	}
	
	function qCheckbox($keyval,$default=0,$style='',$xtra='')
	{
		$value = Tzn::getBol($keyval,$default);
		$form = '<input id="c_'.TznUtils::convXHTML($keyval).'" type="checkbox" name="'.$keyval.'" value="1" ';
		$form .= Tzn::_style($style);
		if ($value) {
			$form .= 'checked="true" ';
		}
		if ($xtra) {
			$form .= $xtra.' ';
		}
		$form .= '/>';
		print $form;
	}
	
	function qCheckboxLabel($label, $keyval, $default=0,$style='',$xtra='') {
		Tzn::qCheckbox($keyval, $default, $style, $xtra);
		echo ' <label for="c_'.TznUtils::convXHTML($keyval).'">'.$label.'</label>';
	}

	function qCheckbox2($name,$value,$checked=false,$style='',$xtra='')
	{
		$form = '<input id="c_'.TznUtils::convXHTML($keyval).'" type="checkbox" name="'.$name.'" value="'.$value.'" ';
		$form .= Tzn::_style($style);
		if ($checked) {
			$form .= 'checked="true" ';
		}
		if ($xtra) {
			$form .= $xtra.' ';
		}
		$form .= '/>';
		print $form;
	}
	
	function qMultiCheck($keyval,$default='',$style='',$cols=1)
	{
		$form = '<ul id="q_'.TznUtils::convXHTML($keyval).'" ';
		$form .= Tzn::_style($style);
		if ($xtra) {
			$form .= $xtra.' ';
		}
		$form .= '>';
		
		if (!$default) {
			$default = $this->$keyval;
		}
		if (!is_array($default)) {
			if ($default) {
				$default = array($default);
			} else {
				$default = array();
			}
		}
		
		$arr = $keyval;
		if (is_object($this) && is_array($this->_properties[$keyval])) {
			$arr = $this->_properties[$keyval];
		}
		if ($j = count($arr)) {
			$keyed = $this->_isArrayIndexed($arr);
			$i = 0;
			foreach ($arr as $key => $val) { 
				if (!$keyed) {
					$key = $val;
				}
				$form .= '<li';
				$i++;
				if (($i % $cols) > 0 && ($i < $j)) {
					$form .= ' class="colps"';
				} else {
					$form .= ' class="colno"';
				}
				$form .= '>';
				$form .= '<input id="m_'.TznUtils::convXHTML($keyval).$i.'" name="'.$keyval.'[]"'
					.' type="checkbox"'
					.' value="'.$key.'"';
				if (in_array($key,$default)) {
					$form .= ' checked="checked"';
				}
				$form .= ' /> ';
				$form .= '<label for="m_'.$keyval.$i.'">'.$val.'</label>';
				$form .= '</li>';
			}
		}
		$form .= '</ul>';
		print $form;
		Tzn::pError($name);
	}
	
	function qSel($keyval,$default='',$nochoice='',
		$style='tznFormSelect',$xtra='')
	{
		$form = '<select id="q_'.$keyval.'" name="'.$keyval.'" ';
		$form .= Tzn::_style($style);
		if ($xtra) {
			$form .= $xtra.' ';
		}
		$form .= '>';
		if ($nochoice) {
			$form .='<option value="">'.$nochoice.'</option>';
		}
		$arr = $keyval;
		if (is_object($this) && is_array($this->_properties[$keyval])) {
			$arr = $this->_properties[$keyval];
		}
		if (count($arr)) {
			$keyed = $this->_isArrayIndexed($arr);
			foreach ($arr as $key => $val) { 
				if (!$keyed) {
					$key = $val;
				}
				$form .= '<option value="'.$key.'"'; 
				if ($key == $default) {
					$form .= ' selected="true"';
				}
				$form .= '>'.$val.'</option>';
			}
		}
		$form .= '</select>';
		print $form;
		Tzn::pError($name);
	}

	function qSelect($name,$keyval,$default='',$nochoice='',
		$style='tznFormSelect',$xtra='')
	{
		$form = '<select id="i_'.$name.'" "name="'.$name.'" ';
		$form .= Tzn::_style($style);
		if ($xtra) {
			$form .= $xtra.' ';
		}
		$form .= '>';
		if ($nochoice) {
			$form .='<option value="">'.$nochoice.'</option>';
		}
		if (is_object($this)) {
			if ($this->rMore()) {
				while ($item = $this->rNext()) { 
					$form .= '<option value="'.$item->id.'"';
					if ($item->id == $default) {
						$form .= ' selected="true"';
					}
					$form .= '>'.$item->_value($keyval).'</option>';
				}
			}
		}
		$form .= '</select>';
		print $form;
		Tzn::pError($name);
	}

	function qSelect2($name,$key,$value,$default='',$nochoice='',
		$style='tznFormSelect',$xtra='')
	{
		$form = '<select id="i_'.TznUtils::convXHTML($name).'" name="'.$name.'" ';
		$form .= Tzn::_style($style);
		if ($xtra) {
			$form .= $xtra.' ';
		}
		$form .= '>';
		if ($nochoice) {
			$form .='<option value="">'.$nochoice.'</option>';
		}
		if (is_object($this)) {
			if ($this->rMore()) {
				while ($item = $this->rNext()) { 
					$v2 = $item->_value($key);
					$form .= '<option value="'.$v2.'"';
					if ($v2 == $default) {
						$form .= ' selected="true"';
					}
					$form .= '>'.$item->_value($value).'</option>';
				}
			}
		}
		$form .= '</select>';
		print $form;
		Tzn::pError($name);
	}
	
	function qTimeZone($keyval,$default=0,$style='',$xtra='') {
		if (is_object($this)) {
			$value = Tzn::_value($keyval,$default);
		} else {
			$value = $default;
		}
        $form = '<select name="'.$keyval.'" ';
        $form .= Tzn::_style($style);
        $form .= $xtra.'>';
        for ($i=-12; $i<=12; $i++) {
            $j = ($i * 3600);
            $form .= '<option value="'.$j.'"';
            if ($j == $value) {
                $form .=' selected="true"';
            }
            $form .=">".Tzn::_tmz($j)."</option>";
        }
        $form .= "</select>";
        print $form;
    }
	
	function qDate($keyval,$default='',$style='tznFormDate',$xtra='')
	{
		if (is_object($this)) {
			$value = Tzn::getDte($keyval,'FRM',$default);
		} else {
			$value = $default;
		}
		Tzn::_dateField($keyval,$value,$style,$xtra);
		Tzn::pError($keyval);
	}
	
	function qDateSelect($id,$keyval,$default='',$style='tznCalField',$xtra='')
	{
		if (is_object($this)) {
			$value = Tzn::getDte($keyval,'FRM',$default);
		} else {
			$value = $default;
		}
		Tzn::_dateField($keyval,$value,$style,$xtra,$id);
		// echo '<button class="tznCalButton" type="button" />';
		Tzn::pError($keyval);
	}
	
	function qDateTime($keyval,$tz=TZN_TZDEFAULT,$default=''
		,$style='tznFormDate',$xtra='')
	{
		if (is_object($this)) {
			$value = Tzn::getDte($keyval,'FRM',$tz,$default);
		} else {
			$value = $default;
		}
		Tzn::_dateField($keyval,substr($value,0,10),$style,$xtra);
		$form = ', <input id="i_'.TznUtils::convXHTML($name).'" type="text" name="'.$name.'Time" value="'
			.substr($value,11,5).'" ';
		if (is_int($style)) {
			$form .= 'size="'.$style.'" ';
		} else {
			$form .= Tzn::_style($style);
		}
		if ($xtra) {
			$form .= $xtra.' ';
		}
		$form .= '/>';
		print $form;
		Tzn::pError($keyval);
	}
	
	function qSubmit($name, $label, $value=1,$style='',$xtra='')
	{
		$form = '<button type="submit" id="s_'.TznUtils::convXHTML($name).'" name="'.$name.'" value="'.$value.'" ';
		if (is_int($style)) {
			$form .= 'size="'.$style.'" ';
		} else {
			$form .= Tzn::_style($style);
		}
		if ($xtra) {
			$form .= $xtra.' ';
		}
		$form .= '>'.$label.'</button>';
		print $form;
	}
	
	function qBut($name, $label, $value='',$style='',$xtra='')
	{
		$form = '<button type="button" id="s_'.TznUtils::convXHTML($name).'" name="'.$name.'" value="'.$value.'" ';
		if (is_int($style)) {
			$form .= 'size="'.$style.'" ';
		} else {
			$form .= Tzn::_style($style);
		}
		if ($xtra) {
			$form .= $xtra.' ';
		}
		$form .= '>'.$label.'</button>';
		print $form;
	}
	
	function qSubmitInput($keyval,$default='',$style='',$xtra='')
	{
		if (is_object($this)) {
			$value = Tzn::get($keyval,$default);
		} else {
			$value = $default;
		}
		$form = '<input type="submit" id="s_'.TznUtils::convXHTML($keyval).'" name="'.$keyval.'" value="'.$value.'" ';
		if (is_int($style)) {
			$form .= 'size="'.$style.'" ';
		} else {
			$form .= Tzn::_style($style);
		}
		if ($xtra) {
			$form .= $xtra.' ';
		}
		$form .= '/>';
		print $form;
		// Tzn::pError($keyval);
	}

	function qButtonInput($keyval,$default='',$style='',$xtra='')
	{
		if (is_object($this)) {
			$value = Tzn::get($keyval,$default);
		} else {
			$value = $default;
		}
		$form = '<input type="button" id="s_'.TznUtils::convXHTML($keyval).'" name="'.$keyval.'" value="'.$value.'" ';
		if (is_int($style)) {
			$form .= 'size="'.$style.'" ';
		} else {
			$form .= Tzn::_style($style);
		}
		if ($xtra) {
			$form .= $xtra.' ';
		}
		$form .= '/>';
		print $form;
		// Tzn::pError($keyval);
	}

    function qLevel($name, $level, $default=false) {
		if (is_object($this)) {
            $data =& $this->$name;
        } else {
        	$data = '';
        }
        $level--;
        $id = $name.'['.$level.']';
		echo('<input type="checkbox" id="c_'.TznUtils::convXHTML($id).'" name="'.$id.'" value="1"');
		if (($level < strlen($data) && $data{$level}) || $default) {
			echo(' checked="true"');
		}
		echo(' />');
	}
	
	function qLevelLabel($label, $name, $level, $default=false) {
		Tzn::qLevel($name, $level, $default);
		echo ' <label for="'.TznUtils::convXHTML($name.'['.$level.']').'">'.$label.'</label>';
	}
	
	function _dateField($name,$value,$style,$xtra,$id=null)
	{
		/* -TODO- make real date fields */
		$form = '<input type="text" name="'.$name.'" value="'.$value.'" ';
		if (is_int($style)) {
			$form .= 'size="'.$style.'" ';
		} else {
			$form .= Tzn::_style($style);
		}
		if ($xtra) {
			$form .= $xtra.' ';
		}
		if ($id) {
			$form .= 'id="'.TznUtils::convXHTML($id).'" ';
		}
		$form .= '/>';
		print $form;
	}
	
	/**
	 * _style : create class/style element
	 * @access private 
	 */
	function _style($style)
	{
		if ($style) {
			if (strpos($style,':')) {
				// style definition eg. color:#fff
				$slabel = 'style';
			} else {
				$slabel = 'class';
			}
			return $slabel.'="'.$style.'" ';
		} else {
			return '';
		}
	}
	
	/* --- Error Methods -------------------------------------------- */
	
	function checkEmpty($fields) {
		$arrFields = explode(',',$fields);
		foreach ($arrFields as $field) {
			$field = trim($field);
			if ((!$this->$field) 
				|| (is_object($this->$field) && (!$this->$field->id))
				|| (!is_object($this->$field) && preg_match('/^(0000|9999)-00-00/',$this->$field)))
			{
				// error_log("field $field seems empty [".$this->$field.']');
				if ($GLOBALS['langTznCommon']['field_compulsory']) {
					$this->e($field,$GLOBALS['langTznCommon']['field_compulsory']);
				} else {
					$this->e($field,'this information is compulsory');
				}
			}
		}
		/*
		if (count($this->_error)) {
			error_log('-> wtf we got an error');
			$this->printErrorListLog();
		}
		*/
		return (count($this->_error) == 0);
	}

	/**
	 * e get error
	 */
    function e($key,$value=null)
    {
        if ($key) {
        	if (is_null($value)) {
        		return $this->_error[$key];
        	} else {
        		$this->_error[$key] = $value;
	        }
        }
    }
    /**
	 * gError 
	 * an alias for e()
	 */
    function gError($key)
    {
    	$this->e($key,$value);
    }
    
    /**
	 * print error
	 * this is the function you may redefine in sub classes
	 */
    function printError($key)
    {
        if ($this->_error[$key]) {
            print '<span class="tznError">'.$this->_error[$key].'</span>';
        }
    }
    
    /**
     * pError
     * an alias for printError, for both static use and object instance
     */
    function pError($key)
    {
    	if (is_object($this) && method_exists($this, 'printError')) {
	        $this->printError($key);
	    }
    }

	function hasError() 
	{
		return (count($this->_error));
	}


    function printErrorList()
    {
        // usually used for debug
		if (count($this->_error)) {
			foreach($this->_error as $key => $value) {
				echo $key." =&gt; ".$value."<br/>";
			}
		}
    }
    
    function printErrorListLog()
    {
        // usually used for debug
		if (count($this->_error)) {
			foreach($this->_error as $key => $value) {
				error_log("==> $key : $value");
			}
		}
    }
    
    function printErrorListHTML()
    {
    	echo '<ul>';
		foreach($this->_error as $key => $value) {
			echo '<li>'.$key.': '.$value.'</li>';
		}
		echo '</ul>';
	}
	
	function resetErrors()
	{
		$this->_error = array();
	}
        
    /* --- Miscellaneous  --------------------------------- */

    function cloneme() {
        return $this->clone4();
    }

    function clone4($arrExtra=null) {
		if (preg_match('/^4/',phpversion())) {
			// PHP 4 (auto clone)
			return $this;
		} else {
			// PHP 5 or > (copy by reference by default)
			$obj = clone($this);
			// clone nested objects
			foreach($this->_properties as $key => $type) {
				if (preg_match('/^OBJ/',$type)) {
					$obj->$key = clone($this->$key);
				}
			}
			if ($arrExtra) {
				// extra fields to clone
				foreach($arrExtra as $extra) {
					$obj->$extra = clone($this->$extra);
				}
			}
			return $obj;
		}
	}
	
	function compareObjects($obj,$arr2watch=null,$ignore=false) {
		$arrChanges = array();
		$modified = false;
		$arrProps = array_keys($this->_properties);
		if ($arr2watch) {
			if (!$ignore) {
				$arrProps = array_intersect($arrProps,$arr2watch);
			} else {
				$arrProps = array_diff($arrProps,$arr2watch);
			}
		}
		foreach($this->_properties as $key => $type) {
			if (!in_array($key,$arrProps)) {
				continue;
			}
			$value = $this->$key;
			$orig = $obj->$key;
			if (preg_match('/^OBJ/i',$type)) {
				$value = $value->id;
				$orig = $orig->id;
			}
			if ($orig != $value) {
				if ($value) {
					if ($orig) {
						$arrChanges[$key] = 1; // modified
					} else {
						$arrChanges[$key] = 0; // removed
					}
				} else {
					$arrChanges[$key] = 2; // added
				}
				$modified = true;
			}
		}
		if ($modified) {
			return $arrChanges;
		} else {
			return false;
		}
	}
    
    function convertToFloat($field) {
    	$value = str_replace(',','.',$this->$field);
    	$this->$field = $value;
    }
	
}

class TznUtils
{

	/* --- QUERY AND SESSION METHODS ------------------------------------------ */
	
	function qSession()
    {
    	if (@constant('TZN_TRANS_ID')) {
			print '<input type="hidden" name="'.ini_get('session.name')
    			.'" value="'.session_id().'" />';
    	}
    }
    
    function sanitize($rule, $value) {
    	if (strpos($rule, '/') === false) {
    		// not a regexp
    		$sanitize = 'get'.ucFirst(strtolower($rule));
			return Tzn::$sanitize('xxxxx',$value);
    	} else if (preg_match($rule, $value)) {
    		return $value;
    	} else {
    		return false;
    	}
    }
    
    /**
	* str
	*/
	function convURI($str) {
		//error_log("replacing $str to ");
		if ($str) {
			if (constant('CMS_CHARSET') == 'UTF-8') {
				$str = utf8_decode($str);
			}
			$str = preg_replace(
				array('/[יטךכ]/','/[אבגה]/','/[לםמן]/','/[שתûü]/','/[צפער]/','/[ח‡]/','/[ \'\?\/\\&"]/'),
				array('e','a','i','u','o','c','-'),
				strtolower($str));
			$str = preg_replace('/[^a-z0-9\-]/','_',$str);
			$str = str_replace('---','-',$str);
		}
		//error_log("$str ...");
		return $str;
	}
    
    function convXHTML($val) {
		return str_replace(array('[',']'),array('-',''), $val);
	}

	function getHttpParameter($method,$key,$save=false,$recover=false,$type='STR') {
		$arrValue = array();
		switch($method) {
			case 'get':
				$arrValue =& $_GET;
				break;
			case 'post':
				$arrValue =& $_POST;
				break;
			default:
				$arrValue =& $_REQUEST;
				break;
		}
		// error_log('PARAM '.$key.': ['.$method.']='.$arrValue[$key].' / session='.$_SESSION[$key]);
		$value = null;
		if (isset($arrValue[$key])) {
			// got from HTTP query string
			$value = Tzn::getHttp($arrValue[$key], $type);
			if (get_magic_quotes_gpc() && $value) {
				// remove fucking magic quotes
				$value=stripslashes($value);
			}
		} else if ($recover) {
			$value = $_SESSION[$key];
		}
		if ($save) {
			// save to session
			if ($value) {
				$_SESSION[$key] = $value;
			} else {
				unset($_SESSION[$key]);
			}
		}
		return $value;
	}
	
	/* --- PLUGIN SYSTEM ------------------------------------------------------ */
	
	function initPlugins() {
	
		// -TODO- install / remove plugins
	
		$GLOBALS['tznPlugins'] = array();
		$arr = array();
	
		if ($handle = opendir(CMS_PLUGIN_PATH)) {

			while (false !== ($file = readdir($handle))) {
				if (is_dir(CMS_PLUGIN_PATH.$file) && $file != '.' && $file != '..' 
					&& $file != 'CVS' && !preg_match('/^_/',$file)) 
				{
					$arr[] = $file;
					include CMS_PLUGIN_PATH.$file.'/package.php';
				}
			}

			closedir($handle);
		}
		
		return (count($arr))?$arr:false;
	}

	/* --- MESSAGING ---------------------------------------------------------- */

	function initMessaging() {
		if (!is_array($_SESSION['tznMessage'])) {
			$_SESSION['tznMessage'] = array();
		}
	}

	function addMessage($str) {
		// -TODO- check if message already set
		$_SESSION['tznMessage'][] = $str;
	}
	
	function hasMessage() {
		return count($_SESSION['tznMessage']);
	}
	
	function getMessages(&$isError, $html=true, $clean=true) {
		$str = '';
		foreach($_SESSION['tznMessage'] as $mess) {
			if (preg_match('/^ERROR:/i', $mess)) {
				$isError = true;
				$mess = substr($mess,6);
			}
			if ($str) {
				$str .= "\n";
			}
			$str .= $mess;
		}
		if ($clean) {
			TznUtils::cleanMessages();
		}
		if ($html) {
			return nl2br(htmlentities($str));
		} else {
			return $str;
		}
	}
	
	function cleanMessages() {
		unset($_SESSION['tznMessage']);
		// session_unregister('tznMessage');
	}
	
	/* --- NAVIGATION HISTORY (REFERERS) -------------------------------------- */
	
	function initReferring() {
		if (!is_array($_SESSION['tznReferrers'])) {
			$_SESSION['tznReferrers'] = array();
		}
	}
	
	function autoReferrer($historic=false) {
	
		$url = $_SERVER['REQUEST_URI'];
		if ($historic) {
			$url = substr($_SERVER['HTTP_REFERER'],strlen(CMS_WWW_URL));
		} else {		
			/*
			$url = $_SERVER['PHP_SELF']
				.(($_SERVER['QUERY_STRING'])?('?'.$_SERVER['QUERY_STRING']):'');
			*/
		}
		
		if (!preg_match('/log(in|out|ister|minder)\.php/',$url)) {
			return $url;
		} else {
			// skip login, register, logout and password reminder pages
			return false;
		}
	}
	
	function setReferrer($url='') {
		$_SESSION['tznReferrers'] = array();
		if ($url) {
			$_SESSION['tznReferrers'][] = $url;
		}
		
	}
		
	function addReferrer($url='', $historic=false) {
		if (!$url) {
			if (!$url = TznUtils::autoReferrer($historic)) {
				return false;
			}
		}
		$arr = $_SESSION['tznReferrers']; // copy
	
		// search for previous entry with same url
		while ($tmp = @array_pop($arr)) {
			if ($tmp == $url) {
				// been to this page, need to clean referrers
				$_SESSION['tznReferrers'] = $arr;
				break;	
			}
		}
		
		// add url to referrer
		$_SESSION['tznReferrers'][] = $url;
		return true;
		
	}
	
	function getReferrer($skip=true, $clean=false) {
		$url = './';
		$arr = $_SESSION['tznReferrers']; // copy
		if ($clean) {
			$arr =& $_SESSION['tznReferrers']; // point
		}
		if (!is_array($arr)) {
			// normally not needed, but seems to happen for some reason
			$arr = array();
		}
		if ($skip) {
			array_pop($arr); // skip last one
		}
		while (count($arr)) {
			if ($tmp = array_pop($arr)) {
				$url = $tmp;
				break;
			}
		}
		return $url;
	}
	
	function naturalReferrer($default, $avoid='') {
		$ref = $default;
		if ($_REQUEST['ref']) {
			$ref = $_REQUEST['ref'];
		} else if ($_SERVER['HTTP_REFERER']) {
			$ref = $_SERVER['HTTP_REFERER'];
		}
		if ($avoid && preg_match("/$avoid/i",$ref)) {
			$ref = $default;
		}
		return $ref;
	}
	
	function delReferrer() {
		array_pop($_SESSION['tznReferrers']);
	}
	
	/* --- URLs and REDIRECTION ----------------------------------------------- */
	
	function redirect($url,$message='',$forceRef=false)
    {
    	if (@constant('TZN_TRANS_ID')) {
			if (session_id() && (!preg_match('/'.session_id().'/i',$url))) {
				$url = TznUtils::concatUrl($url,session_name()
					.'='.session_id());
			}
    	}
    	if ($message) {
    		$message = preg_replace("/<script[^>]*>[^<]+<\/script[^>]*>/is"
			,"", $message); 
			$message = preg_replace("/<\/?(div|span|iframe|frame|input|"
				."textarea|script|style|applet|object|embed|form)[^>]*>/is"
				,"", $message);
			if (@constant('TZN_TRANS_STATUS')) {
				TznUtils::addMessage($message);
			} else {
				$url = TznUtils::concatUrl($url,'tznMessage='.urlencode($message));
			}
    	}
		if ($forceRef) {
			$url = TznUtils::concatUrl($url,'ref='.rawurlencode($_SERVER['REQUEST_URI']));
		}
    	header("Location: ".str_replace('&amp;','&',$url));
    	exit;
    }
    
    function concatUrl($url,$param)
    {
    	// hash
    	$hash = '';
		if ($pos = strpos($url,'#')) {
			$hash = substr($url,$pos);
			$url = substr($url,0,$pos);
		}
		if ($pos = strpos($param,'#')) {
			$hash = substr($param,$pos);
		}
		// params
		$url = str_replace('&amp;','&',$url);
		if ($pos = strpos($url,'?')) {
			$arrParam = explode('=',$param);
			if (strpos($url,$arrParam[0].'=')) {
				// parameter already in url
				$strQuery = substr($url,$pos+1);
				$arrQuery = explode('&',$strQuery);
				$arrResult = array();
				$found = false;
				foreach ($arrQuery as $value) {
					if (preg_match('/^'.$arrParam[0].'=/', $value)) {
                        if ($arrParam[1]) {
                            // add only if has a value
    						$arrResult[] = $param;
                        }
						$found = true;
					} else {
						$arrResult[] = $value;
					}
				}
				if ($found) {
					$url = substr($url,0,$pos).'?'.implode('&',$arrResult);
				} else {
					$url .= '&'.$param;
				}
			} else {
				$url .= '&'.$param;
			}
    	} else {
    		$url .= '?'.$param;
    	}
    	return str_replace('&','&amp;',$url).$hash;
    }
	
	/* --- MISCELANEOUS ------------------------------------------------------- */
	
	function strToCamel($str, $firstCap = false) {
		$arr = explode('_',$str);
		$str = '';
		foreach($arr as $sep) {
			if ((!$str && $firstCap) || $str) {
				$str .= ucfirst($sep);
			} else {
				$str .= $sep;
			}
		}
		return $str;
	}
	
	function strToFlat($str) {
		$str = preg_replace('/(?<=\\w)(?=[A-Z])/',"_$1", trim($str));
		return strtolower($str);
	}
	
	function log_message($str) {
		error_log('TZN message : '.$str);
	}
	
	function log_warn($str) {
		error_log('TZN warn : '.$str);
	}
	
	function log_error($str) {
		error_log('TZN error : '.$str);
	}
	
	function _strToArrayRecurs($str,&$i) {
		$arr = array();
		$tmp = '';
		while ($i < strlen($str) && $str{$i} != ')') {
			if ($str{$i} == ',') {
				if ($idx = strpos($tmp,':')) {
					$key = trim(substr($tmp,0,$idx));
					$val = trim(substr($tmp,$idx+1));
					$arr[$key] = trim($val);
				} else {
					$arr[] = trim($tmp);
				}
				$tmp = '';
			} else if ($str{$i} == '(') {
				$i++;
				$arr[] = TznUtils::_strToArrayRecurs($str,$i);
			} else {
				$tmp .= $str{$i};
			}
			$i++;
		}
		if ($str{$i+1} != ')') {
			$i++;
		}
		if ($tmp) {
			if ($idx = strpos($tmp,':')) {
				$key = trim(substr($tmp,0,$idx));
				$val = trim(substr($tmp,$idx+1));
				$arr[$key] = trim($val);
			} else {
				$arr[] = trim($tmp);
			}
		}
		return $arr;
	}
	
	function strToArray($str) {
		$i = 0;
		return TznUtils::_strToArrayRecurs($str,$i);
	}
}

class TznCollection
{
	var $_data;
	
	function TznCollection($data)
	{
		$this->_data = $data;
	}
	
	function p($key,$default='-') {
		if ($key) {
			echo $this->_data[$key];
		} else {
			echo $default;
		}
	}
	
	function qSelect($name, $default=null,$optional=false,
		$style='tznFormSelect',$xtra='')
	{
		$form = '<select name="'.$name.'" ';
		$form .= Tzn::_style($style);
		if ($xtra) {
			$form .= $xtra;
		}
		$form .= '>';
		if ($optional) {
			$form .='<option value="">'.$optional.'</option>';
		}
		foreach ($this->_data as $key => $value) {
			$form .= '<option value="'.$key.'"';
			if ($key == $default) {
				$form .= ' selected="selected"';
			}
			$form .= '>'.$value.'</option>';
		}
		$form .= '</select>';
		echo $form;
	}
}


if ( !function_exists( 'property_exists' ) ) { 
    function property_exists( $class, $property ) { 
        if ( is_object( $class ) ) { 
            $vars = get_object_vars( $class ); 
        } else { 
            $vars = get_class_vars( $class ); 
        } 
        return array_key_exists( $property, $vars ); 
    } 
}