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
	array('name' => 'Hans Albert', 'last' => 'Einstein'));

function seek(&$array, $offset)
{

	if ($offset == 0)
	{
		return reset($array);
	}
	elseif ($offset < 0)
	{
		return end($array);
	}
	else
	{
		reset($array);
		for ($i = 0; $i < $offset; $i++)
		{
			next($array);
		}
		return current($array);
	}
}

var_dump($data);

var_dump(current($data));
var_dump(Sco_Array_Helper::seek($data, 3));
var_dump(current($data));
var_dump(Sco_Array_Helper::seek($data, Sco_Array::SEEK_END));
var_dump(current($data));

exit;

require_once 'Benchmark/Iterate.php';

class_exists('Sco_Array_Comparer_Helper');

$benchmark = new Benchmark_Iterate;

$data = array(
	array('name' => 'Albert', 'last' => 'Einstein'),
	array('name' => 'Lieserl', 'last' => 'Einstein'),
	array('name' => 'Alan', 'last' => 'Turing'),
	array('name' => 'Mileva', 'last' => 'Einstein'),
	array('name' => 'Hans Albert', 'last' => 'Einstein'));

@$benchmark->run(100, 'Sco_Array_Sorter_Helper::merge_sort', &$data, 'sort_some_people');

$result = $benchmark->get();
var_dump($result['mean']);

Sco_Array_Sorter_Helper::merge_sort(&$data, 'sort_some_people');

print_r($data);

$data = array(
	array('name' => 'Albert', 'last' => 'Einstein'),
	array('name' => 'Lieserl', 'last' => 'Einstein'),
	array('name' => 'Alan', 'last' => 'Turing'),
	array('name' => 'Mileva', 'last' => 'Einstein'),
	array('name' => 'Hans Albert', 'last' => 'Einstein'));

@$benchmark->run(100, 'Sco_Array_Sorter_Helper::merge_sort_assoc', &$data, 'sort_some_people');

$result = $benchmark->get();
var_dump($result['mean']);

Sco_Array_Sorter_Helper::merge_sort_assoc(&$data, 'sort_some_people');

print_r($data);

$data = array(
	array('name' => 'Albert', 'last' => 'Einstein'),
	array('name' => 'Lieserl', 'last' => 'Einstein'),
	array('name' => 'Alan', 'last' => 'Turing'),
	array('name' => 'Mileva', 'last' => 'Einstein'),
	array('name' => 'Hans Albert', 'last' => 'Einstein'));

@$benchmark->run(100, 'usort', &$data, 'sort_some_people');

$result = $benchmark->get();
var_dump($result['mean']);

uasort(&$data, 'sort_some_people');

print_r($data);

function sort_some_people($a, $b)
{
	return strcmp($a['last'], $b['last']);
}

$data = array(
	array('name' => 'Albert', 'last' => 'Einstein'),
	array('name' => 'Lieserl', 'last' => 'Einstein'),
	array('name' => 'Alan', 'last' => 'Turing'),
	array('name' => 'Mileva', 'last' => 'Einstein'),
	array('name' => 'Hans Albert', 'last' => 'Einstein'));

function dec(&$v, $k)
{
	$v = array($v, $k);
}
function undec(&$v, $k)
{
	$v = $v[0];
}

array_walk($data, 'dec'); // decorate
uasort(&$data, 'sort_some_people'); // sort
array_walk($data, 'undec'); // undecorate

var_dump($data);
