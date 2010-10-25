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
	class scoexc extends Scorpio_helper_exc_Core {
	}
}

class Scorpio_helper_exc_Core {
	protected static $instances = null;

	static $_prefix_ = 'Scorpio_Exception_';
	static $_Exception_ = array('php' => 'Scorpio_Exception_PHP', );

	public static function &instance($overwrite = false) {
		if (!static::$instances) {
			$ref = new ReflectionClass(($overwrite && !in_array($overwrite, array(true, 1), true)) ? $overwrite:get_called_class());
			static::$instances = $ref->newInstance();
		} elseif ($overwrite) {
			$ref = new ReflectionClass(!in_array($overwrite, array(true, 1), true) ? $overwrite:get_called_class());
			static::$instances = $ref->newInstance();
		}

		return static::$instances;
	}

	function __construct() {

		// make sure self::$instances is newer
		if (!static::$instances || !in_array(get_called_class(), class_parents(static::$instances))) {
			static::$instances = $this;
		}

//		print_r(array(get_called_class(), class_parents(static ::$instances), class_parents(self ::$instances), class_parents(get_called_class())));

		return static::$instances;
	}

	static function set($key, $value) {
		$key = split('.', $key, 2);

		if ($key[0] == 'prefix') {
			static ::$_prefix_ = $value;
		} elseif ($key[1] == 'exc') {
			static ::$_Exception_[$key] = $value;
		}

		return static ::instance();
	}

	static function _mod($class) {

		$class_lc = strtolower($class);
		$ret = static ::$_prefix_ . ucfirst($class);
		if (array_key_exists($class_lc, $_Exception_)) {
			$ret = static ::$_Exception_[$class_lc];
		}

		return $ret;
	}

	static function &exec() {
		list($k, $v) = func_get_args();

		$k = static ::_mod($k);

		$ref = new ReflectionClass($k);
		return $ref->newInstanceArgs($v);
	}

	static function &php() {
		return static::exec('php', func_get_args());
	}
}

?>