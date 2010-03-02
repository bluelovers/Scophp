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
	class Scorpio_Loader extends Scorpio_Loader_Core {
	}
}

class Scorpio_Loader_Core {

	const OBJ_UNDEF = 0;
	const OBJ_HELPER = 1;
	const OBJ_LIB = 2;
	const OBJ_CORE = 3;

	const OBJ_ZEND = 101;

	protected static $instances = null;

	public static function instance($overwrite = false) {
		if (!self::$instances) {
			$ref = new ReflectionClass(($overwrite && !in_array($overwrite, array(true, 1), true)) ?
				$overwrite : 'Scorpio_Loader');
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
		if (preg_match('/^sco(?<name>[a-zA-Z][_\w\d]+)$/', $class, $matchs)) {
			$ret = array(self::OBJ_HELPER, $class, $matchs['name'], null);
		} elseif (preg_match('/^Scorpio_helper_(?<name>[a-zA-Z][_\w\d]+)$/', $class, $matchs)) {
			$ret = array(self::OBJ_HELPER, $class, $matchs['name'], false);

		} elseif (preg_match('/^Sco_(?<name>[a-zA-Z][_\w\d]+)$/', $class, $matchs)) {
			$ret = array(self::OBJ_CORE, $class, $matchs['name'], null);
		} elseif (preg_match('/^Scorpio_SYS_(?<name>[a-zA-Z][_\w\d]+)$/', $class, $matchs)) {
			$ret = array(self::OBJ_CORE, $class, $matchs['name'], false);

		} elseif (preg_match('/^Scorpio_(?<name>[A-Z][_\w\d]+)$/', $class, $matchs)) {
			$ret = array(self::OBJ_LIB, $class, $matchs['name'], false);

		} elseif (preg_match('/^Zend_(?<name>[A-Z][_\w\d]+)$/', $class, $matchs)) {
			$ret = array(self::OBJ_ZEND, $class, $matchs['name'], false);

		} else {
			$ret = array(self::OBJ_UNDEF, $class, $class, false);
		}

		return $ret;
	}

	static function helper($name, $rename = null, $path = null) {
		$core = '_Core';
		$class = 'Scorpio_helper_' . $name;
		$rename_def = 'sco' . $name;
		$rename_new = $rename ? $rename : $rename_def;

		return self::_load(self::OBJ_HELPER, array($core, $class, $rename_def, $rename_new,
			$rename, $name, $path, ));
	}

	static function core($name, $rename = null, $path = null) {
		$core = '_Core';
		$class = 'Scorpio_SYS_' . $name;
		$rename_def = 'Sco_' . $name;
		$rename_new = $rename ? $rename : $rename_def;

		return self::_load(self::OBJ_CORE, array($core, $class, $rename_def, $rename_new,
			$rename, $name, $path, ));
	}

	static function lib($name, $rename = null, $path = null) {
		$core = '_Core';
		$class = 'Scorpio_' . $name;
		$rename_def = $class;
		$rename_new = $rename ? $rename : $rename_def;

		return self::_load(self::OBJ_LIB, array($core, $class, $rename_def, $rename_new,
			$rename, $name, $path, ));
	}

	static function load($class) {

		//echo '[['.$class.']]';
//		exit();

		list($type, $class, $name, $rename) = self::instance(true)->class_parse($class);

		if (($suffix = strrpos($class, '_')) > 0) {
			// Find the class suffix
			$suffix = substr($class, $suffix + 1);
		} else {
			// No suffix
			$suffix = false;
		}

		$issco = ($type > 0 && $type < 100) ? true : false;

		if ($issco && $suffix != 'Core') {
			if ($type == self::OBJ_HELPER) {
				return self::instance()->helper($name, $rename);
			} elseif ($type == self::OBJ_CORE) {
				return self::instance()->core($name, $rename);
			} elseif ($type == self::OBJ_LIB) {
				return self::instance()->lib($name, $rename);
			}
		} elseif ($issco && $suffix == 'Core') {
			$core = $issco ? (($suffix == 'Core') ? '' : '_Core') : '';
			return self::_load($type, array($core, $class, $class, $class, true, $name, null));
		}

		//echo '[['.$class.']]';

		//$core = $issco ? (($suffix == 'Core') ? '' : '_Core') : '';
//
//		return self::_load($type, array($core, $class, $class, $class, true, $name));
	}

	private static function _load($type, array $args) {
		//extract($args, EXTR_OVERWRITE);

		list($core, $class, $rename_def, $rename_new, $rename, $name, $path) = $args;

		if ($type == self::OBJ_HELPER) {
			$syspath = SYSPATH . 'Scorpio/helpers/';
		} elseif ($type == self::OBJ_CORE) {
			$syspath = SYSPATH . 'Scorpio/system/';
		} else {
			$_temp = split('_', $name);
			$name = array_pop($_temp);

			if (!$core) {
				$name = array_pop($_temp);
			}

			$syspath = SYSPATH . 'Scorpio/libs/' . join('/', $_temp) . '/';
		}

		if (!self::exists($rename_def)) {

			//todo: do something

			if (!self::exists($class)) {

				if (!self::exists($class . $core)) {
					$basename = $name . '.php';

					include_once $syspath . $basename;
				}

				//todo: do something

				if ($class != $class . $core)
					self::class_create($class, $class . $core);
			}

			//todo: do something

			if ($rename === false) {
				return new Scorpio_Spl_Class($class);
			} elseif ($class != $rename_def) {
				self::class_create($rename_def, $class, $type == self::OBJ_CORE);
			}
		}

		//todo: do something

		return new Scorpio_Spl_Class($rename_new == $rename_def ? $rename_new : self::
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