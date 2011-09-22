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

	/**
	 * I needed to merge two arrays while keeping the first arrays format and adding the values, if they exist, from the second array.
	 * I was working with several multidimensional arrays and was using one as the primary structure to insert data into a database.
	 *
	 * @return array
	 * @see http://tw2.php.net/manual/en/function.array-merge.php#89445
	 */
	function overlay($a1, $a2) {
		/*
		    $table = tableDescription();
		    // created an array of the table structure
   			$table = array_overlay($table,$_POST);
   			// writes the values submitted from the array into the table using array names in the form like <input type="text" name="column[Value]" value="" />
    	*/
		foreach($a1 as $k => $v) {
			if(!array_key_exists($k,$a2)) continue;
			if(is_array($v) && is_array($a2[$k])){
				$a1[$k] = scoarray::overlay($v,$a2[$k]);
			}else{
				$a1[$k] = $a2[$k];
			}
		}

		return $a1;
	}

	function merge(array $array1,array $array2, $strict = true) {
		$ret = array();

		if ($strict) {
			$ret = array_merge($array1, $array2);
		} else {
			$ret = $array1 + $array2;
			$ret = scoarray::overlay($ret, $array2);
		}

		return $ret;
	}

	function map_all($callback, $arr1) {
		if (is_array($arr1)) {
			foreach ($arr1 as $_k => $_v) {
				$arr1[$_k] = scoarray::map_all($callback, $arr1[$_k]);
			}

			return $arr1;
		}

		return call_user_func_array($callback, array($arr1));
	}

	/**
	 * Count all elements in an array, or properties in an object
	 *
	 * @param array|object $array
	 * @param int $mode = COUNT_NORMAL|COUNT_RECURSIVE
	 *
	 * If the optional mode parameter is set to COUNT_RECURSIVE (or 1), count() will recursively count the array.
	 * This is particularly useful for counting all the elements of a multidimensional array.
	 * The default value for mode is 0. count() does not detect infinite recursion.
	 *
	 * 		COUNT_NORMAL = 0
	 * 		COUNT_RECURSIVE = 1
	 */
	function length($array, $mode = COUNT_NORMAL) {
		return count($array, $mode);
	}

	/**
	 * Tests if an array is associative or not.
	 *
	 *     // Returns TRUE
	 *     Arr::is_assoc(array('username' => 'john.doe'));
	 *
	 *     // Returns FALSE
	 *     Arr::is_assoc('foo', 'bar');
	 *
	 * @param   array   array to check
	 * @return  boolean
	 */
	public static function is_assoc(array $array)
	{
		// Keys of the array
		$keys = array_keys($array);

		// If the array keys of the keys match the keys, then the array must
		// not be associative (e.g. the keys array looked like {0:0, 1:1...}).
		return array_keys($keys) !== $keys;
	}
}

?>