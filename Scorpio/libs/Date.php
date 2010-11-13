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
	class Scorpio_Date extends Scorpio_Date_Core {}
}

/**
 * http://tw2.php.net/manual/en/class.datetime.php
 **/
class Scorpio_Date_Core extends DateTime {
	protected $_date = array();
	const SCO_ISO8601 = 'Y-m-d H:i:s';

	public function __construct($time = 'now', $timezone = null) {

		if (preg_match('/^\s*(?|(\d[10])|(\d*)?(?:\.(\d*))?|(?:0+\.(\d+))\s+(\d+))\s*$/x', $time, $m)) {

			if (strlen($m[1]) != 10) {
				$_t = $m[1];
				$m[1] = $m[2];
				$m[2] = $_t;
			}

			$time = date(static::SCO_ISO8601, $m[1]);

			$this->_date = array(
				$m[1], $m[2]
			);
		} else {
			$m = strtotime($time);
			$this->_date = array(
				$m, 0
			);
		}

		if ($timezone == null) {
			$timezone = new DateTimeZone(scophp::date_default_timezone_get());
		} elseif (is_string($timezone)) {
			$timezone = new DateTimeZone($timezone);
		}

		parent::__construct($time, $timezone);
	}

	public function __toString() {
		return $this->format(static::SCO_ISO8601);
	}
}

?>