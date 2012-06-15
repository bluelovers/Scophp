<?php

/**
 * @author bluelovers
 * @copyright 2012
 */

require_once ('../bootstrap.php');

$data = array(

	'i' => 5,

);

function _hook($_EVENT, $_ARGV, $i)
{
	$_EVENT['data']['i']++;

	return is_int($i);
}

$event = Sco_Hook_Event::setHook('scohook', Null, '_hook')->setData('scohook', $data)->exec('scohook', 1);

var_dump($event, $data);

$event = Sco_Hook_Event::setData('scohook', &$data)->exec('scohook', 1);

var_dump($event, $data);

$event = Sco_Hook_Event::exec('scohook', null, 1);

var_dump($event, $data);

$event = Sco_Hook_Event::setData('scohook', &$data)->exec('scohook', 1);

var_dump($event, $data);