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
	class Scorpio_Date extends Scorpio_Date_Core_ {}
}

/**
 * http://tw2.php.net/manual/en/class.datetime.php
 * http://tw2.php.net/manual/en/class.datetime.php#95830
 **/
class Scorpio_Date_Core_ extends DateTime {
	protected $_date = array();
	const SCO_ISO8601 = 'Y-m-d H:i:s';

	public static function __fixstrdate($s) {
		if (!$s) return $s;

		$s = scoregex::replace(array(
			/**
			 * http://tw2.php.net/manual/en/function.strtotime.php#100632
			 **/
			'/^\s*[a-z]{1,3}\s*,/i'

			/**
			 * UK dates (eg. 27/05/1990) won't work with strotime, even with timezone properly set.
			 * However, if you just replace "/" with "-" it will work fine.
			 * http://tw2.php.net/manual/en/function.strtotime.php#99149
			 **/
			, '/(?:^|\b|[^\d])(?:(\d+)[\/\.](\d+)[\/\.](\d+))(?:$|\b|[^\d])/is'
		), array(
			''
			, '$1-$2-$3'
		), $s);

		return $s;
	}

	public static function __strtotime(&$str, $timezone = -1, $now = 0) {

		if (
			strlen($str) <= 4
			&& scoregex::match('/^\s*[1-9]\d{1,3}\s*$/', $str)
		) {
//			if (
//				$str < 1970
////				|| $str > 2038
//			) return false;

			$s = mktime(0, 0, 0, 0, 0, $str, $timezone);

			return $s;
		}

		$s = static::__fixstrdate(&$str, $timezone);

		if(scoregex::match('/^\s*([012]?[0-9]?:[0-5]{1}\d\s*[aA|pP]?[mM]?)(\s+)(.+)\s*$/', $s, $m)) {
			/**
			 * Unlike "yesterday 14:00", "14:00 yesterday" will return 00:00 of yesterday.
			 * Here's a function that'll fix that:
			 * http://tw2.php.net/manual/en/function.strtotime.php#99992
			 **/
			$d = strtotime($m[3] . $m[2] . $m[1]);
		} elseif (!($d = @strtotime($s)) && ($d !== 0)) {

		}

		return $d;
	}

	public function __construct($time = 'now', $timezone = null) {

		if ($timezone == null) {
			$timezone = new DateTimeZone(scophp::date_default_timezone_get());
		} elseif (is_string($timezone)) {
			$timezone = new DateTimeZone($timezone);
		}

		if (!$time) $time = 'now';

		if (preg_match('/^\s*(?|(\d{10})|(\d{10})?(?:\.(\d*))?|(?:0+\.(\d+))\s+(\d+))\s*$/x', $time, $m)) {

			if (strlen($m[1]) <= 8) {
				$_t = $m[1];
				$m[1] = $m[2];
				$m[2] = $_t;
			}

			$time = date(static::SCO_ISO8601, $m[1]);

			$this->_date = array(
				intval($m[1]), $m[2]
			);
		} elseif (($m = static::__strtotime($time)) !== false) {
			$this->_date = array(
				$m, 0
			);
		}

		parent::__construct($time, $timezone);
	}

	/**
	 * Return Date in ISO8601 format
	 *
	 * @return String
	 */
	public function __toString() {
		return $this->format(static::SCO_ISO8601);
	}

	/**
	 * Return difference between $this and $now
	 *
	 * @param Datetime|String $now
	 * @return DateInterval
	 */
	public function diff($now = 'now', $absolute = false) {
		if(!($now instanceOf DateTime)) {
			$now = new static($now);
		}
		return parent::diff($now, $absolute);
	}

	public function getTimestamp() {
		if ($this->_date[0] === null || $this->_date[0] === false) {
			return false;
		} elseif ($this->_date[1]) {
			return $this->_date[0] . ($this->_date[1] ? '.' . $this->_date[1] : '');
		} else {
			return $this->_date[0];
		}
	}
}

?>