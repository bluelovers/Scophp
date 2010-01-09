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

	function _setup(&$base) {
		if ($base !== null) {
			$this->_scrpio_base_ = $base;

			if (is_object($base)) {
				$this->_scrpio_type_ = 'object';
				$this->_scrpio_ =& $this->_scrpio_base_;
			} elseif (is_array($base)) {
				$this->_scrpio_type_ = 'array';
				$this->_scrpio_ =& $this->_scrpio_base_;
			} else {
				throw new Scrpio_Exception_PHP('#:errno: :error', array());
			}
		}
	}

	protected function &__get($var) {
		return $this->_scrpio_type_ == 'object' ? $this->_scrpio_->{$var} : $this->_scrpio_[$var];
	}

	protected function __set($var, $val) {

		if ($this->_scrpio_type_ == 'object') {
			$this->_scrpio_[$var] = $val;
		} else {
			$this->_scrpio_->{$var} = $val;
		}

		return $this->__get($var);
	}
}

?>