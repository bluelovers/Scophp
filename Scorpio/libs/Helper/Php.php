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
	class scophp extends Scorpio_Helper_Php_Core {
	}
}

class Scorpio_Helper_Php_Core extends Scorpio_Spl_Array {
	protected static $_ = array(
		'_INI' => array(),
		'_TMP' => array(),
		'GLOBALS' => array(),
	);
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
		static $_init = null;
		if ($_init === null) {
			$_init = true;

			foreach(static::$_ini_var_map as $_k => $_v) {
				static::$_ini_var_map[$_k] = array_flip($_v);
			}

			global $_ENV;

			// 			date_default_timezone_set('America/Los_Angeles');

			static::$_['_ENV'] = &$_ENV;
			static::$_['_ENV_DEF'] = array();

			static::ini_get('safe_mode', 1);

			$functions = static::ini_get('disable_functions', 1);
			if (!empty($functions)) {
				foreach($functions as $_k => $_v) {
					$functions[$_k] = trim(strtolower($_v));
				}
			}
			static::$_['_INI']['disable_functions'] = (array)$functions;

//			static::set('timestamp', microtime(true));

			$this->_scorpio_ = array(
				'GLOBALS' => &static::$_['GLOBALS'],
				'_TMP' => &static::$_['_TMP'],
				'_ENV' => &static::$_['_ENV'],

//				'_INI' => &static::$_['_INI'],
			);
		}

		// make sure static::$instances is newer
		if (!static::$instances || !in_array(get_class($this), class_parents(static::$instances))) {
			static::$instances = $this;
		}

		$this->_scorpio_ = &static::$instances->_scorpio_;

		return static::$instances;
	}

	function __get($var) {
		return static::get($var);
	}

	function __set($var, $val) {
		return static::set($var, $val);
	}

	public static function set_include_path($path) {
		!empty($path) && set_include_path($path . PATH_SEPARATOR . get_include_path());

		return static::instance();
	}

	public static function get($var) {
		static::$instances or static::instance();

		return isset(static::$_['GLOBALS'][$var]) ? static::$_['GLOBALS'][$var] : null;
	}

	public static function set($var, $val) {
		static::$instances or static::instance();

		static::_setglobals($var, $val);

		static::$_['GLOBALS'][$var] = $val;
		return static::$instances;
	}

	public static function gettmp($var) {
		//static::$instances OR static::instance();

		return isset(static::$_['_TMP'][$var]) ? static::$_['_TMP'][$var] : null;
	}

	public static function settmp($var, $val) {
		static::$instances or static::instance();

		static::_setglobals($var, $val);

		static::$_['_TMP'][$var] = $val;
		return static::$instances;
	}

	protected static function _setglobals(&$var, &$val) {
		if ($var == 'timestamp') {
			$mtime = explode('.', $val);

			scodate::offsetfix();

			static::$instances->timenow = array( // 				'time' => date::gmdate("$dateformat $timeformat", $mtime[0]),
				//				'today' => gmdate("$dateformat", $mtime[0]),
			'offset' => static::fixzero(static::$instances->offset), 'year' => scodate::date('Y',
				$mtime[0]), 'month' => scodate::date('n', $mtime[0]), 'date' => scodate::date('j',
				$mtime[0]), 'hour' => scodate::date('h', $mtime[0]), 'minute' => scodate::date('i',
				$mtime[0]), 'second' => scodate::date('s', $mtime[0]), 'microsecond' => sprintf
				("%0.9f", bcsub($val, $mtime[0], 9)),
				// 				'mtime' => (int)$mtime[0]+sprintf("%10.7f",$val - $mtime[0]),
				'mtime' => $val, );

			// 			$val = (int)$val;
		} elseif ($var == 'offset') {
			$val = static::fixzero($val);
		}
	}

	/**
	 * fix -0 => 0
	 * @param $n
	 */
	public static function fixzero($n) {
		return $n ? $n : 0;
	}

	/**
	 * @see http://php.net/setlocale
	 */
	public static function setlocale() {
		// TODO: php::setlocale
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
	 * @param $var
	 * @return bool
	 */
	public static function chkenv($var) {
		static::$instances or static::instance();

		return static::env_get($var) == getenv($var);
	}

	/**
	 * get default $_ENV value
	 *
	 * @param $var
	 * @param $force
	 */
	protected static function _env_get($var, $force = true) {
		($force || !isset(static::$_['_ENV_DEF'][$var])) && static::$_['_ENV_DEF'][$var] =
			getenv($var);

		return static::$_['_ENV_DEF'][$var];
	}

	/**
	 * get $_ENV
	 * @param $var
	 */
	public static function getenv($var) {
		static::$instances or static::instance();

		!isset(static::$_['_ENV'][$var]) && static::$_['_ENV'][$var] = static::_env_get($var);

		return static::$_['_ENV'][$var];
	}

	/**
	 * hook set $_ENV
	 * @param $var
	 * @param $val
	 */
	protected static function _env_set(&$var, &$val) {
		// XXX:

		if (uc($var) == 'TZ') {
			$var = uc($var);
			@date_default_timezone_set($val);
			@ini_set('date.timezone', $val);
		}
	}

	/**
	 * set $_ENV
	 * @param $var
	 * @param $val
	 */
	public static function env_set($var, $val) {
		static::$instances or static::instance();

		static::_env_get($var);
		static::_env_set($var, $val);

		// 		@putenv($var.'='.$val);
		static::$_['_ENV'][$var] = $val;

		return static::$instances;
	}

	/**
	 * set $_ENV
	 * @param $string key=value
	 */
	public static function putenv($string) {
		static::$instances or static::instance();

		list($var, $val) = split('=', $string, 2);

		static::_env_get($var);
		static::_env_set($var, $val);

		@putenv($var . '=' . $val);
		static::$_['_ENV'][$var] = $val;

		return static::$instances;
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
				$string[$key] = call_user_func_array(__method__, array($val, &$delimiter, &$strip));
			}
		} else {
			$string = addcslashes($strip ? stripcslashes($string) : $string, $delimiter);
		}

		return $string;
	}

	public static function addslashes($string, $strip = false) {

		if (is_array($string)) {
			foreach ($string as $key => $val) {
				$string[$key] = call_user_func_array(__method__, array($val, &$strip));
			}
		} else {
			$string = addslashes($strip ? stripslashes($string) : $string);
		}

		return $string;
	}

	public static function get_static_value($class, $name, $val = null) {
		$ref = new Scorpio_Spl_Ref($class);

		return $ref->getStaticPropertyValue($name, $val);
	}

	public static function set_static_value($class, $name, $val) {
		$ref = new Scorpio_Spl_Ref($class);

		$ref->setStaticPropertyValue($name, $val);

		return $ref;
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
		if (scovalid::is_empty($version)) {
			return PHP_VERSION;
		} else {
			return (empty($operator) || $operator === 1 || $operator === true || $operator < 0) ? version_compare(PHP_VERSION, $version) : version_compare(PHP_VERSION, $version, $operator);
		}
	}
}

?>