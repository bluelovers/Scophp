<?php

/**
 * @author bluelovers
 * @copyright 2012
 */

$error_reporting = error_reporting(E_ALL ^ E_NOTICE ^ E_DEPRECATED);

define('SCORPIO_MICROTIME', microtime(true));
define('SCORPIO_TIME', (int)SCORPIO_MICROTIME);

define('REQUEST_TIME', SCORPIO_TIME);
define('REQUEST_MICROTIME', SCORPIO_MICROTIME);

$_SERVER['REQUEST_TIME'] = REQUEST_TIME;
$_SERVER['REQUEST_MICROTIME'] = REQUEST_MICROTIME;

//$_SCORPIO_KEY = md5(uniqid('get_included_files', true));
//$_ENV[$_SCORPIO_KEY]['get_included_files'] = get_included_files();

require_once ('Zend/Loader/Autoloader.php');

Zend_Loader_Autoloader::getInstance()->suppressNotFoundWarnings(true);

if (!defined('SCORPIO_PATH_SYS'))
{
	$path = dirname(__FILE__);

	Zend_Loader::loadFile('Const/Env.php', $path, true);
	!class_exists('Sco_File_Format') && Zend_Loader::loadClass('Sco_File_Format', $path, true);

	define('SCORPIO_PATH_SYS', Sco_File_Format::path($path));

	unset($path);
}
else
{
	Zend_Loader::loadFile('Const/Env.php', SCORPIO_PATH_SYS, true);
}

!defined('NL') && define('NL', LF);

if (!class_exists('Sco_File_Format') || !class_exists('Sco_Loader_Autoloader') || !class_exists('Sco_Loader'))
{
	$exists = false;
	$get_include_path = get_include_path();

	$dir_parent = realpath(SCORPIO_PATH_SYS . '../');

	foreach (explode(PATH_SEPARATOR, $get_include_path) as $path)
	{
		if (!$exists && realpath($path) == $dir_parent)
		{
			$exists = true;
			break;
		}
	}

	!$exists && set_include_path($dir_parent . PATH_SEPARATOR . $get_include_path);

	//Zend_Loader::loadClass('Sco_Loader_Autoloader', SCORPIO_PATH_SYS);
	//Zend_Loader::loadClass('Sco_Loader', SCORPIO_PATH_SYS);
	!class_exists('Sco_File_Format', false) && Zend_Loader::loadFile('Scorpio/Sco/File/Format.php', null, true);
	!class_exists('Sco_Loader', false) && Zend_Loader::loadFile('Scorpio/Sco/Loader.php', null, true);
	!class_exists('Sco_Loader_Autoloader', false) && Zend_Loader::loadFile('Scorpio/Sco/Loader/Autoloader.php', null, true);

	//!$exists && set_include_path($get_include_path);

	!$exists && set_include_path(Sco_File_Format::path($dir_parent) . PATH_SEPARATOR . $get_include_path);
	//var_dump(get_include_path());
}

Sco_Loader_Autoloader::getInstance()->pushAutoloader(SCORPIO_PATH_SYS, 'Sco_', true)->setDefaultAutoloader(array('Sco_Loader', 'loadClass'));

Sco::instance();

error_reporting($error_reporting);
unset($get_include_path, $path, $exists, $error_reporting, $dir_parent);