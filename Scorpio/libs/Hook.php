<?

/**
 * A tool for running hook functions.
 *
 * Copyright 2004, 2005 Evan Prodromou <evan@wikitravel.org>.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA
 *
 * @author Evan Prodromou <evan@wikitravel.org>
 * @see hooks.txt
 * @file
 */

/**
 * This Script Base on MediaWiki Hook Script.
 *
 * @author bluelovers
 * @copyright 2010
 */

if (0) {
	// for IDE
	class Scorpio_Hook extends Scorpio_Hook_Core_ {}
}

class Scorpio_Hook_Core_ {
	protected static $handlers = array();

	/**
	 * save all try called hook
	 */
	protected static $handlers_called = array();

	const RET_FAILED = null;
//	const RET_FAILED = false;
	const RET_SUCCESS = true;
	const RET_STOP = false;
	const RET_ERROR = false;

//	static $data = null;
//	static $args = null;

	static $event = null;

	static $throw_exception = false;

	public static function add($event, $args) {
		self::$handlers[$event][] = &$args;
	}

	public static function &get($event) {
		return self::$handlers[$event];
	}

	public static function remove($event) {
		$ret = self::exists($event);

		unset(self::$handlers[$event]);

		return $ret;
	}

	protected static function _support($force = false) {
		static $_support;

		if ($_support === null || $force) {
			$_support = array();
			$_support['closure'] = version_compare(PHP_VERSION, '5.3.0', '>=') ? true : false;
			$_support['Scorpio_Exception'] = class_exists('Scorpio_Exception');
			$_support['Scorpio_Hook_Exception'] = class_exists('Scorpio_Hook_Exception');
			$_support['Scorpio_Event'] = class_exists('Scorpio_Event');
		}

		return $_support;
	}

	public static function exists($event, $strict = false) {
		$_support = self::_support();

		// 強化判斷是否存在 hook
		if ( !isset( self::$handlers[$event] ) || empty(self::$handlers[$event]) ) {
			return false;
		} elseif (!is_array(self::$handlers)) {
			if ($strict && $_support['Scorpio_Exception'] && Scorpio_Hook::$throw_exception) throw new Scorpio_Exception("Global hooks array is not an array!\n");
			return false;
		} elseif (!is_array(self::$handlers[$event])) {
			if ($strict && $_support['Scorpio_Exception'] && Scorpio_Hook::$throw_exception) throw new Scorpio_Exception("Hooks array for event '%(event)s' is not an array!\n");
			return false;
		}

		return ( count( self::$handlers[$event] ) != 0 ) ? true : false;
	}

	/**
	 * alias method for execute
	 */
	public static function run($event, $args = array(), $iscall = 0) {
		return self::execute($event, $args, $iscall);
	}

	/**
	 * Call hook functions defined in Hooks::register
	 *
	 * Because programmers assign to $wgHooks, we need to be very
	 * careful about its contents. So, there's a lot more error-checking
	 * in here than would normally be necessary.
	 *
	 * @param $event String: event name
	 * @param $args Array: parameters passed to hook functions
	 * @return Boolean
	 */
	public static function execute($event, $args = array(), $iscall = 0) {
		$_support = self::_support();

		Scorpio_Hook::$handlers_called[$event] += 1;

		// Return quickly in the most common case
		if ( !isset( self::$handlers[$event] ) ) {
			return true;
		} elseif (!is_array(self::$handlers)) {
			if ($_support['Scorpio_Exception'] && Scorpio_Hook::$throw_exception) {
				throw new Scorpio_Exception("Global hooks array is not an array!\n");
			}
			return self::RET_FAILED;
		} elseif (!is_array(self::$handlers[$event])) {
			if ($_support['Scorpio_Exception'] && Scorpio_Hook::$throw_exception) {
				throw new Scorpio_Exception("Hooks array for event '%(event)s' is not an array!\n", array('event' => $event));
			}
			return self::RET_FAILED;
		}

//		self::$data[$event] = $args;
//		self::$args[$event] = $args;

		self::$event = $event;

		$_cache_handlers = &self::get($event);

		foreach ((array)$_cache_handlers as $index => $hook) {

			$object = null;
			$method = null;
			$func = null;
			$data = null;
			$have_data = false;
			$closure = false;

			$retval = null;

			/* $hook can be: a function, an object, an array of $function and $data,
			 * an array of just a function, an array of object and method, or an
			 * array of object, method, and data.
			 */

			if ( is_array( $hook ) ) {
				if ( count( $hook ) < 1 ) {
					if ($_support['Scorpio_Exception'] && Scorpio_Hook::$throw_exception) {
						throw new Scorpio_Exception('Empty array in hooks for ' . $event . "\n");
					}
				} else if ( is_object( $hook[0] ) ) {
					$object = $_cache_handlers[$index][0];
					if ( $_support['closure'] && $object instanceof Closure ) {
						$closure = true;
						if ( count( $hook ) > 1 ) {
							$data = $hook[1];
							$have_data = true;
						}
					} else {
						if ( count( $hook ) < 2 ) {
							$method = 'on' . $event;
						} else {
							$method = $hook[1];
							if ( count( $hook ) > 2 ) {
								$data = $hook[2];
								$have_data = true;
							}
						}
					}

				// bluelovers
				} else if (is_string($hook[0]) && $hook[0] == 'func' && count($hook) == 3) {
					// 追加 $_EVENT 來允許存取 Scorpio_Event::instance($event)->data)
					$func = create_function('$_EVENT, '.$hook[1], $hook[2]);

					$have_eval = true;
				// bluelovers

				} else if ( is_string( $hook[0] ) ) {
					$func = $hook[0];
					if ( count( $hook ) > 1) {
						$data = $hook[1];
						$have_data = true;
					}
				} else {
					if ($_support['Scorpio_Exception'] && Scorpio_Hook::$throw_exception) {
						throw new Scorpio_Exception( 'Unknown datatype in hooks for ' . $event . "\n" );
					}
				}
			} else if ( is_string( $hook ) ) { # functions look like strings, too
				$func = $hook;
			} else if ( is_object( $hook ) ) {
				$object = $_cache_handlers[$index];
				if ( $_support['closure'] && $object instanceof Closure ) {
					$closure = true;
				} else {
					$method = 'on' . $event;
				}
			} else {
				if ($_support['Scorpio_Exception'] && Scorpio_Hook::$throw_exception) {
					throw new Scorpio_Exception( 'Unknown datatype in hooks for ' . $event . "\n" );
				}
			}

			/* We put the first data element on, if needed. */
			if ( $have_data ) {
				$hook_args = array_merge(array($data), $args);
//				$hook_args = array_merge(array($data), self::$args[$event]);
			} else {
				$hook_args = $args;
//				$hook_args = self::$args[$event];
			}

			// 檢查是否支援 Scorpio_Event
			if ($_support['Scorpio_Event']) {
				array_unshift($hook_args, Scorpio_Event::instance($event)
					->counter_add($index)
					->data()
				);
			} else {
				array_unshift($hook_args, null);
			}

			if ( $closure ) {
				$callback = $object;
				$func = "hook-$event-closure";
			} elseif ( isset( $object ) ) {
				$func = get_class( $object ) . '::' . $method;
				$callback = array( $object, $method );
			// 5.1 compat code
			// mediawiki 已經不使用以下這一段代碼來相容 PHP 5.1
			} elseif ( false !== ( $pos = strpos( $func, '::' ) ) ) {
				$callback = array( substr( $func, 0, $pos ), substr( $func, $pos + 2 ) );
			// 5.1 compat code
			} else {
				$callback = $func;
			}

			// Run autoloader (workaround for call_user_func_array bug)
			is_callable( $callback );

			/**
			 * Call the hook. The documentation of call_user_func_array clearly
			 * states that FALSE is returned on failure. However this is not
			 * case always. In some version of PHP if the function signature
			 * does not match the call signature, PHP will issue an warning:
			 * Param y in x expected to be a reference, value given.
			 *
			 * In that case the call will also return null. The following code
			 * catches that warning and provides better error message. The
			 * function documentation also says that:
			 *     In other words, it does not depend on the function signature
			 *     whether the parameter is passed by a value or by a reference.
			 * There is also PHP bug http://bugs.php.net/bug.php?id=47554 which
			 * is unsurprisingly marked as bogus. In short handling of failures
			 * with call_user_func_array is a failure, the documentation for that
			 * function is wrong and misleading and PHP developers don't see any
			 * problem here.
			 */
			$retval = null;
			set_error_handler( 'Scorpio_Hook::hookErrorHandler' );
			//wfProfileIn( $func );
			if ($_support['Scorpio_Hook_Exception']) {
				try {
					$retval = call_user_func_array( $callback, $hook_args );
				} catch ( Scorpio_Hook_Exception $e ) {
					$badhookmsg = $e->getMessage();
				}
			} elseif ($_support['Scorpio_Exception']) {
				try {
					$retval = call_user_func_array( $callback, $hook_args );
				} catch ( Scorpio_Exception $e ) {
					$badhookmsg = $e->getMessage();
				}
			} else {
				try {
					$retval = call_user_func_array( $callback, $hook_args );
				} catch ( Exception $e ) {
					$badhookmsg = $e->getMessage();
				}
			}
			//wfProfileOut( $func );
			restore_error_handler();

			/* String return is an error; false return means stop processing. */
			//TODO: add hook ret object
			if ( is_string( $retval ) ) {

//				self::clear($event);
				if ($_support['Scorpio_Exception'] && Scorpio_Hook::$throw_exception) {
					throw new Scorpio_Exception( $retval );
				}

				return self::RET_ERROR;
			} elseif( $retval === self::RET_FAILED ) {

//				self::clear($event);

				if ( $closure ) {
					$prettyFunc = "$event closure";
				} elseif( is_array( $callback ) ) {
					if( is_object( $callback[0] ) ) {
						$prettyClass = get_class( $callback[0] );
					} else {
						$prettyClass = strval( $callback[0] );
					}
					$prettyFunc = $prettyClass . '::' . strval( $callback[1] );
				} else {
					$prettyFunc = strval( $callback );
				}

				if ($_support['Scorpio_Exception'] && Scorpio_Hook::$throw_exception) {
					if ( $badhookmsg ) {
						throw new Scorpio_Exception(
							'Detected bug in an extension! ' .
							"Hook $prettyFunc has invalid call signature; " . $badhookmsg
						);
					} else {
						throw new Scorpio_Exception(
							'Detected bug in an extension! ' .
							"Hook $prettyFunc failed to return a value; " .
							'should return true to continue hook processing or false to abort.'
						);
					}
				}
			} elseif ( $retval === self::RET_STOP ) {

//				self::clear($event);

				return self::RET_STOP;
			}
		}

//		self::clear($event);

		return self::RET_SUCCESS;
	}

//	public static function clear($event) {
////		$clear_data = '';
////		self::$data[$event] =& $clear_data;
//	}

	/**
	 * This REALLY should be protected... but it's public for compatibility
	 *
	 * @param $errno Unused
	 * @param $errstr String: error message
	 * @return Boolean: false
	 */
	public static function hookErrorHandler( $errno, $errstr ) {
		$_support = self::_support();

		if (Scorpio_Hook::$throw_exception
			&& strpos( $errstr, 'expected to be a reference, value given' ) !== false
		) {
			if ($_support['Scorpio_Exception']) {
				throw new Scorpio_Exception( $errstr );
			}
		}

		return false;
	}
}

?>