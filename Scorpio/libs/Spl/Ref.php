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
	class Scorpio_Spl_Ref extends Scorpio_Spl_Ref_Core {
	}
}

class Scorpio_Spl_Ref_Core extends ReflectionClass {

	protected static $instances = array();
	protected $_name = null;

	protected static $cache = true;

	public function __construct($argument) {
		$name = is_object($argument) ? get_class($argument) : $argument;

		if (self::$cache && self::$instances[$name])
			return self::$instances[$name];

		parent::__construct($name);

		$this->_name = $this->getName();

		if (self::$cache)
			self::$instances[$this->_name] = &$this;
	}
}

?>