<?php

/**
 * @author bluelovers
 * @copyright 2010
 */

if (0) {
	// for IDE
	class scodb_mysqli extends Scorpio_helper_db_mysqli_Core_ {
	}
}

class Scorpio_helper_db_mysqli_Core_ extends Scorpio_helper_db_mysql {
	protected $func_prefix = 'mysqli_';

	public static function &instance() {
		$class = __CLASS__;

		if (!isset($this->$instances[$class])) {
			self::$instances[$class] = new $class;
		}

		return self::$instances[$class];
	}
}

?>