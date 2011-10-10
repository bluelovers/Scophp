<?php

/**
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
	class scodb_mysqli extends Scorpio_helper_db_mysqli_Core_ {
	}
}

class Scorpio_helper_db_mysqli_Core_ extends Scorpio_helper_db_mysql {
	protected static $func_prefix = 'mysqli_';
}

?>