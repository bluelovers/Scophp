<?php

/**
 *
 * $HeadURL$
 * $Revision$
 * $Author$
 * $Date$
 * $Id$
 *
 * @author bluelovers
 * @copyright 2010
 */

if (0) {
	// for IDE
	class scoutf8 extends Scorpio_Helper_Utf8_Core {
	}
}

class Scorpio_Helper_Utf8_Core {

	protected static $instances = null;

	public static $default_driver = 'mbstring';
	protected static $drivers;

	// 取得構造物件
	public static function &instance($overwrite = false) {
		if (!static::$instances) {
			$ref = new ReflectionClass(($overwrite && !in_array($overwrite, array(true, 1), true)) ? $overwrite:get_called_class());
			static::$instances = $ref->newInstance();
		} elseif ($overwrite) {
			$ref = new ReflectionClass(!in_array($overwrite, array(true, 1), true) ? $overwrite:get_called_class());
			static::$instances = $ref->newInstance();
		}

		return static::$instances;
	}

	// 建立構造
	function __construct() {

		// make sure static::$instances is newer
		// 當未建立 static::$instances 時 會以當前 class 作為構造類別
		// 當已建立 static::$instances 時 如果呼叫的 class 不屬於當前 static::$instances 的父類別時 則會自動取代; 反之則 不做任何動作
		if (!static::$instances || !in_array(get_called_class(), class_parents(static::$instances))) {
			static::$instances = $this;
		}

//		print_r(array(get_called_class(), class_parents(static ::$instances), class_parents(self ::$instances), class_parents(get_called_class())));

		mb_internal_encoding("UTF-8");

		return static::$instances;
	}

	static function mb_decode($string) {
		//$string = iconv('UTF-8', 'ISO-8859-2', $string);
		//$string = utf8_decode($string);
		//$string = mb_convert_encoding($string, "ISO-8859-1", "UTF-8");
		return $string;
	}

	static function mb_encode($string) {
		//$string = iconv('ISO-8859-2', 'UTF-8', $string);
		//$string = utf8_encode($string);
		//$string = mb_convert_encoding($string, "UTF-8", "ISO-8859-1");
		return $string;
	}

	/**
	 * Pads a UTF-8 string to a certain length with another string.
	 * @see http://php.net/str_pad
	 *
	 * @author  Harry Fuecks <hfuecks@gmail.com>
	 *
	 * @param   string   input string
	 * @param   integer  desired string length after padding
	 * @param   string   string to use as padding
	 * @param   string   padding type: STR_PAD_RIGHT, STR_PAD_LEFT, or STR_PAD_BOTH
	 * @return  string
	 */
	public static function str_pad($str, $final_str_length, $pad_str = ' ', $pad_type =
		STR_PAD_RIGHT) {

		if (static::is_ascii($str) and static::is_ascii($pad_str)) {
			return str_pad($str, $final_str_length, $pad_str, $pad_type);
		}

		$str_length = mb_strlen($str);

		if ($final_str_length <= 0 or $final_str_length <= $str_length) {
			return $str;
		}

		$pad_str_length = mb_strlen($pad_str);
		$pad_length = $final_str_length - $str_length;

		if ($pad_type == STR_PAD_RIGHT) {
			$repeat = ceil($pad_length / $pad_str_length);
			return mb_substr($str . str_repeat($pad_str, $repeat), 0, $final_str_length);
		}

		if ($pad_type == STR_PAD_LEFT) {
			$repeat = ceil($pad_length / $pad_str_length);
			return mb_substr(str_repeat($pad_str, $repeat), 0, floor($pad_length)) . $str;
		}

		if ($pad_type == STR_PAD_BOTH) {
			$pad_length /= 2;
			$pad_length_left = floor($pad_length);
			$pad_length_right = ceil($pad_length);
			$repeat_left = ceil($pad_length_left / $pad_str_length);
			$repeat_right = ceil($pad_length_right / $pad_str_length);

			$pad_left = mb_substr(str_repeat($pad_str, $repeat_left), 0, $pad_length_left);
			$pad_right = mb_substr(str_repeat($pad_str, $repeat_right), 0, $pad_length_left);
			return $pad_left . $str . $pad_right;
		}

		trigger_error('utf8::str_pad: Unknown padding type (' . $pad_type . ')',
			E_USER_ERROR);
	}

	function mb_unserialize($serial_str) {
		$serial_str = preg_replace('!s:(\d+):"(.*?)";!se', "'s:'.strlen('$2').':\"$2\";'",
			$serial_str);
		$serial_str = str_replace("\r", "", $serial_str);
		return unserialize($serial_str);
	}

	/**
	 * A replacement that should be safe on utf-8 strings.
	 * http://www.php.net/manual/en/function.stripslashes.php#89785
	 */
	function mb_stripslashes($str) {
		return preg_replace(array('/\x5C(?!\x5C)/u', '/\x5C\x5C/u'), array('','\\'), $str);
	}
}

?>