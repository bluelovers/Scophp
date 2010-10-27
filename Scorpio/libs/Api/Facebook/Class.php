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
	class Scorpio_Api_Facebook_Class extends Scorpio_Api_Facebook_Class_Core {
	}
}

class Scorpio_Api_Facebook_Class_Core {
	protected $core = null;
	protected $_data = array();

	public function __construct(&$core) {
		$this->core = &$core;
	}

	function _ksort_by_array($array, $fields, $fields_filp = false) {
		if (empty($array)) return array();

		$fields = $fields_filp ? array_flip($fields) : $fields;

		$_array_add = array_diff_key($array, $fields);
		$_array = array();

		foreach ($fields as $_k => $_v) {
			if ((isset($array[$_k]) || !empty($array[$_k])) && $array[$_k] !== null) {
				$_array[$_k] = $array[$_k];
			}
		}

		if (!empty($_array_add)) {
			 $ret = $_array + (array)$_array_add;
		} else {
			$ret = $_array;
		}

		return $ret;
	}
}

?>