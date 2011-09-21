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
	class scophp extends Scorpio_helper_php_Core_ {
	}
}

class Scorpio_helper_php_Core_ {
	protected static $instances = null;

	protected static $_ini_var_map = array(
		'bool' => array(
			'safe_mode', 'register_globals'
		),
		'array' => array(
			'disable_functions',
		),
	);

	// 取得構造物件
	public static function &instance($overwrite = false) {

	}

	function __construct() {
		static $_init = null;

		$functions = array_unique(static::ini_get('disable_functions', 1));
		if (!empty($functions)) {
			foreach($functions as $_k => $_v) {
				$functions[$_k] = trim(strtolower($_v));
			}
		}
		$_['_INI']['disable_functions'] = (array)$functions;
	}

	public static function set_include_path($path) {
		!empty($path) && set_include_path($path . PATH_SEPARATOR . get_include_path());

		return static::instance();
	}

	/**
	 * fix -0 => 0
	 * @param $n
	 */
	public static function fixzero($n) {
		return $n ? $n : 0;
	}

	/**
	 * @see http://php.net/timezones
	 */
	public static function settimezone($tz) {
		// TODO: php::settimezone
		static::$instances or static::instance();

		static::env_set('TZ', $tz);
	}

	public static function date_default_timezone_set($tz) {
		static::settimezone($tz);
	}

	public static function date_default_timezone_get() {
		return @date_default_timezone_get();
	}

	public static function phpinfo($key = null, $ob = false) {
		echo '<pre>';
		phpinfo($key);
		echo '</pre>';
	}

	public static function ini_get($var = null, $force = false) {
		if (empty($var)) return static::$_['_INI'];

		if ($force || !isset(static::$_['_INI'][$var])) {
			$v = ini_get($var);

			if (static::$_ini_var_map['bool'][$var] !== null) {
				$v = static::_ini_bool($v);
			} elseif (static::$_ini_var_map['array'][$var] !== null && !is_array($v)) {
				$v = $v !== '' ? explode(',', $v) : array();
			}

			static::$_['_INI'][$var] = $v;
		}

		return static::$_['_INI'][$var];
	}

	public static function _ini_bool($val) {
		if (is_bool($val) === true) {
			return $val;
		} elseif ($val === null || $val === '' || $val === 0) {
			return false;
		}

		$val_lc = trim(strtolower($val));

		$ret = null;
		switch($val_lc) {
			case 'on':
			case 'true':
			case 'yes':
				$ret = true;
				break;
			case 'off':
			case 'false':
			case 'no':
				$ret = false;
				break;
			default:
				$ret = $val;
				break;
		}

		return $ret;
	}

	/**
	 *
	 * @param $string
	 * @param $replace
	 * @param $http_response_code
	 */
	public static function header($string, $replace = true, $http_response_code = null) {
		Scorpio_Event::run('php.header', array(&$string, &$replace, &$http_response_code));
		header($string, $replace, $http_response_code);

		//echo $string."<br>";
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
				return get_runtime_defined_vars(get_defined_vars());
			}
		} else {
			throw new Scorpio_Exception_PHP('PHP Warning: scophp::include_file(): Filename cannot be empty or not exists!!');
		}

		return array();
	}

	/**
	 *
	 * @param $varList
	 * @param $excludeList
	 * @example get_runtime_defined_vars(get_defined_vars(), array('b'));
	 * @example get_runtime_defined_vars(get_defined_vars());
	 */
	public static function get_runtime_defined_vars(array $varList, $excludeList =
		array()) {
		/**

		 * $a = 1;

		 * function abc($c = 2) {
		 * global $a;
		 * $b = 3;

		 * $a = 4;
		 * $GLOBALS['s'] = 5;

		 * get_runtime_defined_vars(get_defined_vars(), array('b'));
		 * }
		 * abc();
		 * get_runtime_defined_vars(get_defined_vars(), array('b'));

		 * Array
		 * (
		 * [c] => 2
		 * [a] => 4
		 * )
		 * Array
		 * (
		 * [a] => 4
		 * [s] => 5
		 * )
		 **/

		if ($varList) {
			$excludeList = array_merge((array )$excludeList, array('GLOBALS', '_FILES',
				'_COOKIE', '_POST', '_GET', '_SERVER'));
			$varList = array_diff_key((array )$varList, array_flip($excludeList));
		}

		//		print_r($varList);

		return $varList;
	}

	/**
	 * Checks if the class method exists in the given object .
	 *
	 * @return bool
	 */
	public static function func_exists($object, $method_name = null) {
		return $method_name === null ? function_exists($object) : method_exists($object,
			$method_name);
	}

	public static function func_callback($func, $callback) {
		//todo: need a new name

		$newcallback = is_array($callback) ? "array('$callback[0]', '$callback[1]')" :
			"'$callback'";

		$newfunc = <<< EOM
		function $func() {
			\$args = func_get_args();
			return call_user_func_array($newcallback, $args);
		}
EOM
		;
		eval($newfunc);

		return $func;
	}

	public static function addcslashes($string, $delimiter, $strip = false) {

		if (is_array($string)) {
			foreach ($string as $key => $val) {
				$string[$key] = call_user_func_array(__method__, array($val, $delimiter, $strip));
			}
		} else {
			$string = addcslashes($strip ? stripcslashes($string) : $string, $delimiter);
		}

		return $string;
	}

	public static function addslashes($string, $strip = false) {

		if (is_array($string)) {
			foreach ($string as $key => $val) {
				$string[$key] = call_user_func_array(__method__, array($val, $strip));
			}
		} else {
			$string = addslashes($strip ? stripslashes($string) : $string);
		}

		return $string;
	}

	public static function error_reporting($level = null, $add = null) {
		if ($level === null) {
			return error_reporting();
		} else {

			if (defined('E_DEPRECATED')) {
				$level = $level ^ E_DEPRECATED;
			}

			if ($add !== null) {
				$level = $level & $add;
			}

			return error_reporting($level);
		}
	}

	/**
	 * @example ../docs/test/scophp_version.php
	 **/
	public static function version($version = null, $operator = '>=') {
		if (func_num_args() >= 3) {
			list($ver, $version, $operator) = func_get_args();
		} else {
			$ver = PHP_VERSION;
		}
		if (scovalid::is_empty($version)) {
			return $ver;
		} else {
			return (empty($operator) || $operator === 1 || $operator === true || $operator < 0) ? version_compare($ver, $version) : version_compare($ver, $version, $operator);
		}
	}
}

?>