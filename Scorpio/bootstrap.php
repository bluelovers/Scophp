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

if (!defined('SCORPIO_SYSPATH'))
{
	include_once dirname(__FILE__) . '/Const/Env.php';
	include_once dirname(__FILE__) . '/Sco/File.php';

	define('SCORPIO_SYSPATH', Sco_File::dirname(__FILE__, '', 1));
}
else
{
	require_once SCORPIO_SYSPATH . 'Const/Env.php';
}

set_include_path(SCORPIO_SYSPATH . PATH_SEPARATOR . get_include_path());

Zend_Loader::loadClass('Sco_Loader_Autoloader');

Sco_Loader_Autoloader::getInstance()->pushAutoloader(SCORPIO_SYSPATH, 'Sco_', true)->setDefaultAutoloader(array('Sco_Loader', 'loadClass'));

error_reporting($error_reporting);
