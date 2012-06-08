<?php

/**
 * @author bluelovers
 * @copyright 2012
 */

/**
 * $t[] = Sco_Date_Helper::microtime();
 * $t[] = Sco_Date_Helper::microtime_split($t[0][1].$t[0][0]);
 * var_dump($t);
 */
class Sco_Date_Helper
{

	const MICROTIME_LEN = 8;

	public static function microtime($get_as_float = false, $microtime = null)
	{
		if ($microtime)
		{
			if (is_array($microtime))
			{
				list($microsec, $time) = $microtime;

				return array(
					sprintf('%-08.8s', (string )$microsec),
					(int)$time,
					(float)((float)$time + (float)('0.' . (string )$microsec)),
					);
			}
		}
		else
		{
			$microtime = microtime(true);
		}

		if ($get_as_float)
		{
			return (float)$microtime;
		}
		else
		{
			$time = floor($microtime);

			// 0.31520000
			//$microsec = bcsub((float)$microtime, (float)$time, 8);

			// 0.15699505805969
			$microsec = (float)$microtime - $time;

			return array(
				(string )substr($microsec, 2, self::MICROTIME_LEN),
				(int)$time,
				(float)$microtime,
				);
		}
	}

	public static function microtime_split($microtime)
	{
		$time = substr($microtime, 0, 10);
		$microsec = substr($microtime, 10, self::MICROTIME_LEN);

		return array((string )$microsec, (int)$time);
	}

	public static function date_format_fix($format, $timestamp)
	{
		$microtime = $u = null;

		$pos = 0;
		while (preg_match('`(?<!\\\\)u`', $format, $match, PREG_OFFSET_CAPTURE, $pos))
		{
			if (!isset($u))
			{
				if (!isset($microtime))
				{
					$microtime = self::microtime(false, $timestamp);
				}

				$u = sprintf('%-06.6s', $microtime[0]);
			}

			$format = substr_replace($format, $u, $match[0][1], 1);

			$pos = $match[0][1] + 6;
		}

		//var_dump($timestamp, $microtime, self::microtime_split($microtime[1].$microtime[0]));

		return $format;
	}

}
