<?php

include_once '../../../Scorpio/Bootstrap.php';

echo '<pre>';

foreach (array(
	microtime(true),
	microtime(),
	time(),
	'S'.microtime(true),
	'S'.microtime(),
	'S'.time(),
	'now',
	'2011-10-11 03:48:26',
	'2011-10-11 03:48:26 0.821526',
) as $d) {
	echo "\n".$d."\n";

	$r = preg_match('/(?|(\d{10})|(\d{10})?(?:\.(\d*))?|(?:0+\.(\d+))\s+(\d+))(?>$)/', $d, $m);

	var_dump(array(
		$r,
		$m,
	));

	$r = Scorpio_Date::_preg_match_timestamp($d, $m1);

	var_dump(array(
		$r,
		$m1,
	));

	var_dump(@@strtotime($d));
}

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

	$_o->setTimestamp(time() + 3600 * 1);

	echo $_o;

	var_dump($_o);

	$_o->setTimestamp(microtime(true) + 3600 * 2);

	echo $_o;

	var_dump($_o);

	$_o->modify('+1 hour');

	echo $_o;

	var_dump($_o);

	echo '<hr>';
}

$_o = new DateTime($d, new DateTimeZone('Asia/Taipei'));

	var_dump($_o);

function profile($dump = FALSE) {
    static $profile;

    // Return the times stored in profile, then erase it
    if ($dump) {
    	$temp = dmicrotime(true) - $profile;
        unset($profile);
        return number_format($temp, 6);
    }

    $profile = dmicrotime(true);
}

function dmicrotime() {
	return array_sum(explode(' ', microtime()));
}

$do = 100;

while($j < 5) {
	$j++;
	$i = 0;

	echo '<hr>';

	profile();

	while ($i < $do) {
		$i++;

		$_o = new DateTime($d);
	}

	var_dump(array(
		profile(true),
		'DateTime'
	));

	sleep(1);

	$i = 0;

	profile();

	while ($i < $do) {
		$i++;

		$_o = new Scorpio_Date($d);
	}

	var_dump(array(
		profile(true),
		'Scorpio_Date'
	));

	echo '<hr>';

	sleep(1);
}

?>