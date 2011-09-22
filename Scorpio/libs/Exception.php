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
	 * exception handler, displays the error message, source of the
	 * exception, and the stack trace of the error.
	 *
	 * @uses    Kohana::message()
	 * @uses    Kohana_Exception::text()
	 * @param   object   exception object
	 * @return  void
	 */
	public static function handle(Exception $e) {
		try {
			// Get the exception information
			$type = get_class($e);
			$code = $e->getCode();
			$message = $e->getMessage();

			// Create a text version of the exception
			$error = Scorpio_Exception::text($e);

			// Add this exception to the log
			Scorpio_Log::add('error', $error);

			// Manually save logs after exceptions
			Scorpio_Log::save();

			if (Scorpio_Kenal::config('kohana/core.display_errors') === false) {
				// Do not show the details
				$file = $line = null;
				$trace = array();

				$template = '_disabled';
			} else {
				$file = $e->getFile();
				$line = $e->getLine();
				$trace = $e->getTrace();

				$template = '';
			}

			if ($e instanceof Scorpio_Exception) {
				$template = $e->getTemplate() . $template;

				if (!headers_sent()) {
					$e->sendHeaders();
				}

				// Use the human-readable error name
				$code = Scorpio_Kenal::message('kohana/core.errors.' . $code);
			} else {
				$template = Scorpio_Exception::$template . $template;

				if (!headers_sent()) {
					header('HTTP/1.1 500 Internal Server Error');
				}

				if ($e instanceof ErrorException) {
					// Use the human-readable error name
					$code = Scorpio_Kenal::message('kohana/core.errors.' . $e->getSeverity());

					if (version_compare(PHP_VERSION, '5.3', '<')) {
						// Workaround for a bug in ErrorException::getTrace() that exists in
						// all PHP 5.2 versions. @see http://bugs.php.net/45895
						for ($i = count($trace) - 1; $i > 0; --$i) {
							if (isset($trace[$i - 1]['args'])) {
								// Re-position the arguments
								$trace[$i]['args'] = $trace[$i - 1]['args'];

								unset($trace[$i - 1]['args']);
							}
						}
					}
				}
			}

			// Clean the output buffer if one exists
			ob_get_level() and ob_clean();
		}
		catch (Exception $e) {
			// Clean the output buffer if one exists
			ob_get_level() and ob_clean();

			// Display the exception text
			echo Scorpio_Exception::text($e), LF;
		}

		if (Scorpio_Kenal::$server_api === 'cli') {
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

	/**
	 * Returns an array of lines from a file.
	 *
	 *     // Returns the current line of the current file
	 *     echo Kohana_Exception::debug_source(__FILE__, __LINE__);
	 *
	 * @param   string   file to open
	 * @param   integer  line number to find
	 * @param   integer  number of padding lines
	 * @return  array
	 */
	public static function debug_source($file, $line_number, $padding = 5) {
		// Make sure we can read the source file
		if (!is_readable($file)) return array();

		// Open the file and set the line position
		$file = fopen($file, 'r');
		$line = 0;

		// Set the reading range
		$range = array('start' => $line_number - $padding, 'end' => $line_number + $padding);

		// Set the zero-padding amount for line numbers
		$format = '% ' . strlen($range['end']) . 'd';

		$source = array();
		while (($row = fgets($file)) !== false) {
			// Increment the line number
			if (++$line > $range['end']) break;

			if ($line >= $range['start']) {
				$source[sprintf($format, $line)] = $row;
			}
		}

		// Close the file
		fclose($file);

		return $source;
	}

	/**
	 * Returns an array of strings that represent each step in the backtrace.
	 *
	 * @param   array  trace to analyze
	 * @return  array
	 */
	public static function trace($trace = null) {
		if ($trace === null) {
			// Start a new trace
			$trace = debug_backtrace();
		}

		// Non-standard function calls
		$statements = array('include', 'include_once', 'require', 'require_once');

		$output = array();
		foreach ($trace as $step) {
			if (!isset($step['function'])) {
				// Invalid trace step
				continue;
			}

			if (isset($step['file']) and isset($step['line'])) {
				// Include the source of this step
				$source = Scorpio_Exception::debug_source($step['file'], $step['line']);
			}

			if (isset($step['file'])) {
				$file = $step['file'];

				if (isset($step['line'])) {
					$line = $step['line'];
				}
			}

			// function()
			$function = $step['function'];

			if (in_array($step['function'], $statements)) {
				if (empty($step['args'])) {
					// No arguments
					$args = array();
				} else {
					// Sanitize the file path
					$args = array($step['args'][0]);
				}
			} elseif (isset($step['args'])) {
				if ($step['function'] === '{closure}') {
					// Introspection on closures in a stack trace is impossible
					$params = null;
				} else {
					if (isset($step['class'])) {
						if (method_exists($step['class'], $step['function'])) {
							$reflection = new ReflectionMethod($step['class'], $step['function']);
						} else {
							$reflection = new ReflectionMethod($step['class'], '__call');
						}
					} else {
						$reflection = new ReflectionFunction($step['function']);
					}

					// Get the function parameters
					$params = $reflection->getParameters();
				}

				$args = array();

				foreach ($step['args'] as $i => $arg) {
					if (isset($params[$i])) {
						// Assign the argument by the parameter name
						$args[$params[$i]->name] = $arg;
					} else {
						// Assign the argument by number
						$args[$i] = $arg;
					}
				}
			}

			if (isset($step['class'])) {
				// Class->method() or Class::method()
				$function = $step['class'] . $step['type'] . $step['function'];
			}

			$output[] = array(
				'function' => $function,
				'args' => isset($args) ? $args : null,
				'file' => isset($file) ? $file : null,
				'line' => isset($line) ? $line : null,
				'source' => isset($source) ? $source : null,
			);

			unset($function, $args, $file, $line, $source);
		}

		return $output;
	}
}

?>