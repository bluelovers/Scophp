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

define('IN_SCORPIO', true);
define('DIR_SEP', '/');
define('LF', "\n");
define('TAB', "\t");
define('CR', "\r");

// Define Kohana error constant
define('E_SCORPIO', 200042);

// Define 404 error constant
!defined('E_PAGE_NOT_FOUND') and define('E_PAGE_NOT_FOUND', 200043);

// Define database error constant
!defined('E_DATABASE_ERROR') and define('E_DATABASE_ERROR', 200044);

// Test of PHP is running in Windows
define('OS_IS_WIN', (strpos($_SERVER['OS'], 'Windows_') === 0 or DIRECTORY_SEPARATOR === '\\'));

// benchmarks are prefixed to prevent collisions
define('SYSTEM_BENCHMARK', 'system_benchmark');

!defined('E_DEPRECATED') and define('E_DEPRECATED', 8192);

?>