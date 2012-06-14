<?php

/**
 * @author bluelovers
 * @copyright 2012
 */

require_once ('../bootstrap.php');

$time = microtime(true);

$Parameters = new Sco_CodeGenerator_Php_Parameters();

$Parameters->setParameters(array('_EVENT', '_ARGV'));

echo $Parameters->generate();

printf('Processed in %.8f second(s)' . NL, microtime(true) - $time);

$time = microtime(true);

$Parameters = new Sco_CodeGenerator_Php_Parameters();

$Parameters->setParameters(array('_EVENT', '_ARGV'));

echo $Parameters->generate();

printf('Processed in %.8f second(s)' . NL, microtime(true) - $time);

$func = new Sco_CodeGenerator_Php_Function();

$func->setName('test');
$func->setParameters($Parameters);
$func->setBody('return null;    //123' . TAB);

echo $func->generate(true);

$func_ref = new Sco_Reflection_Function('_shutdown_function');

//var_dump($func_ref->getStartLine(), $func_ref->getEndLine());

$func = Sco_CodeGenerator_Php_Function::fromReflection($func_ref);

echo $func;

$func_ref = new Sco_Reflection_Function('_a');

var_dump($func_ref->getStartLine(), $func_ref->getEndLine());

$func = Sco_CodeGenerator_Php_Function::fromReflection($func_ref);

echo $func;

/**

 * $func_ref = new Sco_Reflection_Function('var_dump');

 * var_dump($func_ref);

 * $func = Sco_CodeGenerator_Php_Function::fromReflection($func_ref);

 * echo $func;

 */

function _a()
{
	return 999;
}
