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
	class Scorpio_Db_Result_Mysql extends Scorpio_Db_Result_Mysql_Core {
	}
}

class Scorpio_Db_Result_Mysql_Core extends Scorpio_Db_Result {
	public function __construct($result, $sql, &$db) {
		$this->db = $db;

		// Store the result locally
		$this->result = $result;

		$this->sql = $sql;

		if (is_resource($this->result)) {
			$this->total_rows = $this->db->num_rows($this->result);
		} elseif (is_bool($result)) {
			if ($result == false) {
				throw new Scorpio_Db_Exception('#:errno: :error [ :query ]', array(':error' => $this->db->error(), ':query' => $this->sql, ':errno' => $this->db->errno()));
			} else {
				// It's a DELETE, INSERT, REPLACE, or UPDATE query
				$this->insert_id = $this->db->insert_id();
				$this->total_rows = $this->db->affected_rows();
			}
		}

	}
}

?>