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
	class scotext extends Scorpio_helper_text_Core_ {
	}
}

class Scorpio_helper_text_Core_ {

	protected static $instances = null;

	// 取得構造物件
	public static function &instance($overwrite = false) {
		$_class_ = 'scotext';

		if (!isset(scotext::$instances)) {
			$ref = new ReflectionClass($_class_);
			scotext::$instances = $ref->newInstance();
		/*
		} elseif ($overwrite) {
			$ref = new ReflectionClass($_class_);
			scotext::$instances = $ref->newInstance();
		*/
		}

		return scotext::$instances;
	}

	// 建立構造
	function __construct() {
		if (!isset(scotext::$instances)) {
			scotext::$instances = $this;
		}
		return scotext::$instances;
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
	 *
	 * @example test script
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
						$replace = scotext::sprintf_hack($search, $_args[$varname]);
					} elseif ($varname == 'LF') {
						$replace = scotext::sprintf_hack($search, LF);
					} else {
						//echo 'undef: ' . $varname . ":";
						$replace = sprintf($search, null);
					}

					$replace = scotext::sprintf_quote($replace);

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
					//$replace = scotext::mb_encode(sprintf(scotext::mb_decode($fultext), scotext::mb_decode(array_shift($args))));

					$replace = scotext::sprintf_hack($fultext, array_shift($args));

					$replace = scotext::sprintf_quote($replace);
					$format = preg_replace('/(?<!%)' . preg_quote($fultext, '/') . '/s', $replace, $format,
						1);

					//echo 'classic: ' . $fultext . ":";
					//					echo htmlspecialchars('/(?<!%)'.preg_quote($fultext, '/').'/s')."\n";
					//					echo $replace."\n";
				}
			}

			//$format = sprintf($format, null);

			//echo $format."\n";
			$format = scotext::sprintf_quote($format, 1);
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
		$parse = scotext::sprintf_parse($format);

		//echo var_dump($parse);
		//		exit();

		if ($parse['type'] == 's') {
			$pad = (!empty($parse['pad2']) || $parse['pad3'] !== '') ? (!empty($parse['pad2']) ?
				$parse['pad2'] : (string )$parse['pad3']) : ' ';

			$ret = $parse['pre'] . scotext::str_pad($parse['size2'] ? mb_substr($string, 0, $parse['size2']) :
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
	 * Returns human readable sizes. Based on original functions written by
	 * [Aidan Lister](http://aidanlister.com/repos/v/function.size_readable.php)
	 * and [Quentin Zervaas](http://www.phpriot.com/d/code/strings/filesize-format/).
	 *
	 *		echo scotext::bytes(filesize($file));
	 *
	 * @param		integer  size in bytes
	 * @param		string   a definitive unit
	 * @param		string   the return string format
	 * @param		boolean  whether to use SI prefixes or IEC
	 * @return		string
	 *
	 * @author		Kohana Team
	 * @copyright	(c) 2007-2011 Kohana Team
	 */
	public static function bytes($bytes, $force_unit = NULL, $format = NULL, $si = TRUE)
	{
		// Format string
		$format = ($format === NULL) ? '%01.2f %s' : (string) $format;

		// IEC prefixes (binary)
		if ($si == FALSE OR strpos($force_unit, 'i') !== FALSE)
		{
			$units = array('B', 'KiB', 'MiB', 'GiB', 'TiB', 'PiB');
			$mod   = 1024;
		}
		// SI prefixes (decimal)
		else
		{
			$units = array('B', 'kB', 'MB', 'GB', 'TB', 'PB');
			$mod   = 1000;
		}

		// Determine unit to use
		if (($power = array_search( (string) $force_unit, $units)) === FALSE)
		{
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
					$strOut .= scotext::code2utf($unicode);
					$iPos += 4;
				} else {
					// Escaped ascii character
					$hexVal = substr ($strIn, $iPos, 2);
					if (hexdec($hexVal) > 127) {
						// Convert to Unicode
						$strOut .= scotext::code2utf(hexdec ($hexVal));
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

	/**
	 * Parse a URL and return its components
	 * include ipv6, ip, domainname, localdomain
	 * base on {@link http://www.php.net/manual/en/function.parse-url.php#86115 j_parseUrl}
	 * @example ../docs/test/scotext_parse_uri.php
	 **/
	function parse_uri($url, $retkey = '') {
		static $r;
		if (!$r) {
			$r  = '^(?P<uri_source>'
			.	'(?:(?P<scheme>[a-z0-9+-._]+):(?://)?)?'
			.	'(?:'
			.		'(?P<host_source>'
			.			'(?:(?P<userinfo>(?:[a-z0-9-._~!$&\'()*+,;=:]|%[0-9a-f]{2})+)@)?'
			.			'(?P<host>'
			.				'(?:\[((?:[a-z0-9:])+)\])'
			.				'|(?:[a-z0-9-_\.]+\.)?(?:\d+\.\d+\.\d+\.\d+)'
			.				'|(?:'
			.						'xn--[a-z0-9]+'
			.						'|[a-z0-9-._~!$&\'()*+,;=]'
			.						'|%[0-9a-f]{2}'
			.						'|[\x{4e00}-\x{9fa5}]+'
			.					')+'
			.					'\.(?:xn--[a-z0-9]+|[a-z-_]+|[\x{4e00}-\x{9fa5}]+)'
			.				'|(?:[a-z0-9-_]+(?!(?:\:\w+)+))'
			.			')'
			.			'(?::(?P<port>\d+))?'
			.		')?'
			.		'(?P<path>'
			.			'(?:/(?:[a-z0-9-._~!$&\'()*+,;=:@/]|%[0-9a-f]{2})+?)'
			.			'|'
			.			'(?:/'
			.				'(?:[a-z0-9-._~!$&\'()*+,;=:@]|%[0-9a-f]{2})+'
			.				'(?:[a-z0-9-._~!$&\'()*+,;=:@\/]|%[0-9a-f]{2})+?'
			.			')'
			.			'|\b[^\?\#]+'
			.		')'
			.	')'
			.	'(?:\?(?P<query>(?:[a-z0-9-._~!$&\'()*+,;=:\/?@]|%[0-9a-f]{2})*))?'
			.	'(?:#(?P<anchor>(?:[a-z0-9-._~!$&\'()*+,;=:\/?@]|%[0-9a-f]{2})*))?'
			.	')$';

			$r = '`'.$r.'`ius';
		}

		$parts = array();
		if (!preg_match($r, $url, $parts)) {
			$parts = parse_url($url);
		}

		foreach ($parts as $k => $v) {
			if (is_int($k) || $v === '') unset($parts[$k]);
		}

		list($parts['user'], $parts['pass']) = explode(':', $parts['userinfo'], 2);

		$parts['authority'] = ($parts['userinfo'] ? $parts['userinfo'].'@':'').
			$parts['host'].
			($parts['port'] ? ':'.$parts['port'] : '');

		$parts['fragment'] = $parts['anchor'];

		if ($parts['host']) {
			static $r2;
			if (!$r2) {
				$r2 = '/^(?:'
				.		'(?:'
				.			'(?:(?P<ip_subdomain>.*)\.)?'
				.			'(?P<ip_maindomian>(?:\d+\.){3}(?:\d+))'
				.		')'
				.		'|(?:'
				.			'(?:(?P<host_subdomain>.*)\.)?'
				.			'(?P<host_maindomian>[a-z0-9_\-]+|[\x{4e00}-\x{9fa5}]+|xn--[a-z0-9]+)'
				.			'(?:\.(?P<host_ext>[a-z]{2,6}|[\x{4e00}-\x{9fa5}]+|xn--[a-z0-9]+))'
				.		')'
				.		'|(?:'
				.			'(?:(?P<host2_subdomain>.*)\.)?'
				.			'(?P<host2_maindomian>[a-z0-9_\-]+)'
				.		')'
				.	')$/iu';
			}
			$match = array();
			if (preg_match($r2, $parts['host'], $match)) {
				foreach (array('ip', 'host', 'host2') as $k) {
					if ($match[$k.'_maindomian']) {
						$parts['domain'] = $match[$k.'_maindomian'].($match[$k.'_ext'] ? '.'.$match[$k.'_ext'] : '');
						$parts['subdomain'] = $match[$k.'_subdomian'];
						$parts['domain_main'] = $match[$k.'_maindomian'];
						$parts['domain_ext'] = $match[$k.'_ext'];
					}
				}
			}
		}

		if ($retkey) {
			return $parts[$retkey];
		}

		static $f;
		if (!$f) {
			$f = create_function('$value', 'return ($value !== 0 && empty($value)) ? false : true;');
		}
		$parts = array_filter($parts, $f);

		return $parts;
	}

	/**
	 * @see parse_uri()
	 **/
	function uri_parse($url, $retkey = '') {
		return scotext::parse_uri($url, $retkey);
	}

	function uri_build($parse_uri, $notparse = false) {
		return $notparse ? http_build_query($parse_uri) : ($parse_uri['scheme'] ? $parse_uri['scheme'].'://' : '').
			($parse_uri['userinfo'] ? $parse_uri['userinfo'].'@' : '').
			$parse_uri['host'].
			($parse_uri['port'] ? ':'.$parse_uri['port'] : '').
			$parse_uri['path'].
			($parse_uri['query'] ? '?'.$parse_uri['query'] : '').
			($parse_uri['anchor'] ? '?'.$parse_uri['anchor'] : '');
	}

	function uri_modify() {

	}

	function uri_parse_agv($url, $decode = false) {
		/*
		$ret = $urlmatch = array();
		$refererhost = parse_url($url);

		preg_match_all('/(\w+)=([^&]*)/i', $refererhost['query'], $urlmatch);

		foreach ($urlmatch[1] as $key => $value) {
			$ret[$value] = $urlmatch[2][$key];
		}
		*/
		$ret = array();
		parse_str(str_replace('&amp;', '&', $decode ? urldecode($url) : $url), $ret);

		return (array)$ret;
	}

	/*
	 * 全形字轉半形字用
	 */
	function str_f2h($str, $h2f = 0){
		/*
		//全形英數字及符號
		$f=array("　","０","１","２","３","４","５","６","７","８","９","Ａ","Ｂ","Ｃ","Ｄ","Ｅ","Ｆ","Ｇ","Ｈ","Ｉ","Ｊ","Ｋ","Ｌ","Ｍ","Ｎ","Ｏ","Ｐ","Ｑ","Ｒ","Ｓ","Ｔ","Ｕ","Ｖ","Ｗ","Ｘ","Ｙ","Ｚ","ａ","ｂ","ｃ","ｄ","ｅ","ｆ","ｇ","ｈ","ｉ","ｊ","ｋ","ｌ","ｍ","ｎ","ｏ","ｐ","ｑ","ｒ","ｓ","ｔ","ｕ","ｖ","ｗ","ｘ","ｙ","ｚ","～","！","＠","＃","＄","％","^","＆","＊"," （","）","＿","＋","｜","‘","－","＝","＼","｛","｝","〔","〕","：","”","；","’"," ＜","＞","？","，","．","／");
		//半形英數字及符號
		$h=array(" ","0","1","2","3","4","5","6","7","8","9","A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z","a","b","c","d","e","f","g","h","i","j","k","l","m","n","o","p","q","r","s","t","u","v","w","x","y","z","~","!","@","#","\$","%","^","&","*"," (",")","_","+","|","`","-","=","\\\\","{","}","[","]",":"," \"",";","'","<",">","?",",",".","/");//注意"\\\\"為四條
		*/

		//全形英數字及符號
		$f = array ('　', '０', '１', '２', '３', '４', '５', '６', '７', '８', '９', 'Ａ', 'Ｂ', 'Ｃ', 'Ｄ', 'Ｅ', 'Ｆ', 'Ｇ', 'Ｈ', 'Ｉ', 'Ｊ', 'Ｋ', 'Ｌ', 'Ｍ', 'Ｎ', 'Ｏ', 'Ｐ', 'Ｑ', 'Ｒ', 'Ｓ', 'Ｔ', 'Ｕ', 'Ｖ', 'Ｗ', 'Ｘ', 'Ｙ', 'Ｚ', 'ａ', 'ｂ', 'ｃ', 'ｄ', 'ｅ', 'ｆ', 'ｇ', 'ｈ', 'ｉ', 'ｊ', 'ｋ', 'ｌ', 'ｍ', 'ｎ', 'ｏ', 'ｐ', 'ｑ', 'ｒ', 'ｓ', 'ｔ', 'ｕ', 'ｖ', 'ｗ', 'ｘ', 'ｙ', 'ｚ', '～', '！', '＠', '＃', '＄', '％', '^', '＆', '＊', ' （', '）', '＿', '＋', '｜', '‘', '－', '＝', '＼', '｛', '｝', '〔', '〕', '：', '”', '；', '’', ' ＜', '＞', '？', '，', '．', '／', '︿',);
		//半形英數字及符號
		$h = array (' ', '0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z', '~', '!', '@', '#', '$', '%', '^', '&', '*', ' (', ')', '_', '+', '|', '`', '-', '=', '\\', '{', '}', '[', ']', ':', '"', ';', '\'', '<', '>', '?', ',', '.', '/', '^',);

		return $h2f ? str_replace($h, $f, $str) : str_replace($f, $h, $str);
	}

	function is_codepage($str, $code = '') {
		$codepage = array(
			'BIG5' => '[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|[\xa1-\xfe])'
			, 'UTF-8' => '[\x00-\x7f]|[\xc0-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xf7][\x80-\xbf]{3}'
			, 'GBK' => '[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]'
			, 'UTF-16' => '[\x00-\xd7][\xe0-\xff]|[\xd8-\xdf][\x00-\xff]{2}'
			, 'JIS' => '[\x20-\x7e]|[\x21-\x5f]|[\x21-\x7e]{2}'
			, 'SJIS' => '[\x20-\x7e]|[\xa1-\xdf]|([\x81-\x9f]|[\xe0-\xef])([\x40-\x7e]|[\x80-\xfc])'
			, 'EUC_JP' => '[\x20-\x7e]|\x81[\xa1-\xdf]|[\xa1-\xfe][\xa1-\xfe]|\x8f[\xa1-\xfe]{2}'
			// EUC_JP標點符號及特殊字符
			, 'EUC_JP-symbol' => '[\xa1-\xa2][\xa0-\xfe]'
//			EUC_JP全角數字
			, 'EUC_JP-num' => '\xa3[\xb0-\xb9]'
//			EUC_JP全角大寫英文
			, 'EUC_JP-en_u' => '\xa3[\xc1-\xda]'
//			EUC_JP全角小寫英文
			, 'EUC_JP-en_l' => '\xa3[\xe1-\xfa]'
//			EUC_JP全角平假名
			, 'EUC_JP-s1' => '\xa4[\xa1-\xf3]'
//			EUC_JP全角片假名
			, 'EUC_JP-s2' => '\xa3[\xb0-\xb9]|\xa3[\xc1-\xda]|\xa5[\xa1-\xf6][\xa3][\xb0-\xfa]|[\xa1][\xbc-\xbe]|[\xa1][\xdd]'
//			EUC_JP全角漢字
			, 'EUC_JP-s3' => '[\xb0-\xcf][\xa0-\xd3]|[\xd0-\xf4][\xa0-\xfe]|[\xB0-\xF3][\xA1-\xFE]|[\xF4][\xA1-\xA6]|[\xA4][\xA1-\xF3]|[\xA5][\xA1-\xF6]|[\xA1][\xBC-\xBE]'
//			GB2312漢字
			, 'GB2312-s1' => '[\xb0-\xf7][\xa0-\xfe]'

//			, '' => ''
//			, '' => ''
//			, '' => ''
//			, '' => ''
//			, '' => ''
		);
		$ret = '';

		if ($code) {
			$code = strtoupper($code);
			$ret = ($codepage[$code] && preg_match( '/^(' . $codepage[$code] . ')+/', $str)) ? $code : '';
		} else {
			foreach ($codepage as $c => $v) {
				if ($ret) {
					if (!in_array($ret, array('UTF-8', 'UTF-16'))) {
						$ret = split('-', $ret);
						$ret = $ret[0];
					}
					break;
				}
				$ret = preg_match( '/^(' . $codepage[$c] . ')+/', $str ) ? $c : '';
			}
		}

		return $ret;
	}

	function str2hex($s) {
		$r = '';
		$hexes = array ('0','1','2','3','4','5','6','7','8','9','a','b','c','d','e','f');
		for ($i=0; $i < strlen($s); $i++)
			$r .= ($hexes [(ord($s{$i}) >> 4)] . $hexes [(ord($s{$i}) & 0xf)]);
		return $r;
	}

	function hex2str($s) {
		$r = '';
		for ( $i = 0; $i < strlen($s); $i += 2) {
			$x1 = ord($s{$i});
			$x1 = ($x1>=48 && $x1<58) ? $x1-48 : $x1-97+10;
			$x2 = ord($s{$i+1});
			$x2 = ($x2>=48 && $x2<58) ? $x2-48 : $x2-97+10;
			$r .= chr((($x1 << 4) & 0xf0) | ($x2 & 0x0f));
		}
		return $r;
	}

	/**
	 * Make a string lowercase
	 */
	function lc($text) {
		return scotext::strtolower($text);
	}

	/**
	 * Make a string lowercase
	 */
	function strtolower($text) {
		return strtolower($text);
	}

	/**
	 * Make a string uppercase
	 */
	function uc($text) {
		return scotext::strtoupper($text);
	}

	/**
	 * Make a string uppercase
	 */
	function strtoupper($text) {
		return strtoupper($text);
	}

	/**
	 * Get string length
	 */
	function strlen($text) {
		return strlen($text);
	}

	/**
	 * if $var is array, Count all elements in an array, or properties in an object
	 * if $var is string, Returns the length of the given string
	 *
	 * @param string|array $var
	 * @return int
	 */
	function length($var) {
		if (is_array($var)) {
			return scoarray::count($var);
		} else {
			return scotext::strlen($var);
		}
	}

	/**
	 * Returns a string with the first character of str capitalized, if that character is alphabetic.
	 *
	 * @param string $str
	 */
	function ucfirst($str) {
		return ucfirst($str);
	}

	/**
	 * Returns a string with the first character of str , lowercased if that character is alphabetic.
	 *
	 * @param string $str
	 */
	function lcfirst($str) {
		return $str;
	}

	/**
	 * Returns a string with the first character of each word in str capitalized, if that character is alphabetic.
	 * The definition of a word is any string of characters that is immediately after a whitespace
	 * (These are: space, form-feed, newline, carriage return, horizontal tab, and vertical tab).
	 *
	 * @param string $str
	 */
	function ucwords($str) {
		return ucwords($str);
	}

}

?>