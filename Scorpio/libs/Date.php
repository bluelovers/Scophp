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

	protected $_date = null;

	const SCO_ISO8601 = 'Y-m-d H:i:s';

	// Second amounts for various time increments
	const S_YEAR   = 31556926;
	const S_MONTH  = 2629744;
	const S_WEEK   = 604800;
	const S_DAY    = 86400;
	const S_HOUR   = 3600;
	const S_MINUTE = 60;

	const B_TIMEZONE = 'GMT';
	const D_TIMEZONE = 'Asia/Taipei';

	public function __construct($time = 'now', $timezone = null) {
		if (!isset($time)) $time = 'now';

		if (!isset($timezone)) {
			/*
			$timezone_default = date_default_timezone_get();
			*/
			$timezone_default = Scorpio_Date::D_TIMEZONE;
			$timezone = new DateTimeZone($timezone_default);
		} elseif (is_string($timezone)) {
			$timezone = new DateTimeZone($timezone);
		}

		if ($time == 'now') $time = microtime(true);

		if (
			is_float($time)
			|| (
				preg_match('/(?|(\d{10})|(\d{10})?(?:\.(\d*))?|(?:0+\.(\d+))\s+(\d+))(?>$)/', $time, $m)
				&& $m[0] != ''
			)
		) {
			$_o = new scodate();
			$this->_date = array();
			$this->_date[0] = $_o->timestamp($time);
			$this->_date[1] = $_o->microsecond();

			unset($_o);

			$_o = new DateTime(gmdate(Scorpio_Date::SCO_ISO8601, $this->_date[0]), new DateTimeZone(Scorpio_Date::B_TIMEZONE));

			$offset = $timezone->getOffset($_o);

			unset($_o);

			$time = gmdate(Scorpio_Date::SCO_ISO8601, $this->_date[0] + $offset);
		}

		parent::__construct($time, $timezone);

		if (!isset($this->_date)) {
			$this->_date = array(
				$this->getTimestamp(),
				$this->getMicrosecond(),
			);
		}

		return $this;
	}

	/**
	 * Return Date in ISO8601 format
	 *
	 * @return String
	 */
	public function __toString() {
		return $this->format(Scorpio_Date::SCO_ISO8601.' u');
	}

	/**
	 * DateTime::format -- date_format —
	 * Returns date formatted according to given format
	 */
	public function format($format) {
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

		$_o = new scodate();
		$this->_date[0] = $_o->timestamp($unixtimestamp);

  		parent::setTimestamp($this->_date[0]);

  		$timestamp = parent::getTimestamp();

  		if ((string)$this->_date[0] !== (string)$timestamp) {
  			$this->_date[1] = $_o->microsecond();
		} else {
			$this->_date[0] .= substr($this->_date[1], 1);
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
		$_o = new scodate();
		$_o->timestamp($microsecond);
		$this->_date[1] = $_o->microsecond();;

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

}

?>