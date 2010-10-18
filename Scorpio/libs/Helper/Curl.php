<?php

/**
 * 2004-2007, Dick Munroe, released under the GPL.
 *
 * @author Dick Munroe (munroe@csworks.com)
 * @copyright copyright
 * @license http://www.csworks.com/publications/ModifiedNetBSD.html
 * @version 1.2.0
 * @package cURL
 * @see http://www.phpclasses.org/package/1988-PHP-cURL-extension-wrapper-access-remote-Web-resources.html
 *
 * The cURL class is a thin wrapper around the procedural interface
 * to cURL provided by PHP.  I use it mostly as a base class for
 * web services whose low level interface is, literally, web pages.
 *
 * There are a few differences (value added, I guess) between the interface
 * provided by this class and the procedural cURL interface.  Most
 * noticable are:
 *
 *   1. The curl::exec function (when returning data to the caller rather
 *      than simply outputing it) always parses the HTTP header and returns
 *      only the body portion of the reqeust.  The header is available via
 *      the curl::getHeader method.
 *   2. The status of the last curl::exec is always maintained.  It is
 *      available via the curl::getStatus method.  In addition to the information
 *      returned by curl_getinfo, that of curl_error and curl_errno is folded
 *      in as well.
 * @example ./example.class.curl.php
 */

// Edit History:

// Dick Munroe munroe@csworks.com 30-Nov-2004
// Initial Version Created.

// Dick Munroe munroe@csworks.com 01-Dec-2004
// Forgot to check for cURL actually being in this instance of PHP.

// Dick Munroe (munroe@csworks.com) 07-Apr-2006
// Fix tab characters.
// Add utility function to return post string.

// Richard W. Schlatter (richard@rth10260.info) 27-Apr-2006
// Extend processing for headers when CURLOPT_FOLLOWLOCATION is also set.
// Only the headers of the final page will be used to return parsed headers.
// Add utility function to return array of all collected headers.

// Dick Munroe (munroe@csworks.com) 01-May-2006
// asPostString doesn't need to be an object specific method.

// Dick Munroe (munroe@csworks.com) 02-May-2006
// Not all versions of PHP allow returning of a reference to the result
// of a function.

// Richard W. Schlatter (richard@rth10260.info) 03-May-2006
// For consistency, return an empty array if there aren't any headers
// to be parsed.

// Richard W. Schlatter (richard@rth10260.info) 05-Jun-2006
// Don't parse headers in the event of an error when executing a cURL request.

// Dick Munroe (munroe@csworks.com) 17-Dec-2007 1.2.0
// Add a function to parse post strings as this is frequently needed capability.

if (0) {
	// for IDE
	class Scorpio_Helper_Curl extends Scorpio_Helper_Curl_Core {}
	class scocurl extends Scorpio_Helper_Curl {}
}

class Scorpio_Helper_Curl_Core {
	/**
	 * The mapping to caseless header names.
	 *
	 * @access private
	 * @var array
	 */

	var $m_caseless ;

	/**
	 * The handle for the current curl session.
	 *
	 * @access private
	 * @var resource
	 */

	var $m_handle ;

	/**
	 * The parsed contents of the HTTP header if one happened in the
	 * message.  All repeated elements appear as arrays.
	 *
	 * The headers are stored as an associative array, the key of which
	 * is the name of the header, e.g., Set-Cookie, and the values of which
	 * are the bodies of the header in the order in which they occurred.
	 *
	 * Some headers can be repeated in a single header, e.g., Set-Cookie and
	 * pragma, so each type of header has an array containing one or more
	 * headers of the same type.
	 *
	 * The names of the headers can, potentially, vary in spelling from
	 * server to server and client to client.  No attempt to regulate this
	 * is made, i.e., the curl class does not force all headers to lower
	 * or upper class, but it DOES collect all headers of the same type
	 * under the spelling of the type of header used by the FIRST header
	 * of that type.
	 *
	 * For example, two headers:
	 *
	 *    1. Set-Cookie: ...
	 *    2. set-cookie: ...
	 *
	 * Would appear as $this->m_header['Set-Cookie'][0] and ...[1]
	 *
	 * @access private
	 * @var mixed
	 */

	var $m_header ;

	/**
	 * Current setting of the curl options.
	 *
	 * @access private
	 * @var mixed
	 */

	var $m_options ;

	/**
	 * Status information for the last executed http request.  Includes the errno and error
	 * in addition to the information returned by curl_getinfo.
	 *
	 * The keys defined are those returned by curl_getinfo with two additional
	 * ones specified, 'error' which is the value of curl_error and 'errno' which
	 * is the value of curl_errno.
	 *
	 * @link http://www.php.net/curl_getinfo
	 * @link http://www.php.net/curl_errno
	 * @link http://www.php.net/curl_error
	 * @access private
	 * @var mixed
	 */

	var $m_status ;

	/**
	 * Collection of headers when curl follows redirections as per CURLOPTION_FOLLOWLOCATION.
	 * The collection includes the headers of the final page too.
	 *
	 * @access private
	 * @var array
	 */

	var $m_followed ;
	var $m_setting ;

	public static $default_options = array(
		CURLOPT_USERAGENT		=> 'Mozilla/5.0 (compatible; Scorpio +http://www.bluelovers.net/)',
		CURLOPT_CONNECTTIMEOUT	=> 5,
		CURLOPT_TIMEOUT			=> 5,

		CURLOPT_FOLLOWLOCATION	=> true,
		CURLOPT_RETURNTRANSFER	=> true,
		CURLOPT_AUTOREFERER		=> true,

		CURLOPT_SSL_VERIFYPEER	=> 0,

		CURLOPT_MAXREDIRS		=> 10,
		CURLOPT_ENCODING		=> '',
	);

	protected static $instances = null;

	public static function &instance() {
		$args = func_get_args();
		$emptyinstances = null;
		static::$instances = &$emptyinstances;

		$ref = new ReflectionClass(get_called_class());
		static::$instances =& $ref->newInstanceArgs((array)$args);

		return static::$instances;
	}

	public static function &_self() {
		return static::$instances;
	}

	/**
	 * curl class constructor
	 *
	 * Initializes the curl class for it's default behavior:
	 *   o no HTTP headers.
	 *   o return the transfer as a string.
	 *   o URL to access.
	 * By default, the curl class will simply read the URL provided
	 * in the constructor.
	 *
	 * @link http://www.php.net/curl_init
	 * @param string $theURL [optional] the URL to be accessed by this instance of the class.
	 */

	function __construct($theURL = null) {
		if (!function_exists('curl_init')) {
			trigger_error('PHP was not built with --with-curl, rebuild PHP to use the curl class.',
				E_USER_ERROR) ;
		}

		register_shutdown_function(array($this, '__destruct'));

		$this->m_handle = curl_init() ;

		$this->m_caseless = null ;
		$this->m_header = null ;
		$this->m_options = null ;
		$this->m_status = null ;
		$this->m_followed = null ;
		$this->m_setting = null ;

		if (!empty($theURL)) {
			$this->setopt(CURLOPT_URL, $theURL) ;
		}
		$this->setopt(CURLOPT_HEADER, false) ;
		$this->setopt(CURLOPT_RETURNTRANSFER, true) ;

		return $this;
	}

	/**
	 * Free the resources associated with the curl session.
	 *
	 * @link http://www.php.net/curl_close
	 */

	function close($force = false) {
		!$this->m_closed && @curl_close($this->m_handle) ;
//		$this->m_handle = null ;

		if ($force) {
			$this->_clear();
		} else {
			$this->m_closed = true;
		}

		return $this;
	}

	function _clear($chkmode = false) {
		if (!$chkmode || $this->m_closed) {
			$this->m_closed = false;
			$this->m_handle = null;
		}
	}

	/**
	 * Execute the curl request and return the result.
	 *
	 * @link http://www.php.net/curl_exec
	 * @link http://www.php.net/curl_getinfo
	 * @link http://www.php.net/curl_errno
	 * @link http://www.php.net/curl_error
	 * @return string The contents of the page (or other interaction as defined by the
	 *                 settings of the various curl options).
	 */

	function exec($theURL = null) {
		$this->_clear(1);

		if (!empty($theURL)) {
			$this->setopt(CURLOPT_URL, $theURL) ;
		}

		if ($this->getOption(CURLOPT_COOKIEJAR) == true) {
			$this->setopt(CURLOPT_COOKIEFILE, $this->cookies()) ;
			$this->setopt(CURLOPT_COOKIEJAR, $this->cookies()) ;
		}

		$this->_setopt(1);

		$theReturnValue = curl_exec($this->m_handle) ;

		$this->m_status = curl_getinfo($this->m_handle) ;
		$this->m_status['errno'] = curl_errno($this->m_handle) ;
		$this->m_status['error'] = curl_error($this->m_handle) ;

		// Collect headers espesically if CURLOPT_FOLLOWLOCATION set.
		// Parse out the http header (from last one if any).

		$this->m_header = null ;
		$this->m_followed = null;

		// If there has been a curl error, just return a null string.

		if ($this->m_status['errno']) {
			return '' ;
		}

		if ($this->getOption(CURLOPT_HEADER)) {
			$this->m_followed = array() ;
			$rv = $theReturnValue ;

			while (count($this->m_followed) <= $this->m_status['redirect_count']) {
				$theArray = preg_split("/(\r\n){2,2}/", $rv, 2) ;

				$this->m_followed[] = $theArray[0] ;

				$rv = $theArray[1] ;
			}

			$this->parseHeader($theArray[0]) ;
			$this->m_retval = $theArray[1];

			if (0 && $this->getOption(CURLOPT_FOLLOWLOCATION)) {
				$_f = $this->getFollowedHeaders();
				foreach ($_f as $_fa) {
					if ($_fa[0] && preg_match('%^HTTP/\d+\.\d+\s+(\d{3})\s+.*$%', $_fa[0], $_r) && ($_r[1] == 302 || $_r[1] == 301)) {
						for ($i=1, $ii = count($_fa); $i < $ii; $i++) {
							if (preg_match('%^Location:\s*(.+)\s*$%i', $_fa[$i], $_r)) {

//								print_r($_r);
//								exit();

								return $this->exec($_r[1]);
							}

//							echo $_fa[i].'<br>';
						}
					}

//					echo '<pre>';
//				print_r(array($_fa, $_r, $i, $ii, preg_match('%^HTTP/\d+\.\d+\s+(\d{3})\s+.*$%', $_fa[0], $_r), $_r));
//				exit();
				}


			}
		} else {
			$this->m_retval = $theReturnValue ;
		}

		if (
			0 && $this->getSetting('javascript_loop') > 0
			&& (
				preg_match("/>[[:space:]]+window\.location\.replace\('(.*)'\)/i", $this->m_retval, $m)
				|| preg_match("/>[[:space:]]+window\.location\=\"(.*)\"/i", $this->m_retval, $m)
			)
		) {
			$this->setSetting('javascript_loop', $this->getSetting('javascript_loop') -1)->exec($m[1]);
		}

		return $this->m_retval;
	}

	function getExec($detial = false) {
		return $detial ? array(
			'header' => $this->getHeader(),
			'exec' => $this->m_retval,
			'followed' => $this->getFollowedHeaders(),
			'options' => $this->m_options,
			'status' => $this->getStatus(),
		) : $this->m_retval;
	}

	/**
	 * Returns the parsed http header.
	 *
	 * @param string $theHeader [optional] the name of the header to be returned.
	 *                           The name of the header is case insensitive.  If
	 *                           the header name is omitted the parsed header is
	 *                           returned.  If the requested header doesn't exist
	 *                           false is returned.
	 * @returns mixed
	 */

	function getHeader($theHeader = null) {

		// There can't be any headers to check if there weren't any headers
		// returned (happens in the event of errors).

		if (empty($this->m_header)) {
			return false ;
		}

		if (empty($theHeader)) {
			return $this->m_header ;
		} else {
			$theHeader = strtoupper($theHeader) ;
			if (isset($this->m_caseless[$theHeader])) {
				return $this->m_header[$this->m_caseless[$theHeader]] ;
			} else {
				return false ;
			}
		}
	}

	/**
	 * Returns the current setting of the request option.  If no
	 * option has been set, it return null.
	 *
	 * @param integer $ the requested CURLOPT.
	 * @returns mixed
	 */

	function getOption($theOption) {
		if (isset($this->m_options[$theOption])) {
			return $this->m_options[$theOption] ;
		}

		return null ;
	}

	/**
	 * Did the last curl exec operation have an error?
	 *
	 * @return mixed The error message associated with the error if an error
	 *                occurred, false otherwise.
	 */

	function hasError() {
		if (isset($this->m_status['error'])) {
			return (empty($this->m_status['error']) ? false : $this->m_status['error']) ;
		} else {
			return false ;
		}
	}

	/**
	 * Parse an HTTP header.
	 *
	 * As a side effect it stores the parsed header in the
	 * m_header instance variable.  The header is stored as
	 * an associative array and the case of the headers
	 * as provided by the server is preserved and all
	 * repeated headers (pragma, set-cookie, etc) are grouped
	 * with the first spelling for that header
	 * that is seen.
	 *
	 * All headers are stored as if they COULD be repeated, so
	 * the headers are really stored as an array of arrays.
	 *
	 * @param string $theHeader The HTTP data header.
	 */

	function parseHeader($theHeader) {
		$this->m_caseless = array() ;

		$theArray = preg_split("/(\r\n)+/", $theHeader) ;

		// Ditch the HTTP status line.

		if (preg_match('/^HTTP/', $theArray[0])) {
			$theArray = array_slice($theArray, 1) ;
		}

		foreach ($theArray as $theHeaderString) {
			$theHeaderStringArray = preg_split("/\s*:\s*/", $theHeaderString, 2) ;

			$theCaselessTag = strtoupper($theHeaderStringArray[0]) ;

			if (!isset($this->m_caseless[$theCaselessTag])) {
				$this->m_caseless[$theCaselessTag] = $theHeaderStringArray[0] ;
			}

			$this->m_header[$this->m_caseless[$theCaselessTag]][] = $theHeaderStringArray[1] ;
		}
	}

	/**
	 * Return the status information of the last curl request.
	 *
	 * @param string $theField [optional] the particular portion
	 *                          of the status information desired.
	 *                          If omitted the array of status
	 *                          information is returned.  If a non-existant
	 *                          status field is requested, false is returned.
	 * @returns mixed
	 */

	function getStatus($theField = null) {
		if (empty($theField)) {
			return $this->m_status ;
		} else {
			if (isset($this->m_status[$theField])) {
				return $this->m_status[$theField] ;
			} else {
				return false ;
			}
		}
	}

	/**
	 * Set a curl option.
	 *
	 * @link http://www.php.net/curl_setopt
	 * @param mixed $theOption One of the valid CURLOPT defines.
	 * @param mixed $theValue the value of the curl option.
	 */

	function setopt($theOption, $theValue = null) {
		$this->_clear(1);

		if (is_array($theOption)) {
			foreach ($theOption as $_k => $_v) {
				$this->setopt($_k, $_v);
			}
		} else {

			if ($theOption == CURLOPT_URL) {
				$theValue = str_replace('&amp;', '&', urldecode(trim($theValue)));
			}

//			curl_setopt($this->m_handle, $theOption, $theValue) ;
			$this->m_options[$theOption] = $theValue ;
		}

		return $this;
	}

	function _setopt($default = false) {

		if ($default) {
			foreach (static::$default_options as $_k => $_v) {
				if ($this->getOption($_k) === null) {
					$this->setopt($_k, $_v);
				}
			}
		}

		foreach ($this->m_options as $theOption => $theValue) {
			curl_setopt($this->m_handle, $theOption, $theValue);
		}

		return $this;
	}

	function setSetting($theOption, $theValue) {
		if ($this->m_closed) {
			$this->m_closed = false;
			$this->m_handle = null;
		}

		if (is_array($theOption)) {
			foreach ($theOption as $_k => $_v) {
				$this->setSetting($_k, $_v);
			}
		} else {
//			curl_setopt($this->m_handle, $theOption, $theValue) ;
			$this->m_setting[$theOption] = $theValue ;
		}

		return $this;
	}

	function getSetting($theOption) {
		if (isset($this->m_setting[$theOption])) {
			return $this->m_setting[$theOption] ;
		}

		return null ;
	}

	/**
	 *
	 * @desc Post string as an array
	 * @param string $ by reference data to be written.
	 * @return array hash containing the post string as individual elements, urldecoded.
	 * @access public
	 */

	function &fromPostString(&$thePostString) {
		$return = array() ;
		$fields = explode('&', $thePostString) ;
		foreach($fields as $aField) {
			$xxx = explode('=', $aField) ;
			$return[$xxx[0]] = urldecode($xxx[1]) ;
		}

		return $return ;
	}

	/**
	 * Arrays are walked through using the key as a the name.  Arrays
	 * of Arrays are emitted as repeated fields consistent with such things
	 * as checkboxes.
	 *
	 * @desc Return data as a post string.
	 * @param mixed $ by reference data to be written.
	 * @param string $ [optional] name of the datum.
	 * @access public
	 */

	function &asPostString(&$theData, $theName = null) {
		$thePostString = '' ;
		$thePrefix = $theName ;

		if (is_array($theData)) {
			foreach ($theData as $theKey => $theValue) {
				if ($thePrefix === null) {
					$thePostString .= '&' . static::asPostString($theValue, $theKey) ;
				} else {
					$thePostString .= '&' . static::asPostString($theValue, $thePrefix . '[' . $theKey . ']') ;
				}
			}
		} else {
			$thePostString .= '&' . urlencode((string)$thePrefix) . '=' . urlencode($theData) ;
		}

		$xxx = &substr($thePostString, 1) ;

		return $xxx ;
	}

	/**
	 * Returns the followed headers lines, including the header of the retrieved page.
	 * Assumed preconditions: CURLOPT_HEADER and expected CURLOPT_FOLLOWLOCATION set.
	 * The content is returned as an array of headers of arrays of header lines.
	 *
	 * @param none $ .
	 * @returns mixed an empty array implies no headers.
	 * @access public
	 */

	function getFollowedHeaders() {
		$theHeaders = array() ;
		if ($this->m_followed) {
			foreach ($this->m_followed as $aHeader) {
				$theHeaders[] = explode("\r\n", $aHeader) ;
			} ;
			return $theHeaders ;
		}

		return $theHeaders ;
	}

	function cookies($chkmode = false) {
		static $cookies;

		(!$chkmode && !$cookies) && $cookies = tempnam ($_SERVER['SERVER_ROOT'].'/tmp', "CURLCOOKIE");

		return $cookies;
	}

	public function __destruct() {
		$this->close(1);
		if ($this->cookies(1)) unlink($this->cookies(1));
	}
}

?>
