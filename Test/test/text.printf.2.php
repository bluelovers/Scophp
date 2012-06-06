<?php

/**
 * @author bluelovers
 * @copyright 2012
 */

require_once ('../bootstrap.php');

Sco_Text_Format::suppressArgvWarnings(true);

$test_list = array();

$n =  array('n' => 43951789);
$u = array('u' => -43951789);
$c = array('c' => 65); // ASCII 65 is 'A'

// notice the double %%, this prints a literal '%' character
array_push(&$test_list, array("%b", $n)); // binary representation
array_push(&$test_list, array("%c", $c)); // print the ascii character, same as chr() function
array_push(&$test_list, array("%d", $n)); // standard integer representation
array_push(&$test_list, array("%e", $n)); // scientific notation
array_push(&$test_list, array("%u", $n)); // unsigned integer representation of a positive integer
array_push(&$test_list, array("%u", $u)); // unsigned integer representation of a negative integer
array_push(&$test_list, array("%f", $n)); // floating point representation
array_push(&$test_list, array("%o", $n)); // octal representation
array_push(&$test_list, array("%s", $n)); // string representation
array_push(&$test_list, array("%x", $n)); // hexadecimal representation (lower-case)
array_push(&$test_list, array("%X", $n)); // hexadecimal representation (upper-case)

array_push(&$test_list, array("%+d", $n)); // sign specifier on a positive integer
array_push(&$test_list, array("%+d", $u)); // sign specifier on a negative integer

array_push(&$test_list, array("%0+20d", $n));
array_push(&$test_list, array("%0+20d", $u));

array_push(&$test_list, array("%'.+20d", $n));
array_push(&$test_list, array("%'.+20d", $u));

$s = 'monkey';
$t = 'many monkeys';

array_push(&$test_list, array("[%s]",      $s)); // standard string output
array_push(&$test_list, array("[%10s]",    $s)); // right-justification with spaces
array_push(&$test_list, array("[%-10s]",   $s)); // left-justification with spaces
array_push(&$test_list, array("[%010s]",   $s)); // zero-padding works on strings too
array_push(&$test_list, array("[%'#10s]",  $s)); // use the custom padding character '#'
array_push(&$test_list, array("[%10.10s]", $t)); // left-justification but with a cutoff of 10 characters

array_push(&$test_list, array("second: %2\$s ; first: %1\$s", array('1st', '2nd')));

array_push(&$test_list, array('second: %second$s ; first: %first$s', array('1st', '2nd')));

array_push(&$test_list, array("second: %2\$s ; first: %1\$s ; second: %2\$s ; first: %1\$s", array('1st', '2nd')));

array_push(&$test_list, array("second: %2\$s ; first: %1\$s ; second: %2\$s ; first: %1\$s %s", array('1st', '2nd', '3nd')));

foreach ($test_list as $data)
{
	list ($format, $args) = $data;

	echo str_repeat('=', 80).LF;
	echo $format.LF;
	echo str_repeat('-', 80).LF;

	$orig = vsprintf($format, (array)$args);
	$frame = Sco_Text_Format::vsprintf($format, (array)$args);

	$error = ($frame !== $orig);

	echo $orig.LF;
	printf('<span style="color: %s">', $error ? 'red' : '#cccccc');
	echo $frame.LF;
	echo('</span>');
}