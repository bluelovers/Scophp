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

require_once '../inc/Constants.php';

class Scorpio_Kenal_Core {
	// Server API that PHP is using. Allows testing of different APIs.
	public static $server_api = PHP_SAPI;

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

	public static function log($type, $message, $variables = null) {
		return Scorpio_Kenal::instance();
	}

	function _class_loader($class) {
		$_core_ = '_Core_';

		$m = array();
		if (preg_match('/^(?<pre>Scorpio_)(?<class>.+)(?<core>'.$_core_.')?$/', $m)) {
			if (!class_exists($m['core'] ? $m[0] : $m['pre'].$m['class'].$_core_, false)) {
				$paths = split('_', $m['class']);
				$file = array_pop($paths);
				if ($paths = join(DIR_SEP, $paths)) {
					$paths .= DIR_SEP;
				}
				include $paths.$file.'.php';
			}

			if (!$m['core'] && !class_exists($m['pre'].$m['class'], false)) {
				$extension = 'class ' . $m['pre'].$m['class'] . ' extends ' . $m['pre'].$m['class'].$_core_ . ' { }';

				eval($extension);
			}

			return true;
		}
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
	}
}

?>