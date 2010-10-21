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

	/**
	 * Check an email address for correct format.
	 *
	 * @link  http://www.iamcal.com/publish/articles/php/parsing_email/
	 * @link  http://www.w3.org/Protocols/rfc822/
	 *
	 * @param   string   email address
	 * @param   boolean  strict RFC compatibility
	 * @return  boolean
	 */
	public static function email($email, $strict = FALSE)
	{
		if ($strict === TRUE)
		{
			$qtext = '[^\\x0d\\x22\\x5c\\x80-\\xff]';
			$dtext = '[^\\x0d\\x5b-\\x5d\\x80-\\xff]';
			$atom  = '[^\\x00-\\x20\\x22\\x28\\x29\\x2c\\x2e\\x3a-\\x3c\\x3e\\x40\\x5b-\\x5d\\x7f-\\xff]+';
			$pair  = '\\x5c[\\x00-\\x7f]';

			$domain_literal = "\\x5b($dtext|$pair)*\\x5d";
			$quoted_string  = "\\x22($qtext|$pair)*\\x22";
			$sub_domain     = "($atom|$domain_literal)";
			$word           = "($atom|$quoted_string)";
			$domain         = "$sub_domain(\\x2e$sub_domain)*";
			$local_part     = "$word(\\x2e$word)*";

			$expression     = "/^$local_part\\x40$domain$/D";
		}
		else
		{
			$expression = '/^[-_a-z0-9\'+*$^&%=~!?{}]++(?:\.[-_a-z0-9\'+*$^&%=~!?{}]+)*+@(?:(?![-.])[-a-z0-9.]+(?<![-.])\.[a-z]{2,6}|\d{1,3}(?:\.\d{1,3}){3})(?::\d++)?$/iD';
		}

		return (bool) preg_match($expression, (string) $email);
	}

	/**
	 * Validate a URL.
	 *
	 * @param   string   URL
	 * @return  boolean
	 */
	public static function url($url)
	{
		// Based on http://www.apps.ietf.org/rfc/rfc1738.html#sec-5
		if ( ! preg_match(
			'~^

			# scheme
			[-a-z0-9+.]++://

			# username:password (optional)
			(?:
				    [-a-z0-9$_.+!*\'(),;?&=%]++   # username
				(?::[-a-z0-9$_.+!*\'(),;?&=%]++)? # password (optional)
				@
			)?

			(?:
				# ip address
				\d{1,3}+(?:\.\d{1,3}+){3}+

				| # or

				# hostname (captured)
				(
					     (?!-)[-a-z0-9]{1,63}+(?<!-)
					(?:\.(?!-)[-a-z0-9]{1,63}+(?<!-)){0,126}+
				)
			)

			# port (optional)
			(?::\d{1,5}+)?

			# path (optional)
			(?:/.*)?

			$~iDx', $url, $matches))
			return FALSE;

		// We matched an IP address
		if ( ! isset($matches[1]))
			return TRUE;

		// Check maximum length of the whole hostname
		// http://en.wikipedia.org/wiki/Domain_name#cite_note-0
		if (strlen($matches[1]) > 253)
			return FALSE;

		// An extra check for the top level domain
		// It must start with a letter
		$tld = ltrim(substr($matches[1], (int) strrpos($matches[1], '.')), '.');
		return ctype_alpha($tld[0]);
	}

	/*
		$str = "/\[img=(\d{1,4})[x|\,](\d{1,4})\]\s*([^\[\<\r\n]+?)\s*\[\/img\]/ies";

//$str = '/^(.)(.+)\1([a-zA-Z]*)/';

preg_match('/^(?<pattern>[^\s\'"\\\])(?<expression>.+)\1(?<options>[a-zA-Z]*)$/s', $str, $m);

echo $str;
print_r($m);
	*/
	function regex($str) {
		if (preg_match('/^(?<pattern>[^\s\'"\\\])(?<expression>.+)\1(?<options>[a-zA-Z]*)$/s', $str, $m)) {

			$ret = array(
				'source' => $str,
				'pattern' => $m['pattern'],
				'expression' => $m['expression'],
				'options' => $m['options'],
			);

			return $ret;
		}

		return false;
	}

	function array($array, $notempty = false) {
		return $notempty ? (!empty($array) && is_array($array) && $array != array()) : is_array($array);
	}

	function empty($s, $strict = FALSE) {
		return $strict ? (($s === null || $s === '' || $s === array()) ? false : true) : !empty($s);
	}
}

?>