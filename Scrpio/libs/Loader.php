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
	class Scrpio_Loader extends Scrpio_Loader_Core {
	}
}

class Scrpio_Loader_Core {

	const OBJ_UNDEF = 0;
	const OBJ_HELPER = 1;
	const OBJ_LIB = 2;
	const OBJ_CORE = 3;

	const OBJ_ZEND = 101;

	protected static $instances = null;

	public static function instance($overwrite = false) {
		if (!self::$instances) {
			$ref = new ReflectionClass(($overwrite && !in_array($overwrite, array(true, 1), true)) ?
				$overwrite : 'Scrpio_Loader');
			self::$instances = $ref->newInstance();
		} elseif ($overwrite) {
			$ref = new ReflectionClass(!in_array($overwrite, array(true, 1), true) ? $overwrite :
				get_class(self::$instances));
			self::$instances = $ref->newInstance();
		}

		return self::$instances;
	}

	function __construct() {

		// make sure self::$instances is newer
		if (!self::$instances || !in_array(get_class($this), class_parents(self::$instances))) {
			self::$instances = $this;
		}

		return self::$instances;
	}

	static function class_parse($class) {
		$ret = $matchs = array();
		if (preg_match('/^sco(?<name>[a-zA-Z][_\w\d]+)$/', $matchs) || preg_match('/^Scrpio_helper_(?<name>[a-zA-Z][_\w\d]+)$/',
			$matchs)) {
			$ret = array(self::OBJ_HELPER, $class, $matchs['name']);
		} elseif (preg_match('/^Sco_(?<name>[a-zA-Z][_\w\d]+)$/', $matchs) || preg_match
		('/^Scrpio_SYS_(?<name>[a-zA-Z][_\w\d]+)$/', $matchs)) {
			$ret = array(self::OBJ_CORE, $class, $matchs['name']);
		} elseif (preg_match('/^Scrpio_(?<name>[A-Z][_\w\d]+)$/', $matchs)) {
			$ret = array(self::OBJ_LIB, $class, $matchs['name']);
		} elseif (preg_match('/^Zend_(?<name>[A-Z][_\w\d]+)$/', $matchs)) {
			$ret = array(self::OBJ_ZEND, $class, $matchs['name']);
		} else {
			$ret = array(self::OBJ_UNDEF, $class, $class);
		}

		return $ret;
	}

	static function helper($name, $rename = null, $path = null) {
		$core = '_Core';
		$class = 'Scrpio_helper_' . $name;
		$rename_def = 'sco' . $name;
		$rename_new = $rename ? $rename : $rename_def;

		return self::_load(self::OBJ_HELPER, array($core, $class, $rename_def, $rename_new, $rename,
			$name, $path, ));
	}

	static function core($name, $rename = null, $path = null) {
		$core = '_Core';
		$class = 'Scrpio_SYS_' . $name;
		$rename_def = 'Sco_' . $name;
		$rename_new = $rename ? $rename : $rename_def;

		return self::_load(self::OBJ_CORE, array($core, $class, $rename_def, $rename_new, $rename,
			$name, $path, ));
	}

	static function lib($name, $rename = null, $path = null) {
		$core = '_Core';
		$class = 'Scrpio_' . $name;
		$rename_def = $class;
		$rename_new = $rename ? $rename : $rename_def;

		return self::_load(self::OBJ_LIB, array($core, $class, $rename_def, $rename_new, $rename,
			$name, $path, ));
	}

	private function _load(string $type, array $args) {
		extract($args, EXTR_OVERWRITE);

		if ($type == self::OBJ_HELPER) {
			$syspath = SYSPATH . 'Scrpio/helpers/';
		} elseif ($type == self::OBJ_CORE) {
			$syspath = SYSPATH . 'Scrpio/system/';
		} else {
			$_temp = split('_', $name);
			$name = array_pop($_temp);
			$syspath = SYSPATH . 'Scrpio/libs/' . join('/', $_temp) . '/';
		}

		if (!self::exists($rename_def)) {

			//todo: do something

			if (!self::exists($class)) {

				if (!self::exists($class . $core)) {
					$basename = $name . '.php';

					include_once $syspath . $basename;
				}

				//todo: do something

				self::class_create($class, $class . $core);
			}

			//todo: do something

			if ($rename === false) {
				return new Scrpio_Spl_Class($class);
			} elseif ($class != $rename_def) {
				self::class_create($rename_def, $class);
			}
		}

		//todo: do something

		return new Scrpio_Spl_Class($rename_new == $rename_def ? $rename_new : self::
			class_create($rename_new, $rename_def));
	}

	static function class_create($class, $extends, $final = false, $abstract = false) {
		$extension = 'class ' . $class . ' extends ' . $extends . ' { }';
		$ref = new ReflectionClass($extends);

		if ($ref->isAbstract() || $abstract) {
			$extension = 'abstract ' . $extension;
		}

		if ($final) {
			$extension = 'final ' . $extension;
		}

		eval($extension);

		return $class;
	}

	static function exists($class) {
		return (class_exists($class, false) || interface_exists($class, false)) ? true : false;
	}
}

?>