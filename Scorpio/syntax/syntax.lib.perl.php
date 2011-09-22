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
	/**
	 * Make a string lowercase
	 */
	function lc($str) {
		if (!isset(Scorpio_Kenal::$func[__FUNCTION__])) {
			Scorpio_Kenal::$func[__FUNCTION__] = array(
				'scotext', 'strtolower'
			);
		}

		return Scorpio_Kenal::_call_func(__FUNCTION__, $str);
	}
}

if (!function_exists("uc")) {
	/**
	 * Make a string uppercase
	 */
	function uc($str) {
		if (!isset(Scorpio_Kenal::$func[__FUNCTION__])) {
			Scorpio_Kenal::$func[__FUNCTION__] = array(
				'scotext', 'strtoupper'
			);
		}

		return Scorpio_Kenal::_call_func(__FUNCTION__, $str);
	}
}

if (!function_exists("length")) {
	/**
	 * Get string length
	 */
	function length($str) {
		return is_string($str) ? scotext::strlen($str) : count($str);
	}
}

?>