<?php

/**
 * @author bluelovers
 * @copyright 2012
 */

require_once ('../bootstrap.php');

var_dump($_COOKIE);

Sco_Cookie_Object::set('abc', 123);

var_dump(Sco_Cookie_Object::get('abc'));

Sco_Cookie_Object::set('abc', null);

Sco_Cookie_Object::save();

var_dump($_COOKIE);

var_dump(headers_list());