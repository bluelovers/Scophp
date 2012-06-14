<?php

/**
 * @author bluelovers
 * @copyright 2012
 */

require_once ('../bootstrap.php');

$Parameters = new Sco_CodeGenerator_Php_Parameters();

$Parameters->setParameters(array('_EVENT', '_ARGV'));

echo $Parameters->generate();