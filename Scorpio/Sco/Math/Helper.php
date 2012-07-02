<?php

/**
 * @author bluelovers
 * @copyright 2012
 */

class Sco_Math_Helper
{

	public static $rand = 1000000;

	public static function minmax($num, $min, $max)
	{
		return max($min, min($max, $num));
	}

	public static function rand_seed()
	{
		srand((microtime(true) - time()) * 10000000);
		mt_srand((microtime(true) - time()) * 10000000);
	}

	protected static function _srand()
	{
		$scale = 15;
		//bcscale(9);

		self::$rand = bcadd((float)microtime(true) - (float)time(), (float)self::$rand * mt_rand(-100, 200) / 100, $scale);

		return self::$rand;
	}

	protected static function _rand(array & $r, $retval)
	{
		shuffle($r['a']);

		$r['n3'] = $r['a'][$r['n1']];
		$r['n4'] = $r['a'][$r['n2']];

		if ($retval) return $r['c'] ? $r['n3'] : $r['n4'];

		if ($r['n4'] == $r['n3'] || $ra == $r['n3'])
		{
			$r['r'] = 2;
		}
		else
		{
			$r['n4'] = $ra ? $ra : $r['a'][$r['n2']];

			$r['r'] = $r['c'] ? (($r['n4'] >= $r['n3']) ? 1 : 0) : (($r['n4'] <= $r['n3']) ? 1 : 0);
		}
	}

	public static function rand($ra = 0, $rb = 0, $low = 1, $high = 100, $step = 1, $retval = true)
	{
		srand((float)microtime(true) * rand(-100, 200) / 100 * self::_srand());

		$r = array();

		$r['a'] = range($low, $high, ($step ? $step : 1));
		$r['n1'] = rand($low - 1, $high);
		$r['n2'] = rand($low - 1, $high);
		$r['c'] = rand(0, 1 + $rb);

		self::_rand($r['a'], $retval);

		return $r['r'];
	}

	public static function mt_rand($ra = 0, $rb = 0, $low = 1, $high = 100, $step = 1, $retval = true)
	{
		mt_srand((float)microtime(true) * mt_rand(-100, 200) / 100 * self::_srand() + ((float)microtime(true) - time()));

		$r = array();

		$r['a'] = range($low, $high, ($step ? $step : 1));
		$r['n1'] = mt_rand($low - 1, $high);
		$r['n2'] = mt_rand($low - 1, $high);
		$r['c'] = mt_rand(0, 1 + $rb);

		self::_rand($r['a'], $retval);

		return $r['r'];
	}

	/**
	 * fix -0 => 0
	 * @param $n
	 */
	public static function fixzero($n)
	{
		return $n ? $n : 0;
	}

	public static function sign($n)
	{
		if ($n)
		{
			return $n / abs($n);
		}
		else
		{
			return 0;
		}
	}

}
