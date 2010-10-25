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
	class Scorpio_Api_Facebook_Friends extends Scorpio_Api_Facebook_Friends_Core {
	}
}

class Scorpio_Api_Facebook_Friends_Core extends Scorpio_Api_Facebook_Class {
	function getlists() {
		return $friends = $this->core->api('/me/friends');
	}
}

?>