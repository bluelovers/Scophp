<?

/**
 * @author bluelovers
 * @copyright 2011
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

	private $_sleep;

	protected $_date	= null;

	const SCO_ISO8601	= 'Y-m-d H:i:s';
	const SCO_ISO8601_U	= 'Y-m-d H:i:s u';

	// Second amounts for various time increments
	const S_YEAR		= 31556926;
	const S_MONTH		= 2629744;
	const S_WEEK		= 604800;
	const S_DAY			= 86400;
	const S_HOUR		= 3600;
	const S_MINUTE		= 60;

	const B_TIMEZONE	= 'GMT';
	const D_TIMEZONE	= 'Asia/Taipei';

	/**
	 * default timezone for DateTime
	 */
	static $D_TIMEZONE	= 'Asia/Taipei';

	public static function &instance($time = 'now', $timezone = null) {
		$_o = new Scorpio_Date($time, $timezone);
		return $_o;
	}

	public function __construct($time = 'now', $timezone = null) {
		$timezone = Scorpio_Date::_createDateTimeZone($timezone);

		if (
			!isset($time)
			|| $time == 'now'
		) {
			/*
			$time = 'now';
			*/
			$time = microtime(true);
		}

		if (
			is_float($time)
			|| (
				/*
				preg_match('/(?|(\d{10})|(\d{10})?(?:\.(\d*))?|(?:0+\.(\d+))\s+(\d+))(?>$)/', $time, $m)
				&& $m[0] != ''
				*/
				$this->_preg_match_timestamp($time, $m)
			)
		) {
			/*
			$_o = new scodate();
			$this->_date = array();
			$this->_date[0] = $_o->timestamp($time);
			$this->_date[1] = $_o->microsecond();
			*/
			$_o = $this->_microtime($time);
			$this->_date = $_o;

			unset($_o);

			$_o = new DateTime(gmdate(Scorpio_Date::SCO_ISO8601, $this->_date[0]), Scorpio_Date::_createDateTimeZone(Scorpio_Date::B_TIMEZONE));

			$offset = $timezone->getOffset($_o);

			unset($_o);

			$time = gmdate(Scorpio_Date::SCO_ISO8601, $this->_date[0] + $offset);
		}

		parent::__construct($time, $timezone);

		if (!isset($this->_date)) {
			$this->_date = array(
				$this->getMicrotime(),
				$this->getMicrosecond(),
			);
		}

		return $this;
	}

	function _preg_match_timestamp($time, &$m) {
		/*
		$ret = preg_match('/(?|(\d{10})|(\d{10})(?:\.(\d*))?|(\d{10})?(?:\.(\d*))|(?:0+\.(\d+))\s+(\d+))(?>$)/', $time, $m);
		*/
		$ret = preg_match('/(?|(\d{10})|(\d{10})(?:\.(\d*))?|(?:0+\.(\d+))\s+(\d+))(?>$)/', $time, $m);
		if ($ret && empty($m[0])) {
			$ret = false;
		} elseif ($ret && (
			isset($m[2])
		)) {
			$ret = (strlen($m[1]) == 10 || strlen($m[2]) == 10);
		}

		return (bool)$ret;
	}

	function _microsecond($update) {
		if ($update === true) {
			$_o = $this->_microtime(true);

			return $_o[1];
		} elseif ($update > 0) {
			if ($update > 1) {
				list($timestamp) = explode('.', (string)$update);

				$microsecond = $update - (int)$timestamp;
			} else {
				$microsecond = $update;
			}

			$microsecond = substr($microsecond, 0, 10);

			return (string)$microsecond;
		} else {
			return '0';
		}
	}

	function _microtime($time) {
		$ret = array(0, 0);

		if ($time === true) {
			list($microsecond, $timestamp) = explode(' ', microtime());

			$ret[1] = $this->_microsecond((string)$microsecond);
			$microsecond = substr($ret[1], 1);

			$ret[0] = (string)$timestamp . (string)$microsecond;
		} elseif ($time !== true && $time > 0) {
			if (is_float($time) || strpos($time, ' ') === false) {
				list($timestamp, $microsecond) = explode('.', $time);

				$ret[1] = $this->_microsecond($time);
			} else {
				list($microsecond, $timestamp) = explode(' ', $time);

				$ret[1] = $this->_microsecond((string)$microsecond);
			}

			$microsecond = substr($ret[1], 1);

			$ret[0] = (string)$timestamp . (string)$microsecond;
		} elseif ($time === false && defined('SCORPIO_MICROTIME')) {
			$ret = $this->_microtime(SCORPIO_MICROTIME);
		}

		return $ret;
	}

	/**
	 * Return Date in ISO8601 format
	 *
	 * @return String
	 */
	public function __toString() {
		return $this->format(Scorpio_Date::SCO_ISO8601);
	}

	/**
	 * DateTime::format -- date_format —
	 * Returns date formatted according to given format
	 */
	public function format($format = Scorpio_Date::SCO_ISO8601) {
		if (strpos($format, 'u') !== false) {
			$format = preg_replace('`(?<!\\\\)u`', sprintf('%06d', $this->getMicrosecond() * 1000000), $format);
		}

		return parent::format($format);
	}

	/**
	 * DateTime::getTimestamp -- date_timestamp_get —
	 * Gets the Unix timestamp
	 *
	 * @return int
	 */
	public function getTimestamp() {
		return parent::getTimestamp();
	}

	/**
	 * DateTime::setTimestamp -- date_timestamp_set —
	 * Sets the date and time based on an Unix timestamp
	 *
	 * @return Scorpio_Date
	 */
	public function setTimestamp($unixtimestamp) {
		/*
		$_o = new scodate();
		$this->_date[0] = $_o->timestamp($unixtimestamp);
		*/
		if ($_is_int = is_int($unixtimestamp)) {
			$this->_date[0] = $unixtimestamp;
		} else {
			$_o = $this->_microtime($unixtimestamp);
			$this->_date[0] = $_o[0];
		}

  		parent::setTimestamp($this->_date[0]);

		if ($_is_int) {
			$this->_date[0] = $this->getMicrotime();
		} else {
	  		$timestamp = parent::getTimestamp();

	  		if ((string)$this->_date[0] !== (string)$timestamp) {
	  			$this->_date[1] = $_o[1];
			} else {
				$this->_date[0] = $this->getMicrotime();
			}
		}

  		return $this;
	}

	/**
	 * @return float
	 */
	public function getMicrosecond() {
		return isset($this->_date[1]) ? $this->_date[1] : 0;
	}

	/**
	 * @return Scorpio_Date
	 */
	public function setMicrosecond($microsecond) {
		/*
		$_o = new scodate();
		$_o->timestamp($microsecond);
		$this->_date[1] = $_o->microsecond();;
		*/
		$_o = $this->_microtime($microsecond);
		$this->_date[1] = $_o[1];

		return $this;
	}

	/**
	 * DateTime::setDate -- date_date_set — Sets the date
	 *
	 * @return Scorpio_Date
	 */
	public function setDate($year, $month, $day) {
		parent::setDate($year, $month, $day);

		return $this;
	}

	/**
	 * DateTime::setISODate -- date_isodate_set — Sets the ISO date
	 *
	 * @return Scorpio_Date
	 */
	public function setISODate($year, $month, $day = 1) {
		parent::setISODate($year, $month, $day);

		return $this;
	}

	/**
	 * DateTime::getTimezone -- date_timezone_get — Return time zone relative to given DateTime
	 *
	 * @return DateTimeZone
	 */
	public function getTimezone() {
		return parent::getTimezone();
	}

	/**
	 * DateTime::setTimezone -- date_timezone_set — Sets the time zone for the DateTime object
	 *
	 * @return Scorpio_Date
	 */
	public function setTimezone($timezone) {

		$timezone = Scorpio_Date::_createDateTimeZone($timezone);

		parent::setTimezone($timezone);

		return $this;
	}

	/**
	 * DateTime::modify -- date_modify — Alters the timestamp
	 *
	 * @return Scorpio_Date
	 */
	public function modify($modify) {
		parent::modify($modify);

		return $this;
	}

	/**
	 * Return current Unix timestamp with microseconds
	 *
	 * @return float
	 */
	public function getMicrotime() {
		$this->_date[0] = $this->getTimestamp();
		$this->_date[0] .= substr($this->_date[1], 1);

		return $this->_date[0];
	}

	/**
	 * DateTime::getOffset -- date_offset_get — Returns the timezone offset
	 *
	 * @return int
	 */
	public function getOffset() {
		return parent::getOffset();
	}

	/**
	 * @param DateInterval $interval The amount of time to add
	 */
	public function add($interval) {
		return parent::add($interval);
	}


	/**
	 * @param DateInterval $interval The amount of time to add
	 */
	public function sub($interval) {
		return parent::sub($interval);
	}

	/**
	 * DateTime::diff -- date_diff —
	 * Returns the difference between two DateTime objects
	 *
	 * @param DateTime $datetime2
	 * @param bool $absolute = false
	 *
	 * @return DateInterval
	 */
	public function diff($datetime2, $absolute = false) {
		return parent::diff($datetime2, $absolute);
	}

	/**
	 * DateTime::createFromFormat -- date_create_from_format —
	 * Returns new DateTime object formatted according to the specified format
	 *
	 * @param string $format
	 * @param string $time
	 * @param DateTimeZone $timezone = null
	 *
	 * @return Scorpio_Date
	 */
	public static function createFromFormat($format, $time, $timezone = null) {
		$timezone = Scorpio_Date::_createDateTimeZone($timezone);

		$dt = parent::createFromFormat($format, $time, $timezone);

		$idt = new Scorpio_Date($dt->format(Scorpio_Date::SCO_ISO8601), $dt->getTimezone());
		return $idt;
	}

	public function __sleep() {
		$this->_sleep = array(
			$this->getMicrotime(),
			$this->getTimezone()->getName(),
		);

		return array('_sleep');
	}

	public function __wakeup() {
		$this->__construct($this->_sleep[0], $this->_sleep[1]);

		unset($this->_sleep);
	}

	/**
	 * @return Scorpio_Date_Zone|DateTimeZone
	 */
	public static function _createDateTimeZone($timezone = null) {
		if (!isset($timezone)) $timezone = Scorpio_Date::$D_TIMEZONE;

		if (
			is_string($timezone)
			|| !is_a($timezone, 'DateTimeZone')
		) {
			if (class_exists('Scorpio_Date_Zone')) {
				$timezone = new Scorpio_Date_Zone($timezone);
			} else {
				$timezone = new DateTimeZone($timezone);
			}
		}

		return $timezone;
	}

}

?>