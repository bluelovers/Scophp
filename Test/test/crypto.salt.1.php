<?php

/**
 * @author bluelovers
 * @copyright 2012
 */

require_once ('../bootstrap.php');

//var_dump(Sco_Crypto_Salt::word_table('Jiangbin'));
//
//var_dump(Sco_Crypto_Salt::word_table(Sco_Crypto_Salt::uniqid()));
//
//var_dump(Sco_Crypto_Salt::word_table(Sco_Crypto_Salt::random()));

$c = new Sco_Crypto_Vigenere();
var_dump($e = $c->encode('I known what love is because of you 123 = +'));

var_dump($c->decode($e));

var_dump($e = $c->encode('PHP 123 实现维吉尼亚加密算法'));

var_dump($c->decode($e));

var_dump($e = $c->encode('123456789'));

var_dump($c->decode($e));
