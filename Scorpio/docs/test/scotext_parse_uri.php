<?php

/**
 *
 * $HeadURL: https://bluelovers.net $
 * $Revision: $
 * $Author: $
 * $Date: $
 * $Id: $
 *
 * @author bluelovers
 * @copyright 2010
 */

include_once '../../helpers/text.php';

class scocurl extends Scorpio_Helper_Curl_Core {}

echo '<style>* {font-size: 12px;}.red {color: red;}.blue {color: blue;}</style><pre>';

foreach(array(
	'http://in-here.us/home.php?mod=space&uid=1&do=profile',
	'http://in-here.us/home/space-uid-1-view-admin.html',
	'http://in-here.us/home/space-uid-1-do-profile.html',
	'http://ca3.php.net/manual/en/function.parse-url.php',
	'http://ca3.php.台灣/manual/en/function.parse-url.php',
	'http://ca3.中文.台灣/manual/en/function.parse-url.php',
	'foo://username:password@example.com:8042/over/there/index.dtb;type=animal?name=ferret#nose',
	'urn:example:animal:ferret:nose',
	'urn:192:168:168:1',
	"foo://username:password@[2001:4860:0:2001::68]:8042".
      "/over/there/index.dtb;type=animal?name=ferret#nose",
    'http://192.168.0.25/manual/en/function.parse-url.php',
    'http://a.192.168.0.25/manual/en/function.parse-url.php',
    'http://localhost/manual/en/function.parse-url.php',
    'http://a.localhost/manual/en/function.parse-url.php',
    'http://user-game/manual/en/function.parse-url.php',
    'http://a.user-game/manual/en/function.parse-url.php',
    'http://中文.user-game/manual/en/function.parse-url.php',
    'http://www.xn--0trt46c.xn--kpry57d/manual/en/function.parse-url.php',
    'http://a.www.xn--0trt46c.xn--kpry57d/manual/en/function.parse-url.php',
    'http://中文.www.xn--0trt46c.xn--kpry57d/manual/en/function.parse-url.php',
    'http://xn--kpry57d/manual/en/function.parse-url.php',
) as $_url) {
	echo "----------------------\n";
	$unitvalue = parse_url($_url);
	echo $_url."\n";
	_unit_print_r(parse_url($_url), $unitvalue);
	_unit_print_r(scotext::parse_url($_url), $unitvalue);
}

function _unit_print_r($array, $unitvalue = array(), $pp = '') {
	$c	= "\n";
	$s	= $pp.'Array ('.$c;
	$p	= $pp."\t";
	foreach ($array as $k => $v) {
		if ($v === null) continue;

		if (is_array($v)) {
			$v = unit_print_r($v, $unitvalue[$k], $p);
		}

		$t = $p.'['.$k.'] => '.$v.$c;
		if (isset($unitvalue[$k]) && $v != $unitvalue[$k]) {
			$t = '<span class="red">'.$t.'</span>';
		} elseif (!isset($unitvalue[$k])) {
			$t = '<span class="blue">'.$t.'</span>';
		}
		$s .= $t;
	}
	$s .= $pp.')'.$c;
	if ($pp) return $s;
	echo $s;
}

?>