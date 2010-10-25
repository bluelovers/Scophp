<?php

/*
	Scorpio Developer Team (C)2000-2010 Bluelovers Net.

	$HeadURL$
	$Revision$
	$Author: bluelovers$
	$Date$
	$Id$
*/

if (!function_exists("lc")) {
	function lc($str) {
		return scotext::strtolower($str);
	}
}

if (!function_exists("uc")) {
	function uc($str) {
		return scotext::strtoupper($str);
	}
}

if (!function_exists("length")) {
	function length($str) {
		return is_string($str) ? scotext::strlen($str) : count($str);
	}
}

?>