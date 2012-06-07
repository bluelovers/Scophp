<?php

/**
 * @author bluelovers
 * @copyright 2012
 */

require_once ('../bootstrap.php');

$test_list = array();

$vprintf[0] = '[%-20s] [%20s] %.3f %(num).3f %%s %%%s %%%s%% Hello, %(place)s, how is it hanning at %(place)s?, %(noexists)s, %(noexists2)s %s works just as well %(name)s: %(value)d %s %d%% %.3f';
$vprintf_argv[0] = array(
	'place' => 'world333',
	'sprintf',
	'not used',
	'num' => 'world666',
	'sprintf',
	'not used',
	'name' => 'world999',
	'sprintf',
	'not used',
	'value' => 'world',
	'sprintf',
	'not used',
	'sprintf',
	'not used',
	'sprintf',
	'not used',
	'sprintf',
	'not used',
	);

class_exists('Sco_Text_Format');

Sco_Text_Format::suppressArgvWarnings(Sco_Text_Format::ERROR_WARNING);
Sco_Text_Format::forceMode(true);

//Sco_Text_Format::handleLostArgv(Sco_Text_Format::LOSTARGV_VISIBLE);

array_push(&$test_list, array("%(num).3f", array('num' => 'world666')));

array_push(&$test_list, array("%("));
array_push(&$test_list, array("%)"));
array_push(&$test_list, array("%%"));
array_push(&$test_list, array("%$"));
array_push(&$test_list, array("%/"));
array_push(&$test_list, array("%\\"));
array_push(&$test_list, array("%0"));
array_push(&$test_list, array("%|"));
array_push(&$test_list, array("%%("));
array_push(&$test_list, array("%%)"));
array_push(&$test_list, array("%%%"));
array_push(&$test_list, array("%%$"));
array_push(&$test_list, array("%%/"));
array_push(&$test_list, array("%%\\"));
array_push(&$test_list, array("%%0"));
array_push(&$test_list, array("%%|"));
array_push(&$test_list, array("%(1\$s%)", array('123', array(456))));
array_push(&$test_list, array("%(s%)", array('123', array(456))));

array_push(&$test_list, array('%2$s ; %s ; first: %1$s ; %s ; second: %2$s ; first: %1$s ; %s %(v5)s', array('1st', '2nd')));
array_push(&$test_list, array('%2$s ; %s ; first: %1$s ; %s ; second: %2$s ; first: %1$s ; %s %%(v5)s', array('1st', '2nd')));


foreach ($test_list as $data)
{
	list($format, $args) = $data;

	echo str_repeat('=', 80) . LF;
	echo $format . LF;
	echo str_repeat('-', 80) . LF;

	$args = (array )$args;

	$time = microtime(true);

	$orig = vsprintf($format, $args);

	$time1 = microtime(true);

	$frame = Sco_Text_Format::vsprintf($format, $args);

	$time2 = microtime(true);

	$error = ($frame !== $orig || empty($frame));

	var_dump($orig);
	printf('Processed in %.8f second(s)' . LF, $time1 - $time);
	printf('<span style="color: %s">', $error ? 'red' : '#cccccc');
	var_dump($frame);
	echo ('</span>');
	printf('Processed in %.8f second(s)' . LF, $time2 - $time1);
}

exit;

echo $vprintf[0] . LF;
echo str_repeat('-', 80) . LF;

$time = microtime(true);

echo Sco_Text_Format::vsprintf($vprintf[0], $vprintf_argv[0]) . LF;

printf('Processed in %.8f second(s)' . LF, microtime(true) - $time);

$run = 1000;

Sco_Text_Format::matchMode(1);

printf('matchMode: %s' . LF, Sco_Text_Format::matchMode());

$time = microtime(true);

for ($i = 0; $i < $run; $i++)
{
	Sco_Text_Format::vsprintf($vprintf[0], $vprintf_argv[0]);
}

printf('Processed in %.8f second(s) / %.8f second(s)' . LF, $total = microtime(true) - $time, $total / $run);

Sco_Text_Format::matchMode(2);

printf('matchMode: %s' . LF, Sco_Text_Format::matchMode());

$time = microtime(true);

for ($i = 0; $i < $run; $i++)
{
	Sco_Text_Format::vsprintf($vprintf[0], $vprintf_argv[0]);
}

printf('Processed in %.8f second(s) / %.8f second(s)' . LF, $total = microtime(true) - $time, $total / $run);
