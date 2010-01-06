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
	class Scrpio_Db_Result extends Scrpio_Db_Result_Core {}
}

class Scrpio_Db_Result_Core {
	protected $result;
	protected $db;

	protected $total_rows  = 0;
	protected $current_row = 0;
	protected $insert_id;
}

?>