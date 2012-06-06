<?php

/**
 * @author bluelovers
 * @copyright 2012
 */

require_once ('../bootstrap.php');

$ref = 9;

$array = array(
	'b' => 2,

	'ref' => &$ref,

	'ref2' => &$ref,

	'ref3' => &$ref,

	'c' => 3,
	'a' => 0,
	);

array_splice($array, 0, 0, array('a' => 1));

var_dump($array);

//var_dump(array_unshift_assoc($array, 'a', __LINE__));
array_unshift_assoc2($array, 'a', __LINE__);

var_dump($array);

array_push_assoc($array, 'ref', __LINE__);

var_dump($array, $ref);

var_dump(array_remove_key($array, 'ref2'));

var_dump($array, $ref);

var_dump(Sco_Array_Helper::array_unshift_assoc($array, 'ref3', __LINE__));
var_dump($array, $ref);

$array['g'] = null;

var_dump($array, $ref);

var_dump(Sco_Array_Helper::array_remove_key($array, array('a', 'b', 'g', 'ref3', 'c')));
var_dump($array, $ref);

function array_unshift_assoc(&$arr, $key, $val)
{
	$arr = array_reverse($arr, true);
	$arr[$key] = $val;
	return array_reverse($arr, true);
}

function array_unshift_assoc2(&$arr, $key, $val)
{
	array_remove_key(&$arr, $key);

	$arr = array_merge(array($key => $val), $arr);

	return $arr;
}

function array_push_assoc(&$arr, $key, $val)
{
	array_remove_key(&$arr, $key);

	$arr = array_merge($arr, array($key => $val));

	return $arr;
}

function array_remove_key(&$arr, $key)
{
	$old = $arr[$key];

		$null = null;
		$arr[$key] = &$null;
		unset($arr[$key]);

		return $old;
}