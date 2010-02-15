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
	class scophp extends Scrpio_helper_php_Core {
	}
}

class Scrpio_helper_php_Core extends Scrpio_Spl_Array {
	protected static $_ = array();
	protected static $instances = null;

	protected static $_scrpio_self_classname_ = 'scophp';

	public static function &instance($overwrite = false) {
		if (!self::$instances) {
			$ref = new ReflectionClass(($overwrite && !in_array($overwrite, array(true, 1), true)) ?
				$overwrite : 'scophp');
			self::$instances = $ref->newInstance();
		} elseif ($overwrite) {
			$ref = new ReflectionClass(!in_array($overwrite, array(true, 1), true) ? $overwrite :
				get_class(self::$instances));
			self::$instances = $ref->newInstance();
		}

		return self::$instances;
	}

	function __construct() {
		if (self::$instances === null) {
			self::$instances = $this;

			global $_ENV;

			// 			date_default_timezone_set('America/Los_Angeles');

			self::$_['_ENV'] = &$_ENV;
			self::$_['_ENV_DEF'] = array();
			self::$_['_INI'] = array();
			self::$_['_TMP'] = array();
			self::$_['GLOBALS'] = array();

			self::$_['_INI']['safe_mode'] = self::_ini_bool(self::_ini_get('safe_mode'));

			$functions = explode(',', self::_ini_get('disable_functions'));
			$functions = array_map('trim', $functions);
			$functions = array_map('strtolower', $functions);

			self::$_['_INI']['disable_functions'] = (array )$functions;

			self::set('timestamp', microtime(true));
		}

		// make sure self::$instances is newer
		if (!self::$instances || !in_array(get_class($this), class_parents(self::$instances))) {
			self::$instances = $this;
		}

		$this->_scrpio_ &= self::$instances->_scrpio_ = array(
			'GLOBALS' => &self::$_['GLOBALS'],
			'_TMP' => &self::$_['_TMP'],
			'_ENV' => &self::$_['_ENV'],
		);

		return self::$instances;
	}

	protected static function _self($name = null, $val = null) {
		$self = self::$instances ? get_class(self::$instances) : self::$_scrpio_self_classname_;

		if ($name) {
			return $val !== null ? scophp::set_static_value($self, $name, $val) : scophp::get_static_value($self, $name);
		} else {
			return $self;
		}
	}

	function __get($var) {
		return self::get($var);
	}

	function __set($var, $val) {
		return self::set($var, $val);
	}

	public static function set_include_path($path) {
		!empty($path) && set_include_path($path . PATH_SEPARATOR . get_include_path());

		return self::instance();
	}

	public static function get($var) {
		self::$instances or self::instance();

		return isset(self::$_['GLOBALS'][$var]) ? self::$_['GLOBALS'][$var] : null;
	}

	public static function set($var, $val) {
		self::$instances or self::instance();

		self::_setglobals($var, $val);

		self::$_['GLOBALS'][$var] = $val;
		return self::$instances;
	}

	public static function gettmp($var) {
		//self::$instances OR self::instance();

		return isset(self::$_['_TMP'][$var]) ? self::$_['_TMP'][$var] : null;
	}

	public static function settmp($var, $val) {
		self::$instances or self::instance();

		self::_setglobals($var, $val);

		self::$_['_TMP'][$var] = $val;
		return self::$instances;
	}

	protected static function _setglobals(&$var, &$val) {
		if ($var == 'timestamp') {
			$mtime = explode('.', $val);

			scodate::offsetfix();

			self::$instances->timenow = array( // 				'time' => date::gmdate("$dateformat $timeformat", $mtime[0]),
				//				'today' => gmdate("$dateformat", $mtime[0]),
			'offset' => self::fixzero(self::$instances->offset), 'year' => scodate::date('Y',
				$mtime[0]), 'month' => scodate::date('n', $mtime[0]), 'date' => scodate::date('j',
				$mtime[0]), 'hour' => scodate::date('h', $mtime[0]), 'minute' => scodate::date('i',
				$mtime[0]), 'second' => scodate::date('s', $mtime[0]), 'microsecond' => sprintf
				("%0.9f", bcsub($val, $mtime[0], 9)),
				// 				'mtime' => (int)$mtime[0]+sprintf("%10.7f",$val - $mtime[0]),
				'mtime' => $val, );

			// 			$val = (int)$val;
		} elseif ($var == 'offset') {
			$val = self::fixzero($val);
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
		self::$instances or self::instance();

		self::setenv('TZ', $tz);
	}

	public static function date_default_timezone_set($tz) {
		self::settimezone($tz);
	}

	public static function phpinfo($key = null, $ob = false) {
		echo '<pre>';
		phpinfo($key);
		echo '</pre>';
	}

	public static function getini($var) {
		self::$instances or self::instance();

		!isset(self::$_['_INI'][$var]) && self::$_['_INI'][$var] = self::_ini_get($var);

		return self::$_['_INI'][$var];
	}

	protected static function _ini_get($var) {
		// XXX:

		$val = ini_get($var);

		return $val;
	}

	public static function _ini_bool($val) {
		$val_lc = trim(strtolower($val));

		return $val_lc == 'on' || $val_lc == 'true' || $val_lc == 'yes' || preg_match("/^\s*[+-]?0*[1-9]/",
			$val);
	}

	/**
	 *
	 * @param $var
	 * @return bool
	 */
	public static function chkenv($var) {
		self::$instances or self::instance();

		return self::getenv($var) == getenv($var);
	}

	/**
	 * get default $_ENV value
	 *
	 * @param $var
	 * @param $force
	 */
	protected static function _getenv($var, $force = true) {
		($force || !isset(self::$_['_ENV_DEF'][$var])) && self::$_['_ENV_DEF'][$var] =
			getenv($var);

		return self::$_['_ENV_DEF'][$var];
	}

	/**
	 * get $_ENV
	 * @param $var
	 */
	public static function getenv($var) {
		self::$instances or self::instance();

		!isset(self::$_['_ENV'][$var]) && self::$_['_ENV'][$var] = self::_getenv($var);

		return self::$_['_ENV'][$var];
	}

	/**
	 * hook set $_ENV
	 * @param $var
	 * @param $val
	 */
	protected static function _setenv(&$var, &$val) {
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
	public static function setenv($var, $val) {
		self::$instances or self::instance();

		self::_getenv($var);
		self::_setenv($var, $val);

		// 		@putenv($var.'='.$val);
		self::$_['_ENV'][$var] = $val;

		return self::$instances;
	}

	/**
	 * set $_ENV
	 * @param $string key=value
	 */
	public static function putenv($string) {
		self::$instances or self::instance();

		list($var, $val) = split('=', $string, 2);

		self::_getenv($var);
		self::_setenv($var, $val);

		@putenv($var . '=' . $val);
		self::$_['_ENV'][$var] = $val;

		return self::$instances;
	}

	/**
	 *
	 * @param $string
	 * @param $replace
	 * @param $http_response_code
	 */
	public static function header($string, $replace = true, $http_response_code = null) {
		Scrpio_Event::run('php.header', array(&$string, &$replace, &$http_response_code));
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
			throw new Scrpio_Exception_PHP('PHP Warning: scophp::include_file(): Filename cannot be empty or not exists!!');
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
		$ref = new Scrpio_Spl_Ref($class);

		return $ref->getStaticPropertyValue($name, $val);
	}

	public static function set_static_value($class, $name, $val) {
		$ref = new Scrpio_Spl_Ref($class);

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
			}.

			return error_reporting($level);
		}
	}
}

?>