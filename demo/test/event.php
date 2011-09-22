<?php

include_once './_include_header.php';

include_once '../../Scorpio/libs/Hook.php';
include_once '../../Scorpio/libs/Event.php';

if (!class_exists('Scorpio_Hook')) {
	class Scorpio_Hook extends Scorpio_Hook_Core_ {}
	class Scorpio_Event extends Scorpio_Event_Core_ {}
}

echo '
<style>
* { font-size: 12px; }
</style>
';

echo '<pre>';

Scorpio_Hook::add('test', function() {
	$args = func_get_args();
	$_EVENT = array_shift($args);

	$_EVENT['event.args']['i'] .= ', '.$args[0];
	$_EVENT['event.data']['i'] .= ', '.$args[0];

	print_r(array(
		$_EVENT,
		$args,

		Scorpio_Hook::$event,
	));
	echo '<br>-----------------------<br>';

	return (0 && ($args[0] % 2) == 0) ? Scorpio_Hook::RET_STOP : Scorpio_Hook::RET_SUCCESS;
});

Scorpio_Hook::add('test', function() {
	$args = func_get_args();
	$_EVENT = array_shift($args);

	$_EVENT['event.args']['i'] .= ', '.$args[0];
	$_EVENT['event.data']['i'] .= ', '.$args[0];

	print_r(array(
		$_EVENT,
		$args,

		Scorpio_Hook::$event,
	));
	echo '<br>-----------------------<br>';

	return Scorpio_Hook::RET_SUCCESS;
});


for ($i=0; $i<10; $i++) {
	echo 'for_$i: '.$i.' - Start<br>';

	Scorpio_Event::instance('test')->run(array('i' => $i), array('i' => $i));

	echo 'for_$i: '.$i.' - End<br>';
	echo '<hr>';
}

echo '<hr>';
echo '<hr>';

for ($i=0; $i<10; $i++) {
	echo 'for_$i: '.$i.' - Start<br>';

	Scorpio_Hook::execute('test', array('i' => $i));

	echo 'for_$i: '.$i.' - End<br>';
	echo '<hr>';
}

?>