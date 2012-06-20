<?php

/**
 * @author bluelovers
 * @copyright 2012
 */

class Sco_PHP_Global
{

	public static $_SERVER;

	public static function init()
	{
		self::$_SERVER = $_SERVER;
	}

}
