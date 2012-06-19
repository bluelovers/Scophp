<?php

/**
 * @author bluelovers
 * @copyright 2012
 */

require_once ('../bootstrap.php');

var_dump($_COOKIE);

Sco_Cookie::set('abc', 123);
Sco_Cookie::set('a2', 123);

var_dump(Sco_Cookie::get('abc'));

Sco_Cookie::set('abc', null);

Sco_Cookie::save();

var_dump($_COOKIE);

var_dump(headers_list(), Sco_Cookie::getInstance());