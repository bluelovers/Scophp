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

class Scrpio_Base_Core {

	private static $instance;

	function __construct() {

	}

	public static function Init() {
		static $_do;

		if (!$_do) {
			$_do = true;

			require_once('./libs/File.php');

			$file = new Scrpio_File_Core();

			$base = $file->dirname(dirname(__FILE__), '..');

			foreach ($file->scandir_ext('php', $base.'syntax') as $file) {
				include_once($base.'syntax/'.$file);
			}
		}
	}

}

?>