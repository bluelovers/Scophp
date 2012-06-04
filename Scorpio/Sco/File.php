<?php

/**
 * @author bluelovers
 * @copyright 2012
 */

class Sco_File
{

	public static function dirname($path, $chdir = '', $dirnamefunc = false)
	{
		if ($dirnamefunc) $path = dirname($path);

		return ($chdir) ? self::path($path, $chdir) : self::path($path);
	}

	public static function fix($url)
	{
		// FIXME - fix url::fix regex

		return preg_replace(array( //			'/([\\/]+(\s*\.\s*[\\/]+)*)+/i',
			'/([\\\\\\/]+(\s*\.\s*[\\\\\\/]+)*)+/i',
			'/\/+[^\.\/:]+\/+([^\.\/:]+\/\s*\.\.\s*\/+)?\s*\.\.\s*\/+/i',
			'/(^|\/+)[^\.\/:]+\/+\s*\.\.\s*\/+/i',
			'/^\.\/+/i',
			'/(^|\/+)[^\.\/:]+\/+\s*\.\.\s*$/i',
			), array(
			'/',
			'/',
			'$1',
			'',
			'$1'), trim($url));
	}

	protected function _path_join()
	{
		$args = func_get_args();

		if (func_num_args() > 1)
		{
			$array = $args;
		}
		else
		{
			$array = $args[0];
			if (is_array($array[0]))
			{
				$array = $array[0];
			}
		}

		if (is_string($array)) return $array;

		$ret = '';
		while (empty($ret) && $ret !== 0 && $ret !== '0')
		{
			$ret = array_shift($array);
		}

		if (!empty($array))
		{
			foreach ($array as $_v)
			{
				$_v = trim($_v);
				if (empty($_v) && $_v !== 0 && $_v !== '0') continue;

				$ret .= '/' . $_v;
			}
		}

		return $ret;
	}

	public static function path()
	{
		$paths = func_get_args();
		return rtrim(self::fix(self::_path_join($paths)), '/') . '/';
	}

	public static function file()
	{
		$paths = func_get_args();
		return rtrim(self::fix(self::_path_join($paths)), '/');
	}

	public static function remove_root($path, $root)
	{
		$root = self::path($root);
		$path = self::file($path);

		$ret = (strpos($path, $root) === 0) ? substr($path, strlen($root)) : $path;

		return $ret;
	}

	public static function basename($path, $suffix = '')
	{
		return basename(array_shift(preg_split('/(\?|#)/', $path)), $suffix);
	}

}
