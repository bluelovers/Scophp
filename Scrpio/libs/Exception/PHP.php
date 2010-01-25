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
	class Scrpio_Exception_PHP extends Scrpio_Exception_PHP_Core {}
}

class Scrpio_Exception_PHP_Core extends Scrpio_Exception {
	public static $enabled = false;
	protected static $_scrpio_self_classname_ = 'Scrpio_Exception_PHP';

	/**
	 * Create a new PHP error exception.
	 *
	 * @return  void
	 */
	public function __construct($code, $error, $file, $line, $context = NULL)
	{
		parent::__construct($error);

		// Set the error code, file, line, and context manually
		$this->code = $code;
		$this->file = $file;
		$this->line = $line;
	}

	protected static function _self($name = null, $val = null) {
		if ($name) {
			return $val !== null ? scophp::set_static_value(self::$_scrpio_self_classname_, $name, $val) : scophp::get_static_value(self::$_scrpio_self_classname_, $name);
		} else {
			return self::$_scrpio_self_classname_;
		}
	}

	/**
	 * Enable Kohana PHP error handling.
	 *
	 * @return  void
	 */
	public static function enable()
	{
		if ( ! self::_self('enabled'))
		{
			// Handle runtime errors
			set_error_handler(array(self::_self(), 'error_handler'));

			// Handle errors which halt execution
			Scrpio_Event::add('system.shutdown', array(self::_self(), 'shutdown_handler'));

			self::_self('enabled', true);
		}
	}

	/**
	 * Disable Kohana PHP error handling.
	 *
	 * @return  void
	 */
	public static function disable()
	{
		if (self::_self('enabled'))
		{
			restore_error_handler();

			Scrpio_Event::clear('system.shutdown', array(self::_self(), 'shutdown_handler'));

			self::_self('enabled', false);
		}
	}

	/**
	 * PHP error handler.
	 *
	 * @throws  Kohana_PHP_Exception
	 * @return  void
	 */
	public static function error_handler($code, $error, $file, $line, $context = NULL)
	{
		// Respect error_reporting settings
		if (error_reporting() & $code)
		{
			$self = self::_self();
			// Throw an exception
			throw new ${self}($code, $error, $file, $line, $context);
		}
	}

	/**
	 * Catches errors that are not caught by the error handler, such as E_PARSE.
	 *
	 * @uses    Kohana_Exception::handle()
	 * @return  void
	 */
	public static function shutdown_handler()
	{
		if (self::_self('enabled') AND $error = error_get_last() AND (error_reporting() & $error['type']))
		{
			$self = self::_self();

			// Fake an exception for nice debugging
			Scrpio_Exception::handle(new ${self}($error['type'], $error['message'], $error['file'], $error['line']));
		}
	}
}

?>