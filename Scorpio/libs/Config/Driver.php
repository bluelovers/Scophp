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
	abstract class Scorpio_Config_Driver extends Scorpio_Config_Driver_Core {}
}

abstract class Scorpio_Config_Driver_Core {
	/**
	 * The changed status of configuration values,
	 * current state versus the stored state.
	 *
	 * @var     bool
	 */
	protected $changed = FALSE;

	/**
	 * Determines if any config has been loaded yet
	 */
	public $loaded = FALSE;
}

?>