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
	class scodate extends Scorpio_helper_date_Core_ {
	}
}

class Scorpio_helper_date_Core_ {

	/**
	 * @return scodate
	 */
	protected static $instances = null;

	protected $_scorpio_attr = array();

	protected static $_scorpio_get_called_class = 'scodate';

	/**
	 * @return scodate
	 */
	public static function &instance($overwrite = false) {

		if ($overwrite && !in_array($overwrite, array(true, 1), true)) {
			$_overwrite = $overwrite;
		} else {
			$_overwrite = self::_scorpio_get_called_class();
		}

		if ($overwrite || !self::$instances) {
			$_scorpio_ref = new ReflectionClass($_overwrite);
			self::$instances = $_scorpio_ref->newInstance();
		}

		return self::$instances;
	}

	/**
	 * like get_called_class — the "Late Static Binding" class name
	 */
	protected static function _scorpio_get_called_class() {
		if (version_compare(PHP_VERSION, '5.3.0', '>=')) {
			return get_called_class();
		} else {
			return self::$_scorpio_get_called_class;
		}
	}

	/**
	 * @return scodate
	 */
	public function __construct() {

		if (!self::$instances) {
			self::$instances = $this;
		}

//		print_r(array(get_called_class(), class_parents(static ::$instances), class_parents(self ::$instances), class_parents(get_called_class())));

		// 初始化 timestamp
		$this->timestamp(true);

		return $this;
	}

	public function __get($k) {
		return $this->_scorpio_attr[$k];
	}

	/**
	 * @return scodate
	 */
	public function __set($k, $v) {
		$this->_scorpio_attr[$k] = $v;

		return $this;
	}

	public function __isset($k) {
		return isset($this->_scorpio_attr[$k]);
	}

	/**
	 * @return scodate
	 */
	public function __unset($k) {
		unset($this->_scorpio_attr[$k]);

		return $this;
	}

	public function __toString() {
		return (string)$this->timestamp();
	}

	public function get($k) {
		return $this->__get($k);
	}

	/**
	 * @return scodate
	 */
	public function set($k, $v) {
		return $this->__set($k, $v);
	}

	public function microsecond($update = false) {

		if ($update === true) {
			$this->timestamp(true);
		}

		return $this->get('microsecond');
	}

	public function timestamp($update = false) {
		if ($update === true) {
			list($microsecond, $timestamp) = explode(' ', microtime());

			$microsecond = substr($microsecond, 0, 10);

			$this->set('microsecond', (string)$microsecond);

			$microsecond = substr($microsecond, 1);
			$this->set('timestamp', (string)$timestamp . (string)$microsecond);

		} elseif ($update !== true && $update > 0) {

			if (strpos($update, ' ') === false) {
				list($timestamp, $microsecond) = explode('.', $update);

				$microsecond = $update - $timestamp;

				/**
				 * 0.51142200
				 */
				$microsecond = substr($microsecond, 0, 10);

				$this->set('microsecond', (string)$microsecond);
				$microsecond = substr($microsecond, 1);
			} elseif (strpos($update, ' ') !== false) {
				list($microsecond, $timestamp) = explode(' ', $update);

				$microsecond = substr($microsecond, 0, 10);

				$this->set('microsecond', (string)$microsecond);
				$microsecond = substr($microsecond, 1);
			}
			$this->set('timestamp', (string)$timestamp . (string)$microsecond);
		}

		return $this->get('timestamp');
	}

	public function offsetfix() {
		$this->set('offsetfix', $this->offset($this->get('locale.timezone')));
		$this->set('offset', 0 - $this->offset('GMT'));

		return $this->get('offsetfix');
	}

	public function gmdate($format, $timestamp = null) {
		$timestamp = null === $timestamp ? $this->get('timestamp') : $timestamp;

		if (strpos($format, 'u') !== false) {
			$format = preg_replace('`(?<!\\\\)u`', sprintf('%06d', ($timestamp - (int)$timestamp) *
				1000000), $format);
		}

		$ret = gmdate($format, $timestamp);

		return $ret;
	}

	public function date($format, $timestamp = null) {
		$timestamp = null === $timestamp ? $this->timestamp() : $timestamp;
		//		$timestamp += (self::offsetfix() - php::instance()->offset);

		if (strpos($format, 'u') !== false) {
			$format = preg_replace('`(?<!\\\\)u`', sprintf('%06d', ($timestamp - (int)$timestamp) *
				1000000), $format);
		}

		return date($format, $timestamp);
	}

	/**
	 * http://tw2.php.net/manual/en/function.strtotime.php#87900
	 **/
	public function gmstrtotime($str) {
		return strtotime($str . " UTC");
	}

	public function strtotime($str, $now = 0, $skip = 0) {
		//BUG:need fix in safemode

		$now = $now ? $now : $this->get('timestamp');

		if (@$d = strtotime($str, $now)) {
			if ($skip > 0) {
				return $d;
			} elseif (preg_match('/(?<!ETC\/)(GMT([\+\-]\d))/i', $str, $match)) {

				//print_r($match);

				return $d - (int)$match[2]*3600 - $this->get('offsetfix');
			} elseif ($skip == 0 && preg_match('/(ETC\/GMT[\+\-]\d|UTC|CST|MDT|EAT)/i', $str, $match)) {

				$dd = $this->offset($match[0]);

				return $d + $dd;
			} else {

				if ($skip == 0 && preg_match('/(?:\d(T)\d.*)?(\+[0-9]{2}\:?[0-9]{2})/i', $str, $match)) {

					//print_r($match);

					$match[2] = (int)$match[2];

					if ($match[1] == 'T') {
						$d += $this->offset('GMT');
						if ($match[2]) $d += $match[2]*3600;
					}

					if ($match[1] == '' && !$match[2]) {
						$d += -$this->get('offsetfix') - $this->get('offset');
					}
				}

				return $d - $this->get('offsetfix');
			}
		} else {
			return 0;
		}

/*

echo '<br>date_default_timezone_get: '.date_default_timezone_get();
echo '<br>offsetfix: '.scophp::instance()->offsetfix/3600;
echo '<br>offset: '.scophp::instance()->offset/3600;
echo '<br>UTC offset: '.scodate::offset('UTC')/3600;

foreach(array(scoexpires::$format, 'c', 'D, d M Y H:i:s', 'e', 'r') as $_) {
	echo '<br>==============================';
	echo '<br>'.$_;
	echo '<br>==========';
	echo '<br>'.scodate::gmdate($_);
	echo '<br>'.scodate::gmdate($_, scodate::strtotime(scodate::gmdate($_)));
	echo '<br>----------';
	echo '<br>'.scodate::date($_);
	echo '<br>'.scodate::date($_, scodate::strtotime(scodate::date($_)));
}

Output:

date_default_timezone_get: Asia/Taipei
offsetfix: 16
offset: -8
UTC offset: -8
==============================
D, d M Y H:i:s T
==========
Wed, 27 Jan 2010 11:11:22 GMT+8
Wed, 27 Jan 2010 11:11:22 GMT+8
----------
Wed, 27 Jan 2010 11:11:22 CST
Wed, 27 Jan 2010 11:11:22 CST
==============================
c
==========
2010-01-27T11:11:22+00:00
2010-01-27T11:11:22+00:00
----------
2010-01-27T11:11:22+08:00
2010-01-27T11:11:22+08:00
==============================
D, d M Y H:i:s
==========
Wed, 27 Jan 2010 11:11:22
Wed, 27 Jan 2010 11:11:22
----------
Wed, 27 Jan 2010 11:11:22
Wed, 27 Jan 2010 11:11:22
==============================
e
==========
UTC
UTC
----------
Asia/Taipei
Asia/Taipei
==============================
r
==========
Wed, 27 Jan 2010 11:11:22 +0000
Wed, 27 Jan 2010 11:11:22 +0000
----------
Wed, 27 Jan 2010 11:11:22 +0800
Wed, 27 Jan 2010 11:11:22 +0800
==============================
H:i:s
==========
11:20:20
11:20:20
----------
11:20:20
11:20:20

*/

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
	public function offset($remote, $local = true, $when = 'now') {
		if ($local === true) {
			$local = date_default_timezone_get();
		}
		if ($remote === null) {
			return 0;
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