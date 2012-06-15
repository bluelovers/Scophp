<?php

/**
 * @author bluelovers
 * @copyright 2012
 */

class Sco_Spl_Callback_Eval extends Sco_Spl_Callback
{

	public function exec()
	{
		$this->_tmp['func_get_args'] = func_get_args();
		$this->_tmp['args'] = array_combine((array)$this->argv, $this->_tmp['func_get_args']);

		extract($this->_tmp['args'], EXTR_REFS | EXTR_OVERWRITE);

		unset($this->_tmp['func_get_args'], $this->_tmp['args']);

		return eval($this->func);
	}

	public function exec_array($argv = array())
	{
		return call_user_func_array(array($this, 'exec'), $argv);
	}

}
