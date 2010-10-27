<?

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
	class scoregex extends Scorpio_Helper_Regex_Core {}
}

class Scorpio_Helper_Regex_Core {
	protected static $instances = null;

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

		return static::$instances;
	}

	/**
	 * 如果設定此修正符，模式中的字符將同時匹配大小寫字母。
	 **/
	const PCRE_CASELESS = 'i';

	/**
	 * 默認情況下，PCRE 將目標字符串作為單一的一「行」字符所組成的
	 * （甚至其中包含有換行符也是如此）。
	 * 「行起始」元字符（^）僅僅匹配字符串的起始，
	 * 「行結束」元字符（$）僅僅匹配字符串的結束，
	 * 或者最後一個字符是換行符時其前面（除非設定了 D 修正符）。
	 * 這和 Perl 是一樣的。
	 *
	 * 當設定了此修正符，「行起始」和「行結束」除了匹配整個字符串開頭和結束外，
	 * 還分別匹配其中的換行符的之後和之前。這和 Perl 的 /m 修正符是等效的。
	 * 如果目標字符串中沒有「\n」字符或者模式中沒有 ^ 或 $，
	 * 則設定此修正符沒有任何效果。
	 **/
	const PCRE_MULTILINE = 'm';

	/**
	 * 如果設定了此修正符，模式中的圓點元字符（.）匹配所有的字符，
	 * 包括換行符。沒有此設定的話，則不包括換行符。
	 * 這和 Perl 的 /s 修正符是等效的。
	 * 排除字符類例如 [^a] 總是匹配換行符的，無論是否設定了此修正符。
	 **/
	const PCRE_DOTALL = 's';

	/**
	 * 如果設定了此修正符，
	 * 模式中的空白字符除了被轉義的或在字符類中的以外完全被忽略，
	 * 在未轉義的字符類之外的 # 以及下一個換行符之間的所有字符，
	 * 包括兩頭，也都被忽略。這和 Perl 的 /x 修正符是等效的，
	 * 使得可以在複雜的模式中加入註釋。
	 *
	 * 然而注意，這僅適用於數據字符。
	 * 空白字符可能永遠不會出現於模式中的特殊字符序列，
	 * 例如引入條件子模式的序列 (?( 中間。
	 **/
	const PCRE_EXTENDED = 'x';

	/**
	 * 如果設定了此修正符，preg_replace() 在替換字符串中對逆向引用作正常的替換，
	 * 將其作為 PHP 代碼求值，並用其結果來替換所搜索的字符串。
	 * 只有 preg_replace() 使用此修正符，其它 PCRE 函數將忽略之。
	 * 注意: 本修正符在 PHP3 中不可用。
 	 **/
	const PREG_REPLACE_EVAL = 'e';

	/**
	 * 如果設定了此修正符，模式被強製為「anchored」，
	 * 即強制僅從目標字符串的開頭開始匹配。
	 * 此效果也可以通過適當的模式本身來實現（在 Perl 中實現的唯一方法）。
	 **/
	const PCRE_ANCHORED = 'A';

	/**
	 * 如果設定了此修正符，模式中的美元元字符僅匹配目標字符串的結尾。
	 * 沒有此選項時，如果最後一個字符是換行符的話，
	 * 美元符號也會匹配此字符之前（但不會匹配任何其它換行符之前）。
	 * 如果設定了 m 修正符則忽略此選項。Perl 中沒有與其等價的修正符。
	 **/
	const PCRE_DOLLAR_ENDONLY = 'D';

	/**
	 * 當一個模式將被使用若干次時，為加速匹配起見值得先對其進行分析。
	 * 如果設定了此修正符則會進行額外的分析。
	 * 目前，分析一個模式僅對沒有單一固定起始字符的 non-anchored 模式有用。
	 **/
	const PCRE_OPTIMIZE = 'S';

	/**
	 * 本修正符反轉了匹配數量的值使其不是默認的重複，
	 * 而變成在後面跟上「?」才變得重複。這和 Perl 不兼容。
	 * 也可以通過在模式之中設定 (?U) 修正符或者在數量符之後跟一個問號（如 .*?）
	 * 來啟用此選項。
	 **/
	const PCRE_UNGREEDY = 'U';

	/**
	 * 此修正符啟用了一個 PCRE 中與 Perl 不兼容的額外功能。
	 * 模式中的任何反斜線後面跟上一個沒有特殊意義的字母導致一個錯誤，
	 * 從而保留此組合以備將來擴充。
	 *
	 * 默認情況下，和 Perl 一樣，
	 * 一個反斜線後面跟一個沒有特殊意義的字母被當成該字母本身。
	 * 當前沒有其它特性受此修正符控制。
	 **/
	const PCRE_EXTRA = 'X';
	const PCRE_INFO_JCHANGED = 'J';

	/**
	 * 此修正符啟用了一個 PCRE 中與 Perl 不兼容的額外功能。
	 * 模式字符串被當成 UTF-8。
	 * 本修正符在 Unix 下自 PHP 4.1.0 起可用，在 win32 下自 PHP 4.2.3 起可用。
	 * 自 PHP 4.3.5 起開始檢查模式的 UTF-8 合法性。
	 **/
	const PCRE_UTF8 = 'u';

	static $error = array(
		PREG_BACKTRACK_LIMIT_ERROR => 'Backtrack limit was exhausted!',
	);

	function chk_regex($str) {
		return scovalid::regex($str);
	}

	/**
	 * Perform a regular expression search and replace
	 * @param mixed $pattern
	 * @param mixed $replacement
	 * @example
	 * echolf(scoregex::filter(array('/\d/', '/[a-z]/', '/[1a]/'), array('A:$0', 'B:$0', 'C:$0'), array('1', 'a', '2', 'b', '3', 'A', 'B', '4')));
Array (
	[0] => A:C:1
	[1] => B:C:a
	[2] => A:2
	[3] => B:b
	[4] => A:3
	[7] => A:4
)
	 **/
	function filter($pattern, $replacement, $input, $limit = -1, &$count = 0) {
		return preg_filter($pattern, $replacement, $input, $limit, &$count);
	}

	/**
	 * Return array entries that match the pattern
	 * @param int $flags PREG_GREP_INVERT
	 **/
	function grep($pattern, $input, $flags = 0) {
		return preg_grep($pattern, $input, $flags);
	}

	/**
	 * Returns the error code of the last PCRE regex execution
	 * @return PREG_BACKTRACK_LIMIT_ERROR
	 * @return PREG_INTERNAL_ERROR
	 * @return PREG_BACKTRACK_LIMIT_ERROR
	 * @return PREG_RECURSION_LIMIT_ERROR
	 * @return PREG_BAD_UTF8_ERROR
	 * @return PREG_BAD_UTF8_OFFSET_ERROR
	 **/
	function last_error() {
		$args = func_get_args();

		return call_user_func_array('preg_last_error', $args);
	}
	/**
	 * Perform a global regular expression match
	 * @param int $flags PREG_PATTERN_ORDER|PREG_SET_ORDER|PREG_OFFSET_CAPTURE
	 **/
	function match_all($pattern, $input, &$matches, $flags = PREG_PATTERN_ORDER, $offset = 0) {
		return call_user_func_array('preg_match_all', $pattern, $input, &$matches, $flags, $offset);
	}

	/**
	 * Perform a regular expression match
	 * @param int $flags 0|PREG_OFFSET_CAPTURE
	 **/
	function match($pattern, $input, &$matches = array(), $flags = 0, $offset = 0) {
		return call_user_func_array('preg_match', $pattern, $input, &$matches, $flags, $offset);
	}

	/**
	 * Quote regular expression characters
	 *
	 * preg_quote() takes str and puts a backslash in front of every character that is part of the regular expression syntax.
	 * This is useful if you have a run-time string that you need to match in some text and the string may contain special regex characters.
	 *
	 * The special regular expression characters are: . \ + * ? [ ^ ] $ ( ) { } = ! < > | : -
	 **/
	function quote($input, $delimiter = null) {
		return preg_quote($input, $delimiter);
	}

	/**
	 * Perform a regular expression search and replace using a callback
	 *
	 * The behavior of this function is almost identical to preg_replace(),
	 * except for the fact that instead of replacement parameter,
	 * one should specify a callback.
	 **/
	function replace_callback($pattern, $callback, $input, $limit = -1, &$count = 0) {
		return preg_replace_callback($pattern, $callback, $input, $limit, &$count);
	}
	/**
	 * Perform a regular expression search and replace
	 **/
	function replace($pattern, $replacement, $subject, $limit = -1, &$count = 0) {
		return preg_replace($pattern, $replacement, $subject, $limit, &$count);
	}

	/**
	 * Split string by a regular expression
	 * @param int $flags PREG_SPLIT_NO_EMPTY|PREG_SPLIT_DELIM_CAPTURE|PREG_SPLIT_OFFSET_CAPTURE
	 **/
	function split($pattern, $input, $limit = -1, $flags = 0) {
		return preg_split($pattern, $input, $limit, $flags);
	}

}

?>