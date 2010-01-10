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
	class Scrpio_Db extends Scrpio_Db_Core {
	}
}

/**
 * @package Scrpio_Db
 */
class Scrpio_Db_Core {

	const SELECT = 1;
	const INSERT = 2;
	const UPDATE = 3;
	const DELETE = 4;
	const CROSS_REQUEST = 5;
	const PER_REQUEST = 6;

	const ALTER = 11;

	protected static $instances = array();

	protected $last_query = null;
	protected $last_runquery = null;

	protected $last_result = null;

	// Raw server connection
	protected $connection = null;

	// Cache (Cache object for cross-request, array for per-request)
	protected $cache;

	// Quote character to use for identifiers (tables/columns/aliases)
	protected $quote = '"';

	/**
	 * Returns a singleton instance of Database.
	 *
	 * @param   string  Database name
	 * @return  Database_Core
	 */
	public static function instance($name = 'default') {
		if (!isset(Scrpio_Db::$instances[$name])) {
			// Load the configuration for this database group
			$config = call_user_func(array(__class__, 'config'), $name);

			if (is_string($config['connection'])) {
				// Parse the DSN into connection array
				$config['connection'] = Scrpio_Db::parse_dsn($config['connection']);
			}

			// Set the driver class name
			$driver = 'Database_' . ucfirst($config['connection']['type']);

			// Create the database connection instance
			Scrpio_Db::$instances[$name] = new $driver($config);
		}

		return Scrpio_Db::$instances[$name];
	}

	public static function config($name = 'default', $attr = null) {
		return Scrpio_Base::config('database.' . $name, $attr);
	}

	/**
	 * Executes the given query, returning the cached version if enabled
	 *
	 * @param  string  SQL query
	 * @return Database_Result
	 */
	public function query($sql, $type = '', $force = null) {
		// Start the benchmark
		$start = microtime(true);

		if (!($force !== null && $force) && is_array($this->cache)) {
			$hash = $this->query_hash($sql);

			if (isset($this->cache[$hash])) {
				// Use cached result
				$result = $this->cache[$hash];

				// It's from cache
				$sql .= ' [CACHE]';
			} else {
				// No cache, execute query and store in cache
				$result = $this->cache[$hash] = $this->query_execute($sql, $type);
			}
		} else {
			// Execute the query, cache is off
			$result = $this->query_execute($sql, $type);
		}

		// Stop the benchmark
		$stop = microtime(true);

		if ($this->config['benchmark'] === true) {
			// Benchmark the query
			Scrpio_Db::$benchmarks[] = array('query' => $sql, 'time' => $stop - $start,
				'rows' => count($result));
		}

		return $result;
	}

	/**
	 * Generates a hash for the given query
	 *
	 * @param   string  SQL query string
	 * @return  string
	 */
	protected function query_hash($sql) {
		return sha1(str_replace("\n", ' ', trim($sql)));
	}

	/**
	 * Converts the given DSN string to an array of database connection components
	 *
	 * @param  string  DSN string
	 * @return array
	 */
	public static function parse_dsn($dsn) {
		$db = array('type' => false, 'user' => false, 'pass' => false, 'host' => false,
			'port' => false, 'socket' => false, 'database' => false);

		// Get the protocol and arguments
		list($db['type'], $connection) = explode('://', $dsn, 2);

		if ($connection[0] === '/') {
			// Strip leading slash
			$db['database'] = substr($connection, 1);
		} else {
			$connection = parse_url('http://' . $connection);

			if (isset($connection['user'])) {
				$db['user'] = $connection['user'];
			}

			if (isset($connection['pass'])) {
				$db['pass'] = $connection['pass'];
			}

			if (isset($connection['port'])) {
				$db['port'] = $connection['port'];
			}

			if (isset($connection['host'])) {
				if ($connection['host'] === 'unix(') {
					list($db['socket'], $connection['path']) = explode(')', $connection['path'], 2);
				} else {
					$db['host'] = $connection['host'];
				}
			}

			if (isset($connection['path']) and $connection['path']) {
				// Strip leading slash
				$db['database'] = substr($connection['path'], 1);
			}
		}

		return $db;
	}

	/**
	 * Constructs a new Database object
	 *
	 * @param   array  Database config array
	 * @return  Database_Core
	 */
	protected function __construct(array $config) {
		// Store the config locally
		$this->config = $config;

		if ($this->config['cache'] !== false) {
			if (is_string($this->config['cache'])) {
				// Use Cache library
				$this->cache = new Scrpio_Cache($this->config['cache']);
			} elseif ($this->config['cache'] === true) {
				// Use array
				$this->cache = array();
			}
		}

		register_shutdown_function(array($this, '__destruct'));
	}

	public function __destruct() {
		$this->disconnect();
	}

	/**
	 * Quotes the given value
	 *
	 * @param   mixed  value
	 * @return  mixed
	 */
	public function quote($value) {
		if (!$this->config['escape'])
			return $value;

		if ($value === null) {
			return 'NULL';
		} elseif ($value === true) {
			return 'TRUE';
		} elseif ($value === false) {
			return 'FALSE';
		} elseif (is_int($value)) {
			return (int)$value;
		} elseif ($value instanceof Scrpio_Db_Exception) {
			return (string )$value;
		}

		return '\'' . $this->escape($value) . '\'';
	}

	/**
	 * Get the table prefix
	 *
	 * @param  string  Optional new table prefix to set
	 * @return string
	 */
	public function table_prefix($new_prefix = null) {
		if ($new_prefix !== null) {
			// Set a new prefix
			$this->config['table_prefix'] = $new_prefix;
		}

		return $this->config['table_prefix'];
	}

	/**
	 * Fetches SQL type information about a field, in a generic format.
	 *
	 * @param   string  field datatype
	 * @return  array
	 */
	protected function sql_type($str) {
		static $sql_types;

		if ($sql_types === null) {
			// Load SQL data types
			$sql_types = Scrpio_Base::config('sql_types');
		}

		$str = trim($str);

		if (($open = strpos($str, '(')) !== false) {
			// Closing bracket
			$close = strpos($str, ')', $open);

			// Length without brackets
			$length = substr($str, $open + 1, $close - 1 - $open);

			// Type without the length
			$type = substr($str, 0, $open) . substr($str, $close + 1);
		} else {
			// No length
			$type = $str;
		}

		if (empty($sql_types[$type]))
			throw new Scrpio_Db_Exception('Undefined field type :type', array(':type' => $str));

		// Fetch the field definition
		$field = $sql_types[$type];

		$field['sql_type'] = $type;

		if (isset($length)) {
			// Add the length to the field info
			$field['length'] = $length;
		}

		return $field;
	}

	function parsesql($queries, $do = 1) {
//		if ($do < 0)
//			return str_replace(array(' ' . $this->tablepre, ' ' . $this->quote . $this->
//				tablepre), array(' [Table]', ' ' . $this->quote . '[Table]'), $queries);
//
//		return $do ? str_replace("\r", "\n", str_replace(array(' cdb_', ' {tablepre}',
//			' ' . $this->quote . '{tablepre}', ' ' . $this->quote . 'cdb_'), array(' ' . $this->
//			tablepre, ' ' . $this->tablepre, ' ' . $this->quote . $this->tablepre, ' ' . $this->
//			quote . $this->tablepre), $queries)) : $queries;

		if ($do < 0) {
			return preg_replace('/(?<=\bFROM|\bUPDATE|\bINSERT INTO|\bDELETE FROM|\bJOIN|\bTABLE|\bTABLES|\b,)\s+(('.preg_quote($this->quote, '/').')?('.preg_quote($this->tablepre, '/').')([_a-z0-9]+))\\2([\s\b])/i', ' '.$this->quote.'[Table]'.'\\4'.$this->quote.'\\5', $queries);
		} elseif ($do) {
			return preg_replace('/(?<=\bFROM|\bUPDATE|\bINSERT INTO|\bDELETE FROM|\bJOIN|\bTABLE|\bTABLES|\b,)\s+(('.preg_quote($this->quote, '/').')?(cdb_|\{tablepre\})([_a-z0-9]+))\\2([\s\b])/i', ' '.$this->quote.$this->tablepre.'\\4'.$this->quote.'\\5', $queries);
		} else {
			return $queries;
		}
/*
$queries = <<<EOM
UPDATE cdb_post
INSERT INTO `cdb_post`
from cdb_post
DELETE FROM `cdb_post `


EOM
;

//UPDATE `[TABLE]post`
//INSERT INTO `[TABLE]post`
//from `[TABLE]post`
//DELETE FROM `cdb_post `
*/
	}

	/**
	 * Returns the last executed query for this database
	 *
	 * @return string
	 */
	public function last_query() {
		return $this->parsesql($this->last_query, -1);
	}

	/**
	 * Returns the last executed runquery for this database
	 *
	 * @return string
	 */
	public function last_runquery() {
		return $this->parsesql($this->last_runquery, -1);
	}

	protected function _result($result, $sql) {
		return new Scrpio_Db_Result($result, $sql, &$this);
	}

}

?>