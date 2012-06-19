<?php

/**
 * @author bluelovers
 * @copyright 2012
 */

class Sco_Cookie_Object extends ArrayObject
{

	/**
	 * Class name of the singleton registry object.
	 * @var string
	 */
	private static $_registryClassName = 'Sco_Cookie_Object';

	/**
	 * Registry object provides storage for shared objects.
	 * @var Sco_Cookie_Object
	 */
	private static $_registry = null;

	protected $_config = array(

		'default_expire' => 0,
		'default_path' => '/',
		'default_domain' => '.',
		'default_secure' => false,
		'default_httponly' => false,

		'autosave' => true,

		);

	protected $_cookies;

	const EXPIRE_NOW = -1;

	/**
	 * Retrieves the default registry instance.
	 *
	 * @return Sco_Cookie_Object
	 */
	public static function &getInstance($registry = null)
	{
		if (self::$_registry === null)
		{
			self::init($registry);
		}

		return self::$_registry;
	}

	public static function setInstance(Sco_Cookie_Object $registry)
	{
		if (self::$_registry !== null)
		{
			throw new Exception('Cookie is already initialized');
		}

		self::setClassName(get_class($registry));
		self::$_registry = $registry;
	}

	/**
	 * Initialize the default registry instance.
	 *
	 * @return void
	 */
	protected static function init($registry)
	{
		self::setInstance(new self::$_registryClassName($registry));
	}

	public static function setClassName($registryClassName = 'Sco_Cookie_Object')
    {
        if (self::$_registry !== null) {
            require_once 'Zend/Exception.php';
            throw new Zend_Exception('Cookie is already initialized');
        }

        if (!is_string($registryClassName)) {
            require_once 'Zend/Exception.php';
            throw new Zend_Exception("Argument is not a class name");
        }

        self::$_registryClassName = $registryClassName;
    }

	/**
	 * Unset the default registry instance.
	 * Primarily used in tearDown() in unit tests.
	 * @returns void
	 */
	public static function _unsetInstance()
	{
		self::$_registry = null;
	}

	/**
	 * getter method, basically same as offsetGet().
	 *
	 * This method can be called from an object of type Zend_Registry, or it
	 * can be called statically.  In the latter case, it uses the default
	 * static instance stored in the class.
	 *
	 * @param string $index - get the value associated with $index
	 * @return mixed
	 * @throws Zend_Exception if no entry is registerd for $index.
	 */
	public static function get($index)
	{
		$instance = self::getInstance();
		return $instance->offsetGet($index);
	}

	/**
	 * setter method, basically same as offsetSet().
	 *
	 * This method can be called from an object of type Zend_Registry, or it
	 * can be called statically.  In the latter case, it uses the default
	 * static instance stored in the class.
	 *
	 * @param string $index The location in the ArrayObject in which to store
	 *   the value.
	 * @param mixed $value The object to store in the ArrayObject.
	 * @return void
	 */
	public static function set($name, $value, $expire = null, $path = null, $domain = null, $secure = null, $httponly = null)
	{
		$instance = self::getInstance();
		$instance->offsetSet($name, $value);

		$instance->_cookies[$name] = array(
			'name' => $name,
			'value' => $value,
			'expire' => $expire,
			'path' => $path,
			'domain' => $domain,
			'secure' => $secure,
			'httponly' => $httponly,
			);
	}

	/**
	 * Returns TRUE if the $index is a named value in the registry,
	 * or FALSE if $index was not found in the registry.
	 *
	 * @param  string $index
	 * @return boolean
	 */
	public static function isRegistered($index)
	{
		if (self::$_registry === null)
		{
			return false;
		}
		return self::$_registry->offsetExists($index);
	}

	/**
	 * @param string $index
	 * @returns mixed
	 *
	 * Workaround for http://bugs.php.net/bug.php?id=40442 (ZF-960).
	 */
	public function offsetExists($index)
	{
		return array_key_exists($index, $this);
	}

	public function __construct($registry = array())
	{
		parent::__construct($_COOKIE, Sco_Array::ARRAY_PROP_BOTH);

		foreach ((array)$registry as $k => $v)
		{
			if (method_exists($this, ucfirst($k)))
			{
				$this->$k($v);
			}
		}
	}

	public function setConfig($config)
	{
		$this->_config = array_merge($this->_config, $config);

		return $this;
	}

	public function getConfig($config)
	{
		return $this->_config;
	}

	public function setCookies($cookies)
	{
		$this->exchangeArray($cookies);

		return $this;
	}

	public function getCookies($cookies)
	{
		return $this->getArrayCopy();
	}

	public static function save()
	{
		$instance = self::getInstance();

		$map = array(
			'expire',
			'path',
			'domain',
			'secure',
			'httponly',
			);

		foreach ($instance as $name => $value)
		{
			$_config = array();

			$_config['name'] = $name;
			$_config['value'] = $value;

			foreach ($map as $k)
			{
				if (isset($instance->_cookies[$name][$k]))
				{
					$_config[$k] = $instance->_cookies[$name][$k];
				}
				elseif ($k == 'expire' && $instance->_config['default_' . $k] > 0)
				{
					$_config[$k] = time() + $instance->_config['default_' . $k];
				}
				else
				{
					$_config[$k] = $instance->_config['default_' . $k];
				}
			}

			self::setcookie($_config);
		}
	}

	public static function setcookie($_config)
	{
		if ($_config['value'] === null || $_config['expire'] < 0)
		{
			$_config['value'] = null;
			$_config['expire'] = self::EXPIRE_NOW;
		}
		else
		{
			$_config['value'] = (string )$_config['value'];
		}

		return setcookie($_config['name'], $_config['value'], (int)$_config['expire'], (string )$_config['path'], (string )$_config['domain'], (bool)$_config['secure'], (bool)$_config['httponly']);
	}

	public function __destruct()
	{
		if ($this->_config['autosave'] && $this === self::$_registry)
		{
			self::save();
		}

		var_dump(headers_list());
	}

}
