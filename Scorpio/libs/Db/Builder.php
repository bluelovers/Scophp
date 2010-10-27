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
	class Scorpio_Db_Builder extends Scorpio_Db_Builder_Core {}
}

class Scorpio_Db_Builder_Core {

	// Valid ORDER BY directions
	protected $order_directions = array('ASC', 'DESC', 'RAND()');

	// Scorpio_Db object
	protected $db;

	// Builder members
	protected $select = array();
	protected $from = array();
	protected $join = array();
	protected $where = array();
	protected $group_by = array();
	protected $having = array();
	protected $order_by = array();
	protected $limit = null;
	protected $offset = null;
	protected $set = array();
	protected $columns = array();
	protected $values = array();
	protected $type;
	protected $distinct = false;

	// TTL for caching (using Cache library)
	protected $ttl = false;

	public function __construct($db = 'default') {
		$this->db = $db;
	}

	/**
	 * Resets all query components
	 */
	public function reset() {
		$this->select = array();
		$this->from = array();
		$this->join = array();
		$this->where = array();
		$this->group_by = array();
		$this->having = array();
		$this->order_by = array();
		$this->limit = null;
		$this->offset = null;
		$this->set = array();
		$this->values = array();
	}

	public function __toString() {
		return $this->compile();
	}

	/**
	 * Compiles the builder object into a SQL query
	 *
	 * @return string  Compiled query
	 */
	protected function compile() {
		if (!is_object($this->db)) {
			// Use default database for compiling to string if none is given
			$this->db = Scorpio_Db::instance($this->db);
		}

		if ($this->type === Scorpio_Db::SELECT) {
			// SELECT columns FROM table
			$sql = $this->distinct ? 'SELECT DISTINCT ' : 'SELECT ';
			$sql .= $this->compile_select();

			if (!empty($this->from)) {
				$sql .= "\nFROM " . $this->compile_from();
			}
		} elseif ($this->type === Scorpio_Db::UPDATE) {
			$sql = 'UPDATE ' . $this->compile_from() . "\n" . 'SET ' . $this->compile_set();
		} elseif ($this->type === Scorpio_Db::INSERT) {
			$sql = 'INSERT INTO ' . $this->compile_from() . "\n" . $this->compile_columns() . "\nVALUES " . $this->compile_values();
		} elseif ($this->type === Scorpio_Db::DELETE) {
			$sql = 'DELETE FROM ' . $this->compile_from();
		}

		if (!empty($this->join)) {
			$sql .= $this->compile_join();
		}

		if (!empty($this->where)) {
			$sql .= "\n" . 'WHERE ' . $this->compile_conditions($this->where);
		}

		if (!empty($this->having)) {
			$sql .= "\n" . 'HAVING ' . $this->compile_conditions($this->having);
		}

		if (!empty($this->group_by)) {
			$sql .= "\n" . 'GROUP BY ' . $this->compile_group_by();
		}

		if (!empty($this->order_by)) {
			$sql .= "\nORDER BY " . $this->compile_order_by();
		}

		if (is_int($this->limit)) {
			$sql .= "\nLIMIT " . $this->limit;
		}

		if (is_int($this->offset)) {
			$sql .= "\nOFFSET " . $this->offset;
		}

		return $sql;
	}

	/**
	 * Compiles the SELECT portion of the query
	 *
	 * @return string
	 */
	protected function compile_select() {
		$vals = array();

		foreach ($this->select as $alias => $name) {
			if ($name instanceof Scorpio_Db_Builder) {
				// Using a subquery
				$name->db = $this->db;
				$vals[] = '(' . (string )$name . ') AS ' . $this->db->quote_column($alias);
			} elseif (is_string($alias)) {
				$vals[] = $this->db->quote_column($name, $alias);
			} else {
				$vals[] = $this->db->quote_column($name);
			}
		}

		return implode(', ', $vals);
	}

	/**
	 * Compiles the FROM portion of the query
	 *
	 * @return string
	 */
	protected function compile_from() {
		$vals = array();

		foreach ($this->from as $alias => $name) {
			if (is_string($alias)) {
				// Using AS format so escape both
				$vals[] = $this->db->quote_table($name, $alias);
			} else {
				// Just using the table name itself
				$vals[] = $this->db->quote_table($name);
			}
		}

		return implode(', ', $vals);
	}

	/**
	 * Compiles the JOIN portion of the query
	 *
	 * @return string
	 */
	protected function compile_join() {
		$sql = '';
		foreach ($this->join as $join) {
			list($table, $keys, $type) = $join;

			if ($type !== null) {
				// Join type
				$sql .= ' ' . $type;
			}

			$sql .= ' JOIN ' . $this->db->quote_table($table);

			$condition = '';
			if ($keys instanceof Scorpio_Db_Exception) {
				$condition = (string )$keys;
			} elseif (is_array($keys)) {
				// ON condition is an array of matches
				foreach ($keys as $key => $value) {
					if (!empty($condition)) {
						$condition .= ' AND ';
					}

					$condition .= $this->db->quote_column($key) . ' = ' . $this->db->quote_column($value);
				}
			}

			if (!empty($condition)) {
				// Add ON condition
				$sql .= ' ON (' . $condition . ')';
			}
		}

		return $sql;
	}

	/**
	 * Compiles the GROUP BY portion of the query
	 *
	 * @return string
	 */
	protected function compile_group_by() {
		$vals = array();

		foreach ($this->group_by as $column) {
			// Escape the column
			$vals[] = $this->db->quote_column($column);
		}

		return implode(', ', $vals);
	}

	/**
	 * Compiles the ORDER BY portion of the query
	 *
	 * @return string
	 */
	public function compile_order_by() {
		$ordering = array();

		foreach ($this->order_by as $column => $order_by) {
			list($column, $direction) = each($order_by);

			$column = $this->db->quote_column($column);

			if ($direction !== null) {
				$direction = ' ' . $direction;
			}

			$ordering[] = $column . $direction;
		}

		return implode(', ', $ordering);
	}

	/**
	 * Compiles the SET portion of the query for UPDATE
	 *
	 * @return string
	 */
	public function compile_set() {
		$vals = array();

		foreach ($this->set as $key => $value) {
			// Using an UPDATE so Key = Val
			$vals[] = $this->db->quote_column($key) . ' = ' . $this->db->quote($value);
		}

		return implode(', ', $vals);
	}

	/**
	 * Join tables to the builder
	 *
	 * @param  mixed   Table name
	 * @param  mixed   Key, or an array of key => value pair, for join condition (can be a Scorpio_Db_Exception)
	 * @param  mixed   Value if $keys is not an array or Scorpio_Db_Exception
	 * @param  string  Join type (LEFT, RIGHT, INNER, etc.)
	 * @return Scorpio_Db_Builder
	 */
	public function join($table, $keys, $value = null, $type = null) {
		if (is_string($keys)) {
			$keys = array($keys => $value);
		}

		if ($type !== null) {
			$type = strtoupper($type);
		}

		$this->join[] = array($table, $keys, $type);

		return $this;
	}

	/**
	 * Add tables to the FROM portion of the builder
	 *
	 * @param   string|array    table name or array(alias => table)
	 * @return  Scorpio_Db_Builder
	 */
	public function from($tables) {
		if (!is_array($tables)) {
			$tables = func_get_args();
		}

		$this->from = array_merge($this->from, $tables);

		return $this;
	}

	/**
	 * Add fields to the GROUP BY portion
	 *
	 * @param  mixed  Field names or an array of fields
	 * @return Scorpio_Db_Builder
	 */
	public function group_by($columns) {
		if (!is_array($columns)) {
			$columns = func_get_args();
		}

		$this->group_by = array_merge($this->group_by, $columns);

		return $this;
	}

	/**
	 * Add conditions to the HAVING clause (AND)
	 *
	 * @param  mixed   Column name or array of columns => vals
	 * @param  string  Operation to perform
	 * @param  mixed   Value
	 * @return Scorpio_Db_Builder
	 */
	public function having($columns, $op = '=', $value = null) {
		return $this->and_having($columns, $op, $value);
	}

	/**
	 * Add conditions to the HAVING clause (AND)
	 *
	 * @param  mixed   Column name or array of triplets
	 * @param  string  Operation to perform
	 * @param  mixed   Value
	 * @return Scorpio_Db_Builder
	 */
	public function and_having($columns, $op = '=', $value = null) {
		if (is_array($columns)) {
			foreach ($columns as $column) {
				$this->having[] = array('AND' => $column);
			}
		} else {
			$this->having[] = array('AND' => array($columns, $op, $value));
		}
		return $this;
	}

	/**
	 * Add conditions to the HAVING clause (OR)
	 *
	 * @param  mixed   Column name or array of triplets
	 * @param  string  Operation to perform
	 * @param  mixed   Value
	 * @return Scorpio_Db_Builder
	 */
	public function or_having($columns, $op = '=', $value = null) {
		if (is_array($columns)) {
			foreach ($columns as $column) {
				$this->having[] = array('OR' => $column);
			}
		} else {
			$this->having[] = array('OR' => array($columns, $op, $value));
		}
		return $this;
	}

	/**
	 * Add fields to the ORDER BY portion
	 *
	 * @param  mixed   Field names or an array of fields (field => direction)
	 * @param  string  Direction or NULL for ascending
	 * @return Scorpio_Db_Builder
	 */
	public function order_by($columns, $direction = null) {
		if (is_array($columns)) {
			foreach ($columns as $column => $direction) {
				if (is_string($column)) {
					$this->order_by[] = array($column => $direction);
				} else {
					// $direction is the column name when the array key is numeric
					$this->order_by[] = array($direction => null);
				}
			}
		} else {
			$this->order_by[] = array($columns => $direction);
		}
		return $this;
	}

	/**
	 * Limit rows returned
	 *
	 * @param  int  Number of rows
	 * @return Scorpio_Db_Builder
	 */
	public function limit($number) {
		$this->limit = (int)$number;

		return $this;
	}

	/**
	 * Offset into result set
	 *
	 * @param  int  Offset
	 * @return Scorpio_Db_Builder
	 */
	public function offset($number) {
		$this->offset = (int)$number;

		return $this;
	}

	public function left_join($table, $keys, $value = null) {
		return $this->join($table, $keys, $value, 'LEFT');
	}

	public function right_join($table, $keys, $value = null) {
		return $this->join($table, $keys, $value, 'RIGHT');
	}

	public function inner_join($table, $keys, $value = null) {
		return $this->join($table, $keys, $value, 'INNER');
	}

	public function outer_join($table, $keys, $value = null) {
		return $this->join($table, $keys, $value, 'OUTER');
	}

	public function full_join($table, $keys, $value = null) {
		return $this->join($table, $keys, $value, 'FULL');
	}

	public function left_inner_join($table, $keys, $value = null) {
		return $this->join($table, $keys, $value, 'LEFT INNER');
	}

	public function right_inner_join($table, $keys, $value = null) {
		return $this->join($table, $keys, $value, 'RIGHT INNER');
	}

	public function open($clause = 'WHERE') {
		return $this->and_open($clause);
	}

	public function and_open($clause = 'WHERE') {
		if ($clause === 'WHERE') {
			$this->where[] = array('AND' => '(');
		} else {
			$this->having[] = array('AND' => '(');
		}

		return $this;
	}

	public function or_open($clause = 'WHERE') {
		if ($clause === 'WHERE') {
			$this->where[] = array('OR' => '(');
		} else {
			$this->having[] = array('OR' => '(');
		}

		return $this;
	}

	public function close($clause = 'WHERE') {
		if ($clause === 'WHERE') {
			$this->where[] = array(')');
		} else {
			$this->having[] = array(')');
		}

		return $this;
	}

	/**
	 * Add conditions to the WHERE clause (AND)
	 *
	 * @param  mixed   Column name or array of columns => vals
	 * @param  string  Operation to perform
	 * @param  mixed   Value
	 * @return Scorpio_Db_Builder
	 */
	public function where($columns, $op = '=', $value = null) {
		return $this->and_where($columns, $op, $value);
	}

	/**
	 * Add conditions to the WHERE clause (AND)
	 *
	 * @param  mixed   Column name or array of triplets
	 * @param  string  Operation to perform
	 * @param  mixed   Value
	 * @return Scorpio_Db_Builder
	 */
	public function and_where($columns, $op = '=', $value = null) {
		if (is_array($columns)) {
			foreach ($columns as $column) {
				$this->where[] = array('AND' => $column);
			}
		} else {
			$this->where[] = array('AND' => array($columns, $op, $value));
		}
		return $this;
	}

	/**
	 * Add conditions to the WHERE clause (OR)
	 *
	 * @param  mixed   Column name or array of triplets
	 * @param  string  Operation to perform
	 * @param  mixed   Value
	 * @return Scorpio_Db_Builder
	 */
	public function or_where($columns, $op = '=', $value = null) {
		if (is_array($columns)) {
			foreach ($columns as $column) {
				$this->where[] = array('OR' => $column);
			}
		} else {
			$this->where[] = array('OR' => array($columns, $op, $value));
		}
		return $this;
	}

	/**
	 * Compiles the given clause's conditions
	 *
	 * @param  array  Clause conditions
	 * @return string
	 */
	protected function compile_conditions($groups) {
		$last_condition = null;

		$sql = '';
		foreach ($groups as $group) {
			// Process groups of conditions
			foreach ($group as $logic => $condition) {
				if ($condition === '(') {
					if (!empty($sql) and $last_condition !== '(') {
						// Include logic operator
						$sql .= ' ' . $logic . ' ';
					}

					$sql .= '(';
				} elseif ($condition === ')') {
					$sql .= ')';
				} else {
					list($columns, $op, $value) = $condition;

					// Stores each individual condition
					$vals = array();

					if ($columns instanceof Scorpio_Db_Exception) {
						// Add directly to condition list
						$vals[] = (string )$columns;
					} else {
						$op = strtoupper($op);

						if (!is_array($columns)) {
							$columns = array($columns => $value);
						}

						foreach ($columns as $column => $value) {
							if ($value instanceof Scorpio_Db_Builder) {
								// Using a subquery
								$value->db = $this->db;
								$value = '(' . (string )$value . ')';
							} elseif (is_array($value)) {
								if ($op === 'BETWEEN' or $op === 'NOT BETWEEN') {
									// Falls between two values
									$value = $this->db->quote($value[0]) . ' AND ' . $this->db->quote($value[1]);
								} else {
									// Return as list
									$value = array_map(array($this->db, 'quote'), $value);
									$value = '(' . implode(', ', $value) . ')';
								}
							} else {
								$value = $this->db->quote($value);
							}

							if (!empty($column)) {
								// Ignore blank columns
								$column = $this->db->quote_column($column);
							}

							// Add to condition list
							$vals[] = $column . ' ' . $op . ' ' . $value;
						}
					}

					if (!empty($sql) and $last_condition !== '(') {
						// Add the logic operator
						$sql .= ' ' . $logic . ' ';
					}

					// Join the condition list items together by the given logic operator
					$sql .= implode(' ' . $logic . ' ', $vals);
				}

				$last_condition = $condition;
			}
		}

		return $sql;
	}

	/**
	 * Set values for UPDATE
	 *
	 * @param  mixed   Column name or array of columns => vals
	 * @param  mixed   Value (can be a Scorpio_Db_Exception)
	 * @return Scorpio_Db_Builder
	 */
	public function set($keys, $value = null) {
		if (is_string($keys)) {
			$keys = array($keys => $value);
		}

		$this->set = array_merge($keys, $this->set);

		return $this;
	}

	/**
	 * Columns used for INSERT queries
	 *
	 * @param  array  Columns
	 * @return Scorpio_Db_Builder
	 */
	public function columns($columns) {
		if (!is_array($columns)) {
			$columns = func_get_args();
		}

		$this->columns = $columns;

		return $this;
	}

	/**
	 * Compiles the columns portion of the query for INSERT
	 *
	 * @return string
	 */
	protected function compile_columns() {
		return '(' . implode(', ', array_map(array($this->db, 'quote_column'), $this->columns)) . ')';
	}

	/**
	 * Values used for INSERT queries
	 *
	 * @param  array  Values
	 * @return Scorpio_Db_Builder
	 */
	public function values($values) {
		if (!is_array($values)) {
			$values = func_get_args();
		}

		$this->values[] = $values;

		return $this;
	}

	/**
	 * Compiles the VALUES portion of the query for INSERT
	 *
	 * @return string
	 */
	protected function compile_values() {
		$values = array();
		foreach ($this->values as $group) {
			// Each set of values to be inserted
			$values[] = '(' . implode(', ', array_map(array($this->db, 'quote'), $group)) . ')';
		}

		return implode(', ', $values);
	}

	/**
	 * Create a SELECT query and specify selected columns
	 *
	 * @param   string|array    column name or array(alias => column)
	 * @return  Scorpio_Db_Builder
	 */
	public function select($columns = null) {
		$this->type = Scorpio_Db::SELECT;

		if ($columns === null) {
			$columns = array('*');
		} elseif (!is_array($columns)) {
			$columns = func_get_args();
		}

		$this->select = array_merge($this->select, $columns);

		return $this;
	}

	/**
	 * Create a SELECT query and specify selected columns
	 *
	 * @param   string|array    column name or array(alias => column)
	 * @return  Scorpio_Db_Builder
	 */
	public function select_distinct($columns = null) {
		$this->select($columns);
		$this->distinct = true;
		return $this;
	}

	/**
	 * Create an UPDATE query
	 *
	 * @param  string  Table name
	 * @param  array   Array of Keys => Values
	 * @param  array   WHERE conditions
	 * @return Scorpio_Db_Builder
	 */
	public function update($table = null, $set = null, $where = null) {
		$this->type = Scorpio_Db::UPDATE;

		if (is_array($set)) {
			$this->set($set);
		}

		if ($where !== null) {
			$this->where($where);
		}

		if ($table !== null) {
			$this->from($table);
		}

		return $this;
	}

	/**
	 * Create an INSERT query.  Use 'columns' and 'values' methods for multi-row inserts
	 *
	 * @param  string  Table name
	 * @param  array   Array of Keys => Values
	 * @return Scorpio_Db_Builder
	 */
	public function insert($table = null, $set = null) {
		$this->type = Scorpio_Db::INSERT;

		if (is_array($set)) {
			$this->columns(array_keys($set));
			$this->values(array_values($set));
		}

		if ($table !== null) {
			$this->from($table);
		}

		return $this;
	}

	/**
	 * Create a DELETE query
	 *
	 * @param  string  Table name
	 * @param  array   WHERE conditions
	 * @return Scorpio_Db_Builder
	 */
	public function delete($table, $where = null) {
		$this->type = Scorpio_Db::DELETE;

		if ($where !== null) {
			$this->where($where);
		}

		if ($table !== null) {
			$this->from($table);
		}

		return $this;
	}

	/**
	 * Count records for a given table
	 *
	 * @param  string  Table name
	 * @param  array   WHERE conditions
	 * @return int
	 */
	public function count_records($table = false, $where = null) {
		if (count($this->from) < 1) {
			if ($table === false)
				throw new Scorpio_Db_Exception('Scorpio_Db count_records requires a table');

			$this->from($table);
		}

		if ($where !== null) {
			$this->where($where);
		}

		// Grab the count AS records_found
		$result = $this->select(array('records_found' => 'COUNT("*")'))->execute();

		return $result->get('records_found');
	}

	/**
	 * Executes the built query
	 *
	 * @param  mixed  Scorpio_Db name or object
	 * @return Database_Result
	 */
	public function execute($db = null) {
		if ($db !== null) {
			$this->db = $db;
		}

		if (!is_object($this->db)) {
			// Get the database instance
			$this->db = Scorpio_Db::instance($this->db);
		}

		$query = $this->compile();

		// Reset the query after executing
		$this->reset();

		if ($this->ttl !== false and $this->type === Scorpio_Db::SELECT) {
			// Return result from cache (only allowed with SELECT)
			return $this->db->query_cache($query, $this->ttl);
		} else {
			// Load the result (no caching)
			return $this->db->query($query);
		}
	}

	/**
	 * Set caching for the query
	 *
	 * @param  mixed  Time-to-live (FALSE to disable, NULL for Cache default, seconds otherwise)
	 * @return Scorpio_Db_Builder
	 */
	public function cache($ttl = null) {
		$this->ttl = $ttl;

		return $this;
	}

} // End Scorpio_Db_Builder


?>