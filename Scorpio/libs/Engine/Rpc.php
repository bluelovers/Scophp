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
	class Scorpio_Engine_Rpc extends Scorpio_Engine_Rpc_Core {
	}
}

class Scorpio_Engine_Rpc_Core {
	var $engine = null;

	protected $_scorpio_engine_ = null;
	protected static $_scorpio_engine_default_ = 'PHPRPC';

	public static function instance($engine = 'default') {
		return new Scorpio_Engine_Rpc($engine);
	}

	function __construct($engine = 'default') {

		$engine == 'default' && $engine = Scorpio_Engine_Rpc::$_scorpio_engine_default_;

		$this->engine = $engine;
		$obj = new ReflectionClass('Scorpio_Engine_Rpc_'.$engine);

		$this->_scorpio_engine_ = $obj->newInstance();
	}

	/**
	 * @return PHPRPC_Server
	 */
	public function &server() {
		$srrver = $this->_scorpio_engine_->server();
		return $srrver;
	}

	public function &client($srrver) {
		$client = $this->_scorpio_engine_->client($srrver);
		return $client;
	}
}

?>