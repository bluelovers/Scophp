<?php

/**
 * @author bluelovers
 * @copyright 2012
 */

require_once ('../bootstrap.php');

foreach (array(
	'_a',
	'_b',
	'_c') as $func)
{
	echo '----------------------------------' . LF;

	$func_ref = new Sco_Reflection_Function($func);

	var_dump($func_ref, $func_ref->getBody(), $func_ref->getParameters());

	var_dump((string)Sco_CodeGenerator_Php_Function::fromReflection($func_ref));
}

function _a($r, $r2)
{
	return 999;
}

function _b($t)
{
	return 999;
}

function &_c($t)
{
	return 999;
}
