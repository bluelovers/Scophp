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
	class scodb_mysqli extends Scorpio_Helper_Db_Mysqli_Core {
	}
}

class Scorpio_Helper_Db_Mysqli_Core extends Scorpio_Helper_Db_Mysql {
	protected static $func_prefix = 'mysqli_';
}

?>