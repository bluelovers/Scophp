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
	public function getOffset($datetime = null) {
		if (!isset($timezone)) {
			$datetime = new DateTime('now', $this);
		}

		return parent::getOffset($datetime);
	}

}

?>