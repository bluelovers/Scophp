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

class Scorpio_Kenal_Core_ {
	// Server API that PHP is using. Allows testing of different APIs.
	public static $server_api = PHP_SAPI;

	protected static $instances = null;

	public static $scoAutoloadLocalClasses = array();
	public static $scoAutoloadClasses = array();

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

	public static function log($type, $message, $variables = null) {
		return Scorpio_Kenal::instance();
	}

	function _class_loader($class) {
		$_core_ = '_Core_';
		$ret = false;

		$m = array();
		if (preg_match('/^(?<pre>Scorpio_)(?<class>.+)(?<core>'.$_core_.')?$/', $m)) {
			if (!class_exists($m['core'] ? $m[0] : $m['pre'].$m['class'].$_core_, false)) {
				$paths = split('_', $m['class']);
				$file = array_pop($paths);
				if ($paths = join(DIR_SEP, $paths)) {
					$paths .= DIR_SEP;
				}
				include_once SCORPIO_SYSPATH.'Scorpio/libs/'.$paths.$file.'.php';
			}

			if (!$m['core'] && !class_exists($m['pre'].$m['class'], false)) {
				$extension = 'class ' . $m['pre'].$m['class'] . ' extends ' . $m['pre'].$m['class'].$_core_ . ' { }';

				eval($extension);
			}

			$ret = true;
		} elseif (preg_match('/^(?<pre>sco)(?<class>[a-zA-Z].+)$/', $m)) {
			if (
				self::_class_loader('Scorpio_helper_'.$m['class'])
				&& !class_exists($m[0], false)
			) {
				$extension = 'class ' . $m['pre'].$m['class'] . ' extends ' . 'Scorpio_helper_'.$m['class'] . ' { }';
				eval($extension);
			}

			$ret = true;
		} elseif (array_key_exists($class, Scorpio_Kenal::$scoAutoloadClasses)) {
			include_once Scorpio_Kenal::$scoAutoloadClasses[$class]['root'].Scorpio_Kenal::$scoAutoloadClasses[$class]['path'].Scorpio_Kenal::$scoAutoloadClasses[$class]['file'];
		} elseif (array_key_exists($class, Scorpio_Kenal::$scoAutoloadLocalClasses)) {
			include_once Scorpio_Kenal::$scoAutoloadLocalClasses[$class]['root'].Scorpio_Kenal::$scoAutoloadLocalClasses[$class]['path'].Scorpio_Kenal::$scoAutoloadLocalClasses[$class]['file'];
		}

		return $ret;
	}

	function _class_autoload($class) {
		Scorpio_Kenal::_class_loader($class);
	}

	function _class_setup($stop = false) {
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
}

?>