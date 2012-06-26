<?php

/**
 * @author bluelovers
 * @copyright 2012
 */

require_once ('../bootstrap.php');

var_dump(Sco_Date_Helper::secondsToTimeString('0 seconds'));

$interval_spec = 'P1WT3H5M360S015U';
//$interval_spec = 'P0Y0M7DT3H5M360S';

$interval = new Sco_Date_Interval($interval_spec);

var_dump($interval, $interval->getSpec(), $interval->getSpec(true), $interval->getSpec(), $interval->calSpec(false), $interval->calSpec(), $interval->getSpec());

$interval2 = new DateInterval($interval->getSpec(null, false));

$interval2->m = 10;

var_dump($interval2, $interval2->format('%a days'));

var_export((array)$interval2);

foreach ($interval as $k => $v)
{
	if (isset($interval2->$k) && $interval->$k !== $interval2->$k)
	{
		var_dump($k, $interval->$k, $interval2->$k);
	}
}