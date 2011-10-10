<?php

/**
 * @author bluelovers
 * @copyright 2011
 */

include_once './../../_include_header.php';

echo '<pre>';

foreach (array(
	'scodb_mysqli',
	'Scorpio_helper_db_mysqli',
) as $class) {
	echo $class . ' = ' . class_exists($class).LF;

	$_class = call_user_func(array(
		$class,
		'instance'
	));

	$_class->test = $class;

	var_dump($_class);

	$_class = new $class;

	$_class->name = get_class($_class);

	var_dump($_class);
}

?>