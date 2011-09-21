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
	class scofile extends Scorpio_helper_file_Core_ {}
}

class Scorpio_helper_file_Core_ extends Scorpio_File {

	static $temp;

	public static function open($filename, $mode, $use_include_path = false, $context = null) {
		self::mkdir(self::dirname($filename, '', 1));

		if (@$fp = fopen($filename, $mode)) {
			return $fp;
		}
		$dir = self::dirname($filename, '', 1);
		//		$dir = preg_replace('/^'.preg_quote(self::dirname($filename), '/.\\+*?[^]$(){}=!<>|:-').'/', '', $filename);
		exit('Can not write to cache files, please check directory ' . $dir);
	}

	public static function dirname($path, $chdir = '', $dirnamefunc = false) {
		// FIXME: do something

		//		$path = dirname($path);
		//		if ($chdir) $path = dirname($path);
		//		return self::path($path);

		if ($dirnamefunc)
			$path = dirname($path);

		return ($chdir) ? self::path($path, $chdir) : self::path($path);
	}

	/**
	 * function write
	 * @todo make it do something
	 */
	public static function write($filename, $context, $dogz = false, $exdata = array
		()) {
		if ($dogz)
			$context = self::gzencode($context);

		$fp = self::open($filename, 'w');
		fwrite($fp, ($exdata['before'] ? $exdata['before'] . LF : '') . $context . ($exdata['after'] ?
			LF . $exdata['after'] : ''));
		fclose($fp);
	}

	public static function load() {
		$filename = self::file(func_get_args());
		$fp = self::open($filename, 'r');
		$context = self::remove_bom(@fread($fp, filesize($filename)));
		fclose($fp);

		return $context;
	}

	public static function remove_bom($str, $mode = 0) {
		switch ($mode) {
			case 1:
				$str = str_replace("\xef\xbb\xbf", '', $str);
			case 2:
				$str = preg_replace("/^\xef\xbb\xbf/", '', $str);
			default:
				if (substr($str, 0, 3) == pack("CCC", 0xef, 0xbb, 0xbf)) {
					$str = substr($str, 3);
				}
		}
		return $str;
	}

	public static function gzencode($context, $level = 9, $encoding_mode = FORCE_GZIP) {
		return gzencode($context, $level, $encoding_mode);
	}

	public static function mkdir($pathname, $mode = 0777, $recursive = false, $context =
		'', $noindex = 0) {
		$pathname = self::path($pathname);

		is_dir(dirname($pathname)) || self::mkdir(dirname($pathname), $mode, $recursive,
			$context, $noindex);

		if (!$ret = is_dir($pathname)) {
			$ret = @mkdir($pathname, $mode, $recursive);
			@chmod($pathname, $mode);

			if (!$noindex)
				@touch($pathname . '/index.htm');
		}

		return $ret;
	}

	public static function scandir($dir = '.', $sort = 0, $no_dots = false) {
		$files = array();
		$dh = @opendir(self::path($dir));

		if ($dh != false) {
			while (($dir_content = readdir($dh)) !== false) {
				if (!$no_dots || !in_array($dir_content, array('.', '..')))
					$files[] = $dir_content;
			}

			if ($sort == 1)
				sort($files, SORT_STRING);
			else
				sort($files, SORT_NUMERIC);
		}

		return $files;
	}

	static function _filter_ext($var) {
		return is_array(self::$temp['args']) ? in_array(self::fileext($var), self::$temp['args']) : (self::
			fileext($var) == self::$temp['args']);
	}

	public static function scandir_ext($ext, $dir = '.') {
		$files = self::scandir($dir, 0, 1);

		self::$temp['args'] = $ext;

		return array_filter($files, array('self', '_filter_ext'));
	}

	public static function unlink($filename) {
		return unlink($filename);
	}

	public static function fix($url) {
		// FIXME - fix url::fix regex

		return preg_replace(array( //			'/([\\/]+(\s*\.\s*[\\/]+)*)+/i',
			'/([\\\\\\/]+(\s*\.\s*[\\\\\\/]+)*)+/i', '/\/+[^\.\/:]+\/+([^\.\/:]+\/\s*\.\.\s*\/+)?\s*\.\.\s*\/+/i',
			'/(^|\/+)[^\.\/:]+\/+\s*\.\.\s*\/+/i', '/^\.\/+/i', '/(^|\/+)[^\.\/:]+\/+\s*\.\.\s*$/i', ),
			array('/', '/', '$1', '', '$1'), trim($url));
	}

	protected function _path_join() {
		$args = func_get_args();

		if (func_num_args() > 1) {
			$array = $args;
		} else {
			$array = $args[0];
			if (is_array($array[0])) {
				$array = $array[0];
			}
		}

		if (is_string($array)) return $array;

		$ret = '';
		while(empty($ret) && $ret !== 0 && $ret !== '0') {
			$ret = array_shift($array);
		}

		if (!empty($array)) {
			foreach ($array as $_v) {
				$_v = trim($_v);
				if (empty($_v) && $_v !== 0 && $_v !== '0') continue;

				$ret .= '/'.$_v;
			}
		}

//		if (end($array) == 'facebook.php') {
//			print_r(array(
//				$array,
//				$ret
//			));
//			exit($ret);
//		}

		return $ret;
	}

	public static function path() {
		$paths = func_get_args();
		//		return trim(preg_replace(array("/(\\\\|\\|\/\.\/)+/", "/\/{2,}/"), '/', join('/', is_array($paths[0]) ? $paths[0] : $paths)), '/').'/';

//		return rtrim(self::fix(join('/', is_array($paths[0]) ? $paths[0] : $paths)), '/') .
//			'/';
		return rtrim(self::fix(self::_path_join($paths)), '/').'/';
	}

	public static function file() {
		$paths = func_get_args();
		//		return trim(preg_replace(array("/(\\\\|\\|\/\.\/)+/", "/\/{2,}/"), '/', join('/', is_array($paths[0]) ? $paths[0] : $paths)), '/');
//		return rtrim(self::fix(join('/', is_array($paths[0]) ? $paths[0] : $paths)), '/');
		return rtrim(self::fix(self::_path_join($paths)), '/');
	}

	public static function fileext($filename) {
		return array_pop(explode('.', self::basename($filename)));
//		return trim(substr(strrchr($filename, '.'), 1, 10));
	}

	public static function preg_files($path, $filter) {
		$path = self::path($path);
		$files = self::scandir(self::path($path), 0, 1);

		$retfiles = array();
		if (is_array($files)) {
			foreach ($files as $file) {
				if (preg_match($filter, $file)) {
					$retfiles[] = $file;
				}

				//				debug($filter, $file, $retfiles, $files);
			}
		}

		return $retfiles;
	}

	public static function preg_delete($path, $dels, $skip = '') {

		$path = self::path($path);
		$files = self::scandir(self::path($path), 0, 1);

		$retfiles = array(0 => array(), 1 => array());
		if (is_array($files)) {
			foreach ($files as $file) {
				if (is_file($path . $file)
					&& preg_match($dels, $file)
					&& (!$skip
						|| (is_array($skip) ? !in_array($file, $skip) : !preg_match($skip, $file))
					)
				) {
					self::unlink($path . $file);
					$retfiles[0][] = $file;
				} else {
					$retfiles[1][] = $file;
				}
			}

			//			($dels && $dels != '/\.js(?:\.gz)?$/i') && dexit(array(
			//				$dels, $file,
			//				$path,
			//				is_file($path.$file),
			//				$retfiles
			//			));
		}

		return $retfiles;
	}

	public static function file_list($d, $x) {
		foreach (array_diff(scandir($d), array('.', '..')) as $f)
			if (is_file($d . '/' . $f) && (($x) ? ereg($x . '$', $f) : 1))
				$l[] = $f;
		return $l;
	}

	public static function remove_root($path, $root) {
		$root = self::path($root);
		$path = self::file($path);

		$ret = (strpos($path, $root) === 0) ? substr($path, strlen($root)) : $path;

		return $ret;
	}

	public static function basename($path, $suffix = '') {
		return basename(array_shift(preg_split('/(\?|#)/', $path)), $suffix);
	}
}

?>