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

if (0) {
	// for IDE
	class Scrpio_SYS_Base extends Scrpio_SYS_Base_Core {}
	class Sco_Base extends Scrpio_SYS_Base {}
}

class Scrpio_SYS_Base_Core {

	protected static $instances = null;

	public static function instance($overwrite = false) {
		if (!self::$instances) {
			$ref = new ReflectionClass(($overwrite && !in_array($overwrite, array(true, 1), true)) ? $overwrite : 'Sco_Base');
			self::$instances = $ref->newInstance();
		} elseif ($overwrite) {
			$ref = new ReflectionClass(!in_array($overwrite, array(true, 1), true) ? $overwrite : get_class(self::$instances));
			self::$instances = $ref->newInstance();
		}

		return self::$instances;
	}

	function __construct() {

		// make sure self::$instances is newer
		if (!self::$instances || !in_array(get_class($this), class_parents(self::$instances))) {
			self::$instances = $this;
		}

		return self::$instances;
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