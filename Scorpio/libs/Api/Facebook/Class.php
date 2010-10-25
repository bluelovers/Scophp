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
	class Scorpio_Api_Facebook_Class extends Scorpio_Api_Facebook_Class_Core {
	}
}

class Scorpio_Api_Facebook_Class_Core {
	protected $core = null;
	protected $_data = array();

	public function __construct(&$core) {
		$this->core = &$core;
	}
}

?>