<?php

/**
 * @author bluelovers
 * @copyright 2012
 */

require_once ('../bootstrap.php');

$ticker = new Sco_Ticker_Iterator();

$ticker->a->addTicker(9);

$ticker->b->subTicker(5);

$ticker->f->addTicker(1);
$ticker->c->addTicker(10);
$ticker->d->addTicker(1);
$ticker->append(2);
$ticker->append(12);
$ticker->append(20);
$ticker->append(1);
$ticker->append(1);
$ticker->e->addTicker(1);
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

var_dump(count($ticker));
print_r($ticker->toArrayValues());

//$benchmark->run(100, array($ticker, 'asort'));
//
//$result = $benchmark->get();
//var_dump($result['mean']);
