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

class Scrpio_File_Core {

	static $temp;

	function open($filename, $mode, $use_include_path = false, $context = null) {
		self::mkdir(self::dirname($filename, '', 1));

		if (@$fp = fopen($filename, $mode)) {
			return $fp;
		}
		$dir = self::dirname($filename, '', 1);
//		$dir = preg_replace('/^'.preg_quote(self::dirname($filename), '/.\\+*?[^]$(){}=!<>|:-').'/', '', $filename);
		exit('Can not write to cache files, please check directory '.$dir);
	}

	function dirname($path, $chdir = '', $dirnamefunc = false) {
		// FIXME: do something

//		$path = dirname($path);
//		if ($chdir) $path = dirname($path);
//		return self::path($path);

		if ($dirnamefunc) $path = dirname($path);

		return ($chdir) ? self::path($path, $chdir) : self::path($path);
	}

	/**
	 * function write
	 * @todo make it do something
	*/
	function write($filename, $context, $dogz = false, $exdata = array()) {
		if ($dogz) $context = self::gzencode($context);

		$fp = self::open($filename, 'w');
		fwrite($fp, ($exdata['before'] ? $exdata['before'] . LF : '').$context.($exdata['after'] ? LF . $exdata['after'] : ''));
		fclose($fp);
	}

	function load() {
		$filename = self::file(func_get_args());
		$fp = self::open($filename, 'r');
		$context = self::remove_bom(@fread($fp, filesize($filename)));
		fclose($fp);

		return $context;
	}

	function remove_bom ($str, $mode = 0){
		switch ($mode) {
			case 1:
				$str = str_replace("\xef\xbb\xbf", '', $str);
			case 2:
				$str = preg_replace("/^\xef\xbb\xbf/", '', $str);
			default:
				if(substr($str, 0,3) == pack("CCC",0xef,0xbb,0xbf)) {
					$str = substr($str, 3);
				}
		}
		return $str;
	}

	function gzencode($context) {
		return gzencode($context, 9, FORCE_GZIP);
	}

	function mkdir($pathname, $mode = 0777, $recursive = false, $context = '', $noindex = 0) {
		$pathname = self::path($pathname);

		is_dir(dirname($pathname)) || self::mkdir(dirname($pathname), $mode, $recursive, $context, $noindex);

		if (!$ret = is_dir($pathname)) {
			$ret = @mkdir($pathname, $mode, $recursive);
			@chmod($pathname, $mode);

			if (!$noindex) @touch($pathname.'/index.htm');
		}

		return $ret;
	}

	function scandir($dir = '.', $sort = 0, $no_dots = false) {
		$files = array();
		$dh = @ opendir(self::path($dir));

		if ($dh != false) {
			while (($dir_content = readdir($dh)) !== false) {
				if (!$no_dots || !in_array($dir_content, array('.','..')))
					$files[] = $dir_content;
			}

			if ($sort == 1)
				sort($files, SORT_STRING);
			else
				sort($files, SORT_NUMERIC);
		}

		return $files;
	}

	function _filter_ext($var) {
		return is_array(self::$temp['args']) ? in_array(self::fileext($var), self::$temp['args']) : (self::fileext($var) == self::$temp['args']);
	}

	function scandir_ext($ext, $dir = '.') {
		$files = self::scandir($dir, 0, 1);

		self::$temp['args'] = $ext;

		return array_filter($files, array('self', '_filter_ext'));
	}

	function unlink($filename) {
		return unlink($filename);
	}

	function fix($url) {
		// FIXME - fix url::fix regex

		return preg_replace(array(
//			'/([\\/]+(\s*\.\s*[\\/]+)*)+/i',
			'/([\\\\\\/]+(\s*\.\s*[\\\\\\/]+)*)+/i',
			'/\/+[^\.\/:]+\/+([^\.\/:]+\/\s*\.\.\s*\/+)?\s*\.\.\s*\/+/i',
			'/(^|\/+)[^\.\/:]+\/+\s*\.\.\s*\/+/i',
			'/^\.\/+/i',
		), array('/', '/', '$1', ''), trim($url));
	}

	function path() {
		$paths = func_get_args();
//		return trim(preg_replace(array("/(\\\\|\\|\/\.\/)+/", "/\/{2,}/"), '/', join('/', is_array($paths[0]) ? $paths[0] : $paths)), '/').'/';

		return ltrim(self::fix(join('/', is_array($paths[0]) ? $paths[0] : $paths)), '/').'/';
	}
	function file() {
		$paths = func_get_args();
//		return trim(preg_replace(array("/(\\\\|\\|\/\.\/)+/", "/\/{2,}/"), '/', join('/', is_array($paths[0]) ? $paths[0] : $paths)), '/');
		return ltrim(self::fix(join('/', is_array($paths[0]) ? $paths[0] : $paths)), '/');
	}

	function fileext($filename) {
		return trim(substr(strrchr($filename, '.'), 1, 10));
	}

	function preg_files($path, $filter) {
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

	function preg_delete($path, $dels, $skip = '') {

		$path = self::path($path);
		$files = self::scandir(self::path($path), 0, 1);

		$retfiles = array(0=>array(), 1=>array());
		if (is_array($files)) {
			foreach ($files as $file) {
				if (is_file($path.$file) && preg_match($dels, $file) && (!$skip || is_array($skip) ? !in_array($file, $skip) : !preg_match($skip, $file))) {
					self::unlink($path.$file);
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

	function file_list($d, $x){
	       foreach(array_diff(scandir($d),array('.','..')) as $f)if(is_file($d.'/'.$f)&&(($x)?ereg($x.'$',$f):1))$l[]=$f;
	       return $l;
	}
}

?>