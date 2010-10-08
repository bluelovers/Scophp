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

	// 取得構造物件
	public static function instance($overwrite = false) {
		if (!static::$instances) {
			$ref = new ReflectionClass(($overwrite && !in_array($overwrite, array(true, 1), true)) ? $overwrite:get_called_class());
			static::$instances = $ref->newInstance();
		} elseif ($overwrite) {
			$ref = new ReflectionClass(!in_array($overwrite, array(true, 1), true) ? $overwrite:get_called_class());
			static::$instances = $ref->newInstance();
		}

		return static::$instances;
	}

	// 建立構造
	function __construct() {

		// make sure self::$instances is newer
		// 當未建立 static::$instances 時 會以當前 class 作為構造類別
		// 當已建立 static::$instances 時 如果呼叫的 class 不屬於當前 static::$instances 的父類別時 則會自動取代; 反之則 不做任何動作
		if (!static::$instances || !in_array(get_called_class(), class_parents(static::$instances))) {
			static::$instances = $this;
		}

//		print_r(array(get_called_class(), class_parents(static ::$instances), class_parents(self ::$instances), class_parents(get_called_class())));

		return static::$instances;
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
				$a1[$k] = array_overlay($v,$a2[$k]);
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
			$ret = static::overlay($ret, $array2);
		}

		return $ret;
	}

	function map_all($callback, $arr1) {
		if (is_array($arr1)) {
			foreach ($arr1 as $_k => $_v) {
				$arr1[$_k] = static::map_all($callback, $arr1[$_k]);
			}

			return $arr1;
		}

		return call_user_func_array($callback, array($arr1));
	}
}

?>