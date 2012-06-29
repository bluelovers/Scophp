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
		$this->setValue($initial_value);

		return $this;
	}

	public function __toString()
	{
		return (string)$this->getValue();
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

	/**
	 * @return Sco_Timer_Counter
	 */
	public function setValue($offset)
	{
		$this->_value = $offset;

		return $this;
	}

	/**
	 * @return integer
	 */
	public function getValue()
	{
		return $this->_value;
	}

	/**
	 * @return Sco_Timer_Counter
	 */
	public function resetValue()
	{
		$this->_value = self::VALUE_DEF;

		return $this;
	}

	/**
	 * @return integer
	 */
	public function addValue($offset)
	{
		$this->_value += $offset;

		return $this->_value;
	}

	/**
	 * @return integer
	 */
	public function subValue($offset)
	{
		$this->_value -= $offset;

		return $this->_value;
	}

	protected function fixRange()
	{
		if (isset($this->_range[1]) && $this->_value >= $this->_range[1])
		{
			$this->_value = $this->_range[1];
		}
		elseif (isset($this->_range[0]) && $this->_value <= $this->_range[0])
		{
			$this->_value = $this->_range[0];
		}
		else
		{
			return false;
		}

		return true;
	}

}
