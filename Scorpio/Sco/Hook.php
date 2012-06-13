<?php

/**
 * @author bluelovers
 * @copyright 2012
 */

class Sco_Hook extends Sco_Spl_Callback_Iterator
{

	const RET_FAILED = null;
	//const RET_FAILED = false;
	const RET_SUCCESS = true;
	const RET_STOP = false;
	const RET_ERROR = false;

	/**
	 * @return bool
	 */
	public static $throw_exception = true;

	protected $hook_name;

	/**
	 * @return Sco_Hook_Event
	 */
	public $hook_event;

	/**
	 * @return Sco_Spl_Callback_Hook
	 */
	var $func;

	public function __construct($hook_name)
	{
		parent::__construct(array(), array('prop' => false));

		$this->setName($hook_name);

		return $this;
	}

	public static function newInstance($hook_name)
	{
		return new self($hook_name);
	}

	public function setName($hook_name)
	{
		$this->hook_name = $hook_name;

		return $this;
	}

	public function getName()
	{
		return $this->hook_name;
	}

	/**
	 * @param Sco_Hook_Event $hook_name
	 */
	public function setEvent($hook_event)
	{
		$this->hook_event = $hook_event;

		return $this;
	}

	/**
	 * @return Sco_Hook_Event
	 */
	public function getEvent()
	{
		return $this->hook_event;
	}

	public static function throwException($flag = null)
	{
		if (null === $flag)
		{
			return self::$throw_exception;
		}

		$old = self::$throw_exception;

		self::$throw_exception = (bool)$flag;

		return $old;
	}

	public function offsetSet($k, $hook_data)
	{
		if (!$hook_data instanceof Sco_Spl_Callback_Hook)
		{
			$hook = new Sco_Spl_Callback_Hook($hook_data);
		}
		else
		{
			$hook = $hook_data;
		}

		parent::offsetSet($k, $hook);
	}

	public function exec_array($argv = null)
	{
		if ($this->disable) return self::RET_STOP;

		if ($argv === null)
		{
			$argv = (array )$this->argv;
		}

		array_unshift($argv, $this);

		foreach ($this as $func)
		{
			$this->result = self::RET_FAILED;
			$this->func = $func;

			$badhookmsg = null;

			try
			{
				$this->result = $this->func->exec_array($argv);
			}
			catch (Exception $e)
			{
				$badhookmsg = $e->getMessage();
			}

			//var_dump($this->func, $this->result, $e);

			/* String return is an error; false return means stop processing. */
			//TODO: add hook ret object
			if (is_string($this->result))
			{
				if (Sco_Hook::$throw_exception)
				{
					throw new Exception($this->result);
				}

				return self::RET_ERROR;
			}
			elseif ($this->result === self::RET_FAILED)
			{
				$prettyFunc = $this->func->callback_name;

				if (Sco_Hook::$throw_exception)
				{
					if ($badhookmsg)
					{
						throw new Exception(sprintf('Detected bug in an extension! Hook %s has invalid call signature; %s', $prettyFunc, $badhookmsg));
					}
					else
					{
						throw new Exception(sprintf('Detected bug in an extension! Hook %s failed to return a value; should return true to continue hook processing or false to abort.', $prettyFunc, $badhookmsg));
					}
				}
			}
			elseif ($this->result === self::RET_STOP)
			{
				return self::RET_STOP;
			}
		}

		return self::RET_SUCCESS;
	}

}
