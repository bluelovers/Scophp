<?php

/**
 * @author bluelovers
 * @copyright 2012
 */

require_once ('../bootstrap.php');

$c = new Sco_Crypto_XOR();

var_dump($c->salt());

for ($i = 0; $i < 100; $i++)
{
	$e = $c->encode($i);
	$r = $c->decode($e);

	if ($r != $i)
	{
		var_dump($i, $e, $r);
	}
}

$i = '0000101';

$e = $c->encode($i);
$r = $c->decode($e);

var_dump($i, $e, $r);