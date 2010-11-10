<?php

include_once '../../Bootstrap.php';
Scorpio_Loader_Core::setup(1);

echolf('<html xmlns:fb="http://www.facebook.com/2008/fbml"><head><style>html, body { font-size: 12px; font-family: \'Lucida Grande\', Verdana, Arial, sans-serif; } .row_red { background-color: red; color: #fff; }</style></head><body>');
echolf('<pre>');

function echolf($text) {
	if (is_array($text)) {
		unit_print_r($text);
	} else {
		echo var_string($text).LF;
	}
}

function var_string($text) {
	if ($text === true) {
		$text = 'true';
	} elseif ($text === false) {
		$text = 'false';
	} elseif ($text === null) {
		$text = 'null';
	}

	return $text;
}

function unit_print_r($array, $unitvalue = array(), $pp = '', $loop = 0) {
	if (is_string($array)) {
		$array = array($array);
	}

	$c	= "\n";
	$s	= ($loop ? '' : $pp).'Array ('.$c;
	$p	= $pp."\t";
	if (0 || is_array($array)) {
		foreach ($array as $k => $v) {
			if ($v === null) continue;

			if (is_array($v)) {
				$v = unit_print_r($v, $unitvalue[$k], $p, 1);
			}

			$t = $p.'['.$k.'] => '.$v.$c;
			if (isset($unitvalue[$k]) && $v != $unitvalue[$k]) {
				$t = '<span class="red">'.$t.'</span>';
			} elseif (!isset($unitvalue[$k])) {
				$t = '<span class="blue">'.$t.'</span>';
			}
			$s .= $t;
		}
	} else {
		$s .= var_export($array);
	}
	$s .= $pp.')'.$c;
	if ($pp) return $s;
	echolf($s);
}

?>
