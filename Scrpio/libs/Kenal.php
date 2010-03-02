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
	class Scorpio_Kenal extends Scorpio_Kenal_Core {
	}
}

class Scorpio_Kenal_Core {

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
		if (!Scorpio_Kenal::$instances) {
			Scorpio_Kenal::$instances = new Scorpio_Kenal;
		}

		return Scorpio_Kenal::$instances;
	}

	function __construct() {
		if (!Scorpio_Kenal::$instances) {
			Scorpio_Kenal::$instances = $this;
		}

		return Scorpio_Kenal::$instances;
	}

	protected static function _method($property) {
		switch ($property) {
			case 'config':
				return Scorpio_Kenal::$config;
				break;
			case 'log':
				return Scorpio_Kenal::$log;
				break;
			case 'find_file':
				return Scorpio_Kenal::$find_file;
				break;
		}

		throw new Scorpio_Exception('The %(property)s property does not exist in the %(class)s class.',
			array('property' => $property, 'class' => 'Scorpio_Kenal'));
	}

	public static function config($key, $slash = false, $required = false) {
		if (Scorpio_Kenal::_method('config')) {
			return call_user_func(Scorpio_Kenal::$config, $key, $slash, $required);
		} else {
			return Scorpio_Config::get($key, $slash, $required);
		}
	}

	public static function log($type, $message, $variables = null) {
		$variables !== null && $message = scotext::sprintf($message, $variables);

		if (Scorpio_Kenal::_method('log')) {
			call_user_func(Scorpio_Kenal::$log, $type, $message);
		} else {
			Scorpio_Log::add($type, $message);
		}

		return Scorpio_Kenal::instance();
	}

	public static function find_file($directory, $filename, $required = false, $ext = false) {
		if (Scorpio_Kenal::_method('find_file')) {
			$ret = call_user_func(Scorpio_Kenal::$find_file, $directory, $filename, $required,
				$ext);
		} else {
			$ret = Scorpio_Kenal::_find_file($directory, $filename, $required, $ext);
		}

		return Scorpio_File::fix($ret);
	}

	protected static function _find_file($directory, $filename, $required = false, $ext = false) {
		return;
	}

	public static function lang() {
		$args = func_get_args();

		if (Scorpio_Kenal::_method('lang')) {
			return call_user_func_array(Scorpio_Kenal::$lang, $args);
		} else {
			return array_shift($args);
		}
	}

	public static function reset() {
		$data = $data1 = $data2 = null;

		Scorpio_Kenal::$server_api = PHP_SAPI;

		Scorpio_Kenal::$config = &$data;
		Scorpio_Kenal::$log = &$data1;
		Scorpio_Kenal::$find_file = &$data2;

		return Scorpio_Kenal::instance();
	}
}

?>