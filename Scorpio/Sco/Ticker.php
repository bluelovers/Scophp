<?php

/**
 * @author bluelovers
 * @copyright 2012
 */

class Sco_Ticker implements Sco_Ticker_Interface
{

	const VALUE_DEF = 0;
	const RANGE_MIN = -1000;
	const RANGE_MAX = 1000;

	/**
	 * @return string
	 */
	protected $_name;

	/**
	 * @return integer
	 */
	protected $_value;

	protected $_range = array(
		self::RANGE_MIN,
		self::RANGE_MAX,
		);

	/**
	 * @return Sco_Timer_Counter
	 */
	public function __construct($initial_value = self::VALUE_DEF, $name = null)
	{
		$this->setName($name);
		$this->setTicker($initial_value);

		return $this;
	}

	public function __toString()
	{
		return (string )$this->currentTicker();
	}

	public function currentTicker()
	{
		return $this->getTicker();
	}

	/**
	 * @return Sco_Timer_Counter
	 */
	public function setName($name)
	{
		$this->_name = $name;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getName()
	{
		return $this->_name;
	}

	public function setTicker($offset)
	{
		$this->_value = $offset;

		$this->reflashTicker();

		return $this;
	}

	/**
	 * @return integer
	 */
	public function getTicker()
	{
		return $this->_value;
	}

	public function resetTicker()
	{
		$this->_value = self::VALUE_DEF;

		return $this;
	}

	/**
	 * @return integer
	 *
	 * @assert (3) == 3
	 */
	public function addTicker($offset)
	{
		$this->_value += $offset;

		$this->reflashTicker();

		return $this->_value;
	}

	/**
	 * @return integer
	 *
	 * @assert (3) == -3
	 */
	public function subTicker($offset)
	{
		$this->_value -= $offset;

		$this->reflashTicker();

		return $this->_value;
	}

	/*
	public function _test(&$i)
	{
	$i+= 5;
	}
	*/

	public function reflashTicker()
	{
		$this->_fixRange(&$this->_value);
	}

	protected function _fixRange($value)
	{
		if (isset($this->_range[1]) && $value >= $this->_range[1])
		{
			$value = $this->_range[1];
		}
		elseif (isset($this->_range[0]) && $value <= $this->_range[0])
		{
			$value = $this->_range[0];
		}

		return $value;
	}

}
