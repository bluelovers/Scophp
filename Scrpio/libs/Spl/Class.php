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
	class Scrpio_Spl_Class extends Scrpio_Spl_Class_Core {
	}
}

class Scrpio_Spl_Class_Core {
	protected $instances = null;

	function __construct($class) {
		$this->_scrpio_base_ = $class;
	}

	private static function _instance() {
		$ref = new ReflectionClass($this->_scrpio_base_);

		if ($ref->hasMethod('instance')) {
			$this->instances = eval($this->_scrpio_base_ . '::instance()');
		} else {
			$this->instances = $ref->newInstance();
		}

		return $this->instances;
	}

	function __call(string $method, array $args = array()) {
		$this->instances or $this->_instance();

		return call_user_func_array(array($this->instances, $method), $args);
	}
}

?>