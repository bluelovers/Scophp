<?php

/**
 * @author bluelovers
 * @copyright 2012
 */

require_once ('../bootstrap.php');

$c = new Sco_Crypto_TEA_XXTEA();

for ($i = 0; $i < 100; $i++)
{
	$e = $c->encode($i);
	$r = $c->decode($e);

	if ($r !== (string)$i)
	{
		var_dump($i, $e, $r);
	}
}

$i = '0000101';

$e = $c->encode($i);
$r = $c->decode($e);

var_dump($i, $e, $r);

$e = Sco_Crypto_TEA_XXTEA::encode($i);
$r = Sco_Crypto_TEA_XXTEA::decode($e);

var_dump($i, $e, $r);