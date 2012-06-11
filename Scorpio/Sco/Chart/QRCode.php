<?php

/**
 * @author bluelovers
 * @copyright 2012
 */

/**
 * @link http://ar2rsawseen.users.phpclasses.org/package/6399-PHP-Generate-QR-Code-images-using-Google-Chart-API.html
 */
class Sco_Chart_QRCode
{

	const FORMAT_BOOKMARK = 'MEBKM:TITLE:%s;URL:%s;;';
	const FORMAT_CONTACT_INFO = 'MECARD:N:%s;ADR:%s;TEL:%s;EMAIL:%s;;';
	const FORMAT_GEO = 'GEO:%s,%s,%s';
	const FORMAT_MAILTO = 'MATMSG:TO:%s;SUB:%s;BODY:%s;;';
	const FORMAT_SMSTO = 'SMSTO:%s:%s';
	const FORMAT_TEL = 'TEL:%s';
	const FORMAT_TEXT = '%s';
	const FORMAT_URL = '%s';
	const FORMAT_WIFI = 'WIFI:T:%s;S:%s;P:%s;;';

	/**
	 * 7%
	 */
	const EC_L = 'L';
	/**
	 * 15%
	 */
	const EC_M = 'M';
	/**
	 * 25%
	 */
	const EC_Q = 'Q';
	/**
	 * 30%
	 */
	const EC_H = 'H';

	const SIZE_DEF = 150;

	/**
	 * @var Sco_Chart_QRCode_Adapter_Abstract
	 */
	protected static $_adapter_class = 'Sco_Chart_QRCode_Adapter_Google';
	protected static $_adapter_size = self::SIZE_DEF;
	protected static $_adapter_ec = self::EC_L;
	protected static $_adapter_options = array(
		'size' => self::SIZE_DEF,
		'ec' => self::EC_L,
		);

	public static $charset = 'UTF-8';

	/**
	 * @var Sco_Chart_QRCode_Adapter_Abstract
	 */
	protected $_adapter;

	/**
	 * @var string
	 */
	protected $_content = null;

	/**
	 * @var array
	 */
	protected $_options = array();

	/**
	 * @param Sco_Chart_QRCode_Adapter_Abstract $adapter_class
	 *
	 * @return self
	 */
	public function __construct($options = array(), $adapter_class = null)
	{
		$this->setOptions(array_merge(self::$_adapter_options, (array )$options), true);
		$this->setAdapter($adapter_class);

		return $this;
	}

	/**
	 * @param Sco_Chart_QRCode_Adapter_Abstract $adapter_class
	 *
	 * @return string
	 */
	public static function setAdapterDefaultClass($adapter_class)
	{
		$old = self::$_adapter_class;
		self::$_adapter_class = (string )$adapter_class;
		return $old;
	}

	public static function getAdapterDefaultClass()
	{
		return self::$_adapter_class;
	}

	public static function setAdapterDefaultSize($adapter_size)
	{
		$old = self::$_adapter_size;
		self::$_adapter_size = (int)$adapter_size;
		return $old;
	}

	public static function getAdapterDefaultSize()
	{
		return self::$_adapter_size;
	}

	public static function setAdapterDefaultEc($adapter_ec)
	{
		$old = self::$_adapter_ec;
		self::$_adapter_ec = (int)$adapter_ec;
		return $old;
	}

	public static function getAdapterDefaultEc()
	{
		return self::$_adapter_ec;
	}

	public static function setAdapterDefaultOptions($adapter_options)
	{
		$old = self::$_adapter_options;
		self::$_adapter_options = $adapter_options;
		return $old;
	}

	public static function getAdapterDefaultOptions()
	{
		return (array )self::$_adapter_options;
	}

	/**
	 * @param Sco_Chart_QRCode_Adapter_Abstract $adapter_class
	 *
	 * @return Sco_Chart_QRCode_Adapter_Abstract
	 */
	public function setAdapter($adapter_class)
	{
		if ($adapter_class === null)
		{
			$adapter_class = self::$_adapter_class;
		}

		$old = $this->_adapter;
		$adapter = null;

		if (!is_object($adapter_class))
		{
			$adapter = new $adapter_class;
		}
		else
		{
			$adapter = $adapter_class;
		}

		if (empty($adapter) || !$adapter instanceof Sco_Chart_QRCode_Adapter_Abstract)
		{
			throw new InvalidArgumentException('\'%s\' must instanceof %s', get_class($adapter), 'Sco_Chart_QRCode_Adapter_Abstract');
		}

		$this->_adapter = $adapter;

		return $old;
	}

	/**
	 * @return Sco_Chart_QRCode_Adapter_Abstract $adapter_class
	 */
	public function getAdapter()
	{
		return $this->_adapter;
	}

	/**
	 * setContent()
	 *
	 * @param string $content
	 * @return self
	 */
	public function setContent($content)
	{
		$this->_adapter->setContent($content);

		return $this;
	}

	/**
	 * getContent()
	 *
	 * @return string
	 */
	public function getContent()
	{
		return $this->_adapter->getContent();
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

	public function setOptions($options, $sync = false)
	{
		foreach ($options as $k => $v)
		{
			$this->_options[$k] = $v;
		}

		if ($sync)
		{
			$this->_adapter->setOptions($this->_options);
		}

		return $this;
	}

	public function getOptions()
	{
		return (array )$this->_options;
	}

	/**
	 * @return Sco_Chart_QRCode_Adapter_Abstract
	 */
	public function generate()
	{
		return $this->_adapter->setOptions($this->_options)->generate();
	}

	/**
	 * creating code with link mtadata
	 *
	 * @return self
	 */
	public function url($url)
	{
		if (preg_match('/^https?:\/\//', $url))
		{
			$this->setContent(sprintf(self::FORMAT_URL, $url));
		}
		else
		{
			$this->setContent(sprintf(self::FORMAT_URL, "http://" . $url));
		}

		return $this;
	}

	/**
	 * creating code with bookmark metadata
	 *
	 * @return self
	 */
	public function bookmark($title, $url)
	{
		$this->setContent(sprintf(self::FORMAT_BOOKMARK, $title, $url));

		return $this;
	}

	/**
	 * creating text qr code
	 *
	 * @return self
	 */
	public function text($text)
	{
		$this->setContent($text);

		return $this;
	}

	/**
	 * creatng code with sms metadata
	 *
	 * @return self
	 */
	public function smsto($phone, $text)
	{
		$this->setContent(sprintf(self::FORMAT_SMSTO, $phone, $text));

		return $this;
	}

	/**
	 * creating code with phone
	 *
	 * @return self
	 */
	public function tel($phone)
	{
		$this->setContent(sprintf(self::FORMAT_TEL, $phone));

		return $this;
	}

	/**
	 * creating code with mecard metadata
	 *
	 * @return self
	 */
	public function contact_info($name, $address, $phone, $email)
	{
		$this->setContent(sprintf(self::FORMAT_CONTACT_INFO, $name, $address, $phone, $email));

		return $this;
	}

	/**
	 * creating code wth email metadata
	 *
	 * @return self
	 */
	public function mailto($email, $subject, $message)
	{
		$this->setContent(sprintf(self::FORMAT_MAILTO, $email, $subject, $message));

		return $this;
	}

	/**
	 * creating code with geo location metadata
	 *
	 * @return self
	 */
	public function geo($lat, $lon, $height)
	{
		$this->setContent(sprintf(self::FORMAT_GEO, $lat, $lon, $height));

		return $this;
	}

	/**
	 * creating code with wifi configuration metadata
	 *
	 * @return self
	 */
	public function wifi($type, $ssid, $pass)
	{
		$this->setContent(sprintf(self::FORMAT_WIFI, $type, $ssid, $pass));

		return $this;
	}

	/**
	 * creating code with i-appli activating meta data
	 *
	 * @return self
	 */
	public function iappli($adf, $cmd, $param)
	{
		$param_str = '';
		foreach ($param as $val)
		{
			$param_str .= "PARAM:" . $val["name"] . "," . $val["value"] . ";";
		}
		$this->setContent("LAPL:ADFURL:" . $adf . ";CMD:" . $cmd . ";" . $param_str . ";");

		return $this;
	}

}
