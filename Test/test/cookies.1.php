<?php

/**
 * @author bluelovers
 * @copyright 2012
 */

require_once ('../bootstrap.php');

ob_start();

Sco_Cookie::setClassName('Sco_Cookie_Object');

var_dump($_COOKIE);

Sco_Cookie::set('abc', 123);
Sco_Cookie::set('a2', 123);

var_dump(Sco_Cookie::get('abc'));

Sco_Cookie::set('abc', null);

setcookie('out', 999);

Sco_Cookie::save();

var_dump($_COOKIE);

var_dump(headers_list(), Sco_Cookie::getInstance());

Sco_Cookie_Helper::header_remove_cookies();

var_dump(headers_list());