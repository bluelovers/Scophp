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
	class scotext extends Scrpio_helper_text_Core {
	}
}

class Scrpio_helper_text_Core {

	protected static $instances = null;

	public static function instance($overwrite = false) {
		if (!self::$instances) {
			$ref = new ReflectionClass(($overwrite && !in_array($overwrite, array(true, 1), true)) ? $overwrite : 'scotext');
			self::$instances = $ref->newInstance();
		} elseif ($overwrite) {
			$ref = new ReflectionClass(!in_array($overwrite, array(true, 1), true) ? $overwrite : get_class(self::$instances));
			self::$instances = $ref->newInstance();
		}

		return self::$instances;
	}

	function __construct() {

		// make sure self::$instances is newer
		if (!self::$instances || !in_array(get_class($this), class_parents(self::$instances))) {
			self::$instances = $this;
		}

		return self::$instances;
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

		if (!empty($format) && is_array($args) && count($args)) {
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

			echo "length: $matchs_len\n";

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

						scoarray::array_remove_keys(&$matchs['fultext'], scoarray::array_search_all($fultext, $matchs['fultext']));

						//$replace = sprintf($search, $_args[$varname]);
						$replace = self::sprintf_hack($search, $_args[$varname]);
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
			var_dump($matchs);
			echo "\n";
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

			echo "hack[1]: $format = $ret\n";

			//if ($format == '%020s') {
			//				//var_dump($parse);
			//				var_dump(array($pad, $parse['pad3'] !== '', ($parse['pad'] && (!empty($parse['pad2']) || $parse['pad3'] !== ''))));
			//				echo "\n";
			//			}


		} else {
			$ret = sprintf($format, $string);
			echo "hack[0]: $format = $ret\n";
		}

		//if ($string == 'ěščřžýáíé') exit("----\n[".$string."]\n[".$ret."]\n------\n");

		return $ret;
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