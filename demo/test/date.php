<?php

include '../../Scorpio/libs/helper/date.php';

if (!class_exists('scodate')) {
	class scodate extends Scorpio_helper_date_Core_ {

	}
}

echo '<pre>';

$scodate = new scodate();
$scodate->cache = 'new scodate';

//$scodate->set('timestamp', microtime(true));

print_r(array(
	'scodate::instance() __toString()' => ''.scodate::instance(true),
	'new scodate __toString()' => ''.$scodate,

	'scodate::instance() ->timestamp()' => ''.scodate::instance()->timestamp(),
	'new scodate->timestamp()' => ''.$scodate->timestamp(),
));

print_r(array(
	'scodate::instance()' => scodate::instance()->set('cache', 'scodate::instance()'),
	'new scodate' => $scodate,

//	'scodate::instance()->_scorpio_get_called_class()' => @scodate::instance()->_scorpio_get_called_class(),
//	'scodate::_scorpio_get_called_class()' => @scodate::_scorpio_get_called_class(),
));

print_r(array(
	"scodate::instance()->get('cache')" => scodate::instance()->get('cache'),
	"\$scodate->get('cache')" => $scodate->get('cache'),

));

print_r(array(
	"scodate::instance()->cache" => scodate::instance()->cache,
	"\$scodate->cache" => $scodate->cache,
));

print_r(array(
	"scodate::instance()->set('cache_set', 'scodate::instance set')" => scodate::instance()->set('cache_set', 'scodate::instance set'),
	"\$scodate->set('cache_set', 'new scodate set')" => $scodate->set('cache_set', 'new scodate set'),
));

print_r(array(
	"scodate::instance()->cache_set = 'scodate::instance set2'" => scodate::instance()->cache_set = 'scodate::instance set2',
	"\$scodate->cache_set = 'new scodate set2'" => $scodate->cache_set = 'new scodate set2',
));

var_dump($scodate);

$scodate->timestamp(microtime());

var_dump($scodate);

$scodate->timestamp(microtime(true));

var_dump($scodate);

$scodate->timestamp(time());

var_dump($scodate);

?>