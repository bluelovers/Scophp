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
	class Scrpio_Exception extends Scrpio_Exception_Core {
	}
}

class Scrpio_Exception_Core extends Exception {

	public static $enabled = false;

	// To hold unique identifier to distinguish error output
	protected $instance_identifier;

	/**
	 * Creates a new translated exception.
	 *
	 * @param string error message
	 * @param array translation variables
	 * @return void
	 */
	public function __construct($message, $variables = null, $code = 0) {
		$this->instance_identifier = uniqid();

		$variables && $message = scotext::sprintf($message, $variables);

		// Sets $this->message the proper way
		parent::__construct($message, $code);
	}

	public static function enable() {
		if (!self::$enabled) {
			set_exception_handler(array('Scrpio_Exception', 'handle'));

			self::$enabled = true;
		}
	}

	public static function disable() {
		if (self::$enabled) {
			restore_exception_handler();

			self::$enabled = false;
		}
	}
}

?>