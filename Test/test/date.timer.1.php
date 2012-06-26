<?php

/**
 * @author bluelovers
 * @copyright 2012
 */

require_once ('../bootstrap.php');

var_dump(Sco_Date_Helper::secondsToTimeString('0 seconds'));
var_dump(Sco_Date_Interval::createFromDateString('10 days, 015 microseconds'));

$interval_spec = 'P1WT3H5M360S015U';
//$interval_spec = 'P1W3DT015U';
//$interval_spec = 'P0Y0M7DT3H5M360S';

$interval = new Sco_Date_Interval($interval_spec);

var_dump($interval, $interval->getSpec(), $interval->formatRelative(), $interval->getTimestamp());

var_dump($interval->format('%m month, %D days, %R%a days %U, %u, %w week'));

//exit;

$interval2 = new DateInterval($interval->getSpec(null, false));

var_dump($interval2, $interval2->format('%m month, %D days, %R%a days %U, %u, %w week'));

var_export((array)$interval2);

foreach ($interval as $k => $v)
{
	if (isset($interval2->$k) && $interval->$k !== $interval2->$k)
	{
		var_dump($k, $interval->$k, $interval2->$k);
	}
}