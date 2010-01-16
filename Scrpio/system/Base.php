<?php

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
	class Scrpio_SYS_Base extends Scrpio_SYS_Base_Core {
	}
	class Sco_Base extends Scrpio_SYS_Base {
	}
}

class Scrpio_SYS_Base_Core {

	protected static $instances = null;

	// Output buffering level
	protected static $buffer_level;

	// The final output that will displayed by Kohana
	public static $output = '';

	public static function &instance($overwrite = false) {
		if (!self::$instances) {
			$ref = new ReflectionClass(($overwrite && !in_array($overwrite, array(true, 1), true)) ?
				$overwrite : 'Sco_Base');
			self::$instances = $ref->newInstance();
		} elseif ($overwrite) {
			$ref = new ReflectionClass(!in_array($overwrite, array(true, 1), true) ? $overwrite :
				get_class(self::$instances));
			self::$instances = $ref->newInstance();
		}

		return self::$instances;
	}

	function __construct() {

		// make sure self::$instances is newer
		if (!self::$instances || !in_array(get_class($this), class_parents(self::$instances))) {
			self::$instances = $this;
		}

		return self::$instances;
	}

	public static function Init() {
		static $_do;

		if (!$_do) {
			$_do = true;

			require_once (SYSPATH . 'Scrpio/libs/File.php');
			require_once (SYSPATH . 'Scrpio/libs/Loader.php');

			$file = new Scrpio_File_Core();

			$base = $file->dirname(dirname(__file__), '..');

			foreach ($file->scandir_ext('php', $base . 'syntax') as $file) {
				include_once ($base . 'syntax/' . $file);
			}

			Scrpio_Loader_Core::lib('Spl_Class');
			Scrpio_Loader_Core::lib('Loader');

			spl_autoload_register(array('Scrpio_Loader', 'load'));
		}
	}

	public static function Setup() {
		Scrpio_Loader::lib('File');
		Scrpio_Loader::lib('Event');
		Scrpio_Loader::core('Base');

		Sco_Base::$buffer_level = ob_get_level();

		// Set autoloader
		spl_autoload_unregister(array('Scrpio_Loader', 'load'));
		spl_autoload_register(array('Sco_Base', 'auto_load'));

		// Register a shutdown function to handle system.shutdown events
		register_shutdown_function(array('Sco_Base', 'shutdown'));

		scophp::date_default_timezone_set(Sco_Base::config('locale.timezone'));
	}

	public static function Start() {

	}

	public static function shutdown() {
		static $run;

		// Only run this function once
		if ($run === true)
			return;

		$run = true;

		// Run system.shutdown event
		Scrpio_Event::run('system.shutdown');

		// Close output buffers
		//Sco_Base::close_buffers(true);

		// Run the output event
		Scrpio_Event::run('system.display', Sco_Base::$output);

		// Render the final output
		Sco_Base::render(Sco_Base::$output);
	}

	public static function auto_load($class) {
		Scrpio_Event::run('system.autoload', $class);

		return self::instance()->loader->load($class);
	}

	/**
	 * Closes all open output buffers, either by flushing or cleaning, and stores
	 * output buffer for display during shutdown.
	 *
	 * @param   boolean  disable to clear buffers, rather than flushing
	 * @return  void
	 */
	public static function close_buffers($flush = true) {
		if (ob_get_level() >= Sco_Base::$buffer_level) {
			// Set the close function
			$close = ($flush === true) ? 'ob_end_flush' : 'ob_end_clean';

			while (ob_get_level() > Sco_Base::$buffer_level) {
				// Flush or clean the buffer
				$close();
			}

			// Store the Kohana output buffer
			ob_end_clean();
		}
	}

	public static function render($output) {
	}

	public static function config($name) {

		if ($name == 'locale.timezone')
			return 'Asia/Taipei';

		return array();
	}

	function &__get($key) {
		switch ($key) {
			case 'loader':
				$ret = Scrpio_Loader::instance();
				break;
			case 'php':
				$ret = scophp::instance();
				break;
			default:

				trigger_error('Sco_Base: Unknown (' . $key . ')', E_USER_ERROR);

				break;
		}

		return $ret;
	}

}

?>