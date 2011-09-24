<?php

/**
 * @author bluelovers
 * @copyright 2011
 */

if (0) {
	// for IDE
	class Scorpio_Date_Zone extends Scorpio_Date_Zone_Core_ {}
}

class Scorpio_Date_Zone_Core_ extends DateTimeZone {

	/**
	 * DateTimeZone::__construct -- timezone_open — Creates new DateTimeZone object
	 *
	 * @return Scorpio_Date_Zone
	 */
	public function __construct($timezone = null) {

		if (!isset($timezone)) $timezone = Scorpio_Date::B_TIMEZONE;

		if (preg_match('/^GMT([+\-]\d+)$/i', $timezone, $m)) {
			$m[1] = 0 - (int)$m[1];
			if ($m[1] >= 0) {
				$m[1] = '+' . abs($m[1]);
			}

			$timezone = 'Etc/GMT' . $m[1];

			unset($m);
		}

		parent::__construct($timezone);

		return $this;
	}

	/**
	 * i don't know why need $datetime
	 * didn't see any from diff value by $datetime
	 *
	 * @param DateTime $datetime
	 * @return int
	 */
	public function getOffset($datetime = null) {
		if (!isset($timezone)) {
			$datetime = new DateTime('now', $this);
		}

		return parent::getOffset($datetime);
	}

}

?>