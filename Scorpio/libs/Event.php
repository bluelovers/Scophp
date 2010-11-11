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
	class Scorpio_Event extends Scorpio_Event_Core {}
}

class Scorpio_Event_Core {
	protected static $_events = array();

	protected static $_has_run = array();

	protected static $_last_event = array();
	protected static $_last_endevent = null;

	protected static $_config = array();

	protected static $_deep = 0;

	protected static $instances = null;

	// ���o�c�y����
	public static function &instance($overwrite = false) {
		if (!static::$instances) {
			$ref = new ReflectionClass(($overwrite && !in_array($overwrite, array(true, 1), true)) ? $overwrite:get_called_class());
			static::$instances = $ref->newInstance();
		} elseif ($overwrite) {
			$ref = new ReflectionClass(!in_array($overwrite, array(true, 1), true) ? $overwrite:get_called_class());
			static::$instances = $ref->newInstance();
		}

		return static::$instances;
	}

	// �إߺc�y
	function __construct() {

		// make sure static::$instances is newer
		// ���إ� static::$instances �� �|�H��e class �@���c�y���O
		// ��w�إ� static::$instances �� �p�G�I�s�� class ���ݩ��e static::$instances �������O�� �h�|�۰ʨ��N; �Ϥ��h ��������ʧ@
		if (!static::$instances || !in_array(get_called_class(), class_parents(static::$instances))) {
			static::$instances = $this;
		}

//		print_r(array(get_called_class(), class_parents(static ::$instances), class_parents(self ::$instances), class_parents(get_called_class())));

		return static::$instances;
	}

	public static function run($event, $args = array()) {
		static::$_has_run[] =& $event;

		isset(static::$_last_event[static::$_deep]) or static::$_last_event[static::$_deep] = array();

		array_push(static::$_last_event[static::$_deep], &$event);

		// do event hooks

		static::$_last_endevent =& $event;

		return static::instance();
	}

	public static function val() {
		$ret = static::$_last_endevent;

		return $ret;
	}

	public static function hook() {
		static::$_events[$event][] = new Scorpio_Hook($event);

		return static::instance();
	}
}

?>