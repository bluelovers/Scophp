<?php

/**
 * @author bluelovers
 * @copyright 2012
 */

class Sco_Ticker_Timer implements Sco_Ticker_Interface
{

	protected $_timestamp;
	protected $_timestamp_stop;

	public function __construct($timestamp = null)
	{
		$this->resetTicker($timestamp);
	}

	/**
	 * @return string
	 */
	public function __toString()
	{
		return sprintf('%0.8f', $this->currentTicker());
	}

	/**
	 * @param array $args
	 * @return float
	 */
	public function currentTicker($args = array())
	{
		$now = $this->_timestamp_stop !== null ? $this->_timestamp_stop : (($args['now']) ? $args['now'] : microtime(true));

		return $now - $this->_timestamp;
	}

	/**
	 * @param float|string|null $timestamp
	 */
	public function resetTicker($timestamp = null)
	{
		if ($timestamp === null || $timestamp === 'now')
		{
			$this->_timestamp = microtime(true);
		}
		else
		{
			$this->_timestamp = $timestamp;
		}
	}

	/**
	 * @return float
	 */
	public function getTicker()
	{
		return $this->_timestamp;
	}

	public function stopTicker($flag = true)
	{
		if ($flag)
		{
			$this->_timestamp_stop = microtime(true);
		}
		else
		{
			$this->_timestamp_stop = null;
		}

		return $this;
	}

}
