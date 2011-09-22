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
	class Scorpio_Kenal extends Scorpio_Kenal_Core_ {
	}
}

require_once SCORPIO_SYSPATH.'Scorpio/inc/Constants.php';

/**
 * Kenal class for Scorpio PHP Framework
 */
class Scorpio_Kenal_Core_ {
	// Server API that PHP is using. Allows testing of different APIs.
	public static $server_api = PHP_SAPI;

	protected static $instances = null;

	public static $scoAutoloadLocalClasses = array();
	public static $scoAutoloadClasses = array();

	/**
	 * 仿製 javascript set function
	 */
	public static $func = array();

	/**
	 * @return Scorpio_Kenal
	 */
	public static function &instance() {
		if (!Scorpio_Kenal::$instances) {
			Scorpio_Kenal::$instances = new Scorpio_Kenal;
		}

		return Scorpio_Kenal::$instances;
	}

	/**
	 * @return Scorpio_Kenal
	 */
	public function __construct() {
		if (!Scorpio_Kenal::$instances) {
			Scorpio_Kenal::$instances = $this;
		}

		return Scorpio_Kenal::$instances;
	}

	/**
	 * @return Scorpio_Kenal
	 */
	public static function log($type, $message, $variables = null) {
		return Scorpio_Kenal::instance();
	}

	/**
	 * @return bool
	 */
	public static function _class_loader_by_defined($class) {
		if (array_key_exists($class, self::$scoAutoloadClasses)) {
			include_once self::$scoAutoloadClasses[$class]['root'].self::$scoAutoloadClasses[$class]['path'].self::$scoAutoloadClasses[$class]['file'];
		} elseif (array_key_exists($class, self::$scoAutoloadLocalClasses)) {
			include_once self::$scoAutoloadLocalClasses[$class]['root'].self::$scoAutoloadLocalClasses[$class]['path'].self::$scoAutoloadLocalClasses[$class]['file'];
		}

		return class_exists($class, false);
	}

	/**
	 * @return bool
	 */
	public static function _class_loader($class, $force = false) {

		static $_cache;

		// 防止無限迴圈
		if (!$force && isset($_cache[$class])) return $_cache[$class];

		$_core_ = '_Core_';
		$ret = null;

		$m = array();
		if ($class != 'Scorpio_Kenal' && Scorpio_Kenal::_class_loader_by_defined($class)) {
			$ret = class_exists($class, false);
		} elseif ($class == 'Scorpio_Kenal' && self::_class_loader_by_defined($class)) {
			// 可利用此判斷載入 Scorpio_Kenal 的封裝類別
			$ret = class_exists($class, false);
		} elseif (preg_match('/^(?<pre>Scorpio_)(?<class>.+)(?<core>'.$_core_.')?$/', $class, $m)) {
			if (!class_exists($m['core'] ? $m[0] : $m['pre'].$m['class'].$_core_, false)) {
				$paths = split('_', $m['class']);
				$file = array_pop($paths);
				if ($paths = join(DIR_SEP, $paths)) {
					$paths .= DIR_SEP;
				}
				include_once SCORPIO_SYSPATH.'Scorpio/libs/'.$paths.$file.'.php';
			}

			if (!$m['core']
				&& !class_exists($m['pre'].$m['class'], false)
				// 防止無限迴圈
				&& class_exists($m['pre'].$m['class'].$_core_, false)
			) {
				$extension = 'class ' . $m['pre'].$m['class'] . ' extends ' . $m['pre'].$m['class'].$_core_ . ' { }';

				eval($extension);
			}

			$ret = class_exists($class, false);
		} elseif (preg_match('/^(?<pre>sco)(?<class>[a-zA-Z].+)$/', $class, $m)) {
			if (
				Scorpio_Kenal::_class_loader('Scorpio_helper_'.$m['class'])
				&& !class_exists($m[0], false)
				// 防止無限迴圈
				&& class_exists('Scorpio_helper_'.$m['class'], false)
			) {
				$extension = 'class ' . $m['pre'].$m['class'] . ' extends ' . 'Scorpio_helper_'.$m['class'] . ' { }';
				eval($extension);
			}

			$ret = class_exists($class, false);
		}

		// 緩存是否成功載入
		$_cache[$class] = $ret;

		return $ret;
	}

	/**
	 * @return bool
	 */
	public static function _class_autoload($class) {
		return Scorpio_Kenal::_class_loader($class);
	}

	/**
	 * @return Scorpio_Kenal
	 */
	public static function _class_setup($stop = false) {
		static $spl_autoload_register;

		if (!class_exists('Scorpio_Kenal', false)) {
			self::_class_loader('Scorpio_Kenal');
		}

		static $_loader = array(
			'Scorpio_Kenal', '_class_autoload'
		);

		if ($stop) {
			if ($spl_autoload_register) $spl_autoload_register = !spl_autoload_unregister($_loader);
		} elseif (!$spl_autoload_register) {
			$spl_autoload_register = spl_autoload_register($_loader);
		}

		return Scorpio_Kenal::instance();
	}

	/**
	 * Call a user function given with an array of parameters
	 * Call a user defined function with the parameters in param_arr .
	 *
	 * @param callback $function
	 * @param array $param_arr
	 * @return mixed
	 */
	function _call_func_array($function, $param_arr = array()) {
		return call_user_func_array(self::$func[$function], $param_arr);
	}

	/**
	 * call_user_func — Call a user function given by the first parameter
	 * Call a user defined function given by the function parameter.
	 *
	 * @param callback $function
	 * @param [mixed $parameter]
	 * @param [mixed $...]
	 * @return mixed
	 */
	function _call_func($function) {
		$param_arr = func_get_args();
		$function = array_shift($param_arr);
		return self::_call_func_array($function, $param_arr);
	}
}

?>