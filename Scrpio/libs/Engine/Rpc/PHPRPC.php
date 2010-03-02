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
	class Scorpio_Engine_Rpc_PHPRPC extends Scorpio_Engine_Rpc_PHPRPC_Core {
	}
}

class Scorpio_Engine_Rpc_PHPRPC_Core {
	/**
	 * return Create a new PHPRPC_Server
	 */
	public function &server() {

		include_once SYSPATH.'Scorpio/vendor/phprpc/php/phprpc_server.php';

		$srrver = new PHPRPC_Server();
		return $srrver;
	}

	/**
	 * return Create a new PHPRPC_Client
	 * @param $srrver phprpc server url
	 */
	public function &client($srrver) {

		include_once SYSPATH.'Scorpio/vendor/phprpc/php/phprpc_client.php';

		$client = new PHPRPC_Client($srrver);
		return $client;
	}
}

?>