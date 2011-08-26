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

			if (!$m['core']) {
				$extension = 'class ' . $m['pre'].$m['class'] . ' extends ' . $m['pre'].$m['class'].$_core_ . ' { }';

				eval($extension);
			}
		}
	}
}

?>