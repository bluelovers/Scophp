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
	class Scrpio_Kenal extends Scrpio_Kenal_Core {
	}
}

class Scrpio_Kenal_Core {

	const CHARSET = 'UTF-8';

	// Server API that PHP is using. Allows testing of different APIs.
	public static $server_api = PHP_SAPI;

	public static $log = null;
	public static $config = null;
	public static $find_file = null;
	public static $lang = null;

	public static $local_lang = 'en_US';

	protected static $instances = null;

	public static function &instance() {
		if (!Scrpio_Kenal::$instances) {
			Scrpio_Kenal::$instances = new Scrpio_Kenal;
		}

		return Scrpio_Kenal::$instances;
	}

	function __construct() {
		if (!Scrpio_Kenal::$instances) {
			Scrpio_Kenal::$instances = $this;
		}

		return Scrpio_Kenal::$instances;
	}

	protected static function _method($property) {
		switch ($property) {
			case 'config':
				return Scrpio_Kenal::$config;
				break;
			case 'log':
				return Scrpio_Kenal::$log;
				break;
			case 'find_file':
				return Scrpio_Kenal::$find_file;
				break;
		}

		throw new Scrpio_Exception('The %(property)s property does not exist in the %(class)s class.',
			array('property' => $property, 'class' => 'Scrpio_Kenal'));
	}

	public static function config($key, $slash = false, $required = false) {
		if (Scrpio_Kenal::_method('config')) {
			return call_user_func(Scrpio_Kenal::$config, $key, $slash, $required);
		} else {
			return Scrpio_Config::get($key, $slash, $required);
		}
	}

	public static function log($type, $message, $variables = null) {
		$variables !== null && $message = scotext::sprintf($message, $variables);

		if (Scrpio_Kenal::_method('log')) {
			call_user_func(Scrpio_Kenal::$log, $type, $message);
		} else {
			Scrpio_Log::add($type, $message);
		}

		return Scrpio_Kenal::instance();
	}

	public static function find_file($directory, $filename, $required = false, $ext = false) {
		if (Scrpio_Kenal::_method('find_file')) {
			$ret = call_user_func(Scrpio_Kenal::$find_file, $directory, $filename, $required,
				$ext);
		} else {
			$ret = Scrpio_Kenal::_find_file($directory, $filename, $required, $ext);
		}

		return Scrpio_File::fix($ret);
	}

	protected static function _find_file($directory, $filename, $required = false, $ext = false) {
		return;
	}

	public static function lang() {
		$args = func_get_args();

		if (Scrpio_Kenal::_method('lang')) {
			return call_user_func_array(Scrpio_Kenal::$lang, $args);
		} else {
			return array_shift($args);
		}
	}

	public static function reset() {
		$data = $data1 = $data2 = null;

		Scrpio_Kenal::$server_api = PHP_SAPI;

		Scrpio_Kenal::$config = &$data;
		Scrpio_Kenal::$log = &$data1;
		Scrpio_Kenal::$find_file = &$data2;

		return Scrpio_Kenal::instance();
	}
}

?>