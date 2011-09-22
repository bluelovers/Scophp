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
	class Scorpio_Exception extends Scorpio_Exception_Core_ {
	}
}

class Scorpio_Exception_Core_ extends Exception {

	public static $enabled = false;
	protected static $_scorpio_self_classname_ = 'Scorpio_Exception';

	// To hold unique identifier to distinguish error output
	protected $instance_identifier;

	/**
	 * @var  array  PHP error code => human readable name
	 */
	public static $php_errors = array(
		E_ERROR					=> 'Fatal Error',
		E_USER_ERROR			=> 'User Error',
		E_PARSE					=> 'Parse Error',
		E_WARNING				=> 'Warning',
		E_USER_WARNING			=> 'User Warning',
		E_STRICT				=> 'Strict',
		E_NOTICE				=> 'Notice',
		E_RECOVERABLE_ERROR		=> 'Recoverable Error',
	);

	/**
	 * Creates a new translated exception.
	 *
	 * @example throw new Scorpio_Exception('Something went terrible wrong, %user', array('user' => $user));
	 *
	 * @param string error message
	 * @param array translation variables
	 * @return void
	 */
	public function __construct($message, array $variables = null, $code = 0) {
		$this->instance_identifier = uniqid();

		if (defined('E_DEPRECATED')) {
			// E_DEPRECATED only exists in PHP >= 5.3.0
			Scorpio_Exception::$php_errors[E_DEPRECATED] = 'Deprecated';
		}

		$variables !== null && $message = scotext::sprintf($message, $variables);

		// Sets $this->message the proper way
		parent::__construct($message, (int)$code);

		/**
		 * Save the unmodified code
		 * @link http://bugs.php.net/39615
		 */
		$this->code = $code;
	}

	/**
	 * Magic object-to-string method.
	 *
	 *     echo $exception;
	 *
	 * @uses    Scorpio_Exception::text
	 * @return  string
	 */
	public function __toString() {
		return Scorpio_Exception::text($this);
	}

	protected static function _self($name = null, $val = null) {
		if ($name) {
			return $val !== null ? scophp::set_static_value(self::$_scorpio_self_classname_, $name, $val) : scophp::get_static_value(self::$_scorpio_self_classname_, $name);
		} else {
			return self::$_scorpio_self_classname_;
		}
	}

	/*
	public static function enable() {
	if (!self::_self('enabled')) {
	set_exception_handler(array(self::_self(), 'handle'));

	self::_self('enabled', true);
	}
	}

	public static function disable() {
	if (self::_self('enabled')) {
	restore_exception_handler();

	self::_self('enabled', false);
	}
	}
	*/

	/**
	 * Get a single line of text representing the exception:
	 *
	 * Error [ Code ]: Message ~ File [ Line ]
	 *
	 * @param   object  Exception
	 * @return  string
	 */
	public static function text($e) {
		return sprintf('%s [ %s ]: %s ~ %s [ %d ]', get_class($e), $e->getCode(), strip_tags($e->getMessage()), Scorpio_Exception::debug_path($e->getFile()), $e->getLine());
	}

	/**
	 * Inline exception handler, displays the error message, source of the
	 * exception, and the stack trace of the error.
	 *
	 * @uses    Scorpio_Exception::text
	 * @param   object   exception object
	 * @return  boolean
	 */
	public static function handler(Exception $e) {
		try {
			// Get the exception information
			$type = get_class($e);
			$code = $e->getCode();
			$message = $e->getMessage();
			$file = $e->getFile();
			$line = $e->getLine();

			// Get the exception backtrace
			$trace = $e->getTrace();

			if ($e instanceof ErrorException) {
				if (isset(Scorpio_Exception::$php_errors[$code])) {
					// Use the human-readable error name
					$code = Scorpio_Exception::$php_errors[$code];
				}

				if (version_compare(PHP_VERSION, '5.3', '<')) {
					// Workaround for a bug in ErrorException::getTrace() that exists in
					// all PHP 5.2 versions. @see http://bugs.php.net/bug.php?id=45895
					for ($i = count($trace) - 1; $i > 0; --$i) {
						if (isset($trace[$i - 1]['args'])) {
							// Re-position the args
							$trace[$i]['args'] = $trace[$i - 1]['args'];

							// Remove the args
							unset($trace[$i - 1]['args']);
						}
					}
				}
			}

			// Create a text version of the exception
			$error = Scorpio_Exception::text($e);

			if (is_object(Scorpio_Kenal::$log)) {
				// Add this exception to the log
				Scorpio_Kenal::$log->add(Log::ERROR, $error);

				// Make sure the logs are written
				Scorpio_Kenal::$log->write();
			}

			if (Scorpio_Kenal::$is_cli) {
				// Just display the text of the exception
				echo "\n{$error}\n";

				exit(1);
			}

			if (!headers_sent()) {
				// Make sure the proper http header is sent
				$http_header_status = ($e instanceof Scorpio_Exception_HTTP) ? $code : 500;

				header('Content-Type: text/html; charset=' . Scorpio_Kenal::$charset, TRUE, $http_header_status);
			}

			if (Scorpio_Request::$current !== NULL AND Scorpio_Request::current()->is_ajax() === TRUE) {
				// Just display the text of the exception
				echo "\n{$error}\n";

				exit(1);
			}

			// Start an output buffer
			ob_start();

			// Include the exception HTML
			if ($view_file = Scorpio_Kenal::find_file('views', Scorpio_Exception::$error_view)) {
				include $view_file;
			} else {
				throw new Scorpio_Exception('Error view file does not exist: views/:file', array('file' => Scorpio_Exception::$error_view, ));
			}

			// Display the contents of the output buffer
			echo ob_get_clean();

			exit(1);
		}
		catch (Exception $e) {
			// Clean the output buffer if one exists
			ob_get_level() and ob_clean();

			// Display the exception text
			echo Scorpio_Exception::text($e), "\n";

			// Exit with an error status
			exit(1);
		}
	}

	/**
	 * Sends an Internal Server Error header.
	 *
	 * @return  void
	 */
	public function sendHeaders() {
		// Send the 500 header
		header('HTTP/1.1 500 Internal Server Error');
	}

	/**
	 * Removes APPPATH, SYSPATH, MODPATH, and DOCROOT from filenames, replacing
	 * them with the plain text equivalents.
	 *
	 * @param   string  path to sanitize
	 * @return  string
	 */
	public static function debug_path($file) {
		$file = str_replace('\\', '/', $file);

		if (strpos($file, SCORPIO_APPPATH) === 0) {
			$file = 'APPPATH/' . substr($file, strlen(SCORPIO_APPPATH));
		} elseif (strpos($file, SCORPIO_SYSPATH) === 0) {
			$file = 'SYSPATH/' . substr($file, strlen(SCORPIO_SYSPATH));
		} elseif (strpos($file, SCORPIO_MODPATH) === 0) {
			$file = 'MODPATH/' . substr($file, strlen(SCORPIO_MODPATH));
		} elseif (strpos($file, SCORPIO_DOCROOT) === 0) {
			$file = 'DOCROOT/' . substr($file, strlen(SCORPIO_DOCROOT));
		}

		return $file;
	}

}

?>