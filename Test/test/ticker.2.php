<?php

/**
 * @author bluelovers
 * @copyright 2012
 */

require_once ('../bootstrap.php');

$ticker = new Sco_Ticker_Iterator(array(1, 3, 9));

$i = 9;

//var_dump($ticker->apply('addTicker', array(&$i)));
var_dump($ticker->apply('_test', array(&$i)));

var_dump($i);

var_dump($ticker);