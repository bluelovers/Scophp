<?php

/**
 * @author bluelovers
 * @copyright 2012
 */

class Sco_Array_Helper
{

	static function array_shuffle($array)
	{
		$keys = array_keys($array);
		shuffle($keys);

		$old = (array )$array;

		$array = array();
		foreach ($keys as $key)
		{
			$array[$key] = $old[$key];
		}

		return $array;
	}

	/**
	 * Tests if an array is associative or not.
	 *
	 *		// Returns TRUE
	 *		Arr::is_assoc(array('username' => 'john.doe'));
	 *
	 *		// Returns FALSE
	 *		Arr::is_assoc('foo', 'bar');
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

	/**
	 * Test if a value is an array with an additional check for array-like objects.
	 *
	 *		// Returns TRUE
	 *		Arr::is_array(array());
	 *		Arr::is_array(new ArrayObject);
	 *
	 *		// Returns FALSE
	 *		Arr::is_array(FALSE);
	 *		Arr::is_array('not an array!');
	 *		Arr::is_array(Database::instance());
	 *
	 * @param   mixed    value to check
	 * @return  boolean
	 */
	public static function is_array($value)
	{
		return (bool)(is_array($value) || (is_object($value) and $value instanceof Traversable));
	}

	/**
	 * @return array
	 */
	public static function array_unshift_assoc($arr, $key, $val)
	{
		self::array_remove_key(&$arr, $key);

		$arr = array_merge(array($key => $val), $arr);

		return $arr;
	}

	/**
	 * @return array
	 */
	public static function array_push_assoc($arr, $key, $val)
	{
		self::array_remove_key(&$arr, $key);

		$arr = array_merge($arr, array($key => $val));

		return $arr;
	}

	/**
	 * safe remove key from array, return old value
	 *
	 * @return mixed|null|array
	 */
	public static function array_remove_key(&$arr, $key)
	{
		$old = null;

		if (is_array($key))
		{
			foreach ($key as $k)
			{
				if (array_key_exists($k, $arr))
				{
					$old[$k] = self::array_remove_key(&$arr, $k);
				}
			}
		}
		elseif (array_key_exists($key, $arr))
		{
			$old = $arr[$key];

			$null = null;
			$arr[$key] = &$null;
			unset($arr[$key]);
		}

		return $old;
	}

}
