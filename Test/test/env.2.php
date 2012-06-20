<?php

/**
 * @author bluelovers
 * @copyright 2012
 */

require_once ('../bootstrap.php');

$array_a = new ArrayIterator(array(
	'a',
	'b',
	'c'));
$array_b = new ArrayIterator(array(
	'd',
	'e',
	'f'));
$iterator = new AppendIterator;
$iterator->append($array_a);
$iterator->append($array_b);
foreach ($iterator as $current)
{
	echo $current . "\n";
}

exit;

Sco_PHP_Global::init();

$_ENV = new ArrayObject($_ENV, Sco_Array::ARRAY_PROP_BOTH);

function a()
{
	//$_SERVER['abc'] = 1;
	$_ENV['abc'] = 2;
}

a();

var_dump($_SERVER, $_ENV, $_GET, $_POST, $GLOBALS);
