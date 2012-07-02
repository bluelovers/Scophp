<?php

/**
 * @author bluelovers
 * @copyright 2012
 */

class Sco_Math_Func_Distance
{

	/**
	 * Taxicab geometry, Manhattan distance, or Manhattan length
	 *
	 * @see http://zh.wikipedia.org/zh-hant/%E6%9B%BC%E5%93%88%E9%A0%93%E8%B7%9D%E9%9B%A2
	 * @see http://en.wikipedia.org/wiki/Taxicab_geometry
	 *
	 * @assert (10, 0) == 10
	 * @assert (0, 10) == 10
	 * @assert (5, 5) == 10
	 */
	public static function ManhattanDistance($x2, $y2, $x1 = 0, $y1 = 0, $return = false)
	{
		if ($return)
		{
			$x = $x2 - $x1;
			$y = $y2 - $y1;

			return array(abs($x) + abs($y), $x, $y);
		}

		return abs($x2 - $x1) + abs($y2 - $y1);
	}

	public static function HexagonDistance($x2, $y2, $x1 = 0, $y1 = 0, $return = false)
	{

	}

	/**
	 * @assert (5, 5) == 45
	 * @assert (-5, 5) == -45
	 * @assert (5, -5) == 135
	 * @assert (-5, -5) == -135
	 * @assert (5, 5, 0, 0, 1)) == 45
	 * @assert (-5, 5, 0, 0, 1)) == 315
	 * @assert (5, -5, 0, 0, 1)) == 135
	 * @assert (-5, -5, 0, 0, 1)) == 225
	 */
	public static function azimuth_compass($x2, $y2, $x1 = 0, $y1 = 0, $abs = false)
	{
		$x = $x2 - $x1;
		$y = $y2 - $y1;

		$p = 360*(atan2($x, $y) / (2*pi()));

		if ($abs && $p < 0)
		{
			$p += 360;
		}

		return $p;
	}

}
