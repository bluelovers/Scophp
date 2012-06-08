<?php

/**
 * @author bluelovers
 * @copyright 2012
 */

require_once ('../bootstrap.php');

printf('%s aaaaaaaaaaaa %s');

$vprintf[0] = '[%-20s] [%20s] %.3f %(num).3f %%s %%%s %%%s%% Hello, %(place)s, how is it hanning at %(place)s? %s works just as well %(name)s: %(value)d %s %d%% %.3f';
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

Sco_Text_Format::suppressArgvWarnings(true);

echo $vprintf[0] . NL;
echo str_repeat('-', 80) . NL;

$time = microtime(true);

echo vsprintf($vprintf[0], $vprintf_argv[0]) . NL;

printf('Processed in %.8f second(s)' . NL, microtime(true) - $time);

echo str_repeat('-', 80) . NL;

Sco_Text_Format::matchMode(1);

$time = microtime(true);

echo Sco_Text_Format::vsprintf($vprintf[0], $vprintf_argv[0]) . NL;

printf('Processed in %.8f second(s)' . NL, microtime(true) - $time);

echo str_repeat('-', 80) . NL;

Sco_Text_Format::matchMode(2);

$time = microtime(true);

echo Sco_Text_Format::vsprintf($vprintf[0], $vprintf_argv[0]) . NL;

printf('Processed in %.8f second(s)' . NL, microtime(true) - $time);

echo str_repeat('-', 80) . NL;

$time = microtime(true);

echo sprintfn2($vprintf[0], $vprintf_argv[0]) . NL;

printf('Processed in %.8f second(s)' . NL, microtime(true) - $time);

echo str_repeat('-', 80) . NL;

$time = microtime(true);

echo Sco_Text_Format::vsprintf('=========Processed in %.8f second(s)=========', array(microtime(true) - $time)). NL;

printf('Processed in %.8f second(s)' . NL, microtime(true) - $time);

echo str_repeat('-', 80) . NL;

$time = microtime(true);

echo sprintf('=========Processed in %.8f second(s)=========', microtime(true) - $time) . NL;

printf('Processed in %.8f second(s)' . NL, microtime(true) - $time);

echo str_repeat('-', 80) . NL;

$time = microtime(true);

echo sprintfn('second: %second$s ; first: %first$s', array('first' => '1st', 'second' => '2nd')) . NL;

printf('Processed in %.8f second(s)' . NL, microtime(true) - $time);

echo str_repeat('-', 80) . NL;

$time = microtime(true);

echo sprintfn2('second: %(second)s ; first: %(first)s', array('first' => '1st', 'second' => '2nd')) . NL;

printf('Processed in %.8f second(s)' . NL, microtime(true) - $time);

echo str_repeat('-', 80) . NL;

$time = microtime(true);

echo Sco_Text_Format::sprintf('%\'_10s %\'=10s %010d', 'ds', 'ds', 51) . NL;

printf('Processed in %.8f second(s)' . NL, microtime(true) - $time);

echo str_repeat('-', 80) . NL;

$time = microtime(true);

echo Sco_Text_Format::vsprintf('%\'_10s %\'=10s %(v3)\'.-10d %0+10d', array('ds', 'ds', 'v3' => 51, 15, 75)) . NL;

printf('Processed in %.8f second(s)' . NL, microtime(true) - $time);

/**
 * version of sprintf for cases where named arguments are desired (php syntax)
 *
 * with sprintf: sprintf('second: %2$s ; first: %1$s', '1st', '2nd');
 *
 * with sprintfn: sprintfn('second: %second$s ; first: %first$s', array(
 *  'first' => '1st',
 *  'second'=> '2nd'
 * ));
 *
 * @param string $format sprintf format string, with any number of named arguments
 * @param array $args array of [ 'arg_name' => 'arg value', ... ] replacements to be made
 * @return string|false result of sprintf call, or bool false on error
 */
function sprintfn($format, array $args = array())
{
	// map of argument names to their corresponding sprintf numeric argument value
	$arg_nums = array_slice(array_flip(array_keys(array(0 => 0) + $args)), 1);

	// find the next named argument. each search starts at the end of the previous replacement.
	for ($pos = 0; preg_match('/(?<=%)([a-zA-Z_]\w*)(?=\$)/', $format, $match, PREG_OFFSET_CAPTURE, $pos); )
	{
		$arg_pos = $match[0][1];
		$arg_len = strlen($match[0][0]);
		$arg_key = $match[1][0];

		// programmer did not supply a value for the named argument found in the format string
		if (!array_key_exists($arg_key, $arg_nums))
		{
			user_error("sprintfn(): Missing argument '${arg_key}'", E_USER_WARNING);
			return false;
		}

		// replace the named argument with the corresponding numeric one
		$format = substr_replace($format, $replace = $arg_nums[$arg_key], $arg_pos, $arg_len);
		$pos = $arg_pos + strlen($replace); // skip to end of replacement for next iteration
	}

	return vsprintf($format, array_values($args));
}

/**
 * version of sprintf for cases where named arguments are desired (python syntax)
 *
 * with sprintf: sprintf('second: %2$s ; first: %1$s', '1st', '2nd');
 *
 * with sprintfn: sprintfn('second: %(second)s ; first: %(first)s', array(
 *  'first' => '1st',
 *  'second'=> '2nd'
 * ));
 *
 * @param string $format sprintf format string, with any number of named arguments
 * @param array $args array of [ 'arg_name' => 'arg value', ... ] replacements to be made
 * @return string|false result of sprintf call, or bool false on error
 */
function sprintfn2($format, array $args = array())
{
	// map of argument names to their corresponding sprintf numeric argument value
	$arg_nums = array_slice(array_flip(array_keys(array(0 => 0) + $args)), 1);

	// find the next named argument. each search starts at the end of the previous replacement.
	for ($pos = 0; preg_match('/(?<=%)\(([a-zA-Z_]\w*)\)/', $format, $match, PREG_OFFSET_CAPTURE, $pos); )
	{
		$arg_pos = $match[0][1];
		$arg_len = strlen($match[0][0]);
		$arg_key = $match[1][0];

		// programmer did not supply a value for the named argument found in the format string
		if (!array_key_exists($arg_key, $arg_nums))
		{
			user_error("sprintfn(): Missing argument '${arg_key}'", E_USER_WARNING);
			return false;
		}

		// replace the named argument with the corresponding numeric one
		$format = substr_replace($format, $replace = $arg_nums[$arg_key] . '$', $arg_pos, $arg_len);
		$pos = $arg_pos + strlen($replace); // skip to end of replacement for next iteration
	}

	return vsprintf($format, array_values($args));
}
