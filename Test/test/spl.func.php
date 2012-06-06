<?php

/**
 * @author bluelovers
 * @copyright 2012
 */

require_once ('../bootstrap.php');

Sco_Spl_Helper::createFunction('d', 'var_dump');

d(time());

Sco_Spl_Helper::createFunction('a', 'd');

a(time());

$callback = new Sco_Spl_Callback('a', time(), microtime(true));

$callback->exec(123);

$callback->exec();