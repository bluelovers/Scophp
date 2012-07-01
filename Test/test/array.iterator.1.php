<?php

/**
 * @author bluelovers
 * @copyright 2012
 */

require_once ('../bootstrap.php');

function print_caps() {

	var_dump($this);

    //echo strtoupper($iterator->current()) . "\n";
    return TRUE;
}

$it = new ArrayIterator(array("Apples", "Bananas", "Cherries"));
iterator_apply($it, "print_caps", array());

var_dump($it);