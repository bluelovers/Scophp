<?php

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
	class scotext extends Scorpio_helper_text_Core {
	}
}

class Scorpio_helper_text_Core {

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
	 *
	 * Returns a string produced according to the formatting string format .
	 *
	 * @param string $format
	 * @param array $args
	 * @param mixed $args, mixed $...
	 *
	 * @see http://tw2.php.net/manual/en/function.sprintf.php#94608
	 * @see http://tw2.php.net/manual/en/function.vsprintf.php#89349
	 */
	static function sprintf() {
		$args = func_get_args();
		$format = array_shift($args);

		$_notvar = false;

		if (!empty($format) && strpos($format, '%') !== false && is_array($args) &&
			count($args)) {
			if (count($args) == 1) {
				$args = is_array($args[0]) ? $args[0] : array($args[0]);
			} else {
				$_notvar = true;
			}
		} else {
			return $format;
		}

		if ($_notvar) {
			return vprintf($format, $args);
		}

		$_args = $args;
		//$_format = $format;

		echo $format;
		/*
		echo "\n";
		if (preg_match_all('/(?:%%|%(?:[0-9]+\$)?[+-]?(?:[ 0]|\'.)?-?[0-9]*(?:\.[0-9]+)?[bcdeufFosxX])/', $format, $match)) {
		var_dump($match);
		}
		echo "\n";
		if (preg_match_all('/ (?<!%) % ( (?: [[:alpha:]_-][[:alnum:]_-]* | ([-+])? [0-9]+ (?(2) (?:\.[0-9]+)? | \.[0-9]+ ) ) ) \$ [-+]? \'? .? -? [0-9]* (\.[0-9]+)? \w/x', $format, $match)) {
		var_dump($match);
		}
		echo "\n";
		if (preg_match_all('/(?<!%)%(\(([a-zA-Z_]\w*)\))?(\-?[\.\w]+)/', $format, $match)) {
		var_dump($match);
		}

		echo "\n";
		if (preg_match_all('/(?<fultext>(?<=%)(?:\((?<varname>[a-zA-Z_]\w*)\))?(?<type>\-?[\.\w]+|%))/', $format, $match)) {
		var_dump($match);
		}*/
		echo "\n";
		if (preg_match_all('/(?<!%)(?<fultext>%+(?:\((?<varname>[a-zA-Z_]\w*)\))?(?<type>(?<pad>\'.|[0-9])?\-?[a-zA-Z\d\.]+|%))/',
			$format, $matchs)) {


			$matchs_len = count($matchs['fultext']);

			//echo "length: $matchs_len\n";

			for ($i = 0; $i < $matchs_len; $i++) {

				if (!isset($matchs['fultext'][$i]) || $matchs['fultext'][$i] === null) {
					//echo "passed: $i\n";
					continue;
				}

				$varname = $matchs['varname'][$i];
				$fultext = $matchs['fultext'][$i];

				if (preg_match('/^(%+)%/', $fultext, $match)) {
					$_prefix = $match[1];
					if (strlen($_prefix) % 2) {

						//echo 'skip[1]: ' . $fultext. "\n";

						//$search = $fultext;
						//						$replace = sprintf($search, null);
						//						$format = preg_replace('/(?<!%)'.preg_quote($fultext, '/').'\b/s', $replace, $format);
						//
						//						echo  $replace."\n";
						//						echo htmlspecialchars('/(?<!%)'.preg_quote($fultext, '/').'/s')."\n";
						//						echo $search."\n";
						//						echo $replace."\n";

						continue;
					} else {
						//echo 'skip[0]: ' . $fultext . "\n";
					}
				} else {
					$_prefix = '%';

					//echo "novar: $fultext\n";
				}

				if (!empty($varname)) {

					$search = $_prefix . $matchs['type'][$i];

					if (array_key_exists($varname, $_args)) {
						unset($args[$varname]);

						//echo 'unset: ' . $varname.':';

						scoarray::remove_keys(&$matchs['fultext'], scoarray::search_all($fultext,
							$matchs['fultext']));

						//$replace = sprintf($search, $_args[$varname]);
						$replace = self::sprintf_hack($search, $_args[$varname]);
					} elseif ($varname == 'LF') {
						$replace = self::sprintf_hack($search, LF);
					} else {
						//echo 'undef: ' . $varname . ":";
						$replace = sprintf($search, null);
					}

					$replace = self::sprintf_quote($replace);

					$format = preg_replace('/(?<!%)' . preg_quote($fultext, '/') . '/s', $replace, $format);
					//echo  $replace."\n";
					//					echo htmlspecialchars('/(?<!%)'.preg_quote($fultext, '/').'/s')."\n";
					//					echo $search."\n";
					//					echo $replace."\n";

				} elseif ($fultext == '%%') {
					//echo 'XXX: ' . $fultext . "\n";
					//$replace = '%';
					//					$format = preg_replace('/(?<!%)'.preg_quote($fultext, '/').'\b/s', $replace, $format);
				} else {
					//$replace = self::mb_encode(sprintf(self::mb_decode($fultext), self::mb_decode(array_shift($args))));

					$replace = self::sprintf_hack($fultext, array_shift($args));

					$replace = self::sprintf_quote($replace);
					$format = preg_replace('/(?<!%)' . preg_quote($fultext, '/') . '/s', $replace, $format,
						1);

					//echo 'classic: ' . $fultext . ":";
					//					echo htmlspecialchars('/(?<!%)'.preg_quote($fultext, '/').'/s')."\n";
					//					echo $replace."\n";
				}
			}

			//$format = sprintf($format, null);

			//echo $format."\n";
			$format = self::sprintf_quote($format, 1);
			//echo $format."\n";
			//
			//var_dump($matchs);
			//echo "\n";
			//			var_dump($args);
		}

		return $format;
	}

	static function sprintf_quote($string, $remove = false) {
		$string = $remove ? str_replace('%%', '%', $string) : str_replace('%', '%%', $string);
		return $string;
	}

	static function sprintf_parse($format) {
		preg_match('/^(?<pre>%+)?%(?<pad>\'(?<pad2>.)|(?<pad3>[0-9]))?(?<sign>-|\+)?(?<size>[1-9][0-9]*)(?:\.(?<size2>\d+))?(?<type>[a-zA-Z])$/',
			$format, $match);

		return $match;
	}

	protected function sprintf_hack($format, $string) {
		$parse = self::sprintf_parse($format);

		//echo var_dump($parse);
		//		exit();

		if ($parse['type'] == 's') {
			$pad = (!empty($parse['pad2']) || $parse['pad3'] !== '') ? (!empty($parse['pad2']) ?
				$parse['pad2'] : (string )$parse['pad3']) : ' ';

			$ret = $parse['pre'] . self::str_pad($parse['size2'] ? mb_substr($string, 0, $parse['size2']) :
				$string, $parse['size'], $pad, $parse['sign'] == '-' ? STR_PAD_RIGHT :
				STR_PAD_LEFT);

			//echo "hack[1]: $format = $ret\n";

			//if ($format == '%020s') {
			//				//var_dump($parse);
			//				var_dump(array($pad, $parse['pad3'] !== '', ($parse['pad'] && (!empty($parse['pad2']) || $parse['pad3'] !== ''))));
			//				echo "\n";
			//			}


		} else {
			$ret = sprintf($format, $string);
			//echo "hack[0]: $format = $ret\n";
		}

		//if ($string == 'ěščřžýáíé') exit("----\n[".$string."]\n[".$ret."]\n------\n");

		return $ret;
	}

	/**
	 * Returns human readable sizes.
	 * @see  Based on original functions written by:
	 * @see  Aidan Lister: http://aidanlister.com/repos/v/function.size_readable.php
	 * @see  Quentin Zervaas: http://www.phpriot.com/d/code/strings/filesize-format/
	 *
	 * @param   integer  size in bytes
	 * @param   string   a definitive unit
	 * @param   string   the return string format
	 * @param   boolean  whether to use SI prefixes or IEC
	 * @return  string
	 */
	public static function bytes($bytes, $force_unit = null, $format = null, $si = true) {
		// Format string
		$format = ($format === null) ? '%01.2f %s' : (string )$format;

		static $units = array();

		// IEC prefixes (binary)
		if ($si == false or strpos($force_unit, 'i') !== false) {
			!$units[0] && $units[0] = array(__('B'), __('KiB'), __('MiB'), __('GiB'), __('TiB'),
				__('PiB'));
			$mod = 1024;
		}
		// SI prefixes (decimal)
		else {
			!$units[1] && $units[1] = array(__('B'), __('kB'), __('MB'), __('GB'), __('TB'),
				__('PB'));
			$mod = 1000;
		}

		// Determine unit to use
		if (($power = array_search((string )$force_unit, $units[$mod == 1000])) === false) {
			$power = ($bytes > 0) ? floor(log($bytes, $mod)) : 0;
		}

		return sprintf($format, $bytes / pow($mod, $power), $units[$power]);
	}

	/**
	 * Tests whether a string contains only 7bit ASCII bytes. This is used to
	 * determine when to use native functions or UTF-8 functions.
	 *
	 * @see http://sourceforge.net/projects/phputf8/
	 * @copyright  (c) 2007-2009 Kohana Team
	 * @copyright  (c) 2005 Harry Fuecks
	 * @license    http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt
	 *
	 * @param   string  string to check
	 * @return  bool
	 */
	public static function is_ascii($str) {
		return is_string($str) and !preg_match('/[^\x00-\x7F]/S', $str);
	}

	/**
	 * Strips out device control codes in the ASCII range.
	 *
	 * @see http://sourceforge.net/projects/phputf8/
	 * @copyright  (c) 2007-2009 Kohana Team
	 * @copyright  (c) 2005 Harry Fuecks
	 * @license    http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt
	 *
	 * @param   string  string to clean
	 * @return  string
	 */
	public static function strip_ascii_ctrl($str) {
		return preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]+/S', '', $str);
	}

	/**
	 * Strips out all non-7bit ASCII bytes.
	 *
	 * @see http://sourceforge.net/projects/phputf8/
	 * @copyright  (c) 2007-2009 Kohana Team
	 * @copyright  (c) 2005 Harry Fuecks
	 * @license    http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt
	 *
	 * @param   string  string to clean
	 * @return  string
	 */
	public static function strip_non_ascii($str) {
		return preg_replace('/[^\x00-\x7F]+/S', '', $str);
	}

	/**
	 * Replaces special/accented UTF-8 characters by ASCII-7 'equivalents'.
	 *
	 * @author  Andreas Gohr <andi@splitbrain.org>
	 * @see http://sourceforge.net/projects/phputf8/
	 * @copyright  (c) 2007-2009 Kohana Team
	 * @copyright  (c) 2005 Harry Fuecks
	 * @license    http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt
	 *
	 * @param   string   string to transliterate
	 * @param   integer  -1 lowercase only, +1 uppercase only, 0 both cases
	 * @return  string
	 */
	public static function transliterate_to_ascii($str, $case = 0) {
		static $UTF8_LOWER_ACCENTS = null;
		static $UTF8_UPPER_ACCENTS = null;

		if ($case <= 0) {
			if ($UTF8_LOWER_ACCENTS === null) {
				$UTF8_LOWER_ACCENTS = array('à' => 'a', 'ô' => 'o', 'ď' => 'd', 'ḟ' => 'f', 'ë' =>
					'e', 'š' => 's', 'ơ' => 'o', 'ß' => 'ss', 'ă' => 'a', 'ř' => 'r', 'ț' => 't',
					'ň' => 'n', 'ā' => 'a', 'ķ' => 'k', 'ŝ' => 's', 'ỳ' => 'y', 'ņ' => 'n', 'ĺ' =>
					'l', 'ħ' => 'h', 'ṗ' => 'p', 'ó' => 'o', 'ú' => 'u', 'ě' => 'e', 'é' => 'e', 'ç' =>
					'c', 'ẁ' => 'w', 'ċ' => 'c', 'õ' => 'o', 'ṡ' => 's', 'ø' => 'o', 'ģ' => 'g', 'ŧ' =>
					't', 'ș' => 's', 'ė' => 'e', 'ĉ' => 'c', 'ś' => 's', 'î' => 'i', 'ű' => 'u', 'ć' =>
					'c', 'ę' => 'e', 'ŵ' => 'w', 'ṫ' => 't', 'ū' => 'u', 'č' => 'c', 'ö' => 'o', 'è' =>
					'e', 'ŷ' => 'y', 'ą' => 'a', 'ł' => 'l', 'ų' => 'u', 'ů' => 'u', 'ş' => 's', 'ğ' =>
					'g', 'ļ' => 'l', 'ƒ' => 'f', 'ž' => 'z', 'ẃ' => 'w', 'ḃ' => 'b', 'å' => 'a', 'ì' =>
					'i', 'ï' => 'i', 'ḋ' => 'd', 'ť' => 't', 'ŗ' => 'r', 'ä' => 'a', 'í' => 'i', 'ŕ' =>
					'r', 'ê' => 'e', 'ü' => 'u', 'ò' => 'o', 'ē' => 'e', 'ñ' => 'n', 'ń' => 'n', 'ĥ' =>
					'h', 'ĝ' => 'g', 'đ' => 'd', 'ĵ' => 'j', 'ÿ' => 'y', 'ũ' => 'u', 'ŭ' => 'u', 'ư' =>
					'u', 'ţ' => 't', 'ý' => 'y', 'ő' => 'o', 'â' => 'a', 'ľ' => 'l', 'ẅ' => 'w', 'ż' =>
					'z', 'ī' => 'i', 'ã' => 'a', 'ġ' => 'g', 'ṁ' => 'm', 'ō' => 'o', 'ĩ' => 'i', 'ù' =>
					'u', 'į' => 'i', 'ź' => 'z', 'á' => 'a', 'û' => 'u', 'þ' => 'th', 'ð' => 'dh',
					'æ' => 'ae', 'µ' => 'u', 'ĕ' => 'e', 'ı' => 'i', );
			}

			$str = str_replace(array_keys($UTF8_LOWER_ACCENTS), array_values($UTF8_LOWER_ACCENTS),
				$str);
		}

		if ($case >= 0) {
			if ($UTF8_UPPER_ACCENTS === null) {
				$UTF8_UPPER_ACCENTS = array('À' => 'A', 'Ô' => 'O', 'Ď' => 'D', 'Ḟ' => 'F', 'Ë' =>
					'E', 'Š' => 'S', 'Ơ' => 'O', 'Ă' => 'A', 'Ř' => 'R', 'Ț' => 'T', 'Ň' => 'N', 'Ā' =>
					'A', 'Ķ' => 'K', 'Ĕ' => 'E', 'Ŝ' => 'S', 'Ỳ' => 'Y', 'Ņ' => 'N', 'Ĺ' => 'L', 'Ħ' =>
					'H', 'Ṗ' => 'P', 'Ó' => 'O', 'Ú' => 'U', 'Ě' => 'E', 'É' => 'E', 'Ç' => 'C', 'Ẁ' =>
					'W', 'Ċ' => 'C', 'Õ' => 'O', 'Ṡ' => 'S', 'Ø' => 'O', 'Ģ' => 'G', 'Ŧ' => 'T', 'Ș' =>
					'S', 'Ė' => 'E', 'Ĉ' => 'C', 'Ś' => 'S', 'Î' => 'I', 'Ű' => 'U', 'Ć' => 'C', 'Ę' =>
					'E', 'Ŵ' => 'W', 'Ṫ' => 'T', 'Ū' => 'U', 'Č' => 'C', 'Ö' => 'O', 'È' => 'E', 'Ŷ' =>
					'Y', 'Ą' => 'A', 'Ł' => 'L', 'Ų' => 'U', 'Ů' => 'U', 'Ş' => 'S', 'Ğ' => 'G', 'Ļ' =>
					'L', 'Ƒ' => 'F', 'Ž' => 'Z', 'Ẃ' => 'W', 'Ḃ' => 'B', 'Å' => 'A', 'Ì' => 'I', 'Ï' =>
					'I', 'Ḋ' => 'D', 'Ť' => 'T', 'Ŗ' => 'R', 'Ä' => 'A', 'Í' => 'I', 'Ŕ' => 'R', 'Ê' =>
					'E', 'Ü' => 'U', 'Ò' => 'O', 'Ē' => 'E', 'Ñ' => 'N', 'Ń' => 'N', 'Ĥ' => 'H', 'Ĝ' =>
					'G', 'Đ' => 'D', 'Ĵ' => 'J', 'Ÿ' => 'Y', 'Ũ' => 'U', 'Ŭ' => 'U', 'Ư' => 'U', 'Ţ' =>
					'T', 'Ý' => 'Y', 'Ő' => 'O', 'Â' => 'A', 'Ľ' => 'L', 'Ẅ' => 'W', 'Ż' => 'Z', 'Ī' =>
					'I', 'Ã' => 'A', 'Ġ' => 'G', 'Ṁ' => 'M', 'Ō' => 'O', 'Ĩ' => 'I', 'Ù' => 'U', 'Į' =>
					'I', 'Ź' => 'Z', 'Á' => 'A', 'Û' => 'U', 'Þ' => 'Th', 'Ð' => 'Dh', 'Æ' => 'Ae',
					'İ' => 'I', );
			}

			$str = str_replace(array_keys($UTF8_UPPER_ACCENTS), array_values($UTF8_UPPER_ACCENTS),
				$str);
		}

		return $str;
	}

	/**
	 * @see http://www.php.net/manual/en/function.preg-replace.php#87816
	 */
	public static function lf($str, $eol = LF, $search = CR) {
		/*
		http://www.php.net/manual/en/function.preg-replace.php#87816

		$sql = preg_replace("/(?<!\\n)\\r+(?!\\n)/", "\r\n", $sql);
		$sql = preg_replace("/(?<!\\r)\\n+(?!\\r)/", "\r\n", $sql);
		$sql = preg_replace("/(?<!\\r)\\n\\r+(?!\\n)/", "\r\n", $sql);
		*/

		($search === null || $search === false) && $search = CR;

		if (strpos($str, $search) !== false) {
			$str = preg_replace("/(?<!\\n)\\r+(?!\\n)/", CR.LF, $str);
			$str = preg_replace("/(?<!\\r)\\n+(?!\\r)/", CR.LF, $str);
			$str = preg_replace("/(?<!\\r)\\n\\r+(?!\\n)/", CR.LF, $str);

			($eol === null || $eol === false) && $eol = LF;

			($eol != CR.LF) && $str = str_replace(CR.LF, $eol, $str);
		}

		return $str;
	}

	public static function ip($ip_address) {
		if ($comma = strrpos($ip_address, ',') !== false) {
			$ip_address = substr($ip_address, $comma + 1);
		}

		if (!scovalid::ip($ip_address)) {
			// Use an empty IP
			$ip_address = '0.0.0.0';
		}

		return $ip_address;
	}

	/**
	 * @see http://tw2.php.net/manual/en/function.urldecode.php#62707
	 */
	function code2utf($num){
		if($num<128)
			return chr($num);
		if($num<1024)
			return chr(($num>>6)+192).chr(($num&63)+128);
		if($num<32768)
			return chr(($num>>12)+224).chr((($num>>6)&63)+128)
				.chr(($num&63)+128);
		if($num<2097152)
			return chr(($num>>18)+240).chr((($num>>12)&63)+128)
				.chr((($num>>6)&63)+128).chr(($num&63)+128);

		return '';
	}

	/**
	 * @see http://tw2.php.net/manual/en/function.urldecode.php#62707
	 */
	function unescape($strIn, $iconv_to = 'UTF-8') {
		$strOut = '';
		$iPos = 0;
		$len = strlen ($strIn);
		while ($iPos < $len) {
			$charAt = substr ($strIn, $iPos, 1);
			if ($charAt == '%') {
				$iPos++;
				$charAt = substr ($strIn, $iPos, 1);
				if ($charAt == 'u') {
					// Unicode character
					$iPos++;
					$unicodeHexVal = substr ($strIn, $iPos, 4);
					$unicode = hexdec ($unicodeHexVal);
					$strOut .= static::code2utf($unicode);
					$iPos += 4;
				} else {
					// Escaped ascii character
					$hexVal = substr ($strIn, $iPos, 2);
					if (hexdec($hexVal) > 127) {
						// Convert to Unicode
						$strOut .= static::code2utf(hexdec ($hexVal));
					} else {
						$strOut .= chr (hexdec ($hexVal));
					}
					$iPos += 2;
				}
			} else {
				$strOut .= $charAt;
				$iPos++;
			}
		}
		if ($iconv_to != "UTF-8") {
			$strOut = iconv("UTF-8", $iconv_to, $strOut);
		}
		return $strOut;
	}

	/*
	 * @see http://tw2.php.net/manual/en/function.urldecode.php#29272
	 * For compatibility of new and old brousers:
	 *		%xx -> char
	 *		%u0xxxx -> char
	 */
	function unicode_decode($txt) {
		$txt = ereg_replace('%u0([[:alnum:]]{3})', '&#x\1;',$txt);
		$txt = ereg_replace('%([[:alnum:]]{2})', '&#x\1;',$txt);
		return ($txt);
	}

	function replace($search, $replace, $subject) {
		if (!is_array($search) && strpos($subject, $search) === false) return $subject;

		if (is_array($search) && count($search) == 1) $search = $search[0];
		if (is_array($replace) && count($replace) == 1) $replace = $replace[0];

		return str_replace($search, $replace, $subject);
	}

}

/*
echo '<pre>';

echo vsprintf('[%-20s] [%20s] %.3f %(num).3f %%s %%%s %%%s%% Hello, %(place)s, how is it hanning at %(place)s? %s works just as well %(name)s: %(value)d %s %d%% %.3f',
array('place' => 'world333', 'sprintf', 'not used', 'num' => 'world666',
'sprintf', 'not used', 'name' => 'world999', 'sprintf', 'not used', 'value' =>
'world', 'sprintf', 'not used', 'sprintf', 'not used', 'sprintf', 'not used',
'sprintf', 'not used', ));
echo "\n";
echo scotext::sprintf("[%(test1)-20s] [%(test1)20s] [%(test1)020s] [%(test1)'#20s] [%(test1)20.20s]
[%(test2)-20s] [%(test2)20s] [%(test2)020s] [%(test2)'#20s] [%(test2)20.20s]
[%(test3)-20s] [%(test3)20s] [%(test3)020s] [%(test3)'#20s] [%(test3)20.20s]

[%(test3)20.3s] [%(test3)20.1s] [%(test3)20.5s]

\n%.3f %(num).3f %%s %%(value)s %(value)s %%%s %%%s%%  %%%%%s%%%% Hello, %(place)s, how is it hanning at %(place)s? %s works just as well %(name)s: %(value)d %s %d%% %.3f",
array('test1' => 'escrzyaie', 'test2' => 'ěščřžýáíé', 'test3' => '姫とボイン',
'place' => 'world', 'sprintf', 'not used', 'name' => 9999, 'num' =>
645321.123456));

*/

?>