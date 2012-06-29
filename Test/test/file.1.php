<?php

/**
 * @author bluelovers
 * @copyright 2012
 */

require_once ('../bootstrap.php');

$fp = new Sco_File_Object('../test/.././test/./.\\\test.txt');

var_dump($fp);

//echo $fp->getContents();


var_dump($fp->getRealPath(), $fp->getPath(), $fp->getPathname(), microtime(), sprintf('%0.8f', microtime(true)), sprintf('%0.8f', '1340959614.07259200'), Sco_Math_Format::clean_decimal('1340959614.072592000000'));

$path = "../test/../test/./test.txt";

$benchmark->run(100, 'Sco_Date_Helper::microtime');

$result = $benchmark->get();
var_dump($result['mean'], $result);

//$benchmark->run(100, 'fix2', $path);
//
//$result = $benchmark->get();
//var_dump($result['mean']);

function fix($url)
{
	// FIXME - fix url::fix regex

	return preg_replace(array( //			'/([\\/]+(\s*\.\s*[\\/]+)*)+/i',
		'/([\\\\\\/]+(\s*\.\s*[\\\\\\/]+)*)+/i',
		'/\/+[^\.\/:]+\/+([^\.\/:]+\/\s*\.\.\s*\/+)?\s*\.\.\s*\/+/i',
		'/(^|\/+)[^\.\/:]+\/+\s*\.\.\s*\/+/i',
		'/^\.\/+/i',
		'/(^|\/+)[^\.\/:]+\/+\s*\.\.\s*$/i',
		), array(
		DIR_SEP,
		DIR_SEP,
		'$1',
		'',
		'$1'), trim($url));
}

function fix2($path)
{
	$path = str_replace(array(DIR_SEP_WIN, DIR_SEP_LINUX), DIR_SEP, $path);

	return $path;
}
