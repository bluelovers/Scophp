<?php

/**
 * @author bluelovers
 * @copyright 2012
 */

require_once ('../bootstrap.php');

var_dump($get_included_files = get_included_files(), get_include_path());

var_dump(class_exists('Sco_Yaml'));

var_dump(array_diff(get_included_files(), $get_included_files));

var_dump(Sco_Yaml::dump($get_included_files));

$ref = new Zend_Reflection_Class('Sco_Yaml');
var_dump($ref->getParentClass(), $ref->getFileName());

var_dump(class_exists('Symfony\Component\Yaml\Yaml', false));

var_dump($yaml = Sco_Yaml::dump(array('a' => ' src="1.png" id=\'9\'')), Sco_Yaml::parse($yaml));
