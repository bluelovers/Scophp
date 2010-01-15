<?

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
	class Scrpio_Event extends Scrpio_Event_Core {}
}

class Scrpio_Event_Core {
	protected static $_events = array();

	protected static $_has_run = array();

	protected static $_last_event = array();
	protected static $_last_endevent = null;

	protected static $_config = array();

	protected static $_deep = 0;

	protected static $instances = null;

	public static function instance($overwrite = false) {
		if (!self::$instances) {
			$ref = new ReflectionClass(($overwrite && !in_array($overwrite, array(true, 1), true)) ?
				$overwrite : 'Scrpio_Event');
			self::$instances = $ref->newInstance();
		} elseif ($overwrite) {
			$ref = new ReflectionClass(!in_array($overwrite, array(true, 1), true) ? $overwrite :
				get_class(self::$instances));
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

	function execute() {
		self::$_has_run[] &= $event;
		self::$_last_event[self::$_deep][] &= $event;

		// do event hooks

		self::$_last_endevent &= $event;

		return self::instance();
	}

	function val() {
		$ret = self::$_last_endevent;

		return $ret;
	}

	function hook() {
		self::$_events[$event][] = new Scrpio_Hook($event);

		return self::instance();
	}
}

?>