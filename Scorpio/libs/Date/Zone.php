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
	 * @return int
	 */
	public function getOffsetByZone($timezone) {
		if (!isset($timezone)) {
			$timezone = new DateTimeZone(Scorpio_Date::B_TIMEZONE);
		} elseif (
			is_string($timezone)
			|| !is_a($timezone, 'DateTimeZone')
		) {
			$timezone = new DateTimeZone($timezone);
		}

		$_o = new DateTime('now', $timezone);

		$offset = $this->getOffset($_o);

		return $offset;
	}

}

?>