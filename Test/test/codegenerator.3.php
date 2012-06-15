<?php

/**
 * @author bluelovers
 * @copyright 2012
 */

require_once ('../bootstrap.php');

$eval = new Sco_Spl_Callback_Eval('++$a; var_dump($a); $a = $a + 5; var_dump($a);', '&$a');

$a = 2;

$eval->exec(&$a);

var_dump($a);

$a = 2;

$eval->exec_array(array(&$a));

var_dump($a);

$a = 2;

$func = create_function('&$a', '++$a; var_dump($a); $a = $a + 5; var_dump($a);');

call_user_func($func, &$a);

var_dump($a);