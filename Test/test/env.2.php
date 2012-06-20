<?php

/**
 * @author bluelovers
 * @copyright 2012
 */

require_once ('../bootstrap.php');

$_SERVER = new ArrayObject();

function a()
{
	$_SERVER['abc'] = 1;
}

a();

var_dump($_SERVER);