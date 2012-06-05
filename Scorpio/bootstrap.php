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
	include_once dirname(__FILE__) . '/Const/Env.php';
	include_once dirname(__FILE__) . '/Sco/File.php';

	define('SCORPIO_PATH_SYS', Sco_File::dirname(__FILE__, '', 1));
	define('SCORPIO_PATH_TOP', Sco_File::dirname(__FILE__, '..', 1));
}
else
{
	require_once SCORPIO_PATH_SYS . 'Const/Env.php';
}

$exists = false;
$paths = array();
foreach (explode(PATH_SEPARATOR, get_include_path()) as $path)
{
	$path = Sco_File::path($path);

	if (!$exists && $path == SCORPIO_PATH_TOP)
	{
		$exists = true;
	}

	$paths[] = $path;
}

if (!$exists) array_unshift($paths, SCORPIO_PATH_TOP);

set_include_path(implode(PATH_SEPARATOR, $paths));
unset($paths, $path, $exists);

//Zend_Loader::loadClass('Sco_Loader_Autoloader', SCORPIO_PATH_SYS);
//Zend_Loader::loadClass('Sco_Loader', SCORPIO_PATH_SYS);
//Zend_Loader::loadFile('Scorpio/Sco/Loader.php');
Zend_Loader::loadFile('Scorpio/Sco/Loader/Autoloader.php');

Sco_Loader_Autoloader::getInstance()->pushAutoloader(SCORPIO_PATH_SYS, 'Sco_', true)->setDefaultAutoloader(array('Sco_Loader', 'loadClass'));

error_reporting($error_reporting);
