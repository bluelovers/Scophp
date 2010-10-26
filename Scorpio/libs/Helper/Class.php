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
	class scoclass extends Scorpio_Helper_Class_Core {
	}
}

class Scorpio_Helper_Class_Core {
	protected static $instances = null;

	// ���o�c�y����
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

	// �إߺc�y
	function __construct() {

		// make sure static::$instances is newer
		// ���إ� static::$instances �� �|�H��e class �@���c�y���O
		// ��w�إ� static::$instances �� �p�G�I�s�� class ���ݩ��e static::$instances �������O�� �h�|�۰ʨ��N; �Ϥ��h ��������ʧ@
		if (!static::$instances || !in_array(get_called_class(), class_parents(static::$instances))) {
			static::$instances = $this;
		}

//		print_r(array(get_called_class(), class_parents(static ::$instances), class_parents(self ::$instances), class_parents(get_called_class())));

		return static::$instances;
	}

    static function create($class, $extends, $final = false, $abstract = false, $retcode = false) {
		$extension = 'class ' . $class . ' extends ' . $extends . ' { }';

		$ref = new ReflectionClass($extends);

		if ($ref->isAbstract() || $abstract) {
			$extension = 'abstract ' . $extension;
		}

		if ($final) {
			$extension = 'final ' . $extension;
		}

        if ($retcode) return $extension;

		eval($extension);

		return $class;
	}

	static function exists($class) {
		static $cache = array();

		if (!isset($cache[$class]) || $cache[$class] == false) {
			$cache[$class] = (class_exists($class, false) || interface_exists($class, false)) ? true : false;
		}

		return $cache[$class];
	}
}

?>