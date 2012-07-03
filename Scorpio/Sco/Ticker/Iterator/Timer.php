<?php

/**
 * @author bluelovers
 * @copyright 2012
 */

class Sco_Ticker_Iterator_Timer extends Sco_Ticker_Iterator
{

	protected $_offsetClass = 'Sco_Ticker_Timer';

	public function timeElapsed($start, $end)
	{
		return $this[$end]->getTicker() - $this[$start]->getTicker();
	}

}
