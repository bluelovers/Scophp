<?php

/**
 * @author bluelovers
 * @copyright 2011
 */

if (!defined('SCORPIO_SYSPATH')) {
	include_once dirname(__FILE__).'/libs/File.php';

	define('SCORPIO_SYSPATH', Scorpio_File_Core::dirname(__FILE__, '..', 1));
}

include_once SCORPIO_SYSPATH . 'Scorpio/libs/Kenal.php';

Scorpio_Kenal_Core_::_class_setup();

foreach (Scorpio_File_Core::scandir_ext('php', SCORPIO_SYSPATH.'Scorpio/syntax') as $_file) {
	include_once SCORPIO_SYSPATH.'Scorpio/syntax/' . $_file;
}

unset($_file);

?>