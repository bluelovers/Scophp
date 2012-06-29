<?php

/**
 * @author bluelovers
 * @copyright 2012
 */

require_once ('../bootstrap.php');

$ticker = new Sco_Ticker_Iterator();

$ticker->a->addValue(9);

$ticker->b->subValue(5);

$ticker->f->addValue(1);
$ticker->c->addValue(10);
$ticker->d->addValue(1);
$ticker->append(2);
$ticker->append(12);
$ticker->append(20);
$ticker->append(1);
$ticker->append(1);
$ticker->e->addValue(1);
$ticker->append(1);
$ticker->append(2);
$ticker->append(12);
$ticker->append(20);
$ticker->append(1);
$ticker->append(1);
$ticker->append(2);
$ticker->append(12);
$ticker->append(20);
$ticker->append(1);
$ticker->append(1);
$ticker->append(2);
$ticker->append(12);
$ticker->append(20);
$ticker->append(1);
$ticker->append(1);

var_dump(count($ticker));

//print_r($ticker->getArrayCopy());

$benchmark = new Benchmark_Iterate;

$benchmark->run(100, array($ticker, 'sort'));

$result = $benchmark->get();
var_dump($result['mean']);

class_exists('Sco_Array_Comparer_Helper');

$benchmark->run(100, array($ticker, 'sort2'));

$result = $benchmark->get();
var_dump($result['mean']);

var_dump(count($ticker));
print_r($ticker);

//$benchmark->run(100, array($ticker, 'asort'));
//
//$result = $benchmark->get();
//var_dump($result['mean']);
