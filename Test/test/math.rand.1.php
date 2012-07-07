<?php

/**
 * @author bluelovers
 * @copyright 2012
 */

require_once ('../bootstrap.php');

Sco_Math_Helper::rand_seed();

$benchmark->run(100, 'Sco_Math_Helper::rand');
$result = $benchmark->get();
var_dump($result['mean']);

$r = array();

for ($i = 0; $i < 10; $i++)
{
	$r[Sco_Math_Helper::rand()]++;
}

ksort($r);

var_dump($r);

$benchmark->run(100, 'Sco_Math_Helper::mt_rand');
$result = $benchmark->get();
var_dump($result['mean']);

$r = array();

for ($i = 0; $i < 10; $i++)
{
	$r[Sco_Math_Helper::mt_rand()]++;
}

ksort($r);

var_dump($r);

Sco_Math_Helper::rand_seed();

$benchmark->run(100, 'Sco_Math_Rand_Helper::rand');
$result = $benchmark->get();
var_dump($result['mean']);

$r = array();

for ($i = 0; $i < 10; $i++)
{
	$r[Sco_Math_Rand_Helper::rand()]++;
}

ksort($r);

var_dump($r);

Sco_Math_Helper::rand_seed();

$benchmark->run(100, 'Sco_Math_Rand_Helper::mt_rand');
$result = $benchmark->get();
var_dump($result['mean']);

$r = array();

for ($i = 0; $i < 10; $i++)
{
	$r[Sco_Math_Rand_Helper::mt_rand()]++;
}

ksort($r);

var_dump($r);