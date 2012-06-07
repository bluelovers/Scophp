<?php

/**
 * @author bluelovers
 * @copyright 2012
 */

class Sco_Text_Format
{

	//const REGEX_PRINTF = '/(?<!%)(?<fultext>%+(?:\((?<varname>[a-zA-Z_]\w*)\))?(?<type>\-?[a-zA-Z\d\.]+|%))/';
	//const REGEX_PRINTF = '/(?<!%)(?<fultext>%+(?:\((?<varname>[a-zA-Z_]\w*)\))?(?<varname2>\d+\$)?(?<pad>[ 0]|\'.)?(?<type>[+\-]?[a-zA-Z\d\.]+|%))/';
	const REGEX_PRINTF = '/(?<!%)(?<fultext>%+(?:\((?<varname>[a-zA-Z_]\w*)\))?(?<varname2>\d+\$)?(?:[ 0]|\'.)?(?:[+\-]?[\d\.]+)?[bcdeEufFgGosxX])/';
	const TYPE_SPECIFIER = 'bcdeEufFgGosxX';

	const LOSTARGV_VISIBLE = 0;
	const LOSTARGV_PAD = 1;

	public static $_suppressArgvWarnings = false;
	public static $_handleLostArgv = LOSTARGV_VISIBLE;

	public static $_forceMode = false;
	public static $_matchMode = 0;

	public static function suppressArgvWarnings($flag = null)
	{
		if (null === $flag)
		{
			return self::$_suppressArgvWarnings;
		}

		$old = self::$_suppressArgvWarnings;

		self::$_suppressArgvWarnings = (bool)$flag;

		return $old;
	}

	public static function handleLostArgv($flag = null)
	{
		if (null === $flag)
		{
			return self::$_handleLostArgv;
		}

		$old = self::$_handleLostArgv;

		self::$_handleLostArgv = $flag;

		return $old;
	}

	public static function forceMode($flag = null)
	{
		if (null === $flag)
		{
			return self::$_forceMode;
		}

		$old = self::$_forceMode;

		self::$_forceMode = $flag;

		return $old;
	}

	public static function matchMode($flag = null)
	{
		if (null === $flag)
		{
			return self::$_matchMode;
		}

		$old = self::$_matchMode;

		self::$_matchMode = $flag;

		return $old;
	}

	/**
	 * python like syntax format
	 * Returns a string produced according to the formatting string format .
	 *
	 * @param string $format
	 * @param array $args
	 * @param mixed $args, mixed $...
	 *
	 * @see http://tw2.php.net/manual/en/function.sprintf.php#94608
	 * @see http://tw2.php.net/manual/en/function.vsprintf.php#89349
	 * @see http://www.php.net/manual/en/function.sprintf.php#93552
	 * @see http://archive.plugins.jquery.com/project/printf
	 *
	 * @example test script
	 * echo '<pre>';
	 *
	 * echo vsprintf('[%-20s] [%20s] %.3f %(num).3f %%s %%%s %%%s%% Hello, %(place)s, how is it hanning at %(place)s? %s works just as well %(name)s: %(value)d %s %d%% %.3f',
	 * array('place' => 'world333', 'sprintf', 'not used', 'num' => 'world666',
	 * 'sprintf', 'not used', 'name' => 'world999', 'sprintf', 'not used', 'value' =>
	 * 'world', 'sprintf', 'not used', 'sprintf', 'not used', 'sprintf', 'not used',
	 * 'sprintf', 'not used', ));
	 * echo "\n";
	 * echo self::sprintf("[%(test1)-20s] [%(test1)20s] [%(test1)020s] [%(test1)'#20s] [%(test1)20.20s]
	 * [%(test2)-20s] [%(test2)20s] [%(test2)020s] [%(test2)'#20s] [%(test2)20.20s]
	 * [%(test3)-20s] [%(test3)20s] [%(test3)020s] [%(test3)'#20s] [%(test3)20.20s]
	 *
	 * [%(test3)20.3s] [%(test3)20.1s] [%(test3)20.5s]
	 *
	 * \n%.3f %(num).3f %%s %%(value)s %(value)s %%%s %%%s%%  %%%%%s%%%% Hello, %(place)s, how is it hanning at %(place)s? %s works just as well %(name)s: %(value)d %s %d%% %.3f",
	 * array('test1' => 'escrzyaie', 'test2' => 'ěščřžýáíé', 'test3' => '姫とボイン',
	 * 'place' => 'world', 'sprintf', 'not used', 'name' => 9999, 'num' =>
	 * 645321.123456));
	 */
	public static function vsprintf($format, $args)
	{
		$args && $args = (array )$args;

		(self::$_forceMode || strpos($format, '%(') !== false) && self::_printf_match(&$format, &$matchs, &$args);

		return vsprintf($format, $args);
	}

	/**
	 * python like syntax format
	 * Returns a string produced according to the formatting string format .
	 *
	 * @param string $format
	 * @param array $args
	 * @param mixed $args, mixed $...
	 */
	public static function sprintf($format, $args = null)
	{
		return self::vsprintf($format, array_slice(func_get_args(), 1));
	}

	protected static function _printf_match(&$format, &$matchs, &$args)
	{
		if (preg_match_all(self::REGEX_PRINTF, $format, &$matchs))
		{
			self::_printf_filter(&$format, &$matchs, &$args);
		}
	}

	protected static function _printf_filter(&$format, &$matchs, &$args)
	{
		//$data = array();
		$k2 = $strtr = array();
		$_lost_args = false;

		$k = 0;
		$count = count($matchs['fultext']);

		$keys = array_flip(array_keys($args));

		for ($i = 0; $i < $count; $i++)
		{
			$fulltext = (string )$matchs['fultext'][$i];

			if (strpos(str_replace('%%', '', $fulltext), '%') !== 0)
			{
				continue;
			}

			$varname = (string )$matchs['varname'][$i];

			/*
			$data['fultext'][] = $fulltext;
			$data['varname'][] = $varname;
			$data['type'][] = $matchs['type'][$i];
			*/

			if ($varname)
			{
				if (array_key_exists($varname, $keys))
				{
					$strtr[$fulltext] = str_replace('(' . $varname . ')', ($keys[$varname] + 1) . '$', str_replace($matchs['varname2'][$i], '', $fulltext));
					$k2[] = ($keys[$varname] + 1) . '$';
				}
				elseif (self::$_handleLostArgv & self::LOSTARGV_PAD)
				{
					$k2[] = ++$k . '$';
					$strtr[$fulltext] = str_replace('(' . $varname . ')', $k . '$', $fulltext);
					$_lost_args[] = $varname;
				}
				else
				{
					$strtr[$fulltext] = '%' . $fulltext;
					$_lost_args[] = $varname;
				}
			}
			elseif ($matchs['varname2'][$i])
			{
				$k2[] = $matchs['varname2'][$i];
			}
			else
			{
				$k2[] = ++$k . '$';
				//$_args[$k] = reset(array_slice($args, $k, 1, true));
			}
		}

		if ($_lost_args || $k2)
		{
			$k2 && $k2 = array_unique($k2);

			if (self::$_suppressArgvWarnings)
			{
				$args = array_pad((array )$args, count((array )$k2), null);
			}
			else
			{
				//var_dump($matchs['fultext'], $args, $k2, $_lost_args, $strtr, implode(', ', (array)$_lost_args));

				throw new InvalidArgumentException(sprintf('Warning: %s(): Too few arguments [ %d ] or lost argument key [ %s ]', __METHOD__, count((array )$k2), implode(', ', (array )$_lost_args)));
			}
		}

		$strtr && $format = strtr($format, $strtr);

		//$args = $_args;

		var_dump($matchs['fultext'], $args, $k2, $strtr);

		return true;
	}

	public static function sprintf_quote($string, $remove = false)
	{
		return $string = $remove ? str_replace('%%', '%', $string) : str_replace('%', '%%', $string);
	}

	protected static function sprintf_parse($format)
	{
		preg_match('/^(?<pre>%+)?%(?<pad>\'(?<pad2>.)|(?<pad3>[0-9]))?(?<sign>-|\+)?(?<size>[1-9][0-9]*)(?:\.(?<size2>\d+))?(?<type>[a-zA-Z])$/', $format, $match);

		return $match;
	}

}
