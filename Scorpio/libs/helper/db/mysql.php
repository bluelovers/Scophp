<?php

/**
 * @author bluelovers
 * @copyright 2010
 */

if (0) {
	// for IDE
	class scodb_mysql extends Scorpio_helper_db_mysql_Core_ {
	}
}

class Scorpio_helper_db_mysql_Core_ {
	protected static $instances = null;

	protected $func_prefix = 'mysql_';

	public static function &instance() {
		$class = __CLASS__;

		if (!isset($this->$instances[$class])) {
			self::$instances[$class] = new $class;
		}

		return self::$instances[$class];
	}

	function __construct() {
		$class = get_class($this);

		self::$instances[$class] = &$this;

		return $this;
	}

	function &__exec($func, $args) {
		return call_user_func_array($this->func_prefix.$func, $args);
	}

	public static $connection;

	/**
	 * Get number of affected rows in previous MySQL operation
	 * Get the number of affected rows by the last INSERT, UPDATE, REPLACE or DELETE query associated with link_identifier
	 *
	 * @param resource $link_identifier
	 */
	function &affected_rows() {
		$args = func_get_args();

		return $this->__exec('affected_rows', $args);
	}

	/**
	 * Returns the name of the character set
	 *
	 * @param resource $link_identifier
	 */
	function &client_encoding() {
		$args = func_get_args();

		return $this->__exec('client_encoding', $args);
	}

	/**
	 * Close MySQL connection
	 *
	 * @param resource $link_identifier
	 */
	function &close() {
		$args = func_get_args();

		return $this->__exec('close', $args);
	}

	/**
	 * Open a connection to a MySQL Server
	 *
	 * @param string $server=ini_get("mysql.default_host")
	 * @param string $username=ini_get("mysql.default_user")
	 * @param string $password=ini_get("mysql.default_password")
	 * @param bool $new_link=false
	 * @param int $client_flags=0
	 *
	 * @return resource
	 */
	function &connect() {
		$args = func_get_args();

		return $this->__exec('connect', $args);
	}

	/**
	 * Create a MySQL database
	 *
	 * @param string $database_name
	 * @param [ resource $link_identifier ]
	 */
	function &create_db() {
		$args = func_get_args();

		return $this->__exec('create_db', $args);
	}

	/**
	 * Move internal result pointer
	 */
	function &data_seek() {
		$args = func_get_args();

		return $this->__exec('data_seek', $args);
	}

	/**
	 * Get result data
	 */
	function &db_name() {
		$args = func_get_args();

		return $this->__exec('db_name', $args);
	}

	/**
	 * Send a MySQL query
	 */
	function &db_query() {
		$args = func_get_args();

		return $this->__exec('db_query', $args);
	}

	/**
	 * Drop (delete) a MySQL database
	 */
	function &drop_db() {
		$args = func_get_args();

		return $this->__exec('drop_db', $args);
	}

	/**
	 * Returns the numerical value of the error message from previous MySQL operation
	 */
	function &errno() {
		$args = func_get_args();

		return $this->__exec('errno', $args);
	}

	/**
	 * Returns the text of the error message from previous MySQL operation
	 */
	function &error() {
		$args = func_get_args();

		return $this->__exec('error', $args);
	}

	/**
	 * Escapes a string for use in a $args = func_get_args();

		$this->__exec('query', $args);
	 */
	function &escape_string() {
		$args = func_get_args();

		return $this->__exec('escape_string', $args);
	}

	/**
	 * Fetch a result row as an associative array , a numeric array, or both
	 */
	function &fetch_array() {
		$args = func_get_args();

		return $this->__exec('fetch_array', $args);
	}

	/**
	 * Fetch a result row as an associative array
	 */
	function &fetch_assoc() {
		$args = func_get_args();

		return $this->__exec('fetch_assoc', $args);
	}

	/**
	 * Get column information from a result and return as an object
	 */
	function &fetch_field() {
		$args = func_get_args();

		return $this->__exec('fetch_field', $args);
	}

	/**
	 * Get the length of each output in a result
	 */
	function &fetch_lengths() {
		$args = func_get_args();

		return $this->__exec('fetch_lengths', $args);
	}

	/**
	 * Fetch a result row as an object
	 */
	function &fetch_object() {
		$args = func_get_args();

		return $this->__exec('fetch_object', $args);
	}

	/**
	 * Get a result row as an enumerated array
	 */
	function &fetch_row() {
		$args = func_get_args();

		return $this->__exec('fetch_row', $args);
	}

	/**
	 * Get the flags associated with the specified field in a result
	 */
	function &field_flags() {
		$args = func_get_args();

		return $this->__exec('field_flags', $args);
	}

	/**
	 * Returns the length of the specified field
	 */
	function &field_len() {
		$args = func_get_args();

		return $this->__exec('field_len', $args);
	}

	/**
	 * Get the name of the specified field in a result
	 */
	function &field_name() {
		$args = func_get_args();

		return $this->__exec('field_name', $args);
	}

	/**
	 * Set result pointer to a specified field offset
	 */
	function &field_seek() {
		$args = func_get_args();

		return $this->__exec('field_seek', $args);
	}

	/**
	 * Get name of the table the specified field is in
	 */
	function &field_table() {
		$args = func_get_args();

		return $this->__exec('field_table', $args);
	}

	/**
	 * Get the type of the specified field in a result
	 */
	function &field_type() {
		$args = func_get_args();

		return $this->__exec('field_type', $args);
	}

	/**
	 * Free result memory
	 */
	function &free_result() {
		$args = func_get_args();

		return $this->__exec('free_result', $args);
	}

	/**
	 * Get MySQL client info
	 */
	function &get_client_info() {
		$args = func_get_args();

		return $this->__exec('get_client_info', $args);
	}

	/**
	 * Get MySQL host info
	 */
	function &get_host_info() {
		$args = func_get_args();

		return $this->__exec('get_host_info', $args);
	}

	/**
	 * Get MySQL protocol info
	 */
	function &get_proto_info() {
		$args = func_get_args();

		return $this->__exec('get_proto_info', $args);
	}

	/**
	 * Get MySQL server info
	 */
	function &get_server_info() {
		$args = func_get_args();

		return $this->__exec('get_server_info', $args);
	}

	/**
	 * Get information about the most recent query
	 */
	function &info() {
		$args = func_get_args();

		return $this->__exec('info', $args);
	}

	/**
	 * Get the ID generated in the last query
	 */
	function &insert_id() {
		$args = func_get_args();

		return $this->__exec('insert_id', $args);
	}

	/**
	 * List databases available on a MySQL server
	 */
	function &list_dbs() {
		$args = func_get_args();

		return $this->__exec('list_dbs', $args);
	}
	/**
	 * List MySQL table fields
	 */
	function &list_fields() {
		$args = func_get_args();

		return $this->__exec('list_fields', $args);
	}

	/**
	 * List MySQL processes
	 */
	function &list_processes() {
		$args = func_get_args();

		return $this->__exec('list_processes', $args);
	}

	/**
	 * List tables in a MySQL database
	 */
	function &list_tables() {
		$args = func_get_args();

		return $this->__exec('list_tables', $args);
	}

	/**
	 * Get number of fields in result
	 */
	function &num_fields() {
		$args = func_get_args();

		return $this->__exec('num_fields', $args);
	}

	/**
	 * Get number of rows in result
	 */
	function &num_rows() {
		$args = func_get_args();

		return $this->__exec('num_rows', $args);
	}

	/**
	 * Open a persistent connection to a MySQL server
	 */
	function &pconnect() {
		$args = func_get_args();

		return $this->__exec('pconnect', $args);
	}

	/**
	 * Ping a server connection or reconnect if there is no connection
	 */
	function &ping() {
		$args = func_get_args();

		return $this->__exec('ping', $args);
	}

	/**
	 * Send a MySQL query
	 */
	function &query() {
		$args = func_get_args();

		return $this->__exec('query', $args);
	}

	/**
	 * Escapes special characters in a string for use in an SQL statement
	 */
	function &real_escape_string() {
		$args = func_get_args();

		return $this->__exec('real_escape_string', $args);
	}

	/**
	 * Get result data
	 */
	function &result() {
		$args = func_get_args();

		return $this->__exec('result', $args);
	}

	/**
	 * Select a MySQL database
	 */
	function &select_db() {
		$args = func_get_args();

		return $this->__exec('select_db', $args);
	}

	/**
	 * Sets the client character set
	 */
	function &set_charset() {
		$args = func_get_args();

		return $this->__exec('set_charset', $args);
	}

	/**
	 * Get current system status
	 */
	function &stat() {
		$args = func_get_args();

		return $this->__exec('stat', $args);
	}

	/**
	 * Get table name of field
	 */
	function &tablename() {
		$args = func_get_args();

		return $this->__exec('tablename', $args);
	}

	/**
	 * Return the current thread ID
	 */
	function &thread_id() {
		$args = func_get_args();

		return $this->__exec('thread_id', $args);
	}

	/**
	 * Send an SQL query to MySQL without fetching and buffering the result rows.
	 */
	function &unbuffered_query() {
		$args = func_get_args();

		return $this->__exec('unbuffered_query', $args);
	}
}

?>