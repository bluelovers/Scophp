<?php

/**
 * @author bluelovers
 * @copyright 2012
 */

error_reporting(E_ALL ^ E_NOTICE ^ E_DEPRECATED);

if (!defined('SCORPIO_SYSPATH')) {
	require_once dirname(__FILE__).'/Const/Env.php';
	require_once dirname(__FILE__).'/Sco/File.php';

	define('SCORPIO_SYSPATH', Sco_File::dirname(__FILE__, '', 1));
}
else
{
	require_once SCORPIO_SYSPATH.'Const/Env.php';
}

