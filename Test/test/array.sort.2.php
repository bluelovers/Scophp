<?php

/**
 * @author bluelovers
 * @copyright 2012
 */

require_once ('../bootstrap.php');

$data =
	array(
				"item1" => -1,
				"item2" => -1,
				"item3" => -1,
				"item4" => 0,
				"item5" => 2,
				"item6" => 2,
				"item7" => 1);


var_dump($data);

echo '-------------------------'.NL;

var_dump(Sco_Array_Sorter_Helper::stable_asort2($data));