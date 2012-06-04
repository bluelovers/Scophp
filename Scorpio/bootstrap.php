<?php

/**
 * @author bluelovers
 * @copyright 2012
 */

$error_reporting = error_reporting(E_ALL ^ E_NOTICE ^ E_DEPRECATED);

//$_SCORPIO_KEY = md5(uniqid('get_included_files', true));
//$_ENV[$_SCORPIO_KEY]['get_included_files'] = get_included_files();

require_once ('Zend/Loader/Autoloader.php');

Zend_Loader_Autoloader::getInstance()
	->suppressNotFoundWarnings(true)
;

if (!defined('SCORPIO_SYSPATH')) {
	include_once dirname(__FILE__).'/Const/Env.php';
	include_once dirname(__FILE__).'/Sco/File.php';

	define('SCORPIO_SYSPATH', Sco_File::dirname(__FILE__, '', 1));
}
else
{
	require_once SCORPIO_SYSPATH.'Const/Env.php';
}

Zend_Loader::loadClass('Sco_Loader_Autoloader');

Sco_Loader_Autoloader::getInstance()
	->pushAutoloader(SCORPIO_SYSPATH, 'Sco_', true)
	->setDefaultAutoloader(array('Sco_Loader', 'loadClass'));
;

error_reporting($error_reporting);
