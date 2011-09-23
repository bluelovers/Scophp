<?php

echo '<pre>';

foreach (array(
	microtime(true),
	microtime(),
	time(),
	'S'.microtime(true),
	'S'.microtime(),
	'S'.time(),
	'now',
) as $d) {
	echo "\n".$d."\n";

	$r = preg_match('/(?|(\d{10})|(\d{10})?(?:\.(\d*))?|(?:0+\.(\d+))\s+(\d+))(?>$)/', $d, $m);

	var_dump(array(
		$r,
		$m,
	));
}

include_once '../../../Scorpio/Bootstrap.php';
/*
include_once('../../../Scorpio/libs/Date.php');

class Scorpio_Date extends Scorpio_Date_Core_ {}
*/

foreach (array(
	microtime(true),
	microtime(),
	time(),
	'now',
) as $d) {
	echo "\n".$d."\n";

	$_o = new Scorpio_Date($d);

	echo $_o;

	var_dump($_o);

	$_o->setTimestamp(microtime(true) + 3600);

	echo $_o;

	var_dump($_o);

	$_o->setTimestamp(time() + 3600 * 2);

	echo $_o;

	var_dump($_o);

	echo '<hr>';
}

$_o = new DateTime($d, new DateTimeZone('Asia/Taipei'));

	var_dump($_o);

?>