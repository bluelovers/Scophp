<?php

/*
	Scorpio Developer Team (C)2000-2010 Bluelovers Net.

	$HeadURL$
	$Revision$
	$Author: bluelovers$
	$Date$
	$Id$
*/

if (!function_exists("stripos")) {
	function stripos($str, $needle) {
		return strpos(strtolower($str), strtolower($needle));
	}
}

if (!function_exists('uniqid')) {
	function uniqid ($prefix, $enc, $more_entropy) {
		function uuid($serverID=1) {
			$t=explode(" ",microtime());
			return sprintf( '%04x-%08s-%08s-%04s-%04x%04x',
				$serverID,
				clientIPToHex(),
				substr("00000000".dechex($t[1]),-8),   // get 8HEX of unixtime
				substr("0000".dechex(round($t[0]*65536)),-4), // get 4HEX of microtime
				mt_rand(0,0xffff), mt_rand(0,0xffff));
		}

		function uuidDecode($uuid) {
			$rez=Array();
			$u=explode("-",$uuid);
			if(is_array($u)&&count($u)==5) {
				$rez=Array(
					'serverID'=>$u[0],
					'ip'=>clientIPFromHex($u[1]),
					'unixtime'=>hexdec($u[2]),
					'micro'=>(hexdec($u[3])/65536)
				);
			}
			return $rez;
		}

		function clientIPToHex($ip="") {
			$hex="";
			if($ip=="") $ip=getEnv("REMOTE_ADDR");
			$part=explode('.', $ip);
			for ($i=0; $i<=count($part)-1; $i++) {
				$hex.=substr("0".dechex($part[$i]),-2);
			}
			return $hex;
		}

		function clientIPFromHex($hex) {
			$ip="";
			if(strlen($hex)==8) {
				$ip.=hexdec(substr($hex,0,2)).".";
				$ip.=hexdec(substr($hex,2,2)).".";
				$ip.=hexdec(substr($hex,4,2)).".";
				$ip.=hexdec(substr($hex,6,2));
			}
			return $ip;
		}
	}
}

if (!function_exists('fputcsv')) {
	function fputcsv($fp, $arr, $del = ",", $enc = "\"") {
		fwrite($fp, (count($arr)) ? $enc . implode("{$enc}{$del}{$enc}", str_replace("\"", "\"\"", $arr)) . $enc . "\n" : "\n");
	}
}

// Future-friendly json_encode
if( !function_exists('json_encode') ) {
	require_once 'Services/JSON.php';

	function json_encode($value) {
		$jsonobj = new Services_JSON();
		return( $jsonobj->encode($value) );
	}
}

// Future-friendly json_decode
if( !function_exists('json_decode') ) {
	require_once 'Services/JSON.php';

	function json_decode($json, $assoc = false ) {
		$jsonobj = new Services_JSON($assoc ? 16 : false);
		return( $jsonobj->decode($json) );
	}
}

if( !function_exists('array_combine') ) {
	function array_combine($arr1, $arr2) {
		$out = array();

		$arr1 = array_values($arr1);
		$arr2 = array_values($arr2);

		foreach($arr1 as $key1 => $value1) {
			$out[(string)$value1] = $arr2[$key1];
		}

		return $out;
	}
}

if(!function_exists('scandir')) {
	function scandir($dir = './', $sort = 0) {
		$no_dots = false;

		$files = array();
		$dh = @ opendir($dir);

		if ($dh != false) {
			while (($dir_content = readdir($dh)) !== false) {
				if (!$no_dots || !in_array($dir_content, array('.','..')))
					$files[] = $dir_content;
			}

			if ($sort == 1)
				rsort($files, SORT_STRING);
			else
				sort($files, SORT_STRING);
		}

		return $files;
	}
}

?>