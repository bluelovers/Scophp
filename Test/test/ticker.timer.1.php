<?php

/**
 * @author bluelovers
 * @copyright 2012
 */

require_once ('../bootstrap.php');

$timer = new Sco_Ticker_Timer();

var_dump((string)$timer);

$timers = new Sco_Ticker_Iterator_Timer();

$timers->setMarker('a1');

sleep(1);

$timers->setMarker('a2');

sleep(1.5);

$timers->setMarker('a1');

var_dump($timers->toArrayValues());

var_dump($timers->timeElapsed('a1', 'a2'));

