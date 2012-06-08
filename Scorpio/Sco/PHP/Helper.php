<?php

/**
 * @author bluelovers
 * @copyright 2012
 */

class Sco_PHP_Helper
{

	public static function prepend_include_path($path)
	{
		return set_include_path($path . PATH_SEPARATOR . get_include_path());
	}

	public static function var_ini_bool($val)
	{
		if (is_bool($val) === true)
		{
			return $val;
		}
		elseif ($val === null || $val === '' || $val === 0)
		{
			return false;
		}

		$val_lc = trim(strtolower($val));

		$ret = null;
		switch ($val_lc)
		{
			case 'on':
			case 'true':
			case 'yes':
				$ret = true;
				break;
			case 'off':
			case 'false':
			case 'no':
				$ret = false;
				break;
			default:
				$ret = $val;
				break;
		}

		return $ret;
	}

	/**
	 * @param $filename
	 * @param bool - return runtime_defined_vars
	 *
	 * @return array|mixed
	 */
	public static function include_file()
	{
		if (is_file(func_get_arg(0)))
		{
			include func_get_arg(0);
			if (true === func_get_arg(1))
			{
				return self::get_runtime_defined_vars(get_defined_vars());
			}
		}
		else
		{
			throw new Exception(sprintf('PHP Warning: %s(): Filename cannot be empty or not exists!!', __METHOD__));
		}

		return array();
	}

	/**
	 *
	 * @param $varList
	 * @param $excludeList
	 * @example get_runtime_defined_vars(get_defined_vars(), array('b'));
	 * @example get_runtime_defined_vars(get_defined_vars());
	 */
	public static function get_runtime_defined_vars(array $varList, $excludeList = array())
	{
		/**

		 * $a = 1;

		 * function abc($c = 2) {
		 * global $a;
		 * $b = 3;

		 * $a = 4;
		 * $GLOBALS['s'] = 5;

		 * get_runtime_defined_vars(get_defined_vars(), array('b'));
		 * }
		 * abc();
		 * get_runtime_defined_vars(get_defined_vars(), array('b'));

		 * Array
		 * (
		 * [c] => 2
		 * [a] => 4
		 * )
		 * Array
		 * (
		 * [a] => 4
		 * [s] => 5
		 * )
		 **/

		if ($varList)
		{
			$excludeList = array_merge((array )$excludeList, array(
				'GLOBALS',
				'_FILES',
				'_COOKIE',
				'_POST',
				'_GET',
				'_SERVER',
				));
			$varList = array_diff_key((array )$varList, array_flip($excludeList));
		}

		//print_r($varList);

		return $varList;
	}

	public static function error_reporting($level = null, $add = null)
	{
		if ($level === null)
		{
			return error_reporting();
		}
		else
		{

			if (defined('E_DEPRECATED'))
			{
				$level = $level ^ E_DEPRECATED;
			}

			if ($add !== null)
			{
				$level |= $add;
			}

			return error_reporting($level);
		}
	}

	/**
	 * Checks if the class method exists in the given object .
	 *
	 * @return bool
	 */
	public static function func_exists($object, $method_name = null)
	{
		return $method_name === null ? function_exists($object) : method_exists($object, $method_name);
	}

}
