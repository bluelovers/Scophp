<?php

/**
 * @author bluelovers
 * @copyright 2011
 */

if (0) {
	// for IDE
	class scoarray extends Scorpio_helper_array_Core_ {
	}
}

class Scorpio_helper_array_Core_ {
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

	static function search_all_not($needle, array $haystack, $strict = false) {
		$array = array();

		foreach ((array)$haystack as $k => $v) {
			if (!$strict && $haystack[$k] != $needle) {
				$array[] = $k;
			} elseif ($strict && $haystack[$k] !== $needle) {
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