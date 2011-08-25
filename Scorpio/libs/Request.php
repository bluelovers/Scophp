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
	class Scorpio_Request extends Scorpio_Request_Core {
	}
}

class Scorpio_Request_Core {

	/**
	 * Enable or disable automatic XSS cleaning
	 */
	public $use_xss_clean = false;

	/**
	 * Are magic quotes enabled?
	 */
	public $magic_quotes_gpc = false;

	/**
	 * IP address of current user
	 */
	public $ip_address;
	public $ip_address_array;

	/**
	 * PHP_SAPI
	 */
	public $server_api = PHP_SAPI;

	/**
	 * init?
	 */
	public $init = false;

	/**
	 * default value is UTF-8
	 */
	public $charset = 'UTF-8';

	protected static $instances = null;

	public static function &instance($overwrite = false) {

	}

	public function __construct() {

	}

	function _init_setting() {
		/**
		 * Use XSS clean?
		 */
		$this->use_xss_clean = (bool)Scorpio_Kenal::config('core.global_xss_filtering');
	}

	function _init_env() {
		/**
		 * magic_quotes_runtime is enabled
		 */
		if (get_magic_quotes_runtime()) {
			@set_magic_quotes_runtime(0);
			Scorpio_Kenal::log('debug',
				'Disable magic_quotes_runtime! It is evil and deprecated: http://php.net/magic_quotes');
		}

		/**
		 * magic_quotes_gpc is enabled
		 */
		if (get_magic_quotes_gpc()) {
			$this->magic_quotes_gpc = true;
			Scorpio_Kenal::log('debug',
				'Disable magic_quotes_gpc! It is evil and deprecated: http://php.net/magic_quotes');
		}
	}

	function _init_globals() {
		/**
		 * register_globals is enabled
		 */
		if (ini_get('register_globals')) {
			if (isset($_REQUEST['GLOBALS'])) {
				// Prevent GLOBALS override attacks
				exit('Global variable overload attack.');
			}

			/**
			 * Destroy the REQUEST global
			 */
			$_REQUEST = array();

			/**
			 * These globals are standard and should not be removed
			 */
			$preserve = array('GLOBALS', '_REQUEST', '_GET', '_POST', '_FILES', '_COOKIE',
				'_SERVER', '_ENV', '_SESSION');

			// This loop has the same effect as disabling register_globals
			foreach (array_diff(array_keys($GLOBALS), $preserve) as $key) {
				global $$key;
				$$key = null;

				// Unset the global variable
				unset($GLOBALS[$key], $$key);
			}

			// Warn the developer about register globals
			Scorpio_Kenal::log('debug',
				'Disable register_globals! It is evil and deprecated: http://php.net/register_globals');
		}
	}

	function _init_input() {
		if (is_array($_GET)) {
			foreach ($_GET as $key => $val) {
				// Sanitize $_GET
				$_GET[$this->clean_input_keys($key)] = $this->clean_input_data($val);
			}
		} else {
			$_GET = array();
		}

		if (is_array($_POST)) {
			foreach ($_POST as $key => $val) {
				// Sanitize $_POST
				$_POST[$this->clean_input_keys($key)] = $this->clean_input_data($val);
			}
		} else {
			$_POST = array();
		}
	}

	function _init_request() {
		$_REQUEST = array();
		$_REQUEST = array_merge($_GET, $_POST);
	}

	function _init_cookies() {
		if (is_array($_COOKIE)) {
			foreach ($_COOKIE as $key => $val) {
				// Ignore special attributes in RFC2109 compliant cookies
				if ($key == '$Version' or $key == '$Path' or $key == '$Domain')
					continue;

				// Sanitize $_COOKIE
				$_COOKIE[$this->clean_input_keys($key)] = $this->clean_input_data($val);
			}
		} else {
			$_COOKIE = array();
		}
	}

	public function _init_postraw() {
		global $HTTP_RAW_POST_DATA;
		if (!isset($HTTP_RAW_POST_DATA)) {
			$HTTP_RAW_POST_DATA = file_get_contents("php://input");
		}
		return $HTTP_RAW_POST_DATA;
	}

	public function init() {

		if ($this->init) return $this;
		$this->init = true;

		// Convert all global variables to Kohana charset
		$_SERVER = $this->clean($_SERVER);

		if ($this->server_api === 'cli') {
			$_GET = $_POST = $_COOKIE = $_REQUEST = array();

			// Convert command line arguments
			$_SERVER['argv'] = $this->clean($_SERVER['argv']);
		} else {
			$_GET = $this->clean($_GET);
			$_POST = $this->clean($_POST);
			$_COOKIE = $this->clean($_COOKIE);
		}

		$this->postraw();
		$this->ip_address();

		Scorpio_Kenal::log('debug', 'Global GET, POST and COOKIE data sanitized');
	}

	public function &__get($var) {
		$preserve = array('_REQUEST', '_GET', '_POST', '_FILES', '_COOKIE', '_SERVER',
			'_ENV', '_SESSION');

		$var = uc($var);
		if ($var == 'GLOBALS') {
			return $$var;
		} elseif ($var == 'POSTRAW') {
			return $this->postraw();
		} elseif (in_array('_' . $var, $preserve)) {

			$var = '_' . $var;

			return $$var;
		} else {
			throw new Scorpio_Exception('The %(property)s property does not exist in the %(class)s class.',
				array('property' => $property, 'class' => get_class($this)));
		}
	}

	public function __set($var, $val) {
		$preserve = array('_REQUEST', '_GET', '_POST', '_FILES', '_COOKIE', '_SERVER',
			'_ENV', '_SESSION');

		$var = uc($var);
		if ($var == 'GLOBALS') {
			return $$var;
		} elseif (in_array('_' . $var, $preserve)) {

			$var = '_' . $var;

			return $$var;
		} else {
			throw new Scorpio_Exception('The %(property)s property does not exist in the %(class)s class.',
				array('property' => $property, 'class' => get_class($this)));
		}
	}

	/**
	 * Fetch an item from the $_GET array.
	 *
	 * @param   string   key to find
	 * @param   mixed    default value
	 * @param   boolean  XSS clean the value
	 * @return  mixed
	 */
	public function get($key = array(), $default = null, $xss_clean = false) {
		return $this->search_array($_GET, $key, $default, $xss_clean);
	}

	/**
	 * Fetch an item from the $_POST array.
	 *
	 * @param   string   key to find
	 * @param   mixed    default value
	 * @param   boolean  XSS clean the value
	 * @return  mixed
	 */
	public function post($key = array(), $default = null, $xss_clean = false) {
		return $this->search_array($_POST, $key, $default, $xss_clean);
	}



	/**
	 * Fetch an item from the cookie::get() ($_COOKIE won't work with signed
	 * cookies.)
	 *
	 * @param   string   key to find
	 * @param   mixed    default value
	 * @param   boolean  XSS clean the value
	 * @return  mixed
	 */
	public function cookie($key = array(), $default = null, $xss_clean = false) {
		return $this->search_array($_COOKIE, $key, $default, $xss_clean);
	}

	/**
	 * Fetch an item from the $_SERVER array.
	 *
	 * @param   string   key to find
	 * @param   mixed    default value
	 * @param   boolean  XSS clean the value
	 * @return  mixed
	 */
	public function server($key = array(), $default = null, $xss_clean = false) {
		return $this->search_array($_SERVER, $key, $default, $xss_clean);
	}

	/**
	 * Fetch an item from a global array.
	 *
	 * @param   array    array to search
	 * @param   string   key to find
	 * @param   mixed    default value
	 * @param   boolean  XSS clean the value
	 * @return  mixed
	 */
	protected function search_array($array, $key, $default = null, $xss_clean = false) {
		if ($key === array())
			return $array;

		if (!isset($array[$key]))
			return $default;

		// Get the value
		$value = $array[$key];

		if ($this->use_xss_clean === false and $xss_clean === true) {
			// XSS clean the value
			$value = $this->xss_clean($value);
		}

		return $value;
	}

	/**
	 * Fetch the IP Address.
	 *
	 * @return string
	 */
	public function ip_address($all = false) {
		if (!$all && $this->ip_address !== null) {
			return $this->ip_address;
		} elseif ($all && is_array($this->ip_address_array)){
			return $this->ip_address_array;
		}

		// Server keys that could contain the client IP address
		$keys = array('HTTP_X_FORWARDED_FOR', 'HTTP_CLIENT_IP', 'REMOTE_ADDR');
		$this->ip_address_array = array();

		foreach ($keys as $key) {
			if ($ip = $this->server($key)) {
				$this->ip_address_array[] = scotext::ip($ip);

				// An IP address has been found
				break;
			}
		}

		$this->ip_address = $this->ip_address_array[0];

		return $all ? $this->ip_address_array : $this->ip_address;
	}



	/**
	 * Clean cross site scripting exploits from string.
	 * HTMLPurifier may be used if installed, otherwise defaults to built in method.
	 * Note - This function should only be used to deal with data upon submission.
	 * It's not something that should be used for general runtime processing
	 * since it requires a fair amount of processing overhead.
	 *
	 * @param   string  data to clean
	 * @param   string  xss_clean method to use ('htmlpurifier' or defaults to built-in method)
	 * @return  string
	 */
	public function xss_clean($data, $tool = null) {
		if ($tool === null) {
			// Use the default tool
			$tool = Scorpio_Kenal::config('core.global_xss_filtering');
		}

		if (is_array($data)) {
			foreach ($data as $key => $val) {
				$data[$key] = $this->xss_clean($val, $tool);
			}

			return $data;
		}

		// Do not clean empty strings
		if (trim($data) === '')
			return $data;

		if (is_bool($tool)) {
			$tool = 'default';
		} elseif (!method_exists($this, 'xss_filter_' . $tool)) {
			Scorpio_Kenal::log('error', 'Unable to use self::$instances->xss_filter_' . $tool .
				'(), no such method exists');
			$tool = 'default';
		}

		$method = 'xss_filter_' . $tool;

		return $this->$method($data);
	}

	/**
	 * Default built-in cross site scripting filter.
	 *
	 * @param   string  data to clean
	 * @return  string
	 */
	protected function xss_filter_default($data) {
		// http://svn.bitflux.ch/repos/public/popoon/trunk/classes/externalinput.php
		// +----------------------------------------------------------------------+
		// | Copyright (c) 2001-2006 Bitflux GmbH                                 |
		// +----------------------------------------------------------------------+
		// | Licensed under the Apache License, Version 2.0 (the "License");      |
		// | you may not use this file except in compliance with the License.     |
		// | You may obtain a copy of the License at                              |
		// | http://www.apache.org/licenses/LICENSE-2.0                           |
		// | Unless required by applicable law or agreed to in writing, software  |
		// | distributed under the License is distributed on an "AS IS" BASIS,    |
		// | WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or      |
		// | implied. See the License for the specific language governing         |
		// | permissions and limitations under the License.                       |
		// +----------------------------------------------------------------------+
		// | Author: Christian Stocker <chregu@bitflux.ch>                        |
		// +----------------------------------------------------------------------+
		//
		// Kohana Modifications:
		// * Changed double quotes to single quotes, changed indenting and spacing
		// * Removed magic_quotes stuff
		// * Increased regex readability:
		//   * Used delimeters that aren't found in the pattern
		//   * Removed all unneeded escapes
		//   * Deleted U modifiers and swapped greediness where needed
		// * Increased regex speed:
		//   * Made capturing parentheses non-capturing where possible
		//   * Removed parentheses where possible
		//   * Split up alternation alternatives
		//   * Made some quantifiers possessive

		// Fix &entity\n;
		$data = str_replace(array('&amp;', '&lt;', '&gt;'), array('&amp;amp;',
			'&amp;lt;', '&amp;gt;'), $data);
		$data = preg_replace('/(&#*\w+)[\x00-\x20]+;/u', '$1;', $data);
		$data = preg_replace('/(&#x*[0-9A-F]+);*/iu', '$1;', $data);
		$data = html_entity_decode($data, ENT_COMPAT, 'UTF-8');

		// Remove any attribute starting with "on" or xmlns
		$data = preg_replace('#(?:on[a-z]+|xmlns)\s*=\s*[\'"\x00-\x20]?[^\'>"]*[\'"\x00-\x20]?\s?#iu',
			'', $data);

		// Remove javascript: and vbscript: protocols
		$data = preg_replace('#([a-z]*)[\x00-\x20]*=[\x00-\x20]*([`\'"]*)[\x00-\x20]*j[\x00-\x20]*a[\x00-\x20]*v[\x00-\x20]*a[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu',
			'$1=$2nojavascript...', $data);
		$data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*v[\x00-\x20]*b[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu',
			'$1=$2novbscript...', $data);
		$data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*-moz-binding[\x00-\x20]*:#u',
			'$1=$2nomozbinding...', $data);

		// Only works in IE: <span style="width: expression(alert('Ping!'));"></span>
		$data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?expression[\x00-\x20]*\([^>]*+>#i',
			'$1>', $data);
		$data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?behaviour[\x00-\x20]*\([^>]*+>#i',
			'$1>', $data);
		$data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:*[^>]*+>#iu',
			'$1>', $data);

		// Remove namespaced elements (we do not need them)
		$data = preg_replace('#</*\w+:\w[^>]*+>#i', '', $data);

		do {
			// Remove really unwanted tags
			$old_data = $data;
			$data = preg_replace('#</*(?:applet|b(?:ase|gsound|link)|embed|frame(?:set)?|i(?:frame|layer)|l(?:ayer|ink)|meta|object|s(?:cript|tyle)|title|xml)[^>]*+>#i',
				'', $data);
		} while ($old_data !== $data);

		return $data;
	}

	/**
	 * HTMLPurifier cross site scripting filter. This version assumes the
	 * existence of the "Standalone Distribution" htmlpurifier library, and is set to not tidy
	 * input.
	 *
	 * @param   string  data to clean
	 * @return  string
	 */
	protected function xss_filter_htmlpurifier($data) {
		/**
		 * @todo License should go here, http://htmlpurifier.org/
		 */
		if (!class_exists('HTMLPurifier_Config', false)) {
			// Load HTMLPurifier
			require Scorpio_Kenal::find_file('vendor', 'htmlpurifier/HTMLPurifier.standalone', true);
		}

		// Set configuration
		$config = HTMLPurifier_Config::createDefault();
		$config->set('HTML.TidyLevel', 'none'); // Only XSS cleaning now

		$cache = Scorpio_Kenal::config('html_purifier.cache');

		if ($cache and is_string($cache)) {
			$config->set('Cache.SerializerPath', $cache);
		}

		// Run HTMLPurifier
		$data = HTMLPurifier::instance($config)->purify($data);

		return $data;
	}

	/**
	 * This is a helper method. It enforces W3C specifications for allowed
	 * key name strings, to prevent malicious exploitation.
	 *
	 * @param   string  string to clean
	 * @return  string
	 */
	public function clean_input_keys($str) {
		if (!preg_match('#^[\pL0-9:_.-]++$#uD', $str)) {
			exit('Disallowed key characters in global data.');
		}

		return $str;
	}

	/**
	 * This is a helper method. It escapes data and forces all newline
	 * characters to "\n".
	 *
	 * @param   unknown_type  string to clean
	 * @return  string
	 */
	public function clean_input_data($str) {
		if (is_array($str)) {
			$new_array = array();
			foreach ($str as $key => $val) {
				// Recursion!
				$new_array[$this->clean_input_keys($key)] = $this->clean_input_data($val);
			}
			return $new_array;
		}

		if ($this->magic_quotes_gpc === true) {
			// Remove annoying magic quotes
			$str = stripslashes($str);
		}

		if ($this->use_xss_clean === true) {
			$str = $this->xss_clean($str);
		}

		$str = scotext::lf($str);

		return $str;
	}

	/**
	 * Recursively cleans arrays, objects, and strings. Removes ASCII control
	 * codes and converts to UTF-8 while silently discarding incompatible
	 * UTF-8 characters.
	 *
	 * @param   string  string to clean
	 * @return  string
	 */
	public static function clean($str) {
		if (is_array($str) or is_object($str)) {
			foreach ($str as $key => $val) {
				// Recursion!
				$str[$this->clean($key)] = $this->clean($val);
			}
		} elseif (is_string($str) and $str !== '') {
			// Remove control characters
			$str = scotext::strip_ascii_ctrl($str);

			if (!scotext::is_ascii($str)) {
				// Disable notices
				$ER = error_reporting( ~ E_NOTICE);

				// iconv is expensive, so it is only used when needed
				$str = iconv($this->charset, $this->charset . '//IGNORE', $str);

				// Turn notices back on
				error_reporting($ER);
			}
		}

		return $str;
	}
}

?>