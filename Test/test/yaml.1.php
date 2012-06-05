<?php

/**
 * @author bluelovers
 * @copyright 2012
 */

require_once ('../bootstrap.php');

$arr = new Sco_Array();

$dump = Sco_Yaml::dump($arr);

var_dump($dump);
