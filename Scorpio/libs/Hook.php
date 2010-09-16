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
	class Scorpio_Hook extends Scorpio_Hook_Core {}
}

class Scorpio_Hook_Core {
	protected static $hooklist = array();
	protected static $calevenlist = array();

	const RET_FAILED = null;
	const RET_SUCCESS = true;

	static $data = null;
	static $args = null;

	public static function add($event, $args) {
		static::$hooklist[$event][] = &$args;
	}

	public static function get($event) {
		return static::$hooklist[$event];
	}

	public static function exists($event, $strict = false) {
		if ( !isset( static::$hooklist[$event] ) ) {
			return false;
		} elseif (!is_array(static::$hooklist)) {
			if ($strict) throw new Scorpio_Exception("Global hooks array is not an array!\n");
			return false;
		} elseif (!is_array(static::$hooklist[$event])) {
			if ($strict) throw new Scorpio_Exception("Hooks array for event '%(event)s' is not an array!\n");
			return false;
		}

		return true;
	}

	public static function execute($event, $args = null, $iscall = 0) {

		if ( !isset( static::$hooklist[$event] ) ) {
			return true;
		} elseif (!is_array(static::$hooklist)) {
			throw new Scorpio_Exception("Global hooks array is not an array!\n");
			return static::RET_FAILED;
		} elseif (!is_array(static::$hooklist[$event])) {
			throw new Scorpio_Exception("Hooks array for event '%(event)s' is not an array!\n");
			return static::RET_FAILED;
		}

		static::$data[$event] = $args;
		static::$args[$event] = $args;

		foreach (static::$hooklist[$event] as $index => $hook) {

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
					throw new Scorpio_Exception("Empty array in hooks for " . $event . "\n");
				} else if ( is_object( $hook[0] ) ) {
					$object = static::$hooklist[$event][$index][0];
					if ( $object instanceof Closure ) {
						$closure = true;
						if ( count( $hook ) > 1 ) {
							$data = $hook[1];
							$have_data = true;
						}
					} else {
						if ( count( $hook ) < 2 ) {
							$method = "on" . $event;
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
					throw new Scorpio_Exception( "Unknown datatype in hooks for " . $event . "\n" );
				}
			} else if ( is_string( $hook ) ) { # functions look like strings, too
				$func = $hook;
			} else if ( is_object( $hook ) ) {
				$object = static::$hooklist[$event][$index];
				if ( $object instanceof Closure ) {
					$closure = true;
				} else {
					$method = "on" . $event;
				}
			} else {
				throw new Scorpio_Exception( "Unknown datatype in hooks for " . $event . "\n" );
			}

			/* We put the first data element on, if needed. */

			if ( $have_data ) {
//				$hook_args = array_merge(array($data), $args);
				$hook_args = array_merge(array($data), static::$args[$event]);
			} else {
//				$hook_args = $args;
				$hook_args = static::$args[$event];
			}

			if ( $closure ) {
				$callback = $object;
				$func = "hook-$event-closure";
			} elseif ( isset( $object ) ) {
				$func = get_class( $object ) . '::' . $method;
				$callback = array( $object, $method );
			} elseif ( false !== ( $pos = strpos( $func, '::' ) ) ) {
				$callback = array( substr( $func, 0, $pos ), substr( $func, $pos + 2 ) );
			} else {
				$callback = $func;
			}

			// Run autoloader (workaround for call_user_func_array bug)
			is_callable( $callback );

			/* Call the hook. */
			//wfProfileIn( $func );
			$retval = call_user_func_array( $callback, $hook_args );
			//wfProfileOut( $func );

			/* String return is an error; false return means stop processing. */

			if ( is_string( $retval ) ) {

				static::clear($event);
				throw new Scorpio_Exception( $retval );

				return false;
			} elseif( $retval === static::RET_FAILED ) {

				static::clear($event);

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
				throw new Scorpio_Exception( "Detected bug in an extension! " .
					"Hook $prettyFunc failed to return a value; " .
					"should return true to continue hook processing or false to abort." );
			} else if ( !$retval ) {

				static::clear($event);

				return false;
			}
		}

		static::clear($event);
	}

	public static function clear($event) {
		$clear_data = '';
		static::$data[$event] =& $clear_data;
	}
}

?>