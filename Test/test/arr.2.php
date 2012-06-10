<?php

/**
 * @author bluelovers
 * @copyright 2012
 */

require_once ('../bootstrap.php');

class_exists('Sco_Array_Helper');

$array = array(
	'b' => 2,

	'ref' => &$ref,

	'ref2' => &$ref,

	'ref3' => &$ref,

	'c' => 3,
	'a' => 0,
	);

$time = microtime(true);

for ($i = 0; $i < 1000; $i++)
{
	Sco_Array_Helper::array_unshift_assoc($array, 'ref3', __LINE__);
}

printf('Processed in %.8f second(s)' . NL, microtime(true) - $time);
