<?php

/**
 * @author bluelovers
 * @copyright 2012
 */

define('DIR_SEP', '/');

define('LF', "\n");
define('CR', "\r");
define('CRLF', CR . LF);

define('TAB', "\t");
define('SPACE', ' ');

!defined('E_DEPRECATED') and define('E_DEPRECATED', 8192);

/**
 * @link http://stackoverflow.com/questions/277224/how-do-i-catch-a-php-fatal-error
 */
define('E_FATAL', E_ERROR | E_USER_ERROR | E_PARSE | E_CORE_ERROR | E_COMPILE_ERROR | E_RECOVERABLE_ERROR);
define('ERROR_REPORTING', E_ALL | E_STRICT);

define('NS_DEFAULT', 'Default');
