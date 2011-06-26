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
	protected static $hooklist = array();
	protected static $calevenlist = array();

//	const RET_FAILED = null;
	const RET_FAILED = false;
	const RET_SUCCESS = true;
	const RET_STOP = false;

	static $data = null;
	static $args = null;

	static $event = null;

	public static function add($event, $args) {
		self::$hooklist[$event][] = &$args;
	}

	public static function get($event) {
		return self::$hooklist[$event];
	}

	public static function exists($event, $strict = false) {
		if ( !isset( self::$hooklist[$event] ) ) {
			return false;
		} elseif (!is_array(self::$hooklist)) {
			if ($strict && class_exists('Scorpio_Exception')) throw new Scorpio_Exception("Global hooks array is not an array!\n");
			return false;
		} elseif (!is_array(self::$hooklist[$event])) {
			if ($strict && class_exists('Scorpio_Exception')) throw new Scorpio_Exception("Hooks array for event '%(event)s' is not an array!\n");
			return false;
		}

		return true;
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

		// Return quickly in the most common case
		if ( !isset( self::$hooklist[$event] ) ) {
			return true;
		} elseif (!is_array(self::$hooklist)) {
			if (class_exists('Scorpio_Exception')) {
				throw new Scorpio_Exception("Global hooks array is not an array!\n");
			}
			return self::RET_FAILED;
		} elseif (!is_array(self::$hooklist[$event])) {
			if (class_exists('Scorpio_Exception')) {
				throw new Scorpio_Exception("Hooks array for event '%(event)s' is not an array!\n");
			}
			return self::RET_FAILED;
		}

		static $_support_closure;
		if ($_support_closure === null) $_support_closure = version_compare(PHP_VERSION, '5.3.0', '>=') ? true : false;

		self::$data[$event] = $args;
		self::$args[$event] = $args;

		self::$event = $event;

		foreach (self::$hooklist[$event] as $index => $hook) {

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
					if (class_exists('Scorpio_Exception')) {
						throw new Scorpio_Exception('Empty array in hooks for ' . $event . "\n");
					}
				} else if ( is_object( $hook[0] ) ) {
					$object = self::$hooklist[$event][$index][0];
					if ( $_support_closure && $object instanceof Closure ) {
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
					$func = create_function($hook[1], $hook[2]);

					$have_eval = true;
				// bluelovers

				} else if ( is_string( $hook[0] ) ) {
					$func = $hook[0];
					if ( count( $hook ) > 1) {
						$data = $hook[1];
						$have_data = true;
					}
				} else {
					if (class_exists('Scorpio_Exception')) {
						throw new Scorpio_Exception( 'Unknown datatype in hooks for ' . $event . "\n" );
					}
				}
			} else if ( is_string( $hook ) ) { # functions look like strings, too
				$func = $hook;
			} else if ( is_object( $hook ) ) {
				$object = self::$hooklist[$event][$index];
				if ( $_support_closure && $object instanceof Closure ) {
					$closure = true;
				} else {
					$method = 'on' . $event;
				}
			} else {
				if (class_exists('Scorpio_Exception')) {
					throw new Scorpio_Exception( 'Unknown datatype in hooks for ' . $event . "\n" );
				}
			}

			/* We put the first data element on, if needed. */
			if ( $have_data ) {
//				$hook_args = array_merge(array($data), $args);
				$hook_args = array_merge(array($data), self::$args[$event]);
			} else {
//				$hook_args = $args;
				$hook_args = self::$args[$event];
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
			//set_error_handler( 'Hooks::hookErrorHandler' );
			//wfProfileIn( $func );
			try {
				$retval = call_user_func_array( $callback, $hook_args );
			} catch ( Exception $e ) {
				$badhookmsg = $e->getMessage();
			}
			//wfProfileOut( $func );
			//restore_error_handler();

			/* String return is an error; false return means stop processing. */
			//TODO: add hook ret object
			if ( is_string( $retval ) ) {

				self::clear($event);
				if (class_exists('Scorpio_Exception')) {
					throw new Scorpio_Exception( $retval );
				}

				return false;
			} elseif( $retval === self::RET_FAILED ) {

				self::clear($event);

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

				if (class_exists('Scorpio_Exception')) {
					throw new Scorpio_Exception(
						'Detected bug in an extension! ' .
						"Hook $prettyFunc failed to return a value; " .
						'should return true to continue hook processing or false to abort.'
					);
				}
			} elseif ( $retval === self::RET_STOP ) {

				self::clear($event);

				return false;
			}
		}

		self::clear($event);
	}

	public static function clear($event) {
		$clear_data = '';
		slef::$data[$event] =& $clear_data;
	}
}

?>