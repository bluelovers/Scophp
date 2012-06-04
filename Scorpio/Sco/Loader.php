<?php

/**
 * @author bluelovers
 * @copyright 2012
 */

class Sco_Loader extends Zend_Loader
{

	protected static $_suppressNotFoundWarnings = false;

	public function suppressNotFoundWarnings($flag = null)
	{
		if (null === $flag)
		{
			return self::$_suppressNotFoundWarnings;
		}

		$old = self::$_suppressNotFoundWarnings;

		self::$_suppressNotFoundWarnings = (bool)$flag;

		return $old;
	}

	protected static function _loadClass($class, $dirs, $noerror = false)
	{
		// Autodiscover the path from the class name
		// Implementation is PHP namespace-aware, and based on
		// Framework Interop Group reference implementation:
		// http://groups.google.com/group/php-standards/web/psr-0-final-proposal
		$className = ltrim($class, '\\');
		$file = '';
		$namespace = '';
		if ($lastNsPos = strripos($className, '\\'))
		{
			$namespace = substr($className, 0, $lastNsPos);
			$className = substr($className, $lastNsPos + 1);
			$file = str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
		}
		$file .= str_replace('_', DIRECTORY_SEPARATOR, $className) . '.php';

		if (!empty($dirs))
		{
			// use the autodiscovered path
			$dirPath = dirname($file);
			if (is_string($dirs))
			{
				$dirs = explode(PATH_SEPARATOR, $dirs);
			}
			foreach ($dirs as $key => $dir)
			{
				if ($dir == '.')
				{
					$dirs[$key] = $dirPath;
				}
				else
				{
					$dir = rtrim($dir, '\\/');
					$dirs[$key] = $dir . DIRECTORY_SEPARATOR . $dirPath;
				}
			}
			$file = basename($file);
			$return = self::loadFile($file, $dirs, true, $noerror);
		}
		else
		{
			$return = self::loadFile($file, null, true, $noerror);
		}

		return array(
			$return,
			$file,
			$dirs);
	}

	public static function loadClass($class, $dirs = null, $ns = null)
	{
		if (class_exists($class, false) || interface_exists($class, false))
		{
			return;
		}

		if ((null !== $dirs) && !is_string($dirs) && !is_array($dirs))
		{
			require_once 'Zend/Exception.php';
			throw new Zend_Exception('Directory argument must be a string or an array');
		}

		list($return, $file, $dirs) = self::_loadClass($class, $dirs, $chk = ($ns && substr($class, 0, $_len = strlen($ns)) == $ns));

		if (!$return && $chk && !class_exists($class, false) && !interface_exists($class, false))
		{
			list($return, $file, $dirs) = self::_loadClass(substr($class, $_len), $dirs);
		}

		if (class_exists($class, false) || interface_exists($class, false))
		{
			return true;
		}
		elseif (!self::$_suppressNotFoundWarnings)
		{
			require_once 'Zend/Exception.php';
			throw new Zend_Exception("File \"$file\" does not exist or class \"$class\" was not found in the file");
		}

		return false;
	}

	public static function loadFile($filename, $dirs = null, $once = false, $noerror = false)
	{
		self::_securityCheck($filename);

		/**
		 * Search in provided directories, as well as include_path
		 */
		$incPath = false;
		if (!empty($dirs) && (is_array($dirs) || is_string($dirs)))
		{
			if (is_array($dirs))
			{
				$dirs = implode(PATH_SEPARATOR, $dirs);
			}
			$incPath = get_include_path();
			set_include_path($dirs . PATH_SEPARATOR . $incPath);
		}

		/**
		 * Try finding for the plain filename in the include_path.
		 */
		$return = false;

		if ($noerror)
		{
			if ($once)
			{
				$return = @include_once ($filename);
			}
			else
			{
				$return = @include ($filename);
			}
		}
		else
		{
			if ($once)
			{
				$return = include_once ($filename);
			}
			else
			{
				$return = include ($filename);
			}
		}

		/**
		 * If searching in directories, reset include_path
		 */
		if ($incPath)
		{
			set_include_path($incPath);
		}

		return $return;
	}

}
