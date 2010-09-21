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
	class Scorpio_Spl_Object extends Scorpio_Spl_Object_Core {}
}

class Scorpio_Spl_Object_Core extends Scorpio_Spl {

	function _setup(&$base) {
		if ($base !== null) {
			$this->_scorpio_base_ = $base;

			if (is_object($base)) {
				$this->_scorpio_type_ = 'object';
				$this->_scorpio_ =& $this->_scorpio_base_;
			} elseif (is_array($base)) {
				$this->_scorpio_type_ = 'array';
				$this->_scorpio_ =& $this->_scorpio_base_;
			} else {
				throw new Scorpio_Exception_PHP('#:errno: :error', array());
			}
		}
	}

	protected function &__get($var) {
		return $this->_scorpio_type_ == 'object' ? $this->_scorpio_->{$var} : $this->_scorpio_[$var];
	}

	protected function __set($var, $val) {

		if ($this->_scorpio_type_ == 'object') {
			$this->_scorpio_[$var] = $val;
		} else {
			$this->_scorpio_->{$var} = $val;
		}

		return $this->__get($var);
	}
}

?>