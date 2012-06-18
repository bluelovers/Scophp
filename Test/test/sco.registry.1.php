<?php

/**
 * @author bluelovers
 * @copyright 2012
 */

require_once ('../bootstrap.php');

//Zend_Registry::set('a', 456);

Sco_Registry::setClassName('Sco_Registry');
Sco_Registry::setInstance(Sco::registry());

Sco_Registry::set('a', 123);

var_dump(Sco_Registry::get('a'), Zend_Registry::get('a'));

var_dump(Sco_Registry::getInstance());