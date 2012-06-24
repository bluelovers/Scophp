<?php

/**
 * @author bluelovers
 * @copyright 2012
 */

require_once ('../bootstrap.php');

$get_loaded_extensions = get_loaded_extensions();

sort($get_loaded_extensions);

print_r($get_loaded_extensions);