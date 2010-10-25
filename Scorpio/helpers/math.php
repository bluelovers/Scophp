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

if (0 || 1) {
	// for IDE
	class scomath extends Scorpio_helper_math_Core {
	}
}

class Scorpio_helper_math_Core {
	protected static $instances = null;

	public static $rand = 1000000;

	public static function &instance($overwrite = false) {
		if (!self::$instances) {
			$ref = new ReflectionClass(($overwrite && !in_array($overwrite, array(true, 1), true)) ?
				$overwrite : 'scomath');
			self::$instances = $ref->newInstance();
		} elseif ($overwrite) {
			$ref = new ReflectionClass(!in_array($overwrite, array(true, 1), true) ? $overwrite :
				get_class(self::$instances));
			self::$instances = $ref->newInstance();
		}

		return self::$instances;
	}

	function __construct() {

		// make sure self::$instances is newer
		if (!self::$instances || !in_array(get_class($this), class_parents(self::$instances))) {
			self::$instances = $this;
		}

		scomath::_srand();

		return self::$instances;
	}

	protected static function _srand() {
		$scale = 15;
		//bcscale(9);

		scomath::$rand = bcadd((float)microtime(true) - time(), (float)scomath::$rand * mt_rand(-100, 200) / 100, $scale);

		return scomath::$rand;
	}

	protected static function _rand(array &$r, $retval) {
		shuffle($r['a']);

		$r['n3'] = $r['a'][$r['n1']];
		$r['n4'] = $r['a'][$r['n2']];

		if ($retval)
			return $r['c'] ? $r['n3'] : $r['n4'];

		if ($r['n4'] == $r['n3'] || $ra == $r['n3']) {
			$r['r'] = 2;
		} else {
			$r['n4'] = $ra ? $ra : $r['a'][$r['n2']];

			$r['r'] = $r['c'] ? (($r['n4'] >= $r['n3']) ? 1 : 0) : (($r['n4'] <= $r['n3']) ?
				1 : 0);
		}
	}

	function rand($ra = 0, $rb = 0, $low = 1, $high = 100, $step = 1, $retval = true) {
		srand((float)microtime(true) * rand(-100, 200)/100 * scomath::_srand());

		$r = array();

		$r['a'] = range($low, $high, ($step ? $step : 1));
		$r['n1'] = rand($low - 1, $high);
		$r['n2'] = rand($low - 1, $high);
		$r['c'] = rand(0, 1 + $rb);

		scomath::_rand($r['a'], $retval);

		return $r['r'];
	}

	function mt_rand($ra = 0, $rb = 0, $low = 1, $high = 100, $step = 1, $retval = true) {
		mt_srand((float)microtime(true) * mt_rand(-100, 200)/100 * scomath::_srand() + ((float)microtime(true)-time()));

		$r = array();

		$r['a'] = range($low, $high, ($step ? $step : 1));
		$r['n1'] = mt_rand($low - 1, $high);
		$r['n2'] = mt_rand($low - 1, $high);
		$r['c'] = mt_rand(0, 1 + $rb);

		scomath::_rand($r['a'], $retval);

		return $r['r'];
	}
}

?>