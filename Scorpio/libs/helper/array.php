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
}

?>