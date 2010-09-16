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
	class scoarray extends Scorpio_helper_array_Core {}
}

class Scorpio_helper_array_Core {
	protected static $instances = null;

	public static function instance($overwrite = false) {
		if (!self::$instances) {
			$ref = new ReflectionClass(($overwrite && !in_array($overwrite, array(true, 1), true)) ? $overwrite : 'scoarray');
			self::$instances = $ref->newInstance();
		} elseif ($overwrite) {
			$ref = new ReflectionClass(!in_array($overwrite, array(true, 1), true) ? $overwrite : get_class(self::$instances));
			self::$instances = $ref->newInstance();
		}

		return self::$instances;
	}

	function __construct() {

		// make sure self::$instances is newer
		if (!self::$instances || !in_array(get_class($this), class_parents(self::$instances))) {
			self::$instances = $this;
		}

		return self::$instances;
	}

	static function remove_keys($haystack, $needle) {
		if (is_array($needle)) {
			$array = array();

			for ($i = 0; $i < count($needle); $i++) {
				$array[] = $haystack[$needle[$i]];
				unset($haystack[$needle[$i]]);
			}
		} else {
			$array = $haystack[$needle];
			unset($haystack[$needle]);
		}

		return $array;
	}

	/**
	 * array_search_match($needle, $haystack)
	 * returns all the keys of the values that match $needle in $haystack
	 *
	 * @return array
	 */
	static function search_all($needle, array $haystack, $strict = false) {
		$array = array();

		foreach ($haystack as $k => $v) {
			if (!$strict && $haystack[$k] == $needle) {
				$array[] = $k;
			} elseif ($strict && $haystack[$k] === $needle) {
				$array[] = $k;
			}
		}

		return $array;
	}

	function in_array_default ($needle, $haystack, $default = null, $strict = false) {
		return in_array($needle, $haystack, $strict) ? $needle : ($default === null ? $haystack[0] : $default);
	}
}

?>