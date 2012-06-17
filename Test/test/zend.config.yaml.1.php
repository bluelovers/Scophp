<?php

/**
 * @author bluelovers
 * @copyright 2012
 */

require_once ('../bootstrap.php');

$config = new Zend_Config_Yaml(dirname(__FILE__) . '/Fixtures/zend.config.yaml.yml');

var_dump($config->toArray());

$writer = new Sco_Config_Writer_Yaml(array('config' => $config, 'filename' => 'zend.config.yaml.1.yml'));

$writer->write();

$config1 = new Zend_Config_Yaml(dirname(__FILE__) . '/zend.config.yaml.1.yml');

var_dump($config1->toArray());

var_dump($config->toArray() == $config1->toArray());