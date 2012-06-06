<?php

/**
 * @author bluelovers
 * @copyright 2012
 */

require_once (dirname(__FILE__) . '/../Scorpio/bootstrap.php');

echo '<pre>';

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

	echo LF.LF;
	printf('Processed in %.8f second(s), %d io, %s/%s.', microtime(true) - SCORPIO_MICROTIME, count(get_included_files()), $size1, $size2);
}
