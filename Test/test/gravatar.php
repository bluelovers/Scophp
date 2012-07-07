<?php

/**
 * @author bluelovers
 * @copyright 2012
 */

require_once ('../bootstrap.php');

$email = "someone@somewhere.com";
$default = "http://www.somewhere.com/homestar.jpg";
$size = 40;

$grav_url = "http://www.gravatar.com/avatar/" . md5(strtolower(trim($email))) . "?d=" . urlencode($default) . "&s=" . $size;

$avatar = new Sco_Api_Avatar_Gravatar($email, $size, $default);

var_dump($avatar, (string )$avatar);
