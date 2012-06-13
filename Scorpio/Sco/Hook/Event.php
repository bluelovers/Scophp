<?php

/**
 * @author bluelovers
 * @copyright 2012
 */

class Sco_Hook_Event extends Sco_Array
{

	protected static $namespace = NS_DEFAULT;

	protected static $_instance;

	/**
	 * Namespace - which namespace this instance of zend-session is saving-to/getting-from
	 *
	 * @var string
	 */
	protected $_namespace = NS_DEFAULT;

	public function __construct($namespace = null)
	{
		if ($namespace === null)
		{
			$namespace = self::$namespace;
		}

		if (isset(self::$_instance[$namespace]))
		{
			throw new RuntimeException(sprintf('Fatal error: Cannot redeclare Event with Namespace \'%s\' previously declared', $namespace));
		}
		elseif (!self::chkNamespace($namespace))
		{
			throw new InvalidArgumentException(sprintf('Fatal error: Invaild Event Namespace \'%s\'', $namespace));
		}

		self::$_instance[$namespace] = &$this;

		parent::__construct(array(), array('prop' => false));

		return $this;
	}

	public static function getInstance($namespace = null)
	{
		if ($namespace === null)
		{
			$namespace = self::$namespace;
		}

		if (isset(self::$_instance[$namespace]))
		{
			return self::$_instance[$namespace];
		}

		return new self();
	}

	public function offsetSet($k, $hook_name)
	{
		$hook = null;

		if (is_string($hook_name))
		{
			$k = $hook_name;
			$hook = new Sco_Hook($hook_name);
		}
		else
		{
			$hook = $hook_name;
		}

		if (!self::chkName($k) || !$hook instanceof Sco_Hook)
		{
			throw new InvalidArgumentException(sprintf('index key %s invaild or class %s not instanceof Sco_Hook', $k, get_class($hook)));
		}

		return parent::offsetSet($k, $hook);
	}

	public function offsetGet($k)
	{
		if (!isset($this[$k]))
		{
			if (self::chkName($k))
			{
				$this->offsetSet($k, $k);
			}
			else
			{
				throw new InvalidArgumentException(sprintf('index key %s invaild or class %s not instanceof Sco_Hook', $k, get_class($hook)));
			}
		}

		return parent::offsetGet($k);
	}

	public static function chkNamespace($namespace)
	{
		return !empty($namespace);
	}

	public static function chkName($hook_name)
	{
		return !empty($hook_name);
	}

}
