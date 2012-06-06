<?php

/**
 * @author bluelovers
 * @copyright 2012
 */

class Sco_Spl_Callback
{
	var $argv;
	var $func;

	function __construct($callback, $argv = null)
	{
		$argv = func_get_args();
		$this->func(array_shift($argv));

		$this->argv = (array)$argv;

		return $this;
	}

	/**
	 * array($this, 'compare')
	 *
	 * @return array
	 */
	function callback()
	{
		return array($this, 'exec');
	}

	function func($func = null)
	{
		if ($func !== null)
		{
			$this->func = $func;
		}

		return $this;
	}

	function exec()
	{
		$argv = func_num_args() > 0 ? func_get_args() : (array)$this->argv;

		return call_user_func_array($this->func, $argv);
	}

	function exec_array($argv = null)
	{
		$argv = $argv !== null ? $argv : (array)$this->argv;

		return call_user_func_array($this->func, $argv);
	}

	function create_function($func_name)
	{
		return Sco_Spl_Helper::create_function($func_name, $this->callback());
	}

}
