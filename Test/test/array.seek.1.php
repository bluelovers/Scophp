<?php

/**
 * @author bluelovers
 * @copyright 2012
 */

require_once ('../bootstrap.php');

$data = array(
	"item1" => -1,
	"item2" => -1,
	"item3" => -1,
	"item4" => 0,
	"item5" => 2,
	"item6" => 2,
	"item7" => 1,
	);

var_dump(Sco_Array_Helper::seek($data, 3));

var_dump(current($data));

var_dump(@Sco_Array_Helper::seek($a = array(0, 1, 2, 3, 4, 5), 3));