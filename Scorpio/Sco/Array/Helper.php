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

		$old = (array)$array;

		$array = array();
		foreach ($keys as $key)
		{
			$array[$key] = $old[$key];
		}

		return $array;
	}

}
