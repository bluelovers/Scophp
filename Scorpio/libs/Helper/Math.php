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
	class scomath extends Scorpio_Helper_Math_Core {
	}
}

class Scorpio_Helper_Math_Core {
	protected static $instances = null;

	public static $rand = 1000000;

	// 取得構造物件
	public static function &instance($overwrite = false) {
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

		static::_srand();

		return static::$instances;
	}

	protected static function _srand() {
		$scale = 15;
		//bcscale(9);

		static::$rand = bcadd((float)microtime(true) - time(), (float)static::$rand * mt_rand(-100, 200) / 100, $scale);

		return static::$rand;
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
		srand((float)microtime(true) * rand(-100, 200)/100 * static::_srand());

		$r = array();

		$r['a'] = range($low, $high, ($step ? $step : 1));
		$r['n1'] = rand($low - 1, $high);
		$r['n2'] = rand($low - 1, $high);
		$r['c'] = rand(0, 1 + $rb);

		static::_rand($r['a'], $retval);

		return $r['r'];
	}

	function mt_rand($ra = 0, $rb = 0, $low = 1, $high = 100, $step = 1, $retval = true) {
		mt_srand((float)microtime(true) * mt_rand(-100, 200)/100 * static::_srand() + ((float)microtime(true)-time()));

		$r = array();

		$r['a'] = range($low, $high, ($step ? $step : 1));
		$r['n1'] = mt_rand($low - 1, $high);
		$r['n2'] = mt_rand($low - 1, $high);
		$r['c'] = mt_rand(0, 1 + $rb);

		static::_rand($r['a'], $retval);

		return $r['r'];
	}
}

?>