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
	class scodate extends Scrpio_helper_date_Core {}
}

class Scrpio_helper_date_Core {
	protected static $instances = null;

	public static function instance($overwrite = false) {
		if (!self::$instances) {
			$ref = new ReflectionClass(($overwrite && !in_array($overwrite, array(true, 1), true)) ? $overwrite : 'scodate');
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

	function timestamp() {
		return scophp::get('timestamp');
	}

	function offsetfix() {
		scophp::set('offsetfix', self::offset(Sco_Base::config('locale.timezone')));
		scophp::set('offset', 0 - scodate::offset('GMT'));

		return scophp::instance()->offsetfix;
	}

	function gmdate($format, $timestamp = null) {
		$timestamp = null === $timestamp ? scophp::get('timestamp') : $timestamp;
		$timestamp += self::offsetfix() + scophp::instance()->offset;
		$format = preg_replace('`(?<!\\\\)u`', sprintf("%06d",($timestamp - (int)$timestamp) * 1000000), $format);

		return gmdate($format, $timestamp);
	}

	function _date($format, $timestamp = null) {
		$timestamp = null === $timestamp ? scophp::get('timestamp') : $timestamp;
//		$timestamp += (self::offsetfix() - php::instance()->offset);
		$format = preg_replace('`(?<!\\\\)u`', sprintf("%06d",($timestamp - (int)$timestamp) * 1000000), $format);

		return date($format, $timestamp);
	}

	function strtotime ($str, $now=0) {
		$now = $now ? $now : scophp::get('timestamp');

		if (@$d = strtotime($str, $now)) {
			return $d - scophp::instance()->offsetfix;
		} else {
			return 0;
		}
	}
}

?>