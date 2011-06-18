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
	protected static $instances = null;

	protected static $_scorpio_ref = null;
	protected $_scorpio_attr = array();

	// 取得構造物件
	public static function &instance($overwrite = false) {

		if ($overwrite && !in_array($overwrite, array(true, 1), true)) {
			$_overwrite = $overwrite;
		} else {
			$_overwrite = self::_scorpio_get_called_class();
		}

		if ($overwrite || !self::$instances) {
			self::$_scorpio_ref = new ReflectionClass($_overwrite);
			self::$instances = self::$_scorpio_ref->newInstance();
		}

		return self::$instances;
	}

	protected static function _scorpio_get_called_class() {
		if (version_compare(PHP_VERSION, '5.3.0', '>=')) {
			return get_called_class();
		} else {
			return 'scodate';
		}
	}

	// 建立構造
	public function __construct() {

		if (!self::$instances) {
			self::$instances = $this;
		}

//		print_r(array(get_called_class(), class_parents(static ::$instances), class_parents(self ::$instances), class_parents(get_called_class())));

		return $this;
	}

	public function __get($k) {
		return $this->_scorpio_attr[$k];
	}

	public function &__set($k, $v) {
		$this->_scorpio_attr[$k] = $v;

		return $this;
	}

	public function __isset($k) {
		return isset($this->_scorpio_attr[$k]);
	}

	public function __unset($k) {
		unset($this->_scorpio_attr[$k]);

		return $this;
	}

	public function get($k) {
		return $this->__get($k);
	}

	public function set($k, $v) {
		return $this->__set($k, $v);
	}

	public function timestamp($update = 0) {
		if ($uopdate) {
			$this->set('timestamp', microtime(true));
		}

		return $this->get('timestamp');
	}

	public function offsetfix() {
		$this->set('offsetfix', $this->offset(Scorpio_Kenal::config('locale.timezone')));
		$this->set('offset', 0 - $this->offset('GMT'));

		return $this->get('offsetfix');
	}

	public function gmdate($format, $timestamp = null) {
		$timestamp = null === $timestamp ? $this->get('timestamp') : $timestamp;
		//$timestamp += self::offsetfix() + scophp::get('offset');
		$timestamp += $this->offsetfix() + $this->get('offset');

		$args = array();

		if (strpos($format, 'u') !== false) {
			$format = preg_replace('`(?<!\\\\)u`', sprintf('%06d', ($timestamp - (int)$timestamp) *
				1000000), $format);
		}

		if (strpos($format, 'T') !== false) {
			$i = count($args);

			$format = preg_replace('`(?<!\\\\)T`', '[:'.$i.':]', $format);
			$args[$i] = 'GMT+'.(($this->get('offset')+$this->get('offsetfix'))/3600);
		}

		$ret = gmdate($format, $timestamp);

		if ($args) {
			for($i = 0; $i<count($args); $i++) {
				$ret = str_replace('[:'.$i.':]', $args[$i], $ret);
			}
		}

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