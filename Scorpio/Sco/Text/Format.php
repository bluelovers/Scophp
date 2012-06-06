<?php

/**
 * @author bluelovers
 * @copyright 2012
 */

class Sco_Text_Format
{

	//const REGEX_PRINTF = '/(?<!%)(?<fultext>%+(?:\((?<varname>[a-zA-Z_]\w*)\))?(?<type>\-?[a-zA-Z\d\.]+|%))/';
	const REGEX_PRINTF = '/(?<!%)(?<fultext>%+(?:\((?<varname>[a-zA-Z_]\w*)\))?(?<pad>\'.\d+)?(?<type>\-?[a-zA-Z\d\.]+|%))/';

	public static $_suppressArgvWarnings = false;

	public function suppressArgvWarnings($flag = null)
	{
		if (null === $flag)
		{
			return self::$_suppressArgvWarnings;
		}

		$old = self::$_suppressArgvWarnings;

		self::$_suppressArgvWarnings = (bool)$flag;

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
		$args && $args = (array)$args;

		if (strpos($format, '%(') !== false && preg_match_all(self::REGEX_PRINTF, $format, &$matchs))
		{
			self::_printf_filter(&$format, &$matchs, &$args);
			//var_dump($matchs);
		}

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
	public static function sprintf($format, $args)
	{
		return self::vsprintf($format, array_slice(func_get_args(), 1));
	}

	protected static function _printf_filter(&$format, &$matchs, &$args)
	{
		//$data = array();
		$_args = $strtr = array();
		$_lost_args = false;

		$k = 0;
		$count = count($matchs['fultext']);

		for ($i = 0; $i < $count; $i++)
		{
			$fulltext = (string)$matchs['fultext'][$i];

			if (strpos(str_replace('%%', '', $fulltext), '%') !== 0)
			{
				continue;
			}

			$varname = (string)$matchs['varname'][$i];

			/*
			$data['fultext'][] = $fulltext;
			$data['varname'][] = $varname;
			$data['type'][] = $matchs['type'][$i];
			*/

			if ($varname)
			{
				if (array_key_exists($varname, $args))
				{
					$_args[$k] = $args[$varname];
				}
				else
				{
					$_args[$k] = null;
					$_lost_args[] = $varname;
				}

				$strtr[$fulltext] = str_replace('(' . $varname . ')', '', $fulltext);
			}
			else
			{
				$_args[$k] = reset(array_slice($args, $k, 1, true));
			}

			$k++;
		}

		if (!self::$_suppressArgvWarnings && ($_lost_args || count($args) < $k))
		{
			throw new InvalidArgumentException(sprintf('Warning: %s(): Too few arguments or lost argument key [%s]', __METHOD__, implode(', ', $_lost_argv)));
		}

		$strtr && $format = strtr($format, $strtr);

		$args = $_args;

		return true;
	}

	public static function sprintf_quote($string, $remove = false)
	{
		$string = $remove ? str_replace('%%', '%', $string) : str_replace('%', '%%', $string);
		return $string;
	}

	protected static function sprintf_parse($format)
	{
		preg_match('/^(?<pre>%+)?%(?<pad>\'(?<pad2>.)|(?<pad3>[0-9]))?(?<sign>-|\+)?(?<size>[1-9][0-9]*)(?:\.(?<size2>\d+))?(?<type>[a-zA-Z])$/', $format, $match);

		return $match;
	}

}
