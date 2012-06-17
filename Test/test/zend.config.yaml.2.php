<?php

/**
 * @author bluelovers
 * @copyright 2012
 */

require_once ('../bootstrap.php');

$config1 = new Zend_Config_Yaml(dirname(__FILE__) . '/zend.config.yaml.1.yml');

var_dump($config1->toArray());
