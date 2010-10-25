<?php

/**
 *
 * $HeadURL$
 * $Revision$
 * $Author$
 * $Date$
 * $Id$
 *
 * @author bluelovers
 * @copyright 2010
 */

if (!defined('SYSPATH')) {
	require_once dirname(__FILE__).'/libs/File.php';

	define('SYSPATH', Scorpio_File_Core::dirname(__FILE__, '..', 1));
}

include_once(SYSPATH . 'Scorpio/libs/Constants.php');
include_once(SYSPATH . 'Scorpio/libs/File.php');
include_once(SYSPATH . 'Scorpio/libs/Loader.php');

foreach (Scorpio_File_Core::scandir_ext('php', Scorpio_File_Core::dirname(SYSPATH.'Scorpio') . 'syntax') as $_file) {
	include_once(Scorpio_File_Core::dirname(SYSPATH.'Scorpio') . 'syntax/' . $_file);
}

unset($_file);

?>