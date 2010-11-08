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
	protected $driver_core = null;
	protected $instances = null;

	public static function &instance() {
		$args = func_get_args();

		$ref = new ReflectionClass(get_called_class());
		$instances =& $ref->newInstanceArgs((array)$args);

		return $instances;
	}

	function &__construct($driver = 'mysql') {
		$this->driver = $driver == 'mysqli' ? $driver : 'mysql';

		$ref = new ReflectionClass('scodb_'.$this->driver);
		$this->driver_core =& $ref->newInstance();

		return $this;
	}

	public function &__call($method, $args) {
		$ref = call_user_func_array(array($this->driver_core, $method), $args);

		return $ref;
	}
}

?>