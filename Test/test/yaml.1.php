<?php

/**
 * @author bluelovers
 * @copyright 2012
 */

require_once ('../bootstrap.php');

$arr = new Sco_Array();


$arr->k = 1;

$arr[] = array(
	array(
		new Sco_Array(),
	),

	clone $arr,

	'd' => array(),
);

$arr->kk = array(
	123,
	new Sco_Array(),
	clone $arr,
);

$arr[] = array();

$dump = Sco_Array_Dumper_Helper::toArrayRecursive($arr);

var_dump($dump, Sco_Yaml::dump($dump));

$dump = Sco_Array_Dumper_Helper::toArrayRecursive($arr, false);

var_dump($dump, Sco_Yaml::dump($dump));
