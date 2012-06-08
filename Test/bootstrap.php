<?php

/**
 * @author bluelovers
 * @copyright 2012
 */

error_reporting(E_ALL ^ E_NOTICE ^ E_DEPRECATED);

require_once (dirname(__FILE__) . '/../Scorpio/bootstrap.php');

mb_internal_encoding('UTF-8');
header('content-type: text/html; charset: ' . 'UTF-8');

echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><pre>';

Sco_Loader_Autoloader::getInstance()->pushAutoloader('D:\Users\Documents\The Project\symfony\symfony1\Yaml', 'Symfony_Component_Yaml_');

register_shutdown_function('_shutdown_function');

function _shutdown_function()
{
	$unit = array(
		'b',
		'kb',
		'mb',
		'gb',
		'tb',
		'pb');

	$size1 = memory_get_usage();
	$size2 = memory_get_usage(true);

	$size1 = rtrim(bcdiv($size1, pow(1024, ($i = floor(log($size1, 1024)))), 4), '0.') . ' ' . $unit[$i];
	$size2 = rtrim(bcdiv($size2, pow(1024, ($i = floor(log($size2, 1024)))), 4), '0.') . ' ' . $unit[$i];

	$get_included_files = get_included_files();

	echo NL.NL;
	//printf('Processed in %.8f second(s), %d io, %s/%s.', microtime(true) - SCORPIO_MICROTIME, count(get_included_files()), $size1, $size2);

	$table = new Zend_Text_Table(array('columnWidths' => array(80)));
	$table->appendRow(array(sprintf('Processed in %.8f second(s), %d io, %s/%s.', microtime(true) - SCORPIO_MICROTIME, count($get_included_files), $size1, $size2)));
	echo $table;
}
