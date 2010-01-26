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
	class scodate extends Scrpio_helper_date_Core {
	}
}

class Scrpio_helper_date_Core {
	protected static $instances = null;

	public static function instance($overwrite = false) {
		if (!self::$instances) {
			$ref = new ReflectionClass(($overwrite && !in_array($overwrite, array(true, 1), true)) ?
				$overwrite : 'scodate');
			self::$instances = $ref->newInstance();
		} elseif ($overwrite) {
			$ref = new ReflectionClass(!in_array($overwrite, array(true, 1), true) ? $overwrite :
				get_class(self::$instances));
			self::$instances = $ref->newInstance();
		}

		return self::$instances;
	}

	protected function __construct() {

		// make sure self::$instances is newer
		if (!self::$instances || !in_array(get_class($this), class_parents(self::$instances))) {
			self::$instances = $this;
		}

		return self::$instances;
	}

	public static function timestamp() {
		return scophp::get('timestamp');
	}

	public static function offsetfix() {
		scophp::set('offsetfix', scodate::offset(Scrpio_Kenal::config('locale.timezone')));
		scophp::set('offset', 0 - scodate::offset('GMT'));

		return scophp::instance()->offsetfix;
	}

	public static function gmdate($format, $timestamp = null) {
		$timestamp = null === $timestamp ? scophp::get('timestamp') : $timestamp;
		$timestamp += self::offsetfix() + scophp::instance()->offset;

		if (strpos($format, 'u') !== false) {
			$format = preg_replace('`(?<!\\\\)u`', sprintf('%06d', ($timestamp - (int)$timestamp) *
				1000000), $format);
		}

		return gmdate($format, $timestamp);
	}

	public static function date($format, $timestamp = null) {
		$timestamp = null === $timestamp ? scophp::get('timestamp') : $timestamp;
		//		$timestamp += (self::offsetfix() - php::instance()->offset);

		if (strpos($format, 'u') !== false) {
			$format = preg_replace('`(?<!\\\\)u`', sprintf('%06d', ($timestamp - (int)$timestamp) *
				1000000), $format);
		}

		return date($format, $timestamp);
	}

	public static function strtotime($str, $now = 0) {
		$now = $now ? $now : scophp::get('timestamp');

		if (@$d = strtotime($str, $now)) {
			return $d - scophp::instance()->offsetfix;
		} else {
			return 0;
		}
	}

	/**
	 * Returns the offset (in seconds) between two time zones.
	 * @see     http://php.net/timezones
	 *
	 * @param   string          timezone to find the offset of
	 * @param   string|boolean  timezone used as the baseline
	 * @param   string          time at which to calculate
	 * @return  integer
	 */
	public static function offset($remote, $local = true, $when = 'now') {
		if ($local === true) {
			$local = date_default_timezone_get();
		}

		// Create timezone objects
		$remote = new DateTimeZone($remote);
		$local = new DateTimeZone($local);

		// Create date objects from timezones
		$time_there = new DateTime($when, $remote);
		$time_here = new DateTime($when, $local);

		// Find the offset
		return $remote->getOffset($time_there) - $local->getOffset($time_here);
	}
}

?>