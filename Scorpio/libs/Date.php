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

	const SCO_ISO8601 = 'Y-m-d H:i:s';

	// Second amounts for various time increments
	const S_YEAR   = 31556926;
	const S_MONTH  = 2629744;
	const S_WEEK   = 604800;
	const S_DAY    = 86400;
	const S_HOUR   = 3600;
	const S_MINUTE = 60;

	public function __construct($time = 'now', $timezone = null) {
		if (!isset($time)) $time = 'now';

		parent::__construct($time, $timezone);

		return $this;
	}

}

?>