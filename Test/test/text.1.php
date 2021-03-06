<?php

/**
 * @author bluelovers
 * @copyright 2012
 */

require_once ('../bootstrap.php');

$s = 'test sentence.中文..';

class_exists('Sco_Text_Helper');

$time = microtime(true);

for ($i = 0; $i < 1000; $i++)
{
	$hex = str_f2h3($s, true);
	str_f2h3($hex);
}

printf('Processed in %.8f second(s)' . NL, microtime(true) - $time);

$time = microtime(true);

for ($i = 0; $i < 1000; $i++)
{
	$hex = Sco_Text_Helper::str_f2h($s, true);
	Sco_Text_Helper::str_f2h($hex);
}

printf('Processed in %.8f second(s)' . NL, microtime(true) - $time);

$time = microtime(true);

for ($i = 0; $i < 1000; $i++)
{
	$hex = str_f2h2($s, true);
	str_f2h2($hex);
}

printf('Processed in %.8f second(s)' . NL, microtime(true) - $time);

$time = microtime(true);

for ($i = 0; $i < 1000; $i++)
{
	$hex = str_f2h($s, true);
	str_f2h($hex);
}

printf('Processed in %.8f second(s)' . NL, microtime(true) - $time);

function str_f2h($str, $h2f = 0)
{
	/*
	//全形英數字及符號
	$f=array("　","０","１","２","３","４","５","６","７","８","９","Ａ","Ｂ","Ｃ","Ｄ","Ｅ","Ｆ","Ｇ","Ｈ","Ｉ","Ｊ","Ｋ","Ｌ","Ｍ","Ｎ","Ｏ","Ｐ","Ｑ","Ｒ","Ｓ","Ｔ","Ｕ","Ｖ","Ｗ","Ｘ","Ｙ","Ｚ","ａ","ｂ","ｃ","ｄ","ｅ","ｆ","ｇ","ｈ","ｉ","ｊ","ｋ","ｌ","ｍ","ｎ","ｏ","ｐ","ｑ","ｒ","ｓ","ｔ","ｕ","ｖ","ｗ","ｘ","ｙ","ｚ","～","！","＠","＃","＄","％","^","＆","＊"," （","）","＿","＋","｜","‘","－","＝","＼","｛","｝","〔","〕","：","”","；","’"," ＜","＞","？","，","．","／");
	//半形英數字及符號
	$h=array(" ","0","1","2","3","4","5","6","7","8","9","A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z","a","b","c","d","e","f","g","h","i","j","k","l","m","n","o","p","q","r","s","t","u","v","w","x","y","z","~","!","@","#","\$","%","^","&","*"," (",")","_","+","|","`","-","=","\\\\","{","}","[","]",":"," \"",";","'","<",">","?",",",".","/");//注意"\\\\"為四條
	*/

	//全形英數字及符號
	$f = array(
		'　',
		'０',
		'１',
		'２',
		'３',
		'４',
		'５',
		'６',
		'７',
		'８',
		'９',
		'Ａ',
		'Ｂ',
		'Ｃ',
		'Ｄ',
		'Ｅ',
		'Ｆ',
		'Ｇ',
		'Ｈ',
		'Ｉ',
		'Ｊ',
		'Ｋ',
		'Ｌ',
		'Ｍ',
		'Ｎ',
		'Ｏ',
		'Ｐ',
		'Ｑ',
		'Ｒ',
		'Ｓ',
		'Ｔ',
		'Ｕ',
		'Ｖ',
		'Ｗ',
		'Ｘ',
		'Ｙ',
		'Ｚ',
		'ａ',
		'ｂ',
		'ｃ',
		'ｄ',
		'ｅ',
		'ｆ',
		'ｇ',
		'ｈ',
		'ｉ',
		'ｊ',
		'ｋ',
		'ｌ',
		'ｍ',
		'ｎ',
		'ｏ',
		'ｐ',
		'ｑ',
		'ｒ',
		'ｓ',
		'ｔ',
		'ｕ',
		'ｖ',
		'ｗ',
		'ｘ',
		'ｙ',
		'ｚ',
		'～',
		'！',
		'＠',
		'＃',
		'＄',
		'％',
		'^',
		'＆',
		'＊',
		' （',
		'）',
		'＿',
		'＋',
		'｜',
		'‘',
		'－',
		'＝',
		'＼',
		'｛',
		'｝',
		'〔',
		'〕',
		'：',
		'”',
		'；',
		'’',
		' ＜',
		'＞',
		'？',
		'，',
		'．',
		'／',
		'︿',
		);
	//半形英數字及符號
	$h = array(
		' ',
		'0',
		'1',
		'2',
		'3',
		'4',
		'5',
		'6',
		'7',
		'8',
		'9',
		'A',
		'B',
		'C',
		'D',
		'E',
		'F',
		'G',
		'H',
		'I',
		'J',
		'K',
		'L',
		'M',
		'N',
		'O',
		'P',
		'Q',
		'R',
		'S',
		'T',
		'U',
		'V',
		'W',
		'X',
		'Y',
		'Z',
		'a',
		'b',
		'c',
		'd',
		'e',
		'f',
		'g',
		'h',
		'i',
		'j',
		'k',
		'l',
		'm',
		'n',
		'o',
		'p',
		'q',
		'r',
		's',
		't',
		'u',
		'v',
		'w',
		'x',
		'y',
		'z',
		'~',
		'!',
		'@',
		'#',
		'$',
		'%',
		'^',
		'&',
		'*',
		' (',
		')',
		'_',
		'+',
		'|',
		'`',
		'-',
		'=',
		'\\',
		'{',
		'}',
		'[',
		']',
		':',
		'"',
		';',
		'\'',
		'<',
		'>',
		'?',
		',',
		'.',
		'/',
		'^',
		);

	return $h2f ? str_replace($h, $f, $str) : str_replace($f, $h, $str);
}

function str_f2h2($str, $h2f = 0)
{
	static $map;

	if (!isset($map))
	{

		$map = array(
			' ' => '　',
			'0' => '０',
			'1' => '１',
			'2' => '２',
			'3' => '３',
			'4' => '４',
			'5' => '５',
			'6' => '６',
			'7' => '７',
			'8' => '８',
			'9' => '９',
			'A' => 'Ａ',
			'B' => 'Ｂ',
			'C' => 'Ｃ',
			'D' => 'Ｄ',
			'E' => 'Ｅ',
			'F' => 'Ｆ',
			'G' => 'Ｇ',
			'H' => 'Ｈ',
			'I' => 'Ｉ',
			'J' => 'Ｊ',
			'K' => 'Ｋ',
			'L' => 'Ｌ',
			'M' => 'Ｍ',
			'N' => 'Ｎ',
			'O' => 'Ｏ',
			'P' => 'Ｐ',
			'Q' => 'Ｑ',
			'R' => 'Ｒ',
			'S' => 'Ｓ',
			'T' => 'Ｔ',
			'U' => 'Ｕ',
			'V' => 'Ｖ',
			'W' => 'Ｗ',
			'X' => 'Ｘ',
			'Y' => 'Ｙ',
			'Z' => 'Ｚ',
			'a' => 'ａ',
			'b' => 'ｂ',
			'c' => 'ｃ',
			'd' => 'ｄ',
			'e' => 'ｅ',
			'f' => 'ｆ',
			'g' => 'ｇ',
			'h' => 'ｈ',
			'i' => 'ｉ',
			'j' => 'ｊ',
			'k' => 'ｋ',
			'l' => 'ｌ',
			'm' => 'ｍ',
			'n' => 'ｎ',
			'o' => 'ｏ',
			'p' => 'ｐ',
			'q' => 'ｑ',
			'r' => 'ｒ',
			's' => 'ｓ',
			't' => 'ｔ',
			'u' => 'ｕ',
			'v' => 'ｖ',
			'w' => 'ｗ',
			'x' => 'ｘ',
			'y' => 'ｙ',
			'z' => 'ｚ',
			'~' => '～',
			'!' => '！',
			'@' => '＠',
			'#' => '＃',
			'$' => '＄',
			'%' => '％',
			'^' => '︿',
			'&' => '＆',
			'*' => '＊',
			' (' => ' （',
			')' => '）',
			'_' => '＿',
			'+' => '＋',
			'|' => '｜',
			'`' => '‘',
			'-' => '－',
			'=' => '＝',
			'\\' => '＼',
			'{' => '｛',
			'}' => '｝',
			'[' => '〔',
			']' => '〕',
			':' => '：',
			'"' => '”',
			';' => '；',
			'\'' => '’',
			'<' => ' ＜',
			'>' => '＞',
			'?' => '？',
			',' => '，',
			'.' => '．',
			'/' => '／',
			);
	}

	return $h2f ? strtr($str, $map) : strtr($str, array_flip($map));
}

function str_f2h3($str, $h2f = 0)
{
	$map = array(
		' ' => '　',
		'0' => '０',
		'1' => '１',
		'2' => '２',
		'3' => '３',
		'4' => '４',
		'5' => '５',
		'6' => '６',
		'7' => '７',
		'8' => '８',
		'9' => '９',
		'A' => 'Ａ',
		'B' => 'Ｂ',
		'C' => 'Ｃ',
		'D' => 'Ｄ',
		'E' => 'Ｅ',
		'F' => 'Ｆ',
		'G' => 'Ｇ',
		'H' => 'Ｈ',
		'I' => 'Ｉ',
		'J' => 'Ｊ',
		'K' => 'Ｋ',
		'L' => 'Ｌ',
		'M' => 'Ｍ',
		'N' => 'Ｎ',
		'O' => 'Ｏ',
		'P' => 'Ｐ',
		'Q' => 'Ｑ',
		'R' => 'Ｒ',
		'S' => 'Ｓ',
		'T' => 'Ｔ',
		'U' => 'Ｕ',
		'V' => 'Ｖ',
		'W' => 'Ｗ',
		'X' => 'Ｘ',
		'Y' => 'Ｙ',
		'Z' => 'Ｚ',
		'a' => 'ａ',
		'b' => 'ｂ',
		'c' => 'ｃ',
		'd' => 'ｄ',
		'e' => 'ｅ',
		'f' => 'ｆ',
		'g' => 'ｇ',
		'h' => 'ｈ',
		'i' => 'ｉ',
		'j' => 'ｊ',
		'k' => 'ｋ',
		'l' => 'ｌ',
		'm' => 'ｍ',
		'n' => 'ｎ',
		'o' => 'ｏ',
		'p' => 'ｐ',
		'q' => 'ｑ',
		'r' => 'ｒ',
		's' => 'ｓ',
		't' => 'ｔ',
		'u' => 'ｕ',
		'v' => 'ｖ',
		'w' => 'ｗ',
		'x' => 'ｘ',
		'y' => 'ｙ',
		'z' => 'ｚ',
		'~' => '～',
		'!' => '！',
		'@' => '＠',
		'#' => '＃',
		'$' => '＄',
		'%' => '％',
		'^' => '︿',
		'&' => '＆',
		'*' => '＊',
		' (' => ' （',
		')' => '）',
		'_' => '＿',
		'+' => '＋',
		'|' => '｜',
		'`' => '‘',
		'-' => '－',
		'=' => '＝',
		'\\' => '＼',
		'{' => '｛',
		'}' => '｝',
		'[' => '〔',
		']' => '〕',
		':' => '：',
		'"' => '”',
		';' => '；',
		'\'' => '’',
		'<' => ' ＜',
		'>' => '＞',
		'?' => '？',
		',' => '，',
		'.' => '．',
		'/' => '／',
		);

	return $h2f ? strtr($str, $map) : strtr($str, array_flip($map));
}
