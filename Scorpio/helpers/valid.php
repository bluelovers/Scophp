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
	class scovalid extends Scorpio_helper_valid_Core {
	}
}

class Scorpio_helper_valid_Core {
	protected static $instances = null;

	// 取得構造物件
	public static function instance($overwrite = false) {
		if (!static::$instances) {
			$ref = new ReflectionClass(($overwrite && !in_array($overwrite, array(true, 1), true)) ? $overwrite:get_called_class());
			static::$instances = $ref->newInstance();
		} elseif ($overwrite) {
			$ref = new ReflectionClass(!in_array($overwrite, array(true, 1), true) ? $overwrite:get_called_class());
			static::$instances = $ref->newInstance();
		}

		return static::$instances;
	}

	// 建立構造
	function __construct() {

		// make sure self::$instances is newer
		// 當未建立 static::$instances 時 會以當前 class 作為構造類別
		// 當已建立 static::$instances 時 如果呼叫的 class 不屬於當前 static::$instances 的父類別時 則會自動取代; 反之則 不做任何動作
		if (!static::$instances || !in_array(get_called_class(), class_parents(static::$instances))) {
			static::$instances = $this;
		}

//		print_r(array(get_called_class(), class_parents(static ::$instances), class_parents(self ::$instances), class_parents(get_called_class())));

		return static::$instances;
	}

	/**
	 * Validate IP
	 *
	 * @param   string   IP address
	 * @param   boolean  allow IPv6 addresses
	 * @param   boolean  allow private IP networks
	 * @return  boolean
	 */
	public static function ip($ip, $ipv6 = false, $allow_private = true) {
		// By default do not allow private and reserved range IPs
		$flags = FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE;
		if ($allow_private === true)
			$flags = FILTER_FLAG_NO_RES_RANGE;

		if ($ipv6 === true)
			return (bool)filter_var($ip, FILTER_VALIDATE_IP, $flags);

		return (bool)filter_var($ip, FILTER_VALIDATE_IP, $flags | FILTER_FLAG_IPV4);
	}

	/**
	 * @return  boolean
	 * @see http://www.php.net/manual/en/function.preg-match.php#87570
	 */
	public static function serialized ($data) {
		// $data = "a:0:{}";
		return (bool)preg_match("/(a|O|s|b)\x3a[0-9]*?((\x3a((\x7b?(.+)\x7d)|(\x22(.+)\x22\x3b)))|(\x3b))/", $data);
	}
}

?>