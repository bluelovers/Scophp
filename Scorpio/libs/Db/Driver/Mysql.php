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

if (0) {
	// for IDE
	class Scorpio_Db_Driver_Mysql extends Scorpio_Db_Driver_Mysql_Core {
	}
}

class Scorpio_Db_Driver_Mysql_Core extends Scorpio_Db {
	// Use SET NAMES to set the character set
	protected static $set_names;

	// Quote character to use for identifiers (tables/columns/aliases)
	protected $quote = '`';

	protected $driver = 'mysql';
	protected $driver_core = null;

	protected function __construct(array $config) {
		parent::__construct($config);

		$this->driver = 'mysql';
		$this->driver_core = scodb::instance($this->driver);

		$this->build();
	}

	protected function _query($sql, $type = '') {
		static $static_cache;
		if ($static_cache == null || $static_cache[$this->driver] === null) {
			$static_cache[$this->driver] = method_exists($this->driver_core, 'unbuffered_query') ? true : false;
		}

		$func = ($type == 'UNBUFFERED' && $static_cache[$this->driver]) ? 'unbuffered_query' : 'query';

		return call_user_func_array(array($this->driver_core, $func), array($sql, $this->connection));
	}

	/**
	 * Sets the character set
	 *
	 * @return void | this
	 */
	public function set_charset($charset) {
		// Make sure the database is connected
		$this->connection or $this->connect();

		static $static_cache;
		if ($static_cache == null || $static_cache[$this->driver] === null) {
			$static_cache[$this->driver] = method_exists($this->driver_core, 'set_charset') ? true : false;
		}

		if ($static_cache[$this->driver] === true) {
			// PHP is compiled against MySQL 4.x
			$status = (bool)$this->_query('SET NAMES ' . $this->quote($charset));
		} else {
			// PHP is compiled against MySQL 5.x
			$status = $this->driver_core->set_charset($charset, $this->connection);
		}

		if ($status === false) {
			// Unable to set charset
			throw new Scorpio_Db_Exception('#:errno: :error', array(':error' => $this->
				_error($this->connection), ':errno' => $this->_errno($this->connection)));

			return false;
		}

		return $this;
	}

	protected function _escape($value) {
		return $this->driver_core->real_escape_string($value, $this->connection);
	}

	protected function _func($func, $args = null) {

		$args = is_array($args) ? $args : ($args === null ? array() : array($args));

		return call_user_func_array(array($this->driver_core, $func), $args);
	}

	protected function __call($func, $args = null) {
		static $methods;

		if (!$methods) {
			$methods = array();

			$methods['result'] = array_flip(array('num_rows', 'fetch_assoc'));

			$methods['connection'] = array_flip(array('insert_id', 'affected_rows', 'error',
				'errno'));

			$methods['mysql'] = array_flip(array('fetch_array'));
		}

		$_func = '';
		if (isset($methods['connection'][$func])) {
			$_func = '_' . $func;
			$_args = array($this->connection);
		} elseif (isset($methods['result'][$func])) {
			$_func = '_' . $func;
			$_args = is_array($args) ? $args : array($this->last_result);
		} elseif (isset($methods['mysql'][$func])) {
			$_func = '_' . $func;
			$_args = $args;
		}

		if (!empty($_func)) {
			if (method_exists($this, $_func)) {
				return call_user_func_array(array($this, $_func), $_args);
			} else {
				return $this->_func($func, $_args);
			}
		}
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
			throw new Scorpio_Db_Exception('#:errno: :error', array(':error' => $this->
				_error($this->connection), ':errno' => $this->_errno($this->connection)));
		}

		return $value;
	}

	protected function _select_db($database) {
		return $this->driver_core->select_db($database, $this->connection);
	}

	protected function _error() {
		return $this->connection ? $this->driver_core->error($this->connection) : $this->driver_core->error();
	}

	protected function _errno() {
		return $this->connection ? $this->driver_core->errno($this->connection) : $this->driver_core->errno();
	}

	protected function _close() {
		return $this->driver_core->close($this->connection);
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

		(($parsesql !== null) ? $parsesql : $this->config['autoparsesql']) && $sql = $this->
			parsesql($sql);

		$result = $this->_query($sql, $type);

		// Set the last query
		$this->last_query = $sql;

		return $this->_result($result, $sql);
	}

	protected function _result($result, $sql) {
		return new Scorpio_Db_Result_Mysql($result, $sql, &$this);
	}

	public function build() {
		return $this->build or $this->build = new Scorpio_Db_Builder_Mysql(&$this);
	}

	protected function _connect($host, $port, $user, $pass, $params) {
		return ($this->config['persistent'] === true) ? $this->driver_core->pconnect($host . $port, $user,
			$pass, $params) : $this->driver_core->connect($host . $port, $user, $pass, true, $params);
	}

	public function connect() {

		if ($this->connection)
			return;

		extract($this->config['connection']);

		$host = isset($host) ? $host : $socket;
		$port = isset($port) ? ':' . $port : '';

		try {
			// Connect to the database
			$this->connection = $this->_connect($host, $port, $user, $pass, $params);
		}
		catch (Scorpio_Exception_PHP $e) {
			// No connection exists
			$this->connection = null;

			// Unable to connect to the database
			throw new Scorpio_Db_Exception('#:errno: :error', array(':error' => $this->
				_error(), ':errno' => $this->_errno()));
		}

		if (!$this->_select_db($database, $this->connection)) {
			// Unable to select database
			throw new Scorpio_Db_Exception('#:errno: :error', array(':error' => $this->
				_error($this->connection), ':errno' => $this->_errno($this->connection)));
		}

		if (isset($this->config['character_set'])) {
			// Set the character set
			$this->set_charset($this->config['character_set']);
		}
	}

	function fetch_first($sql) {
		return $this->fetch_array($this->query($sql));
	}

	function fetch_row($query) {
		$query = $this->driver_core->fetch_row($query);
		return $query;
	}

	function fetch_fields($query) {
		return $this->driver_core->fetch_field($query);
	}

	function version() {
		if (empty($this->version)) {
			$this->version = $this->driver_core->get_server_info($this->connection);
		}
		return $this->version;
	}

	function _tables_create($sql, $dbcharset) {
		$type = strtoupper(preg_replace("/^\s*CREATE TABLE\s+.+\s+\(.+?\).*(ENGINE|TYPE)\s*=\s*([a-z]+?).*$/isU", "\\2", $sql));
		$type = in_array($type, array('MYISAM', 'HEAP', 'INNDB')) ? $type : 'MYISAM';
		return preg_replace("/^\s*(CREATE TABLE\s+.+\s+\(.+?\)).*$/isU", "\\1", $sql).
			($this->version() > '4.1' ? " ENGINE=$type DEFAULT CHARSET=$dbcharset" : " TYPE=$type");
	}

	function query_info($query = null) {

		static $static_cache;
		if ($static_cache == null || $static_cache[$this->driver] === null) {
			$static_cache[$this->driver] = method_exists($this->driver_core, 'info') ? true : false;
		}

		if ($static_cache[$this->driver]) {
			return $query ? $this->driver_core->info($query) : $this->driver_core->info();
		} else {
			return array();
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

	/**
	 * 分析修正多筆 SQL 並且移除註解
	 *
	 * @param string $sql
	 * @param bool $retarray if true return a array
	 */
	function clear($sql, $retarray = false){
		/*
		http://www.php.net/manual/en/function.preg-replace.php#87816

		$sql = preg_replace("/(?<!\\n)\\r+(?!\\n)/", "\r\n", $sql);
		$sql = preg_replace("/(?<!\\r)\\n+(?!\\r)/", "\r\n", $sql);
		$sql = preg_replace("/(?<!\\r)\\n\\r+(?!\\n)/", "\r\n", $sql);
		*/

//		$sql = preg_replace("/(?<!\\n)\\r+(?!\\n)/", LF, $sql);
//		$sql = preg_replace("/(?<!\\r)\\n\\r+(?!\\n)/", LF, $sql);
//		$sql = preg_replace("/\\r\\n/", LF, $sql);

		$sql = scotext::lf($sql);

		$templine = '';
		$newsql = array();

		foreach ( split(LF, $sql) as $line_num => $line ) {
			$temp = trim($line, TAB);
			// Only continue if it's not a comment
			if (substr($temp, 0, 2) != '--' && $temp != '') {
				// Add this line to the current segment
				$templine .= $line;
				// If it has a semicolon at the end, it's the end of the query
				if (substr(trim($line), -1, 1) == ';') {
					$newsql[] = trim($templine, TAB.LF);
					// Reset temp variable to empty
					$templine = '';
				} else {
					 $templine .= LF;
				}
			}
		}

		// 處理如果沒有以  ; 結尾時
		if (!count($newsql) && !empty($templine)) $newsql[] = trim($templine, TAB.LF);

		return $retarray ? $newsql : join(LF, $newsql);
	}

	function runquery($querysql, $auto_parsesql = FALSE) {
		// 判斷是否修正 table_prefix
		$auto_parsesql && $querysql = $this->parsesql($querysql);

		// 儲存此次執行的原始 SQL
		$this->last_runquery = $querysql;

		foreach($this->clear($querysql, true) as $sql) {
			if(trim($sql) == '') continue;

			if(strtoupper(substr($sql, 0, 12)) == 'CREATE TABLE') {
				$this->query($this->createtable($sql, $this->dbcharset));
			} else {
				$this->query($sql);
			}
		}
	}

	function tables_upgrade($updatesql) {

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

			} elseif ($tableinfo = $this->list_fields($table)) {

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
						$query .= "CHANGE `$field` `$field` {$tableinfo['Type']} " . ($tableinfo['Collation'] ?
							" CHARACTER SET {$this->dbcharset} COLLATE {$tableinfo['Collation']} " : "") . ($tableinfo['Null'] ?
							' NOT ' : '') . " NULL {$tableinfo['Extra']} " . ($tableinfo['Default'] != null ?
							" DEFAULT '{$tableinfo['Default']}' " : "") . " COMMENT '$sql' ";

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

	public function list_fields($table, $force = null) {
		static $result;

		if (($force !== null && $force) || !isset($result[$table])) {
			$force = ($force !== null) ? $force : null;
			$result[$table] = array();

			if ($this->version() > '4.1') {
				$query = $this->query('SHOW FULL COLUMNS FROM ' . $this->quote_table($table), '',
					$force);
			} else {
				$query = $this->query('SHOW COLUMNS FROM ' . $this->quote_table($table), '', $force);
			}

			foreach ($query as $row) {
				$column = $this->sql_type($row['Type']);

				$column['default'] = $row['Default'];
				$column['nullable'] = $row['Null'] === 'YES';
				$column['sequenced'] = $row['Extra'] === 'auto_increment';

				if (isset($column['length']) and $column['type'] === 'float') {
					list($column['precision'], $column['scale']) = explode(',', $column['length']);
				}

				if ($varList = array_diff_key($row, array_flip(array('Type', 'Default', 'Null',
					'Extra')))) {

					foreach ($varList as $k => $v) {
						$k = 'sql_' . lc($k);

						!isset($column[$k]) && $column[$k] = $v;
					}
				}

				$result[$row['Field']] = $column;
			}
		}

		return $result[$table];
	}

	function col_exists_chk($table, $col, $force = 0) {
		$chk = array_key_exists($col, $this->list_fields($table, $force)) ? 1 : 0;
		return $chk;
	}

	function get_charset($tablename) {
		$tablestruct = $this->fetch_first("show create table $tablename");
		preg_match("/CHARSET=(\w+)/", $tablestruct['Create Table'], $m);
		return $m[1];
	}

	/**
	 * check table exist
	 *
	 * @param string $table table name without table_prefix
	 * @param bool $nocache don't use cache
	 */
	function tables_exists($table, $nocache = false) {
		// Make sure the database is connected
		$this->connection or $thisdb->connect();
		static $tables;

		if (!$nocache && is_array($this->cache)) {
			$tables = $tables ? $tables : $this->list_tables();

			$exists = in_array($table, $tables);
		} else {
			if ($tables) unset($tables);

			$result = $this->query("SELECT 1 FROM `".$this->table_prefix()."$table` LIMIT 0", $this->connection);

			$exists = $result->result;
		}

		return ($exists) ? true : false;

		/*$tables = mysql_list_tables($this->connection);
		while ( list($temp) = mysql_fetch_array($tables) ) {
			if ($temp == $table) {
				return TRUE;
			}
		}
		return FALSE;*/
	}

	function tables_lock($tablesarray = '', $usetablepre = 1) {

		$sql = $t = $c = '';

		if ($usetablepre) $t = $this->table_prefix();

		foreach (array_unique($tablesarray) as $key => $value) {

			$sql .= $c.$t."{$value} WRITE";

			$c = ', ';
		}

		$this->query("LOCK TABLES $sql");

		return $this;
	}

	function tables_unlock () {
		$this->query("UNLOCK TABLES");

		return $this;
	}

	function query_result($query_string, $index = 0, $field = 0) {
		return $this->result($this->query($query_string), $index, $field);
	}

	function result($query, $row = 0, $field = 0) {
		$query = @$this->driver_core->result($query, $row, $field);
		return $query;
	}

	function result_first($sql, $field = 0) {
		return $this->result($this->query($sql), 0, $field);
	}
}

?>