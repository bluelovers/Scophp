<?php

/**
 * @author bluelovers
 * @copyright 2012
 */

class Sco_Ticker_Iterator extends ArrayObject
{

	protected $_offsetClass = 'Sco_Ticker';

	public function __construct()
	{
		parent::__construct(array(), self::ARRAY_AS_PROPS);
	}

	public function offsetSet($offset, $value)
	{

		if (!is_object($value) && $value !== null)
		{
			return parent::offsetSet($offset, new $this->_offsetClass($value));
		}

		if (!$value instanceof Sco_Ticker_Interface || !$value instanceof $this->_offsetClass)
		{
			throw new InvalidArgumentException();
		}

		return parent::offsetSet($offset, $value);
	}

	public function offsetGet($offset)
	{
		if (!isset($this[$offset]))
		{
			$this->offsetSet($offset, new $this->_offsetClass);
		}

		return parent::offsetGet($offset);
	}

	public function sort()
	{
		$arr2 = $arr = array();

		foreach ($this as $k => &$v)
		{
			$arr[(string )$v][$k] = &$v;
		}

		ksort($arr);

		foreach ($arr as &$list)
		{
			$arr2 += $list;

			/*
			foreach ($list as $k => &$v)
			{
				$arr2[$k] = $v;
			}
			*/
		}

		$this->exchangeArray($arr2);

		return $this;
	}

	public function toArrayValues()
	{
		$array = array();

		foreach ($this as $k => &$v)
		{
			$array[$k] = $v->currentTicker();
		}

		return $array;
	}

}
