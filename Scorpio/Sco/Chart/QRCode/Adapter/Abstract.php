<?php

/**
 * @author bluelovers
 * @copyright 2012
 */

abstract class Sco_Chart_QRCode_Adapter_Abstract
{

	/**
	 * @var string
	 */
	protected $_content = null;

	/**
	 * @var array
	 */
	protected $_options = array();

	/**
	 * setContent()
	 *
	 * @param string $content
	 * @return self
	 */
	public function setContent($content)
	{
		$this->_content = (string )$content;
		return $this;
	}

	/**
	 * getContent()
	 *
	 * @return string
	 */
	public function getContent()
	{
		return (string )$this->_content;
	}

	public function setSize($size)
	{
		if ($size === null)
		{
			$size = Sco_Chart_QRCode::getAdapterDefaultSize();
		}

		$this->_options['size'] = (int)$size;
		return $this;
	}

	public function getSize($size)
	{
		if ($this->_options['size'] === null)
		{
			$this->_options['size'] = Sco_Chart_QRCode::getAdapterDefaultSize();
		}

		return $this->_options['size'];
	}

	public function setEc($ec)
	{
		if ($ec === null)
		{
			$ec = Sco_Chart_QRCode::getAdapterDefaultEc();
		}

		$this->_options['ec'] = $ec;
		return $this;
	}

	public function getEc($size)
	{
		if ($this->_options['ec'] === null)
		{
			$this->_options['ec'] = Sco_Chart_QRCode::getAdapterDefaultEc();
		}

		return $this->_options['ec'];
	}

	public function setOptions($options)
	{
		foreach ($options as $k => $v)
		{
			$this->_options[$k] = $v;
		}

		return $this;
	}

	public function getOptions()
	{
		return (array )$this->_options;
	}

	/**
	 * generate()
	 */
	public function generate()
	{
		$this->setSize($this->_options['size'])->setEc($this->_options['ec']);

		return $this;
	}

}
