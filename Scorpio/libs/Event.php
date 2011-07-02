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

		$this->attr['event.index'] = count(self::$_evens[$event]);
		$this->attr['event.counter'] = 0;
		$this->attr['hook.counter'] = array();

		self::$_evens[$event] = &$this;

		return $this;
	}

	function counter_add($index) {
		if ($this->attr['hook.counter'][$index] === null) {
			$this->attr['hook.counter'][$index] = 0;
		}
		$this->attr['hook.counter'][$index]++;
		return $this;
	}

	function hook_call($method, $args = array()) {
		static $_hook;

		if ($_hook == null) {
			$_hook = $this->attr['hook'] ? $this->attr['hook'] : self::$_hook;
		}

		return call_user_func_array(array($_hook, $method), is_array($args) ? $args : array($args));
	}

	function data() {
		$this->data['event.name'] = $this->attr['event.name'];
		$this->data['event.attr'] = $this->attr;

		return $this->data;
	}

	/**
	 * 呼叫點
	 **/
	function run($args = array(), $data = array()) {
		if ($this->attr['event.stop']) return false;

		if (!$this->hook_call('exists', $this->attr['event.name'])) {
			return false;
		}

		$this->attr['event.counter']++;

		$this->data = array(
			'event.name' => $this->attr['event.name'],

			// 紀錄呼叫的 atgs
			'event.args' => $args,

			// 可在 Hook 之間 共享 Event 的資料
			'event.data' => &$data,

			'event.attr' => $this->attr,
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