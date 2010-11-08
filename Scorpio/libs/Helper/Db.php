<?php

/**
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
	class scodb extends Scorpio_Helper_Db_Core {
	}
}

class Scorpio_Helper_Db_Core {
	protected $driver = 'mysql';
	protected static $driver_core = null;
	protected static $instances = null;

	public static function &instance($driver = null, $core = false) {
		if ($driver == null && static::$instances) return static::$instances;

		$args = func_get_args();

		if ($core) {
			static::$instances = call_user_func_array('Scorpio_Db::instance', $args);
		} else {
			$ref = new ReflectionClass(get_called_class());
			static::$instances = $ref->newInstanceArgs((array)$args);
		}

		return static::$instances;
	}

	public function &__construct($driver = 'mysql') {
		$this->driver = $driver == 'mysqli' ? $driver : 'mysql';

		if (static::driver_core == null || static::driver_core[$this->driver] == null) {
			$ref = new ReflectionClass('scodb_'.$this->driver);
			static::driver_core[$this->driver] =& $ref->newInstance();
		}

		static::$instances = $this;

		return $this;
	}

	public function &__call($method, $args = array()) {
		$ref = call_user_func_array(array($this->driver_core, $method), $args);
		return $ref;
	}

	public static function &__callStatic($method, $args = array()) {
		$ref = static::instance()->__call($method, $args);
		return $ref;
	}
}

?>