<?php

/**
 * @author bluelovers
 * @copyright 2012
 */

require_once ('../bootstrap.php');

class_exists('Sco_Hook');
class_exists('Sco_Hook_Event');
class_exists('Sco_Spl_Callback_Hook');

var_dump(__LINE__);

$time = microtime(true);

$hook = new Sco_Hook('scohook');

printf('Processed in %.8f second(s)' . NL, microtime(true) - $time);

var_dump(__LINE__);

$time = microtime(true);

$hook->append(function ()
{
	$args = func_get_args();
	$_EVENT = array_shift($args);
	var_dump(__FUNCTION__);

	$_EVENT['data'] += 1;

	return Sco_Hook::RET_SUCCESS;
});

$hook->append(function ()
{
	$args = func_get_args();
	$_EVENT = array_shift($args);
	var_dump(__FUNCTION__, 2);

	$_EVENT['data'] += 1;

	return Sco_Hook::RET_SUCCESS;
});

printf('Processed in %.8f second(s)' . NL, microtime(true) - $time);

var_dump(__LINE__, $hook);

$time = microtime(true);

var_dump(__LINE__, $hook->exec(123, 789, 111111111111));

printf('Processed in %.8f second(s)' . NL, microtime(true) - $time);

var_dump(__LINE__, $hook);

$time = microtime(true);

var_dump(__LINE__, $hook->exec(123, 789, 111111111111));

printf('Processed in %.8f second(s)' . NL, microtime(true) - $time);

var_dump(__LINE__);

$time = microtime(true);

$event = new Sco_Hook_Event();

printf('Processed in %.8f second(s)' . NL, microtime(true) - $time);

var_dump(__LINE__);

$time = microtime(true);
$event[] = 'testevent';
printf('Processed in %.8f second(s)' . NL, microtime(true) - $time);

var_dump(__LINE__);

$time = microtime(true);
$event['testevent3'] = 'testevent3';
printf('Processed in %.8f second(s)' . NL, microtime(true) - $time);

var_dump(__LINE__);

$time = microtime(true);
$event['testevent2'] = $hook;
printf('Processed in %.8f second(s)' . NL, microtime(true) - $time);

var_dump(__LINE__, $event);

var_dump(__LINE__, $event['offsetGet']);
