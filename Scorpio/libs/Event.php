<?

/**
 * This Class for call Hook
 *
 * @author bluelovers
 * @copyright 2010
 */

if (0) {
	// for IDE
	class Scorpio_Event extends Scorpio_Event_Core_ {}
}

class Scorpio_Event_Core_ {
	static $stop = false;

	/**
	 * 呼叫點
	 **/
	function run() {
		if (self::$stop) return false;
	}

	/**
	 * 暫停呼叫事件
	 **/
	function stop() {
		if (self::$stop) return ;

		self::$stop = true;
	}

	/**
	 * 繼續執行事件
	 **/
	function play() {
		if (!self::$stop) return ;

		self::$stop = false;
	}
}

?>