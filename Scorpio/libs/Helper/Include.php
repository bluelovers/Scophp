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
	class scoinclude extends Scorpio_Helper_Include_Core {
	}
}

class Scorpio_Helper_Include_Core {
	protected static $instances = null;

	// 取得構造物件
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

	// 建立構造
	function __construct() {

		// make sure static::$instances is newer
		// 當未建立 static::$instances 時 會以當前 class 作為構造類別
		// 當已建立 static::$instances 時 如果呼叫的 class 不屬於當前 static::$instances 的父類別時 則會自動取代; 反之則 不做任何動作
		if (!static::$instances || !in_array(get_called_class(), class_parents(static::$instances))) {
			static::$instances = $this;
		}

//		print_r(array(get_called_class(), class_parents(static ::$instances), class_parents(self ::$instances), class_parents(get_called_class())));

		return static::$instances;
	}

    /**
	 * @param $filename
	 * @param bool - return runtime_defined_vars
	 *
	 * @return array
	 */
	public static function include_file() {
		if (is_file(func_get_arg(0))) {
			include func_get_arg(0);
			if (true === func_get_arg(1)) {
				return scophp::instance()->get_runtime_defined_vars(get_defined_vars());
			}
		} else {
			throw new Scorpio_Exception_PHP('PHP Warning: scophp::include_file(): Filename cannot be empty or not exists!!');
		}

		return array();
	}
}

?>