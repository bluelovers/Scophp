<?

/**
 *
 * $HeadURL$
 * $Revision$
 * $Author$
 * $Date$
 * $Id$
 *
 * @author bluelovers
 * @copyright 2010
 */

class Scrpio_Db_Driver_Mysql_Core extends Scrpio_Db {

	const _FUNC = 'mysql_';

	protected $last_query = '';
	protected $last_runquery = '';

	protected $last_result = null;

	protected $connection = null;
	protected static $set_names;

	// Quote character to use for identifiers (tables/columns/aliases)
	protected $quote = '`';

	protected function _query($sql, $type = '') {
		$func = $type == 'UNBUFFERED' && @function_exists('mysql_unbuffered_query') ? 'mysql_unbuffered_query' : 'mysql_query';

		return $func($sql, $this->connection);
	}

	/**
	 * Sets the character set
	 *
	 * @return void | this
	 */
	public function set_charset($charset) {
		// Make sure the database is connected
		$this->connection or $this->connect();

		self::$set_names === null && self::$set_names = !function_exists('mysql_set_charset');

		if (self::$set_names === true) {
			// PHP is compiled against MySQL 4.x
			$status = (bool)$this->_query('SET NAMES ' . $this->quote($charset));
		} else {
			// PHP is compiled against MySQL 5.x
			$status = mysql_set_charset($charset, $this->connection);
		}

		if ($status === false) {
			// Unable to set charset
			throw new Scrpio_Db_Exception('#:errno: :error', array(':error' => $this->_error($this->connection), ':errno' => $this->_errno($this->connection)));

			return false;
		}

		return $this;
	}

	protected function _escape($value){
		return mysql_real_escape_string($value, $this->connection);
	}

	protected function _func($func, $args = null){
		return $args !== null ?
			call_user_func_array(self::_FUNC.$func, (is_array($args) ? $args : array($args)))
			: call_user_func(self::_FUNC.$func);
	}

	/**
	 * Escapes the given value
	 *
	 * @param  mixed  Value
	 * @return mixed  Escaped value
	 */
	public function escape($value) {
		// Make sure the database is connected
		$this->connection or $this->connect();

		if (($value = $this->_escape($value)) === false) {
			throw new Scrpio_Db_Exception('#:errno: :error', array(':error' => $this->_error($this->connection), ':errno' => $this->_errno($this->connection)));
		}

		return $value;
	}

	protected function _select_db($database) {
		return mysql_select_db($database, $this->connection);
	}

	protected function _error() {
		return $this->connection ? mysql_error($this->connection) : mysql_error();
	}

	protected function _errno() {
		return $this->connection ? mysql_errno($this->connection) : mysql_errno();
	}

	protected function _close() {
		return mysql_close($this->connection);
	}

	public function disconnect() {
		try {
			// Database is assumed disconnected
			$status = true;

			if (is_resource($this->connection)) {
				$status = $this->_close();
			}
		}
		catch (exception $e) {
			// Database is probably not disconnected
			$status = is_resource($this->connection);
		}

		return $status;
	}

	public function query_execute($sql, $type = '', $parsesql = null) {
		// Make sure the database is connected
		$this->connection or $this->connect();

		(($parsesql !== null) ? $parsesql : $this->config['autoparsesql']) && $sql = $this->parsesql($sql);

		$result = $this->_query($sql, $type);

		// Set the last query
		$this->last_query = $sql;

		return $this->_result($result, $sql);
	}

	protected function _result($result, $sql) {
		return new Scrpio_Db_Result_Mysql($result, $sql, &$this);
	}

	function connect() {

		if ($this->connection)
			return;

		extract($this->config['connection']);

		$host = isset($host) ? $host : $socket;
		$port = isset($port) ? ':' . $port : '';

		try {
			// Connect to the database
			$this->connection = ($this->config['persistent'] === true) ? mysql_pconnect($host . $port, $user, $pass, $params) : mysql_connect($host . $port, $user, $pass, true, $params);
		}
		catch (Scrpio_Exception $e) {
			// No connection exists
			$this->connection = null;

			// Unable to connect to the database
			throw new Scrpio_Db_Exception('#:errno: :error', array(':error' => $this->_error(), ':errno' => $this->_errno()));
		}

		if (!$this->_select_db($database, $this->connection)) {
			// Unable to select database
			throw new Scrpio_Db_Exception('#:errno: :error', array(':error' => $this->_error($this->connection), ':errno' => $this->_errno($this->connection)));
		}

		if (isset($this->config['character_set'])) {
			// Set the character set
			$this->set_charset($this->config['character_set']);
		}
	}

	function fetch_array($query, $result_type = MYSQL_ASSOC) {
		return mysql_fetch_array($query, $result_type);
	}

	function fetch_first($sql) {
		return $this->fetch_array($this->query($sql));
	}


	function affected_rows() {
		return mysql_affected_rows($this->connection);
	}

	function error() {
		return $this->_error();
	}

	function errno() {
		return $this->_errno();
	}

	function num_rows($query) {
		$query = mysql_num_rows($query);
		return $query;
	}

	function num_fields($query) {
		return mysql_num_fields($query);
	}

	function free_result($query) {
		return mysql_free_result($query);
	}

	function insert_id() {
		return ($id = mysql_insert_id($this->connection)) >= 0 ? $id : $this->result($this->query("SELECT last_insert_id()"), 0);
	}

	function fetch_row($query) {
		$query = mysql_fetch_row($query);
		return $query;
	}

	function fetch_fields($query) {
		return mysql_fetch_field($query);
	}

	function version() {
		if (empty($this->version)) {
			$this->version = mysql_get_server_info($this->connection);
		}
		return $this->version;
	}


	function halt($message = '', $sql = '') {
		define('CACHE_FORBIDDEN', true);
		require_once DISCUZ_ROOT . './include/db_mysql_error.inc.php';
	}

	function table($table, $add = 1) {
		$ret = $this->tablepre . $table;
		if ($add)
			$ret = '`' . $ret . '`';

		return $ret;
	}


	function query_info($query = null) {
		if (function_exists('mysql_info')) {
			return $query ? mysql_info($query) : mysql_info();
		} else {
			return '';
		}

		//		$return = array();
		//		ereg("Records: ([0-9]*)", $strInfo, $records);
		//		ereg("Duplicates: ([0-9]*)", $strInfo, $dupes);
		//		ereg("Warnings: ([0-9]*)", $strInfo, $warnings);
		//		ereg("Deleted: ([0-9]*)", $strInfo, $deleted);
		//		ereg("Skipped: ([0-9]*)", $strInfo, $skipped);
		//		ereg("Rows matched: ([0-9]*)", $strInfo, $rows_matched);
		//		ereg("Changed: ([0-9]*)", $strInfo, $changed);
		//
		//		$return['records'] = $records[1];
		//		$return['duplicates'] = $dupes[1];
		//		$return['warnings'] = $warnings[1];
		//		$return['deleted'] = $deleted[1];
		//		$return['skipped'] = $skipped[1];
		//		$return['rows_matched'] = $rows_matched[1];
		//		$return['changed'] = $changed[1];
	}

	function parsesql($queries, $do = 1) {
		if ($do < 0)
			return str_replace(array(' ' . $this->tablepre, ' ' . $this->tablepre, ' `' . $this->tablepre), array(' [Table]', ' [Table]', ' `[Table]'), $queries);

		return $do ? str_replace("\r", "\n", str_replace(array(' cdb_', ' {tablepre}', ' `cdb_'), array(' ' . $this->tablepre, ' ' . $this->tablepre, ' `' . $this->tablepre), $queries)) : $queries;
	}

	function getlastsql($br = 0) {
		$ret = $this->parsesql($this->lastsql, -1);

		return $rb ? nl2br($ret) : $ret;
	}

	function getlastrunsql($br = 0) {
		$ret = $this->parsesql($this->lastrunsql, -1);

		return $rb ? nl2br($ret) : $ret;
	}



	function runquery($query, $type = '') {
		(($parsesql !== null) ? $parsesql : $this->config['autoparsesql']) && $sql = $this->parsesql($sql);

		$this->lastrunsql = $query;

		$expquery = explode(";\n", $query);

		foreach ($expquery as $sql) {
			$sql = trim($sql);
			if ($sql == '' || $sql[0] == '#')
				continue;

			if (strtoupper(substr($sql, 0, 12)) == 'CREATE TABLE') {
				$this->query($this->createtable($sql, $this->dbcharset), $type);
			} else {
				$this->query($sql, $type);
			}
		}
	}

	function upgradetable($updatesql) {

		//		array('forums', 'ADD', 'allowtag', "TINYINT(1) NOT NULL DEFAULT '1'"),
		//		array('forums', 'DROP', 'allowpaytoauthor', ""),
		//		array('medals', 'INDEX', '', "ADD INDEX displayorder (displayorder)"),
		//		array('memberfields', 'CHANGE', 'medals', "medals TEXT NOT NULL"),
		//		array('threads', 'MODIFY', 'subject', "char(100)"),
		//		array('posts', 'MODIFY', 'subject', "char(100)"),
		//		array('forumrecommend', 'MODIFY', 'subject', "char(100)"),
		//		array('rsscaches', 'MODIFY', 'subject', "char(100)"),
		//		array('tradelog', 'MODIFY', 'subject', "char(100)"),
		//		array('trades', 'MODIFY', 'subject', "char(100)"),
		//
		//		subjectarray('threads', 'posts', 'forumrecommend', 'rsscaches', 'tradelog', 'trades', 'announcements')

		$successed = true;

		if (is_array($updatesql) && !empty($updatesql[0])) {

			list($table, $action, $field, $sql) = $updatesql;

			if (empty($field) && !empty($sql)) {

				$query = "ALTER TABLE {$this->tablepre}{$table} ";
				if ($action == 'INDEX') {
					$successed = $this->query("$query $sql", "SILENT");
				} elseif ($action == 'UPDATE') {
					$successed = $this->query("UPDATE {$this->tablepre}{$table} SET $sql", 'SILENT');
				}

			} elseif ($tableinfo = $this->loadtable($table)) {

				$fieldexist = isset($tableinfo[$field]) ? 1 : 0;

				$query = "ALTER TABLE {$this->tablepre}{$table} ";

				if ($action == 'MODIFY') {

					$query .= $fieldexist ? "MODIFY $field $sql" : "ADD $field $sql";
					$successed = $this->query($query, 'SILENT');

				} elseif ($action == 'CHANGE') {

					$field2 = trim(substr($sql, 0, strpos($sql, ' ')));
					$field2exist = isset($tableinfo[$field2]);

					if ($fieldexist && ($field == $field2 || !$field2exist)) {
						$query .= "CHANGE $field $sql";
					} elseif ($fieldexist && $field2exist) {
						$this->query("ALTER TABLE {$this->tablepre}{$table} DROP $field2", 'SILENT');
						$query .= "CHANGE $field $sql";
					} elseif (!$fieldexist && $fieldexist2) {
						$this->query("ALTER TABLE {$this->tablepre}{$table} DROP $field2", 'SILENT');
						$query .= "ADD $sql";
					} elseif (!$fieldexist && !$field2exist) {
						$query .= "ADD $sql";
					}
					$successed = $this->query($query);

				} elseif ($action == 'COMMENT') {

					if ($fieldexist && $tableinfo['Comment'] != $sql) {
						$query .= "CHANGE `$field` `$field` {$tableinfo['Type']} " . ($tableinfo['Collation'] ? " CHARACTER SET {$this->dbcharset} COLLATE {$tableinfo['Collation']} " : "") . ($tableinfo['Null'] ? ' NOT ' : '') . " NULL {$tableinfo['Extra']} " . ($tableinfo['Default'] != null ? " DEFAULT '{$tableinfo['Default']}' " : "") . " COMMENT '$sql' ";

						$successed = $this->query($query);
					}

				} elseif ($action == 'ADD') {

					$query .= $fieldexist ? "CHANGE $field $field $sql" : "ADD $field $sql";
					$successed = $this->query($query);
				} elseif ($action == 'DROP') {
					if ($fieldexist) {
						$successed = $this->query("$query DROP $field", "SILENT");
					}
					$successed = true;
				}

			} else {

				$successed = 'TABLE NOT EXISTS';

			}
		}
		return $successed;
	}

	function loadtable($table, $force = 0) {

		//		Field 	Type 	Collation 	Null 	Key 	Default 	Extra 	Privileges 	Comment
		//		pmlife  	int(10) unsigned  	NULL  	NO  	   	0  	   	select,insert,update,references  	PM摮咞暑?彑?

		if (!isset($tables[$table]) || $force) {
			if ($this->version() > '4.1') {
				$query = $this->query("SHOW FULL COLUMNS FROM {$this->tablepre}$table", 'SILENT');
			} else {
				$query = $this->query("SHOW COLUMNS FROM {$this->tablepre}$table", 'SILENT');
				//				$query = $this->query("DESCRIBE {$this->tablepre}$table", 'SILENT');
			}
			while ($field = @$this->fetch_array($query)) {
				$this->tables[$field['Field']] = $field;
			}
		}
		return $this->tables;
	}

	function col_exists_chk($table, $col, $force = 0) {
		$chk = array_key_exists($col, $this->loadtable($table, $force)) ? 1 : 0;
		return $chk;
	}

	function createtable($sql, $dbcharset) {
		$type = strtoupper(preg_replace("/^\s*CREATE TABLE\s+.+\s+\(.+?\).*(ENGINE|TYPE)\s*=\s*([a-z]+?).*$/isU", "\\2", $sql));
		$type = in_array($type, array('MYISAM', 'HEAP')) ? $type : 'MYISAM';
		return preg_replace("/^\s*(CREATE TABLE\s+.+\s+\(.+?\)).*$/isU", "\\1", $sql) . (mysql_get_server_info() > '4.1' ? " ENGINE=$type DEFAULT CHARSET=$dbcharset" : " TYPE=$type");
	}

	function get_charset($tablename) {
		$tablestruct = $this->fetch_first("show create table $tablename");
		preg_match("/CHARSET=(\w+)/", $tablestruct['Create Table'], $m);
		return $m[1];
	}

	function tables_lock($tablesarray = '', $usetablepre = 1) {

		$sql = $t = $c = '';

		if ($usetablepre)
			$t = $GLOBALS['tablepre'];

		foreach (array_unique($tablesarray) as $key => $value) {

			$sql .= $c . $t . "{$value} WRITE";

			$c = ', ';
		}

		$this->query("LOCK TABLES $sql");
	}

	function tables_unlock() {
		$this->query("UNLOCK TABLES");
	}

	function query_first($sql) {
		return $this->fetch_first($sql);
	}

	function query_result($query_string, $index = 0, $field = 0) {
		return $this->result($this->query($query_string), $index, $field);
	}

	function result($query, $row = 0, $field = 0) {
		$query = @mysql_result($query, $row, $field);
		return $query;
	}

	function result_first($sql, $field = 0) {
		return $this->result($this->query($sql), 0, $field);
	}

	function fetch_all($sql, $id = '', $parsesql = 0) {
		$arr = array();
		$query = $this->query($this->parsesql($sql, $parsesql));
		while ($data = $this->fetch_array($query)) {
			$id ? $arr[$data[$id]] = $data : $arr[] = $data;
		}

		$this->free_result($query);

		return $arr;
	}

	function result_all($sql, $field = 0) {
		$arr = array();
		$query = $this->query($sql);

		if ($l = mysql_num_rows($query)) {
			for ($i = 0; $i < $l; $i++) {
				if (is_array($field)) {
					$t = array();
					foreach ($field as $k) {
						$t[$k] = $this->result($query, $i, $k);
					}
					$arr[] = $t;
				} else {
					$arr[] = $this->result($query, $i, $field);
				}
			}
		}

		$this->free_result($query);

		return $arr;
	}

	function query_result_all($sql, $field = 0) {
		return $this->result_all($sql, $field);
	}

	function fetch_assoc($query = null) {
		return mysql_fetch_assoc($query === null ? $this->lastqueryid : $query);
	}
}

?>