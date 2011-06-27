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
	protected static $_hook = 'Scorpio_Hook';
	protected static $_evens = array();

	protected $attr = array();
	public $data = array();
	protected $args = array();

	public static function &instance($event, $hook = null) {
		if (isset(self::$_evens[$event])) {
			return self::$_evens[$event];
		} else {
			$event = new Scorpio_Event($event, $hook);
			return $event;
		}
	}

	public function __construct($event, $hook = null) {
		$this->attr['event.name'] = $event;

		if ($hook && class_exists($hook)) $this->attr['hook'] = $hook;

		self::$_evens[$event] = &$this;

		return $this;
	}

	function hook_call($method, $args = array()) {
		static $_hook;

		if ($_hook == null) {
			$_hook = $this->attr['hook'] ? $this->attr['hook'] : self::$_hook;
		}

		return call_user_func_array(array($_hook, $method), is_array($args) ? $args : array($args));
	}

	/**
	 * 呼叫點
	 **/
	function run($args = array(), $data = array()) {
		if ($this->attr['event.stop']) return false;

		if (!$this->hook_call('exists', $this->attr['event.name'])) {
			return false;
		}

		$this->data = array(
			'event.name' => $this->attr['event.name'],
			'event.args' => $args,
			'event.data' => &$data,
		);
		$this->args = $args;

		$ret = $this->hook_call('execute', array(
			$this->attr['event.name'],
			$this->args
		));

		$this->data = array();
		$this->args = array();

		return $ret;
	}

	/**
	 * 暫停呼叫事件
	 **/
	function stop() {
		if ($this->attr['event.stop']) return ;

		$this->attr['event.stop'] = true;
	}

	/**
	 * 繼續執行事件
	 **/
	function play() {
		if (!$this->attr['event.stop']) return ;

		$this->attr['event.stop'] = false;
	}
}

?>