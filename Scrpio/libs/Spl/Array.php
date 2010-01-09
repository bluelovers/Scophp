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
	class Scrpio_Spl_Array extends Scrpio_Spl_Array_Core {
	}
}

class Scrpio_Spl_Array_Core extends Scrpio_Spl implements Iterator, ArrayAccess {

	function _setup(&$base) {
		$this->_scrpio_base_ = $base;

		if (is_array($base)) {
			$this->_scrpio_ =& $this->_scrpio_base_;
		}
	}

	/**
	 * implements methods of interface ArrayAccess.
	 */
	public function offsetSet($index, $val) {
		$this->_scrpio_[$index] = $val;
	}

	public function offsetGet($index) {
		return $this->_scrpio_[$index];
	}

	public function offsetExists($index) {
		return ($this->_scrpio_[$index] != null);
	}

	/**
	 * It means: unset($array[$index])
	 */
	public function offsetUnset($index) {
		$this->_scrpio_[$index] = null;
		return $this;
	}

	/**
	 * PHP SPL Iterator function.
	 */
	public function rewind() {
		reset($this->_scrpio__);
	}

	public function valid() {
		return current($this->_scrpio_) ? true : false;
	}

	public function current() {
		return current($this->_scrpio_);
	}

	public function key() {
		return key($this->_scrpio_);
	}

	public function next() {
		next($this->_scrpio_);
	}
}

?>