<?php

/**
 * @author bluelovers
 * @copyright 2012
 */

require_once ('../bootstrap.php');

$chk_list = array(
	'apache_request_headers',
	'apache_response_headers',
	'mb_detect_encoding',
	'iconv',
	'http_build_url',
	'class_alias',
	'json_encode',
	'json_decode',
	'lcfirst',
	'spl_object_hash',);

_Env::run($chk_list);

printnl('');

print_r(get_included_files());

class _Env
{

	protected static $_instance;
	public static $result;

	protected $setp;

	public static function run($list = array())
	{
		if (!isset(self::$_instance))
		{
			new self;
		}

		//$ref = new Sco_Reflection_Class(self::$_instance);

		foreach (Sco_Reflection_Helper::get_public_methods(self::$_instance, array(
			__FUNCTION__,
			'result',
			'_var_string'), null, true) as $method)
		{
			self::$_instance->setp[$method] = null;

			self::$result = null;

			printnl(sprintf('[%s]...', $method));

			$color = 'red';

			$time = microtime(true);

			try
			{
				self::$result = @_Env::$method();

				self::$result && $color = '';

				printnl(sprintf('[%s] Processed in %.8f second(s), Result: <span style="display: inline-block; color: %s; ">%s</span>', $method, microtime(true) - $time, $color, self::_var_string(self::$result)));
			}
			catch (Exception $e)
			{
				printnl(sprintf('[%s] Processed in %.8f second(s), Error: <span style="display: inline-block; color: %s; ">%s</span>', $method, microtime(true) - $time, $color, $e->getMessage()));
			}

			self::$_instance->setp[$method] = self::$result;
		}

		foreach ((array )$list as $k => $v)
		{
			if (is_int($k))
			{
				$method = $v;
			}
			else
			{
				$method = $k;
			}

			if (array_key_exists($method, self::$_instance->setp))
			{
				continue;
			}

			$is_callable = is_callable($v);

			self::$_instance->setp[$method] = null;

			self::$result = null;

			$method = implode('::', (array )$method);

			printnl(sprintf('[%s]...', $method));

			$color = 'red';

			$time = microtime(true);

			try
			{
				self::$result = (is_int($k) && !is_array($v)) ? function_exists($v) : call_user_func($v);

				self::$result && $color = '';

				printnl(sprintf('[%s] Processed in %.8f second(s), Result: <span style="display: inline-block; color: %s; ">%s</span>', $method, microtime(true) - $time, $color, self::_var_string(self::$result)));
			}
			catch (Exception $e)
			{
				printnl(sprintf('[%s] Processed in %.8f second(s), Error: <span style="display: inline-block; color: %s; ">%s</span>', $method, microtime(true) - $time, $color, $e->getMessage()));
			}

			self::$_instance->setp[$method] = self::$result;
		}

		printnl(LF, str_repeat('-', 80), LF);

		foreach (self::$_instance->setp as $method => $result)
		{
			$color = $result ? '' : 'red';

			printnl(sprintf('<span style="display: inline-block; color: %3$s;">[%1$s] %2$s</span>', $method, self::_var_string($result), $color));
		}
	}

	public static function result()
	{
		return (array )self::$_instance->setp;
	}

	protected function __construct()
	{
		if (!isset(self::$_instance))
		{
			self::$_instance = $this;
		}
	}

	public static function _var_string($var)
	{
		if ($var === null)
		{
			return 'NULL';
		}
		elseif (is_bool($var))
		{
			return $var === true ? 'True' : 'False';
		}

		return $var;
	}

	public static function date_default_timezone_set()
	{
		$chk_set = array(
			'GMT',
			'Asia/Taipei',
			'Etc/GMT-8',
			);

		$zone = @date_default_timezone_get();
		$i = 0;

		foreach ($chk_set as $v)
		{
			if (empty($zone))
			{
				$zone = $v;
			}

			date_default_timezone_set($v);

			$z = @date_default_timezone_get();

			if (empty($z) || $z != $v)
			{
				printnl(sprintf('[%s][%d] set %s Fail! , return %s', __FUNCTION__, ++$i, $v, $z));

				return false;
			}

			printnl(sprintf('[%s][%d] set %s Ok!', __FUNCTION__, ++$i, $v));
		}

		date_default_timezone_set($zone);

		printnl(sprintf('[%s][%d] reset to %s', __FUNCTION__, ++$i, $zone));

		return true;
	}

	public static function version_compare()
	{
		$chk_set = array(
			Sco::PHP_VERSION,
			'5.2.0',
			'5.3.0',
			);

		$chk_set = array_unique($chk_set);

		$i = 0;

		foreach ($chk_set as $v)
		{
			$result = version_compare(PHP_VERSION, $v, '>=');

			if ($i == 0)
			{
				$ret = $result;
			}

			printnl(sprintf('[%s][%d] PHP Version %s >= %s : %s', __FUNCTION__, ++$i, PHP_VERSION, $v, self::_var_string($result)));
		}

		return $ret;
	}

	public static function xdebug()
	{
		$chk_set = array(
			'xdebug_disable',
			'xdebug_stop_code_coverage',
			);

		$ret = null;

		foreach ($chk_set as $v)
		{
			$result = function_exists($v);

			if ($ret === null || $ret)
			{
				$ret = $result;
			}

			printnl(sprintf('[%s][%d] %s : %s', __FUNCTION__, ++$i, $v, self::_var_string($result)));
		}

		return $ret;
	}

	public static function spl()
	{
		$chk_set = array(
			'CallbackFilterIterator',
			'DateInterval',
			'Counter',
			);

		$ret = null;

		foreach ($chk_set as $v)
		{
			$result = class_exists($v, false);

			if ($ret === null || $ret)
			{
				$ret = $result;
			}

			$result2 = @class_exists($v);

			printnl(sprintf('[%s][%d] %s : %s / %s', __FUNCTION__, ++$i, $v, self::_var_string($result), self::_var_string($result2)));
		}

		return $ret;

	}

	public static function php()
	{
		$phpinfo = Sco_PHP_Helper::phpinfo_array();

		printnl(sprintf('[%s] %s / PHP v%s, %s, %s', __FUNCTION__, PHP_OS, PHP_VERSION, reset(explode(' ', $phpinfo['General']['Compiler'])), $phpinfo['General']['Thread Safety'] == 'enabled' ? 'TS' : 'NTS'));
		printnl(sprintf('[%s] %s', __FUNCTION__, $phpinfo['General']['Server API']));
		printnl(sprintf('[%s] %s', __FUNCTION__, xdebug_call_class()));

		return true;
	}

	public static function exec()
	{
		$chk_set = array(
			'exec',
			'shell_exec',
			);

		$ret = null;

		foreach ($chk_set as $v)
		{
			$result = is_callable($v);

			if ($ret === null || $ret)
			{
				$ret = $result;
			}

			printnl(sprintf('[%s][%d] %s : %s', __FUNCTION__, ++$i, $v, self::_var_string($result)));
		}

		return $ret;
	}

}


