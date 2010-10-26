<?php

/**
 * Controls headers that effect client caching of pages
 *
 * $Id$
 *
 * @package    Core
 * @author     Kohana Team
 * @copyright  (c) 2007-2009 Kohana Team
 * @license    http://kohanaphp.com/license
 */

if (0) {
	// for IDE
	class scoexpires extends Scorpio_Helper_Expires_Core {
	}
}

class Scorpio_Helper_Expires_Core {

	static $format = 'D, d M Y H:i:s T';

	/**
	 * Sets the amount of time before content expires
	 *
	 * @param   integer Seconds before the content expires
	 * @return  integer Timestamp when the content expires
	 */
	public static function set($seconds = 60, $last_modified = 0, $now = 0) {
		!$now && $now = scodate::timestamp();
		!$last_modified && $last_modified = $now;

		$expires = $now + $seconds;

		echo 'Now: ' . gmdate(scoexpires::$format, $now) . "<br>";
		echo 'last_modified: ' . gmdate(scoexpires::$format, $last_modified) . "<br>";

		scophp::header('Last-Modified: ' . gmdate(scoexpires::$format, $last_modified));

		if ($seconds > 0) {
			// HTTP 1.0
			scophp::header('Expires: ' . gmdate(scoexpires::$format, $expires));

			// HTTP 1.1
			scophp::header('Cache-Control: max-age=' . $seconds);
		} elseif ($seconds < 0) {
			scophp::header('Expires: -1');
			scophp::header('Cache-Control: no-store, private, post-check=0, pre-check=0, max-age=0');
			scophp::header('Pragma: no-cache');
		}

		return $expires;
	}

	/**
	 * Parses the If-Modified-Since header
	 *
	 * @return  integer|boolean Timestamp or FALSE when header is lacking or malformed
	 */
	public static function get() {
		if (!empty($_SERVER['HTTP_IF_MODIFIED_SINCE'])) {
			// Some versions of IE6 append "; length=####"
			if (($strpos = strpos($_SERVER['HTTP_IF_MODIFIED_SINCE'], ';')) !== false) {
				$mod_time = substr($_SERVER['HTTP_IF_MODIFIED_SINCE'], 0, $strpos);
			} else {
				$mod_time = $_SERVER['HTTP_IF_MODIFIED_SINCE'];
			}

			return strtotime($mod_time);
		}

		return false;
	}

	/**
	 * Checks to see if content should be updated otherwise sends Not Modified status
	 * and exits.
	 *
	 * @uses    exit()
	 * @uses    expires::get()
	 *
	 * @param   integer         Maximum age of the content in seconds
	 * @return  integer|boolean Timestamp of the If-Modified-Since header or FALSE when header is lacking or malformed
	 */
	public static function check($seconds = 60, $last_modified = 0, $now = 0) {
		if ($last_modified || $last_modified = scoexpires::get()) {
			!$now && $now = scodate::timestamp();

			$expires = $last_modified + $seconds;
			$max_age = $expires - $now;

			if ($max_age > 0) {
				// Content has not expired
				scophp::header($_SERVER['SERVER_PROTOCOL'] . ' 304 Not Modified');
				//				header('Last-Modified: '.gmdate('D, d M Y H:i:s T', $last_modified));
				//
				//				// HTTP 1.0
				//				header('Expires: '.gmdate('D, d M Y H:i:s T', $expires));
				//
				//				// HTTP 1.1
				//				header('Cache-Control: max-age='.$max_age);

				scoexpires::set($max_age, $last_modified, $now);

				// Clear any output
				//Scorpio_Event::add('system.display', create_function('', 'Kohana::$output = "";'));

				//exit;
			} elseif ($max_age < 0) {
				scoexpires::set($max_age, $last_modified, $now);
			}
		}

		return $last_modified;
	}

	/**
	 * Check if expiration headers are already set
	 *
	 * @return boolean
	 */
	public static function headers_set() {
		foreach (headers_list() as $header) {
			if (strncasecmp($header, 'Expires:', 8) === 0 or strncasecmp($header,
				'Cache-Control:', 14) === 0 or strncasecmp($header, 'Last-Modified:', 14) === 0) {
				return true;
			}
		}

		return false;
	}

} // End expires


?>