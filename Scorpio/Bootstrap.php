<?php

/**
 * @author bluelovers
 * @copyright 2011
 *
 * @example include_once {path}.'Scorpio/Bootstrap.php';
 */

if (!defined('SCORPIO_SYSPATH')) {
	include_once dirname(__FILE__).'/libs/File.php';

	/**
	 * define Scorpio PHP Framework root path
	 */
	define('SCORPIO_SYSPATH', Scorpio_File_Core::dirname(__FILE__, '..', 1));
}

include_once SCORPIO_SYSPATH . 'Scorpio/libs/Kenal.php';

Scorpio_Kenal_Core_::_class_setup();

if (!defined('SCORPIO_SYNTAX')) {
	/**
	 * if true include syntax hack (ex: perl...)
	 */
	define('SCORPIO_SYNTAX', true);
}

if (SCORPIO_SYNTAX) {
	foreach (Scorpio_File_Core::scandir_ext('php', SCORPIO_SYSPATH.'Scorpio/syntax') as $_file) {
		include_once SCORPIO_SYSPATH.'Scorpio/syntax/' . $_file;
	}
}

unset($_file);

?>