<?php

/**
 * @author bluelovers
 * @copyright 2012
 */

require_once ('../bootstrap.php');

Sco_Spl_Helper::createFunction('d', 'var_dump');

d(time());

Sco_Spl_Helper::createFunction('0a', 'd');

d(time());
