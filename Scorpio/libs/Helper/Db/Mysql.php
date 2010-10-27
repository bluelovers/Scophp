<?php

/**
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
	class scodb_mysql extends Scorpio_Helper_Db_Mysql_Core {
	}
}

class Scorpio_Helper_Db_Mysql_Core {
	protected static $instances = null;

	protected static $func_prefix = 'mysql_';

	public static function &instance() {
		return static::$instances;
	}

	function __construct() {
	}

	function &__exec($func, $args) {
//		$args = func_get_args();
//		array_shift($args);

		return call_user_func_array(static::$func_prefix.$func, $args);
	}

	/**
	 * Get number of affected rows in previous MySQL operation
	 */
	function &affected_rows() {
		$args = func_get_args();

		return static::__exec('affected_rows', $args);
	}
	/**
	 * Returns the name of the character set
	 */
	function &client_encoding() {
		$args = func_get_args();

		return static::__exec('client_encoding', $args);
	}
	/**
	 * Close MySQL connection
	 */
	function &close() {
		$args = func_get_args();

		return static::__exec('close', $args);
	}
	/**
	 * Open a connection to a MySQL Server
	 */
	function &connect() {
		$args = func_get_args();

		return static::__exec('connect', $args);
	}
	/**
	 * Create a MySQL database
	 */
	function &create_db() {
		$args = func_get_args();

		return static::__exec('create_db', $args);
	}
	/**
	 * Move internal result pointer
	 */
	function &data_seek() {
		$args = func_get_args();

		return static::__exec('data_seek', $args);
	}
	/**
	 * Get result data
	 */
	function &db_name() {
		$args = func_get_args();

		return static::__exec('db_name', $args);
	}
	/**
	 * Send a MySQL query
	 */
	function &db_query() {
		$args = func_get_args();

		return static::__exec('db_query', $args);
	}
	/**
	 * Drop (delete) a MySQL database
	 */
	function &drop_db() {
		$args = func_get_args();

		return static::__exec('drop_db', $args);
	}
	/**
	 * Returns the numerical value of the error message from previous MySQL operation
	 */
	function &errno() {
		$args = func_get_args();

		return static::__exec('errno', $args);
	}
	/**
	 * Returns the text of the error message from previous MySQL operation
	 */
	function &error() {
		$args = func_get_args();

		return static::__exec('error', $args);
	}
	/**
	 * Escapes a string for use in a $args = func_get_args();

		static::__exec('query', $args);
	 */
	function &escape_string() {
		$args = func_get_args();

		return static::__exec('escape_string', $args);
	}
	/**
	 * Fetch a result row as an associative array , a numeric array, or both
	 */
	function &fetch_array() {
		$args = func_get_args();

		return static::__exec('fetch_array', $args);
	}
	/**
	 * Fetch a result row as an associative array
	 */
	function &fetch_assoc() {
		$args = func_get_args();

		return static::__exec('fetch_assoc', $args);
	}
	/**
	 * Get column information from a result and return as an object
	 */
	function &fetch_field() {
		$args = func_get_args();

		return static::__exec('fetch_field', $args);
	}
	/**
	 * Get the length of each output in a result
	 */
	function &fetch_lengths() {
		$args = func_get_args();

		return static::__exec('fetch_lengths', $args);
	}
	/**
	 * Fetch a result row as an object
	 */
	function &fetch_object() {
		$args = func_get_args();

		return static::__exec('fetch_object', $args);
	}
	/**
	 * Get a result row as an enumerated array
	 */
	function &fetch_row() {
		$args = func_get_args();

		return static::__exec('fetch_row', $args);
	}
	/**
	 * Get the flags associated with the specified field in a result
	 */
	function &field_flags() {
		$args = func_get_args();

		return static::__exec('field_flags', $args);
	}
	/**
	 * Returns the length of the specified field
	 */
	function &field_len() {
		$args = func_get_args();

		return static::__exec('field_len', $args);
	}
	/**
	 * Get the name of the specified field in a result
	 */
	function &field_name() {
		$args = func_get_args();

		return static::__exec('field_name', $args);
	}
	/**
	 * Set result pointer to a specified field offset
	 */
	function &field_seek() {
		$args = func_get_args();

		return static::__exec('field_seek', $args);
	}
	/**
	 * Get name of the table the specified field is in
	 */
	function &field_table() {
		$args = func_get_args();

		return static::__exec('field_table', $args);
	}
	/**
	 * Get the type of the specified field in a result
	 */
	function &field_type() {
		$args = func_get_args();

		return static::__exec('field_type', $args);
	}
	/**
	 * Free result memory
	 */
	function &free_result() {
		$args = func_get_args();

		return static::__exec('free_result', $args);
	}
	/**
	 * Get MySQL client info
	 */
	function &get_client_info() {
		$args = func_get_args();

		return static::__exec('get_client_info', $args);
	}
	/**
	 * Get MySQL host info
	 */
	function &get_host_info() {
		$args = func_get_args();

		return static::__exec('get_host_info', $args);
	}
	/**
	 * Get MySQL protocol info
	 */
	function &get_proto_info() {
		$args = func_get_args();

		return static::__exec('get_proto_info', $args);
	}
	/**
	 * Get MySQL server info
	 */
	function &get_server_info() {
		$args = func_get_args();

		return static::__exec('get_server_info', $args);
	}
	/**
	 * Get information about the most recent query
	 */
	function &info() {
		$args = func_get_args();

		return static::__exec('info', $args);
	}
	/**
	 * Get the ID generated in the last query
	 */
	function &insert_id() {
		$args = func_get_args();

		return static::__exec('insert_id', $args);
	}
	/**
	 * List databases available on a MySQL server
	 */
	function &list_dbs() {
		$args = func_get_args();

		return static::__exec('list_dbs', $args);
	}
	/**
	 * List MySQL table fields
	 */
	function &list_fields() {
		$args = func_get_args();

		return static::__exec('list_fields', $args);
	}
	/**
	 * List MySQL processes
	 */
	function &list_processes() {
		$args = func_get_args();

		return static::__exec('list_processes', $args);
	}
	/**
	 * List tables in a MySQL database
	 */
	function &list_tables() {
		$args = func_get_args();

		return static::__exec('list_tables', $args);
	}
	/**
	 * Get number of fields in result
	 */
	function &num_fields() {
		$args = func_get_args();

		return static::__exec('num_fields', $args);
	}
	/**
	 * Get number of rows in result
	 */
	function &num_rows() {
		$args = func_get_args();

		return static::__exec('num_rows', $args);
	}
	/**
	 * Open a persistent connection to a MySQL server
	 */
	function &pconnect() {
		$args = func_get_args();

		return static::__exec('pconnect', $args);
	}
	/**
	 * Ping a server connection or reconnect if there is no connection
	 */
	function &ping() {
		$args = func_get_args();

		return static::__exec('ping', $args);
	}
	/**
	 * Send a MySQL query
	 */
	function &query() {
		$args = func_get_args();

		return static::__exec('query', $args);
	}
	/**
	 * Escapes special characters in a string for use in an SQL statement
	 */
	function &real_escape_string() {
		$args = func_get_args();

		return static::__exec('real_escape_string', $args);
	}
	/**
	 * Get result data
	 */
	function &result() {
		$args = func_get_args();

		return static::__exec('result', $args);
	}
	/**
	 * Select a MySQL database
	 */
	function &select_db() {
		$args = func_get_args();

		return static::__exec('select_db', $args);
	}
	/**
	 * Sets the client character set
	 */
	function &set_charset() {
		$args = func_get_args();

		return static::__exec('set_charset', $args);
	}
	/**
	 * Get current system status
	 */
	function &stat() {
		$args = func_get_args();

		return static::__exec('stat', $args);
	}
	/**
	 * Get table name of field
	 */
	function &tablename() {
		$args = func_get_args();

		return static::__exec('tablename', $args);
	}
	/**
	 * Return the current thread ID
	 */
	function &thread_id() {
		$args = func_get_args();

		return static::__exec('thread_id', $args);
	}
	/**
	 * Send an SQL query to MySQL without fetching and buffering the result rows.
	 */
	function &unbuffered_query() {
		$args = func_get_args();

		return static::__exec('unbuffered_query', $args);
	}
}

?>