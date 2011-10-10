<?php

/**
 * @author bluelovers
 * @copyright 2011
 */

include_once './../../_include_header.php';

echo '<pre>';

foreach (array(
	'scodb_mysql',
	'Scorpio_helper_db_mysql',
) as $class) {
	echo $class . ' = ' . class_exists($class).LF;

	$_class = call_user_func(array(
		$class,
		'instance'
	));

	var_dump($_class);
}

?>