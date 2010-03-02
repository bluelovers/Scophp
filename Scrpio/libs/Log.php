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
	class Scorpio_Log extends Scorpio_Log_Core {}
}

class Scorpio_Log_Core {

	// Configuration
	protected static $config;

	// Drivers
	protected static $drivers;

	// Logged messages
	protected static $messages;

	/**
	 * Add a new message to the log.
	 *
	 * @param   string  type of message
	 * @param   string  message text
	 * @return  void
	 */
	public static function add($type, $message, $variables = null)
	{

		$variables !== null && $message = scotext::sprintf($message, $variables);

		// Make sure the drivers and config are loaded
		if ( ! is_array(Scorpio_Log::$config))
		{
			Scorpio_Log::$config = Scorpio_Kenal::config('log');
		}

		if ( ! is_array(Scorpio_Log::$drivers))
		{
			foreach ( (array) Scorpio_Kenal::config('log.drivers') as $driver_name)
			{
				// Set driver name
				$driver = 'Log_'.ucfirst($driver_name).'_Driver';

				// Load the driver
				if ( ! Scorpio_Kenal::auto_load($driver))
					throw new Scorpio_Exception('Log Driver Not Found: %(driver)s', array('driver' => $driver));

				// Initialize the driver
				$driver = new $driver(array_merge(Scorpio_Kenal::config('log'), Scorpio_Kenal::config('log_'.$driver_name)));

				// Validate the driver
				if ( ! ($driver instanceof Log_Driver))
					throw new Scorpio_Exception('%(driver)s does not implement the Log_Driver interface', array('driver' => $driver));

				Scorpio_Log::$drivers[] = $driver;
			}

			// Always save logs on shutdown
			Event::add('system.shutdown', array('Scorpio_Log', 'save'));
		}

		Scorpio_Log::$messages[] = array('date' => time(), 'type' => $type, 'message' => $message);
	}

	/**
	 * Save all currently logged messages.
	 *
	 * @return  void
	 */
	public static function save()
	{
		if (empty(Scorpio_Log::$messages))
			return;

		foreach (Scorpio_Log::$drivers as $driver)
		{
			// We can't throw exceptions here or else we will get a
			// Exception thrown without a stack frame error
			try
			{
				$driver->save(Scorpio_Log::$messages);
			}
			catch(Exception $e){}
		}

		// Reset the messages
		Scorpio_Log::$messages = array();
	}
}

?>