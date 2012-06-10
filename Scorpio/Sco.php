<?php

/**
 * @author bluelovers
 * @copyright 2012
 */

final class Sco
{

	/**
	 * @var Sco
	 */
	protected static $_instance;
	/**
	 * @var bool
	 */
	private static $_instanced;

	/**
	 * destruct callback
	 *
	 * @var Sco_Spl_Callback_Iterator
	 */
	protected $destruct_handler;

	/**
	 * @var Sco_Registry
	 */
	protected $registry;

	public function __construct()
	{
		if (self::$_instanced || isset(self::$_instance))
		{
			self::__construct_error();
			exit();
		}

		self::$_instance = &$this;
		self::$_instanced = true;

		$this->destruct_handler = new Sco_Spl_Callback_Iterator(array());

		$this->registry = new Sco_Registry();

		//Zend_Registry::setInstance($this->registry);
	}

	/**
	 * exec registed destruct callback
	 */
	public static function destruct()
    {
    	//echo __METHOD__;

		self::$_instance->destruct_handler->exec();
		self::$_instance->destruct_handler->disable(true);
    }

	public static function instance()
    {
        if (!self::$_instanced) {
            new self();
        }

        return true;
    }

	public function __destruct()
	{
		if (self::$_instanced && isset(self::$_instance))
		{
			self::destruct();
		}

		self::$_instance = null;
	}

	public function __clone()
	{
		self::__construct_error();
		exit();
	}

	private static function __construct_error()
	{
		self::$_instance = null;
		exit(sprintf('Fatal error: Cannot redeclare %s() previously declared', __CLASS__));
	}

}
