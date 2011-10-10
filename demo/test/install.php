<?php

/**
 * @author bluelovers
 * @copyright 2011
 */

include_once './_include_header.php';

if (file_exists('install/install.php'))
{
	// Load the installation check
	return include 'install/install.php';
}

?>