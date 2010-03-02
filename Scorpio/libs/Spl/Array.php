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
	class Scorpio_Spl_Array extends Scorpio_Spl_Array_Core {
	}
}

class Scorpio_Spl_Array_Core extends Scorpio_Spl implements Iterator, ArrayAccess {

	function _setup(&$base) {
		$this->_scorpio_base_ = $base;

		if (is_array($base)) {
			$this->_scorpio_ = &$this->_scorpio_base_;
		}
	}

	/**
	 * implements methods of interface ArrayAccess.
	 */
	public function offsetSet($index, $val) {
		$this->_scorpio_[$index] = $val;
	}

	public function offsetGet($index) {
		return $this->_scorpio_[$index];
	}

	public function offsetExists($index) {
		return ($this->_scorpio_[$index] != null);
	}

	/**
	 * It means: unset($array[$index])
	 */
	public function offsetUnset($index) {
		$this->_scorpio_[$index] = null;
		return $this;
	}

	/**
	 * PHP SPL Iterator function.
	 */
	public function rewind() {
		reset($this->_scorpio__);
	}

	public function valid() {
		return current($this->_scorpio_) ? true : false;
	}

	public function current() {
		return current($this->_scorpio_);
	}

	public function key() {
		return key($this->_scorpio_);
	}

	public function next() {
		next($this->_scorpio_);
	}
}

?>