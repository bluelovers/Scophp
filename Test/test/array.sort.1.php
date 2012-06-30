<?php

/**
 * @author bluelovers
 * @copyright 2012
 */

require_once ('../bootstrap.php');

$data = array(
	array('name' => 'Albert', 'last' => 'Einstein'),
	array('name' => 'Lieserl', 'last' => 'Einstein'),
	array('name' => 'Alan', 'last' => 'Turing'),
	array('name' => 'Mileva', 'last' => 'Einstein'),
	array('name' => 'Hans Albert', 'last' => 'Einstein'),
	);

var_dump($data);

echo '-------------------------'.NL;

//$benchmark->run(100, 'Sco_Array_Sorter_Helper::stable_asort', $data);
//$result = $benchmark->get();
//var_dump($result['mean']);
//
//var_export(Sco_Array_Sorter_Helper::stable_asort($data));
//
//$benchmark->run(100, 'Sco_Array_Sorter_Helper::stable_asort2', $data);
//$result = $benchmark->get();
//var_dump($result['mean']);
//
//var_export(Sco_Array_Sorter_Helper::stable_asort2($data));
//
//$sorter = new Sco_Array_Sorter_Stable();
//
//$benchmark->run(100, array($sorter, 'usort'), &$data, 'sort_some_people');
//$result = $benchmark->get();
//var_dump($result['mean']);
//
//var_export($sorter->usort($data, 'sort_some_people'));

$benchmark->run(100, 'Sco_Array_Sorter_Helper::merge_sort_assoc', $data, 'sort_some_people');
$result = $benchmark->get();
var_dump($result['mean']);

var_export(Sco_Array_Sorter_Helper::merge_sort_assoc($data, 'sort_some_people'));

$benchmark->run(100, 'Sco_Array_Sorter_Helper::merge_sort_assoc', $data);
$result = $benchmark->get();
var_dump($result['mean']);

var_export(Sco_Array_Sorter_Helper::merge_sort_assoc($data));

$benchmark->run(100, 'Sco_Array_Sorter_Helper::merge_sort', $data);
$result = $benchmark->get();
var_dump($result['mean']);

var_export(Sco_Array_Sorter_Helper::merge_sort($data));

function sort_some_people($a, $b)
{
	return strcmp($a['last'], $b['last']);
}