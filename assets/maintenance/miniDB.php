<?php
// error_reporting(0);

/******** CONFIG ******/
/*
define("TZN_DB_HOST","localhost");
define("TZN_DB_USER","root");
define("TZN_DB_PASS","");
define("TZN_DB_BASE","blog");
*/

// hack for old version compatibility
include '../../include/config.php';


/* ===== DO NOT MODIFY BELOW THIS POINT ===== */

/**
 * MiniDB Class
 * @author Stan Ozier <stan@tirzen.com>
 * @copyright Copyright &copy; 2005, Stan Ozier
 * @version 1.0
 */

class MiniDB {

	var $_dbLink;
	var $_dbResult;
	var $_count;
	var $_idx;

	function MiniDB() {
		// $this->connect();

		// attempt to implement destructor but doesn't work
		// register_shutdown_function(&$this,"_unset");
	}

	function getCount() {
		return $this->_count;
	}
	
	function isConnected() {
		return ($this->_dbLink)?TRUE:FALSE;
	}

	function connect($host=TZN_DB_HOST, $user=TZN_DB_USER, 
		$pass=TZN_DB_PASS, $base=TZN_DB_BASE) 
	{
		if (!$this->_dbLink) {
			$this->_dbLink = mysql_connect($host, $user, $pass)
				or die("Could not connect to database (mysql_connect)");
			mysql_select_db($base,$this->_dbLink) 
				or die("Can not use database ".$base." (mysql_select_db)");
			$this->_debug = "<span>$host/$base</span>";
			
		}
	}

	function _querySelect($sql, $dbg) {
		if ($this->_dbLink) {
			$this->_dbResult = mysql_query($sql,$this->_dbLink);
			if (!$this->_dbResult) {
				if ($dbg) {
					echo "<div id=\"debug\">".$this->_debug.": <code>".$sql."</code><br/>";
					echo "### Error SQL #".mysql_errno().": ".mysql_error()."###</div>";
				} else {
					echo "<!-- Error SQL #".mysql_errno()." -->";
				}
				$this->_count = -1;
			} else {
				$this->_count = mysql_num_rows($this->_dbResult);
			}
		} else {
			echo "not connected to database";
			$this->_count = -2;
		}
		$this->_idx = 0;
		return $this->_count;
	}

	function _queryAffect($sql, $dbg) {
		if ($this->_dbLink) {
			mysql_query($sql,$this->_dbLink);
			if (($count = mysql_affected_rows($this->_dbLink)) == -1) {
				if ($dbg) {
					echo "<div id=\"debug\"><code>".$sql."</code><br/>";
					echo "### Error SQL #".mysql_errno().": ".mysql_error()."###</div>";
				} else {
					echo "<!-- Error SQL #".mysql_errno()." -->";
				}
			}
			$this->_count = $count;
		} else {
			echo ("not connected to database");
			$this->_count = -2;
		}
		$this->_idx = $this->_count;
		return $this->_count;
	}

	function getInsertId() {
		return mysql_insert_id($this->_dbLink);
	}
	
	function getAffectedRows() {
		return mysql_affected_rows($this->_dbLink);
	}
	
	function getTables() {
		$result = mysql_query('SHOW TABLES',$this->_dbLink);
		if (!$result) {
			echo 'Query failed: '.mysql_error();
			return false;
		}
		if (mysql_num_rows($result) > 0) {
			$arrTables = array();
			while ($row = mysql_fetch_array($result)) {
				$result2 = mysql_query("SHOW COLUMNS FROM ".$row[0],$this->_dbLink);
				$arrStruct = array();
				while ($row2 = mysql_fetch_assoc($result2)) {
					$arrStruct[] = $row2;
				}
				$arrTables[$row[0]] = $arrStruct;
			}
			return $arrTables;
		}
		return FALSE;
	}

	function getColumns($table) {
		$arrCols = array();
		$result = mysql_query("SHOW COLUMNS FROM ".$table,$this->_dbLink);
		if (!$result) {
			echo 'Query failed: ' . mysql_error();
			return false;
		}
		if (mysql_num_rows($result) > 0) {
			while ($row = mysql_fetch_assoc($result)) {
				$arrCols[] = $row['Field'];
			}
		}
		return $arrCols;
	}
	
	function query($sql, $dbg=0) {
		$pos = strpos(ltrim($sql),"SELECT");
        if (($pos !== false) && ($pos == 0)) {
            return $this->_querySelect($sql, $dbg);
        } else {
            return $this->_queryAffect($sql, $dbg);
        }
	}

	function hasMore() {
		return ($this->_idx < $this->_count);
	}
	
	function rMore() {
		return $this->hasMore();
	}

	function getNext() {
		if ($this->hasMore()) {
			$this->_idx++;
			return mysql_fetch_object($this->_dbResult); 
		} else {
			return false;
		}
	}
	
	function rNext() {
		return $this->getNext();
	}

	function resetPointer() {
		if (mysql_data_seek($this->_dbResult, 0)) {
			$this->_idx = 0;
			return true;
		} else {
			return false;
		}
	}
	
	function rReset() {
		return $this->resetPointer();
	}

	function freeResult() {
		if ($this->_dbResult) {
			@mysql_free_result($this->_dbResult);
		}
        $this->_count = 0;
        $this->_idx = 0;
	}
	
	function rFree() {
		$this->freeResult();
	}

	function _unset() {
		$this->freeResult();
		mysql_close($this->_dbLink);
	}
}