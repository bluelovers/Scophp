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
	class Scrpio_Spl_Object extends Scrpio_Spl_Object_Core {}
}

class Scrpio_Spl_Object_Core extends Scrpio_Spl {
	protected $_scrpio_ = array();

	protected function &__get($var) {
		return $this->_scrpio_[$var];
	}

	protected function __set($var, $val) {
		$this->_scrpio_[$var] = $val;
		return $this->_scrpio_[$var];
	}
}

?>