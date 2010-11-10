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
	class Scorpio_Loader extends Scorpio_Loader_Core {
	}
}

class Scorpio_Loader_Core {

	static $suffix = 'Scorpio_';

	const OBJ_UNDEF = 0;
	const OBJ_HELPER = 1;
	const OBJ_LIB = 2;
	const OBJ_CORE = 3;

	const OBJ_ZEND = 101;

	protected static $instances = null;

	public static $map = array(
		'class' => array(),
		'class_cache' => array(),
	);

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
	public function __construct() {

		// make sure static::$instances is newer
		// 當未建立 static::$instances 時 會以當前 class 作為構造類別
		// 當已建立 static::$instances 時 如果呼叫的 class 不屬於當前 static::$instances 的父類別時 則會自動取代; 反之則 不做任何動作
		if (!static::$instances || !in_array(get_called_class(), class_parents(static::$instances))) {
			static::$instances = $this;
		}

//		print_r(array(get_called_class(), class_parents(static ::$instances), class_parents(self ::$instances), class_parents(get_called_class())));

		return static::$instances;
	}

	protected function _class_parse($class) {
		$ret = $matchs = array();
		$fail = false;

		$ret['core'] = '_Core';

		static $suffix_preg = null;
		$suffix_preg === null && $suffix_preg = preg_quote(static::$suffix, '/');

		if (preg_match('/^sco(?<name>[a-zA-Z][_\w\d]+)$/', $class, $matchs)) {
			$ret['name'] = $matchs['name'];
			$ret['source'] = $class;
			$ret['type'] = static::OBJ_HELPER;

			$ret['file'] = ucfirst($ret['name']).'.php';
			$ret['path'] = 'Scorpio/libs/Helper/';

			$_path = explode('_', $ret['name']);

			$rename_def = static::$suffix.'Helper';

			if (count($_path) > 1) {
				$_pop = array_pop($_path);
				foreach ($_path as $_k) {
					$_ku = ucfirst($_k);
					$ret['path'] .= $_ku.'/';

					$rename_def .= '_'.$_ku;
				}
				$_ku = ucfirst($_pop);
				$rename_def .= '_'.$_ku;

				$ret['file'] = $_ku.'.php';
			} else {
				$rename_def .= '_'.ucfirst($ret['name']);
			}

			$rename_new = $class ? $class : $rename_def;

			$ret['rename_core'] = $rename_def.$ret['core'];
			$ret['rename_def'] = $rename_def;
			$ret['rename_new'] = $rename_new;
		} elseif (preg_match('/^(?P<suffix>'.$suffix_preg.')(?:(?P<path>[a-zA-Z][a-zA-Z_\d]+_)(?P<pathsub>(?:[a-zA-Z][a-zA-Z_\d]+_)+)?)?(?<name>(?:(?:[a-zA-BD-Z]|C(?!ore))[a-zA-Z\d]+_?)+)(?P<core>_Core)?$/', $class, $matchs)) {
			$ret['name'] = $matchs['name'];
			$ret['source'] = $class;

			if ($matchs['path'] == 'Helper_') {
				$ret['type'] = static::OBJ_HELPER;
			} else {
				$ret['type'] = static::OBJ_LIB;
			}

			$ret['file'] = ucfirst($ret['name']).'.php';
			$ret['path'] = 'Scorpio/libs/'.Scorpio_File_Core::dirname(str_replace('_', '/', $matchs['path'].$matchs['pathsub'].$matchs['name']), null, 1);

			$rename_def = static::$suffix.$matchs['path'].$matchs['pathsub'].ucfirst($ret['name']);
			$rename_new = $rename_def;

			$ret['rename_core'] = $rename_def.$ret['core'];
			$ret['rename_def'] = $rename_def;
			$ret['rename_new'] = $rename_new;

			$ret['matchs'] = $matchs;
		} elseif (preg_match('/^(?P<suffix>'.$suffix_preg.'|[a-zA-Z][a-zA-Z\d]+_)?(?:(?P<path>[a-zA-Z][a-zA-Z_\d]+_)(?P<pathsub>(?:[a-zA-Z][a-zA-Z_\d]+_)+)?)?(?<name>[a-zA-Z][_\w\d]+)(?P<core>_Core)?$/', $class, $matchs)) {
			$fail = true;
		} else {
			$fail = true;
		}

		if (!$fail) {
			$ret['issco'] = ($ret['type'] > 0 && $ret['type'] < 100) ? true : false;

			if ($ret['issco']) {
				$ret['syspath'] = SYSPATH;
			}

			ksort($ret);
		}

		return $fail ? null : $ret;

/*
Array (
	[core] => _Core
	[file] => Text.php
	[issco] => 1
	[name] => text
	[path] => Scorpio/libs/Helper/
	[rename_core] => Scorpio_Helper_Text_Core
	[rename_def] => Scorpio_Helper_Text
	[rename_new] => scotext
	[source] => scotext
	[syspath] => D:/xampp/svn/clone/discuz/discuzx/upload/extensions/libs/scophp/
	[type] => 1
)

Array (
	[core] => _Core
	[file] => Text.php
	[issco] => 1
	[name] => Text
	[path] => Scorpio/libs/Helper/
	[rename_core] => Scorpio_Helper_Text_Core
	[rename_def] => Scorpio_Helper_Text
	[rename_new] => Scorpio_Helper_Text
	[source] => Scorpio_Helper_Text
	[syspath] => D:/xampp/svn/clone/discuz/discuzx/upload/extensions/libs/scophp/
	[type] => 1
)

Array (
	[core] => _Core
	[file] => PHP.php
	[issco] => 1
	[name] => PHP
	[path] => Scorpio/libs/File/Pack/
	[rename_core] => Scorpio_File_Pack_PHP_Core
	[rename_def] => Scorpio_File_Pack_PHP
	[rename_new] => Scorpio_File_Pack_PHP
	[source] => Scorpio_File_Pack_PHP
	[syspath] => D:/xampp/svn/clone/discuz/discuzx/upload/extensions/libs/scophp/
	[type] => 2
)
*/
	}

	public static function class_parse($class) {
		$ret = array();

		if (static::map('class', $class)) {
			$ret = static::map('class', $class);
		} elseif (static::map('class_cache', $class)) {
			$ret = static::map('class_cache', $class);
		} elseif (!$ret = static::_class_parse($class)) {

		}

		return $ret;
	}

	public static function lib($name, $rename = null) {
		$name2 = explode('/', $name);
		$name2 = empty($name2) ? array($name) : $name2;
		$class = static::$suffix . implode('_', $name2);

		return static::class_parse($class);
	}

	public static function load($class) {
		if (static::exists($class)) {
			return true;
		} elseif ($ret = static::class_parse($class)) {
			if (!static::exists($ret['rename_core'])) {
//				echo(Scorpio_File_Core::file($ret['syspath'], $ret['path'], $ret['file']).LF);
//				print_r($ret);

//				if (empty($ret['syspath']) && $ret['syspath'] !== 0 && $ret['syspath'] !== '0') {
//					$ret['syspath'] = $ret['path'];
//					unset($ret['path']);
//				}

//				if ($class == 'Facebook') exit(Scorpio_File_Core::file($ret['syspath'], $ret['path'], $ret['file']));

				$ret['_include_file'] = Scorpio_File_Core::file($ret['syspath'], $ret['path'], $ret['file']);
				static::map('class_cache', $ret['rename_core'], $ret);

				include_once $ret['_include_file'];
			}

			foreach (array_filter(array_unique(array($ret['rename_core'], $ret['rename_def'], $ret['rename_new']))) as $_class) {
				if (empty($_class)) continue;

				if (!static::exists($_class)) {
//					static::class_create($_class, $_lastclass);

					static::map('class_cache', $_class, $ret)->class_create($_class, $_lastclass);
				}
				$_lastclass = $_class;
			}

			return true;
		} else {
			return null;
		}
	}

	public static function class_create($class, $extends, $final = false, $abstract = false) {
		$extension = 'class ' . $class . ' extends ' . $extends . ' { }';

		$ref = new ReflectionClass($extends);

		if ($ref->isAbstract() || $abstract) {
			$extension = 'abstract ' . $extension;
		}

		if ($final) {
			$extension = 'final ' . $extension;
		}

		eval($extension);
//		echo $extension.LF;

		return $class;
	}

	public static function exists($class, $autoload = false) {
		static $cache = array();

		if (!isset($cache[$class]) || $cache[$class] == false) {
			$cache[$class] = (class_exists($class, $autoload) || interface_exists($class, $autoload)) ? true : false;
		}

		return $cache[$class];
	}

	public static function setup($update = false) {
		static $spl_autoload_register = false;

		if ($update) {
			$spl_autoload_register && spl_autoload_unregister(array(static::instance(), 'load'));

			if (!static::exists('Scorpio_Loader')) {
				static::load('Scorpio_Loader');
			}

			$spl_autoload_register = true;
			spl_autoload_register(array(Scorpio_Loader::instance(1), 'load'));
		} else {
			$spl_autoload_register = true;
			spl_autoload_register(array(static::instance(1), 'load'));
		}

		return static::instance();
	}

	public static function map() {
		$_n = func_num_args();
		$args = func_get_args();
		if ($_n == 1) {
			$ret = static::$map['class'][$args[0]];
			if (empty($ret)) {
				unset(static::$map['class'][$args[0]]);
				$ret = null;
			}
			return (!empty($ret) && $ret !== array() ) ? $ret : null;
		} elseif ($_n == 2) {
			$ret = static::$map[$args[0]][$args[1]];
			if (empty($ret)) {
				unset(static::$map[$args[0]][$args[1]]);
				$ret = null;
			}
			return (!empty($ret) && $ret !== array() ) ? $ret : null;
		} elseif ($_n == 0) {
			return static::$map;
		} else {
			static::$map[$args[0]][$args[1]] = $args[2];
		}

		return static::instance();
	}

	public static function extend() {
		if ($_n = func_num_args()) {
			$args = func_get_args();
			/*
	[core] => _Core
	[file] => Text.php
	[issco] => 1
	[name] => Text
	[path] => Scorpio/libs/Helper/
	[rename_core] => Scorpio_Helper_Text_Core
	[rename_def] => Scorpio_Helper_Text
	[rename_new] => Scorpio_Helper_Text
	[source] => Scorpio_Helper_Text
	[syspath] => D:/xampp/svn/clone/discuz/discuzx/upload/extensions/libs/scophp/
	[type] => 1
			*/

			switch($_n) {
				case 2:
					$args[0] = trim($args[0]);

					if (is_array($args[1])) {
						$ret = $args[1];
						ksort($ret);
//						static::$map['class'][$args[0]] = $ret;
						static::map('class', $args[0], $ret);

						break;
					}
				case 3:
				case 4:
					$args[0] = trim($args[0]);
					if (empty($args[3]) && !empty(static::$map['class'][$args[0]])) break;

					$ret = array(
						'source' => $args[0],

						'file' => Scorpio_File::basename($args[1]),
						'path' => Scorpio_File::dirname($args[1], '', 1),
						'syspath' => trim($args[2]) ? trim($args[2]) : '',
					);
					if (!empty($ret) && $ret !== array()) {

						$ret['rename_core'] = $ret['source'];

						ksort($ret);

//						static::$map['class'][$ret['source']] = $ret;
						static::map('class', $ret['source'], $ret);
					}
					break;
				case 1:
				default:

					break;
			}
		}

		return static::instance();
	}
}

?>