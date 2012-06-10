<?php

/**
 * @author bluelovers
 * @copyright 2012
 */

class Sco_PHP
{

	/**
	 * @return Sco_Spl_Callback_Iterator
	 */
	protected static $_shutdown_handler;

	/**
	 * @return bool
	 */
	protected static $_shutdown_registed;

	public static function void()
	{
		return null;
	}

	public static function errno_const($errno, $chk = false)
	{

		switch ($errno)
		{
			case E_ERROR:
				// 1
				$typestr = 'E_ERROR';
				break;
			case E_WARNING:
				// 2
				$typestr = 'E_WARNING';
				break;
			case E_PARSE:
				// 4
				$typestr = 'E_PARSE';
				break;
			case E_NOTICE:
				// 8
				$typestr = 'E_NOTICE';
				break;
			case E_CORE_ERROR:
				// 16
				$typestr = 'E_CORE_ERROR';
				break;
			case E_CORE_WARNING:
				// 32
				$typestr = 'E_CORE_WARNING';
				break;
			case E_COMPILE_ERROR:
				// 64
				$typestr = 'E_COMPILE_ERROR';
				break;
			case E_CORE_WARNING:
				// 128
				$typestr = 'E_COMPILE_WARNING';
				break;
			case E_USER_ERROR:
				// 256
				$typestr = 'E_USER_ERROR';
				break;
			case E_USER_WARNING:
				// 512
				$typestr = 'E_USER_WARNING';
				break;
			case E_USER_NOTICE:
				// 1024
				$typestr = 'E_USER_NOTICE';
				break;
			case E_STRICT:
				// 2048
				$typestr = 'E_STRICT';
				break;
			case E_RECOVERABLE_ERROR:
				// 4096
				$typestr = 'E_RECOVERABLE_ERROR';
				break;
			case E_DEPRECATED:
				// 8192
				$typestr = 'E_DEPRECATED';
				break;

			case E_USER_DEPRECATED:
				// 16384
				$typestr = 'E_USER_DEPRECATED';
				break;
			default:
				$fail = true;
				$typestr = 'E_UNKNOW['.$errno.']';
				break;
		}

		return $chk ? array($fail, $typestr, $errno) : $typestr;
	}

	/**
	 * @return Sco_Spl_Callback_Iterator
	 */
	public static function shutdown_handler()
	{
		if (!isset(self::$_shutdown_handler))
		{
			self::$_shutdown_handler = new Sco_Spl_Callback_Iterator(array());
		}

		return self::$_shutdown_handler;
	}

	public static function shutdown_disable($disable = true)
	{
		if (!isset(self::$_shutdown_handler))
		{
			self::shutdown_handler();
		}

		return self::$_shutdown_handler->disable($disable);
	}

	/**
	 * Registers a callback to be executed after script execution finishes or exit() is called.
	 */
	public static function shutdown_register($shutdown_handler)
	{
		if ($shutdown_handler === true)
		{
			$callback = array('Sco_PHP_Handler_Error', 'fatal_error_handler');
		}
		else
		{
			$callback = $shutdown_handler;
		}

		$handler = self::shutdown_handler()->append($callback);

		if (!self::$_shutdown_registed)
		{
			register_shutdown_function($handler->callback());

			self::$_shutdown_registed = true;
		}

		return true;
	}

}
