<?php

/**
 * @author bluelovers
 * @copyright 2012
 */

require_once ('../bootstrap.php');

$return = sscanf('Processed in 0.06467295 second(s), 20 io, 1003.0468 kb/1.25 mb. PHP 5.3.3 WINNT ', 'Processed in %s second(s), %D io, %s/%s. PHP %s %s');

var_dump($return);

var_dump(array_combine(array_fill(array(
	'sec',
	'io',
	'k',
	'n',
	'p',
	'o'), count($return)), $return));

function vsscanf($str, $format, $keys)
{
	$array = array();
	$return = sscanf($str, $format);

	while ($return)
	{
		$key = array_shift($keys);

		if ($key === null)
		{
			$array[] = array_shift($return);
		}
		elseif (array_key_exists($key, $array))
		{
			throw new InvalidArgumentException('error');
		}
		else
		{
			$array[$key] = array_shift($return);
		}
	}

	while ($keys)
	{
		$key = array_shift($keys);

		if (array_key_exists($key, $array))
		{
			throw new InvalidArgumentException('error');
		}
		else
		{
			$array[$key] = null;
		}
	}

	return $array;
}

$return = Sco_Text_Format::vsscanf('Processed in 0.06467295 second(s), 20 io, 1003.0468 kb/1.25 mb. PHP 5.3.3 WINNT ', 'Processed in %s second(s), %D io, %s/%s. PHP %s %s', array(
	'sec',
	'io',
	'k',
	'n',
	'p',
	'o',
	'z',
	'x', 'io'));

var_dump($return);
