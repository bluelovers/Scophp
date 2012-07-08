<?php

/**
 * @author bluelovers
 * @copyright 2012
 */

require_once ('../bootstrap.php');

$a = 1;

_d(&$a, 2, 3);

var_dump($a);

exit;

_a(&$a, 2, 3);

var_dump($a);

_b(&$a, 2, 3);

var_dump($a);

var_dump(Sco_PHP_Helper::get_runtime_defined_vars(get_defined_vars()));

function _d()
{
	$args = Sco_PHP::func_get_args();

	$args[0] = 99;

	var_dump(get_defined_vars());

	var_dump(func_get_arg(3));
	var_dump(Sco_PHP::func_get_arg(3));
}

function _a()
{
	/*
	$a = &func_get_args();

	$a[0]++;

	var_dump($GLOBALS['a']);


	$b = func_get_arg(0);

	$b++;

	var_dump($GLOBALS['a']);
	*/

	$k = '$stack = debug_backtrace(); var_dump($stack); return $stack[0][\'args\'];';

	$a = eval($k);
	$a[0]++;

	var_dump($a);

	$stack = debug_backtrace();
	$args = $stack[0]["args"];

	$args[0]++;

	var_dump($args, $GLOBALS['a']);
}

function _b()
{
	$args = _c();

	$args[0] = 10;

	var_dump($args);
}

function &_c()
{
	$stack = debug_backtrace();
	return $stack[1]["args"];
}