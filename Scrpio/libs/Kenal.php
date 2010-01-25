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
	class Scrpio_Kenal extends Scrpio_Kenal_Core {}
}

class Scrpio_Kenal_Core {

	const CHARSET  = 'UTF-8';

	// Server API that PHP is using. Allows testing of different APIs.
	public static $server_api = PHP_SAPI;

	public static $log = null;
	public static $config = null;
	public static $find_file = null;

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

	public static function config($key, $slash = FALSE, $required = FALSE) {
		$variables !== null && $message = scotext::sprintf($message, $variables);

		if (Scrpio_Kenal::_method('config') !== null) {
			call_user_func(Scrpio_Kenal::$config, $key, $slash, $required);
		} else {
			Scrpio_Config::get($key, $slash, $required);
		}

		return Scrpio_Kenal::instance();
	}

	public static function log($type, $message, $variables = null) {
		$variables !== null && $message = scotext::sprintf($message, $variables);

		if (Scrpio_Kenal::_method('log') !== null) {
			call_user_func(Scrpio_Kenal::$log, $type, $message);
		} else {
			Scrpio_Log::add($type, $message);
		}

		return Scrpio_Kenal::instance();
	}

	public static function find_file($directory, $filename, $required = FALSE, $ext = FALSE) {
		if (Scrpio_Kenal::_method('find_file') !== null) {
			call_user_func(Scrpio_Kenal::$find_file, $directory, $filename, $required, $ext);
		} else {
			Scrpio_Kenal::_find_file($directory, $filename, $required, $ext);
		}

		return Scrpio_Kenal::instance();
	}

	protected static function _find_file($directory, $filename, $required = FALSE, $ext = FALSE) {
		return;
	}
}

?>