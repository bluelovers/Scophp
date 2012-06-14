<?php

/**
 * @author bluelovers
 * @copyright 2012
 */

class Sco_CodeGenerator_Php_Function extends Zend_CodeGenerator_Php_Abstract
{

	/**
	 * @var Zend_CodeGenerator_Php_Docblock
	 */
	protected $_docblock = null;

	/**
	 * @var bool
	 */
	protected $_isFinal = false;

	/**
	 * @var array
	 */
	protected $_parameters = array();

	/**
	 * @var string
	 */
	protected $_body = null;

	/**
	 * @var string
	 */
	protected $_name = null;

	public function __construct($options = array())
	{
		$this->_parameters = new Sco_CodeGenerator_Php_Parameters();

		parent::__construct($options);
	}

	/**
	 * setDocblock() Set the docblock
	 *
	 * @param Zend_CodeGenerator_Php_Docblock|array|string $docblock
	 * @return Zend_CodeGenerator_Php_File
	 */
	public function setDocblock($docblock)
	{
		if (is_string($docblock))
		{
			$docblock = array('shortDescription' => $docblock);
		}

		if (is_array($docblock))
		{
			$docblock = new Zend_CodeGenerator_Php_Docblock($docblock);
		}
		elseif (!$docblock instanceof Zend_CodeGenerator_Php_Docblock)
		{
			require_once 'Zend/CodeGenerator/Php/Exception.php';
			throw new Zend_CodeGenerator_Php_Exception('setDocblock() is expecting either a string, array or an instance of Zend_CodeGenerator_Php_Docblock');
		}

		$this->_docblock = $docblock;
		return $this;
	}

	/**
	 * getDocblock()
	 *
	 * @return Zend_CodeGenerator_Php_Docblock
	 */
	public function getDocblock()
	{
		return $this->_docblock;
	}

	/**
	 * setName()
	 *
	 * @param string $name
	 * @return Zend_CodeGenerator_Php_Member_Abstract
	 */
	public function setName($name)
	{
		$this->_name = $name;
		return $this;
	}

	/**
	 * getName()
	 *
	 * @return string
	 */
	public function getName()
	{
		return $this->_name;
	}

	/**
	 * setParameters()
	 *
	 * @param array $parameters
	 * @return Zend_CodeGenerator_Php_Method
	 */
	public function setParameters(Array $parameters)
	{
		$this->_parameters->setParameters($parameters);

		return $this;
	}

	/**
	 * setParameter()
	 *
	 * @param Zend_CodeGenerator_Php_Parameter|array $parameter
	 * @return Zend_CodeGenerator_Php_Method
	 */
	public function setParameter($parameter)
	{
		$this->_parameters->setParameter($parameter);

		return $this;
	}

	/**
	 * getParameters()
	 *
	 * @return array Array of Zend_CodeGenerator_Php_Parameter
	 */
	public function getParameters()
	{
		return $this->_parameters->getParameters();
	}

	/**
	 * setBody()
	 *
	 * @param string $body
	 * @return Zend_CodeGenerator_Php_Method
	 */
	public function setBody($body)
	{
		$this->_body = $body;
		return $this;
	}

	/**
	 * getBody()
	 *
	 * @return string
	 */
	public function getBody()
	{
		return $this->_body;
	}

	public function getParametersString()
	{
		return $this->_parameters->generate();
	}

	/**
	 * generate()
	 *
	 * @return string
	 */
	public function generate()
	{
		$output = '';

		$indent = $this->getIndentation();

		if (($docblock = $this->getDocblock()) !== null)
		{
			$docblock->setIndentation($indent);
			$output .= $docblock->generate();
		}

		$output .= $indent;

		$output .= ' function ' . $this->getName() . '(';

		$output .= $this->getParametersString();

		$output .= ')' . self::LINE_FEED . $indent . '{' . self::LINE_FEED;

		if ($this->_body && $this->isSourceDirty())
		{
			$output .= '        ' . str_replace(self::LINE_FEED, self::LINE_FEED . $indent . $indent, trim($this->_body)) . self::LINE_FEED;
		}
		elseif ($this->_body)
		{
			$output .= $this->_body . self::LINE_FEED;
		}

		$output .= $indent . '}' . self::LINE_FEED;

		return $output;
	}

}
