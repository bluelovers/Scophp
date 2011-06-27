<?php

include_once '../../Scorpio/libs/Hook.php';
include_once '../../Scorpio/libs/Event.php';

if (!class_exists('Scorpio_Hook')) {
	class Scorpio_Hook extends Scorpio_Hook_Core_ {}
	class Scorpio_Event extends Scorpio_Event_Core_ {}
}

echo '<pre>';

Scorpio_Hook:add('test', function() {
	print_r(func_get_args());
	echo '<br>-----------------------<br>';
});


for ($i=0; $i<10; $i++) {
	echo 'for_$i: '.$i.' - Start<br>';

	Scorpio_Event('test')->run(array('i' => $i), array('i' => $i));

	echo 'for_$i: '.$i.' - End<br>';
	echo '<hr>';
}

?>