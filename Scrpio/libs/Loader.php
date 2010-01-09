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
	static function helper($name, $rename = null, $path = null) {
		$core = '_Core';
		$class = 'Scrpio_helper_' . $name;
		$rename_def = 'sco' . $name;
		$rename_new = $rename ? $rename : $rename_def;

		return self::_load('helpers', array(
			$core,
			$class,
			$rename_def,
			$rename_new,

			$rename,
			$name,
			$path,
		));
	}

	static function core($name, $rename = null, $path = null) {
		$core = '_Core';
		$class = 'Scrpio_SYS_' . $name;
		$rename_def = 'Sco_' . $name;
		$rename_new = $rename ? $rename : $rename_def;

		return self::_load('core', array(
			$core,
			$class,
			$rename_def,
			$rename_new,

			$rename,
			$name,
			$path,
		));
	}

	static function lib($name, $rename = null, $path = null) {
		$core = '_Core';
		$class = 'Scrpio_' . $name;
		$rename_def = $class;
		$rename_new = $rename ? $rename : $rename_def;

		return self::_load('libraries', array(
			$core,
			$class,
			$rename_def,
			$rename_new,

			$rename,
			$name,
			$path,
		));
	}

	private function _load(string $type, array $args) {
		extract($args, EXTR_OVERWRITE);

		if ($type == 'helpers') {
			$syspath = SYSPATH.'Scrpio/'.$type.'/';
		} elseif ($type == 'core') {
			$syspath = SYSPATH.'Scrpio/system/';
		} else {
			$_temp = split('_', $name);
			$name = array_pop($_temp);
			$syspath = SYSPATH.'Scrpio/libs/'.join('/', $_temp).'/';
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

		return new Scrpio_Spl_Class($rename_new == $rename_def ? $rename_new : self::class_create($rename_new, $rename_def));
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