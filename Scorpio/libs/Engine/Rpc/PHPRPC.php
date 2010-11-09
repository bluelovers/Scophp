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

//		include_once SYSPATH.'Scorpio/vendor/phprpc/php/phprpc_server.php';

		if (!Scorpio_Loader_Core::exists('PHPRPC_Server', true)) {
			Scorpio_Loader_Core::instance()
				->extend('PHPRPC_Server', 'Scorpio/vendor/phprpc/php/phprpc_server.php', SYSPATH)
				->load('PHPRPC_Server');
		}

		$srrver = new PHPRPC_Server();
		return $srrver;
	}

	/**
	 * return Create a new PHPRPC_Client
	 * @param $srrver phprpc server url
	 */
	public function &client($srrver) {

//		include_once SYSPATH.'Scorpio/vendor/phprpc/php/phprpc_client.php';

		if (!Scorpio_Loader_Core::exists('PHPRPC_Client', true)) {
			Scorpio_Loader_Core::instance()
				->extend('PHPRPC_Client', 'Scorpio/vendor/phprpc/php/phprpc_client.php', SYSPATH)
				->load('PHPRPC_Client');
		}

		$client = new PHPRPC_Client($srrver);
		return $client;
	}
}

?>