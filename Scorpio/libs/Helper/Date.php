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
	class scodate extends Scorpio_Helper_Date_Core {
	}
}

class Scorpio_Helper_Date_Core {
	protected static $instances = null;

	// 取得構造物件
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

	// 建立構造
	function __construct() {

		// make sure static::$instances is newer
		// 當未建立 static::$instances 時 會以當前 class 作為構造類別
		// 當已建立 static::$instances 時 如果呼叫的 class 不屬於當前 static::$instances 的父類別時 則會自動取代; 反之則 不做任何動作
		if (!static::$instances || !in_array(get_called_class(), class_parents(static::$instances))) {
			static::$instances = $this;
		}

//		print_r(array(get_called_class(), class_parents(static ::$instances), class_parents(self ::$instances), class_parents(get_called_class())));

		return static::$instances;
	}

	public static function timestamp($update = 0) {
		return $update ? scophp::set('timestamp', microtime(true))->get('timestamp') : scophp::get('timestamp');
	}

	public static function offsetfix() {
		scophp::set('offsetfix', scodate::offset(Scorpio_Kenal::config('locale.timezone')));
		scophp::set('offset', 0 - scodate::offset('GMT'));

		return scophp::get('offsetfix');
	}

	public static function gmdate($format, $timestamp = null) {
		$timestamp = null === $timestamp ? scophp::get('timestamp') : $timestamp;
		//$timestamp += static::offsetfix() + scophp::get('offset');
		$timestamp += static::offsetfix() + scophp::get('offset');

		$args = array();

		if (strpos($format, 'u') !== false) {
			$format = preg_replace('`(?<!\\\\)u`', sprintf('%06d', ($timestamp - (int)$timestamp) *
				1000000), $format);
		}

		if (strpos($format, 'T') !== false) {
			$i = count($args);

			$format = preg_replace('`(?<!\\\\)T`', '[:'.$i.':]', $format);
			$args[$i] = 'GMT+'.((scophp::get('offset')+scophp::get('offsetfix'))/3600);
		}

		$ret = gmdate($format, $timestamp);

		if ($args) {
			for($i = 0; $i<count($args); $i++) {
				$ret = str_replace('[:'.$i.':]', $args[$i], $ret);
			}
		}

		return $ret;
	}

	public static function date($format, $timestamp = null) {
		$timestamp = null === $timestamp ? static::timestamp() : $timestamp;
		//		$timestamp += (static::offsetfix() - php::instance()->offset);

		if (strpos($format, 'u') !== false) {
			$format = preg_replace('`(?<!\\\\)u`', sprintf('%06d', ($timestamp - (int)$timestamp) *
				1000000), $format);
		}

		return date($format, $timestamp);
	}

	/**
	 * http://tw2.php.net/manual/en/function.strtotime.php#87900
	 **/
	function gmstrtotime($str) {
		return strtotime($str . " UTC");
	}

	public static function strtotime($str, $now = 0, $skip = 0) {
		//BUG:need fix in safemode

		$now = $now ? $now : scophp::get('timestamp');

		if (@$d = strtotime($str, $now)) {
			if ($skip > 0) {
				return $d;
			} elseif (preg_match('/(?<!ETC\/)(GMT([\+\-]\d))/i', $str, $match)) {

				//print_r($match);

				return $d - (int)$match[2]*3600 - scophp::get('offsetfix');
			} elseif ($skip == 0 && preg_match('/(ETC\/GMT[\+\-]\d|UTC|CST|MDT|EAT)/i', $str, $match)) {

				$dd = scodate::offset($match[0]);

				return $d + $dd;
			} else {

				if ($skip == 0 && preg_match('/(?:\d(T)\d.*)?(\+[0-9]{2}\:?[0-9]{2})/i', $str, $match)) {

					//print_r($match);

					$match[2] = (int)$match[2];

					if ($match[1] == 'T') {
						$d += scodate::offset('GMT');
						if ($match[2]) $d += $match[2]*3600;
					}

					if ($match[1] == '' && !$match[2]) {
						$d += -scophp::get('offsetfix') - scophp::get('offset');
					}
				}

				return $d - scophp::get('offsetfix');
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
	public static function offset($remote, $local = true, $when = 'now') {
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