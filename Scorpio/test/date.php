<?php

include '../libs/helper/date.php';

if (!class_exists('scodate')) {
	class scodate extends Scorpio_helper_date_Core_ {

	}
}

echo '<pre>';

$scodate = new scodate();
$scodate->cache = 'new scodate';

print_r(array(
	'scodate::instance()' => scodate::instance(true)->set('cache', 'scodate::instance()'),
	'new scodate' => $scodate,

//	'scodate::instance()->_scorpio_get_called_class()' => @scodate::instance()->_scorpio_get_called_class(),
//	'scodate::_scorpio_get_called_class()' => @scodate::_scorpio_get_called_class(),

	"scodate::instance()->get('cache')" => scodate::instance()->get('cache'),
	"scodate::instance()->cache" => scodate::instance()->cache,
	"\$scodate->get('cache')" => $scodate->get('cache'),
	"\$scodate->cache" => $scodate->cache,

	"scodate::instance()->set('cache', 'scodate::instance set')" => scodate::instance()->set('cache', 'scodate::instance set'),
	"scodate::instance()->cache = 'scodate::instance set2'" => scodate::instance()->cache = 'scodate::instance set2',
	"\$scodate->set('cache', 'new scodate set')" => $scodate->set('cache', 'new scodate set'),
	"\$scodate->cache = 'new scodate set2'" => $scodate->cache = 'new scodate set2',
));

?>