<?php

/**
 * @author bluelovers
 * @copyright 2012
 */

class Sco_Date_Interval extends ArrayObject
{

	const WEEK = 604800;
	const DAY = 86400;

	const HOUR = 3600;
	const MINUTE = 60;
	const SECOND = 1;

	protected $_timestamp;

	protected $_interval_spec;

	protected $_options = array(
		'microtime' => true,
	);

	public function __construct($interval_spec)
	{
		parent::__construct(array(
			'y' => 0,
			'm' => 0,
			'd' => 0,
			'h' => 0,
			'i' => 0,
			's' => 0,
			'invert' => 0,
			'days' => false,

			'u' => null,
			), self::ARRAY_AS_PROPS);

		$this->_interval_spec = (string )$interval_spec;

		list($timestamp, $r) = $this->_parse_spec($this->_interval_spec);

		$this->_timestamp = abs($timestamp);

		foreach ($r as $_k => $_v)
		{
			$this[$_k] = $_v;
		}

		if ($timestamp < 0)
		{
			$this->invert = 1;
		}
	}

	public static function createFromDateString($time)
	{
		$now = time();

		$end = strtotime($time, $now);

		$timestamp = $end - $now;

		return new self("PT{$timestamp}S");
	}

	public function format($format)
	{
		return $format;
	}

	public function getSpec($recalculate = false, $microtime = null)
	{
		if ($microtime === null)
		{
			$microtime = $this->_options['microtime'];
		}

		if ($recalculate)
		{
			return self::formatSpec($this->calSpec(false), $microtime);
		}

		return self::formatSpec($this, $microtime);
	}

	public static function formatSpec($arr, $microtime = true)
	{
		return sprintf('P%dY%dM%dDT%dH%dM%dS', $arr['y'], $arr['m'], $arr['d'], $arr['h'], $arr['i'], $arr['s']).(($microtime && $arr['u'] > 0) ? sprintf(Sco_Date_Helper::MICROTIME_PRINTF.'U', $arr['u']) : '');
	}

	public function calSpec($update = true)
	{
		$s = 0;
		foreach (array(
			'd',
			'h',
			'i',
			's') as $k)
		{
			switch ($k)
			{
				case 'd':
					$s += $this[$k] * self::DAY;
					break;
				case 'h':
					$s += $this[$k] * self::HOUR;
					break;
				case 'i':
					$s += $this[$k] * self::MINUTE;
					break;
				case 's':
					$s += $this[$k] * self::SECOND;
					break;
			}
		}

		$r = array();

		foreach (array(
			'd',
			'h',
			'i',
			's') as $k)
		{
			switch ($k)
			{
				case 'd':
					$r[$k] = floor($s / self::DAY);
					break;
				case 'h':
					$r[$k] = floor($s / self::HOUR) % 24;
					break;
				case 'i':
					$r[$k] = floor($s / self::MINUTE) % 60;
					break;
				case 's':
					$r[$k] = floor($s / self::SECOND) % 60;
					break;
			}
		}

		if ($update)
		{
			foreach ($r as $k => $v)
			{
				$this[$k] = $r[$k];
			}

			return $this;
		}
		else
		{
			$arr = $this->getArrayCopy();

			foreach ($r as $k => $v)
			{
				$arr[$k] = $r[$k];
			}

			return $arr;
		}
	}

	protected function _parse_spec($interval_spec)
	{
		$timestamp = 0;

		if (strpos($interval_spec, 'P') === 0)
		{
			$spec = explode('T', substr($interval_spec, 1), 2);

			$r = array();

			if ($str = $spec[0])
			{
				$pos = 0;
				while (preg_match('/(\d+)([YMDW])/', $str, $match, PREG_OFFSET_CAPTURE, $pos))
				{
					$u = strtolower($match[2][0]);
					$r[$u] = (int)$match[1][0];

					$pos = $match[2][1] + 1;
				}
			}

			if ($str = $spec[1])
			{
				$pos = 0;
				while (preg_match('/(\d+)([HMSU])/', $str, $match, PREG_OFFSET_CAPTURE, $pos))
				{
					$u = strtolower($match[2][0]);

					if ($u == 'm') $u = 'i';

					if ($u == 'u')
					{
						$r[$u] = $match[1][0];
					}
					else
					{
						$r[$u] = (int)$match[1][0];
					}

					$pos = $match[2][1] + 1;
				}
			}

			if ($r['w'])
			{
				$r['d'] += $r['w'] * 7;
				unset($r['w']);
			}
		}

		return array($timestamp, $r);
	}

}
