<?php

/**
 * @author bluelovers
 * @copyright 2012
 */

echo '<pre>';
var_export(ini_get_all());

$ini_vgo = array(
	'allow_call_time_pass_reference' => array(
		'global_value' => '1',
		'local_value' => '1',
		'access' => 6,
		),
	'allow_url_fopen' => array(
		'global_value' => '1',
		'local_value' => '1',
		'access' => 4,
		),
	'allow_url_include' => array(
		'global_value' => '0',
		'local_value' => '0',
		'access' => 4,
		),
	'always_populate_raw_post_data' => array(
		'global_value' => '0',
		'local_value' => '0',
		'access' => 6,
		),
	'arg_separator.input' => array(
		'global_value' => '&',
		'local_value' => '&',
		'access' => 6,
		),
	'arg_separator.output' => array(
		'global_value' => '&',
		'local_value' => '&',
		'access' => 7,
		),
	'asp_tags' => array(
		'global_value' => '',
		'local_value' => '',
		'access' => 6,
		),
	'assert.active' => array(
		'global_value' => '1',
		'local_value' => '1',
		'access' => 7,
		),
	'assert.bail' => array(
		'global_value' => '0',
		'local_value' => '0',
		'access' => 7,
		),
	'assert.callback' => array(
		'global_value' => NULL,
		'local_value' => NULL,
		'access' => 7,
		),
	'assert.quiet_eval' => array(
		'global_value' => '0',
		'local_value' => '0',
		'access' => 7,
		),
	'assert.warning' => array(
		'global_value' => '1',
		'local_value' => '1',
		'access' => 7,
		),
	'auto_append_file' => array(
		'global_value' => '',
		'local_value' => '',
		'access' => 6,
		),
	'auto_detect_line_endings' => array(
		'global_value' => '0',
		'local_value' => '0',
		'access' => 7,
		),
	'auto_globals_jit' => array(
		'global_value' => '1',
		'local_value' => '1',
		'access' => 6,
		),
	'auto_prepend_file' => array(
		'global_value' => '',
		'local_value' => '',
		'access' => 6,
		),
	'bcmath.scale' => array(
		'global_value' => '0',
		'local_value' => '0',
		'access' => 7,
		),
	'browscap' => array(
		'global_value' => NULL,
		'local_value' => NULL,
		'access' => 4,
		),
	'cgi.check_shebang_line' => array(
		'global_value' => '1',
		'local_value' => '1',
		'access' => 4,
		),
	'cgi.discard_path' => array(
		'global_value' => '0',
		'local_value' => '0',
		'access' => 4,
		),
	'cgi.fix_pathinfo' => array(
		'global_value' => '1',
		'local_value' => '1',
		'access' => 4,
		),
	'cgi.force_redirect' => array(
		'global_value' => '1',
		'local_value' => '1',
		'access' => 4,
		),
	'cgi.nph' => array(
		'global_value' => '0',
		'local_value' => '0',
		'access' => 7,
		),
	'cgi.redirect_status_env' => array(
		'global_value' => NULL,
		'local_value' => NULL,
		'access' => 4,
		),
	'cgi.rfc2616_headers' => array(
		'global_value' => '0',
		'local_value' => '0',
		'access' => 7,
		),
	'curl.cainfo' => array(
		'global_value' => '',
		'local_value' => '',
		'access' => 4,
		),
	'date.default_latitude' => array(
		'global_value' => '31.7667',
		'local_value' => '31.7667',
		'access' => 7,
		),
	'date.default_longitude' => array(
		'global_value' => '35.2333',
		'local_value' => '35.2333',
		'access' => 7,
		),
	'date.sunrise_zenith' => array(
		'global_value' => '90.583333',
		'local_value' => '90.583333',
		'access' => 7,
		),
	'date.sunset_zenith' => array(
		'global_value' => '90.583333',
		'local_value' => '90.583333',
		'access' => 7,
		),
	'date.timezone' => array(
		'global_value' => 'Asia/Chongqing',
		'local_value' => 'Asia/Chongqing',
		'access' => 7,
		),
	'default_charset' => array(
		'global_value' => '',
		'local_value' => '',
		'access' => 7,
		),
	'default_mimetype' => array(
		'global_value' => 'text/html',
		'local_value' => 'text/html',
		'access' => 7,
		),
	'default_socket_timeout' => array(
		'global_value' => '60',
		'local_value' => '60',
		'access' => 7,
		),
	'define_syslog_variables' => array(
		'global_value' => '0',
		'local_value' => '0',
		'access' => 7,
		),
	'detect_unicode' => array(
		'global_value' => '1',
		'local_value' => '1',
		'access' => 7,
		),
	'disable_classes' => array(
		'global_value' => '',
		'local_value' => '',
		'access' => 4,
		),
	'disable_functions' => array(
		'global_value' => 'show_source, system, shell_exec, passthru, exec, popen, proc_open, allow_url_fopen',
		'local_value' => 'show_source, system, shell_exec, passthru, exec, popen, proc_open, allow_url_fopen',
		'access' => 4,
		),
	'display_errors' => array(
		'global_value' => '1',
		'local_value' => '1',
		'access' => 7,
		),
	'display_startup_errors' => array(
		'global_value' => '',
		'local_value' => '',
		'access' => 7,
		),
	'doc_root' => array(
		'global_value' => NULL,
		'local_value' => NULL,
		'access' => 4,
		),
	'docref_ext' => array(
		'global_value' => '',
		'local_value' => '',
		'access' => 7,
		),
	'docref_root' => array(
		'global_value' => '',
		'local_value' => '',
		'access' => 7,
		),
	'enable_dl' => array(
		'global_value' => '',
		'local_value' => '',
		'access' => 4,
		),
	'error_append_string' => array(
		'global_value' => NULL,
		'local_value' => NULL,
		'access' => 7,
		),
	'error_log' => array(
		'global_value' => 'error_log',
		'local_value' => 'error_log',
		'access' => 7,
		),
	'error_prepend_string' => array(
		'global_value' => NULL,
		'local_value' => NULL,
		'access' => 7,
		),
	'error_reporting' => array(
		'global_value' => '22519',
		'local_value' => '22519',
		'access' => 7,
		),
	'exif.decode_jis_intel' => array(
		'global_value' => 'JIS',
		'local_value' => 'JIS',
		'access' => 7,
		),
	'exif.decode_jis_motorola' => array(
		'global_value' => 'JIS',
		'local_value' => 'JIS',
		'access' => 7,
		),
	'exif.decode_unicode_intel' => array(
		'global_value' => 'UCS-2LE',
		'local_value' => 'UCS-2LE',
		'access' => 7,
		),
	'exif.decode_unicode_motorola' => array(
		'global_value' => 'UCS-2BE',
		'local_value' => 'UCS-2BE',
		'access' => 7,
		),
	'exif.encode_jis' => array(
		'global_value' => '',
		'local_value' => '',
		'access' => 7,
		),
	'exif.encode_unicode' => array(
		'global_value' => 'ISO-8859-15',
		'local_value' => 'ISO-8859-15',
		'access' => 7,
		),
	'exit_on_timeout' => array(
		'global_value' => '0',
		'local_value' => '0',
		'access' => 7,
		),
	'expose_php' => array(
		'global_value' => '1',
		'local_value' => '1',
		'access' => 4,
		),
	'extension_dir' => array(
		'global_value' => '/usr/local/lib/php/extensions/no-debug-non-zts-20090626',
		'local_value' => '/usr/local/lib/php/extensions/no-debug-non-zts-20090626',
		'access' => 4,
		),
	'fastcgi.logging' => array(
		'global_value' => '1',
		'local_value' => '1',
		'access' => 4,
		),
	'file_uploads' => array(
		'global_value' => '1',
		'local_value' => '1',
		'access' => 4,
		),
	'filter.default' => array(
		'global_value' => 'unsafe_raw',
		'local_value' => 'unsafe_raw',
		'access' => 6,
		),
	'filter.default_flags' => array(
		'global_value' => NULL,
		'local_value' => NULL,
		'access' => 6,
		),
	'from' => array(
		'global_value' => NULL,
		'local_value' => NULL,
		'access' => 7,
		),
	'gd.jpeg_ignore_warning' => array(
		'global_value' => '0',
		'local_value' => '0',
		'access' => 7,
		),
	'highlight.bg' => array(
		'global_value' => '#FFFFFF',
		'local_value' => '#FFFFFF',
		'access' => 7,
		),
	'highlight.comment' => array(
		'global_value' => '#FF8000',
		'local_value' => '#FF8000',
		'access' => 7,
		),
	'highlight.default' => array(
		'global_value' => '#0000BB',
		'local_value' => '#0000BB',
		'access' => 7,
		),
	'highlight.html' => array(
		'global_value' => '#000000',
		'local_value' => '#000000',
		'access' => 7,
		),
	'highlight.keyword' => array(
		'global_value' => '#007700',
		'local_value' => '#007700',
		'access' => 7,
		),
	'highlight.string' => array(
		'global_value' => '#DD0000',
		'local_value' => '#DD0000',
		'access' => 7,
		),
	'html_errors' => array(
		'global_value' => '1',
		'local_value' => '1',
		'access' => 7,
		),
	'iconv.input_encoding' => array(
		'global_value' => 'ISO-8859-1',
		'local_value' => 'ISO-8859-1',
		'access' => 7,
		),
	'iconv.internal_encoding' => array(
		'global_value' => 'ISO-8859-1',
		'local_value' => 'ISO-8859-1',
		'access' => 7,
		),
	'iconv.output_encoding' => array(
		'global_value' => 'ISO-8859-1',
		'local_value' => 'ISO-8859-1',
		'access' => 7,
		),
	'ignore_repeated_errors' => array(
		'global_value' => '',
		'local_value' => '',
		'access' => 7,
		),
	'ignore_repeated_source' => array(
		'global_value' => '',
		'local_value' => '',
		'access' => 7,
		),
	'ignore_user_abort' => array(
		'global_value' => '0',
		'local_value' => '0',
		'access' => 7,
		),
	'imagick.locale_fix' => array(
		'global_value' => '0',
		'local_value' => '0',
		'access' => 7,
		),
	'imagick.progress_monitor' => array(
		'global_value' => '0',
		'local_value' => '0',
		'access' => 4,
		),
	'implicit_flush' => array(
		'global_value' => '',
		'local_value' => '',
		'access' => 7,
		),
	'include_path' => array(
		'global_value' => '.:/usr/lib/php:/usr/local/lib/php',
		'local_value' => '.:/usr/lib/php:/usr/local/lib/php',
		'access' => 7,
		),
	'log_errors' => array(
		'global_value' => '1',
		'local_value' => '1',
		'access' => 7,
		),
	'log_errors_max_len' => array(
		'global_value' => '1024',
		'local_value' => '1024',
		'access' => 7,
		),
	'magic_quotes_gpc' => array(
		'global_value' => '1',
		'local_value' => '1',
		'access' => 4,
		),
	'magic_quotes_runtime' => array(
		'global_value' => '0',
		'local_value' => '0',
		'access' => 7,
		),
	'magic_quotes_sybase' => array(
		'global_value' => '0',
		'local_value' => '0',
		'access' => 7,
		),
	'mail.add_x_header' => array(
		'global_value' => '0',
		'local_value' => '0',
		'access' => 6,
		),
	'mail.force_extra_parameters' => array(
		'global_value' => NULL,
		'local_value' => NULL,
		'access' => 6,
		),
	'mail.log' => array(
		'global_value' => NULL,
		'local_value' => NULL,
		'access' => 6,
		),
	'max_execution_time' => array(
		'global_value' => '30',
		'local_value' => '30',
		'access' => 7,
		),
	'max_file_uploads' => array(
		'global_value' => '20',
		'local_value' => '20',
		'access' => 4,
		),
	'max_input_nesting_level' => array(
		'global_value' => '64',
		'local_value' => '64',
		'access' => 6,
		),
	'max_input_time' => array(
		'global_value' => '60',
		'local_value' => '60',
		'access' => 6,
		),
	'max_input_vars' => array(
		'global_value' => '1000',
		'local_value' => '1000',
		'access' => 6,
		),
	'mbstring.detect_order' => array(
		'global_value' => NULL,
		'local_value' => NULL,
		'access' => 7,
		),
	'mbstring.encoding_translation' => array(
		'global_value' => '0',
		'local_value' => '0',
		'access' => 6,
		),
	'mbstring.func_overload' => array(
		'global_value' => '0',
		'local_value' => '0',
		'access' => 4,
		),
	'mbstring.http_input' => array(
		'global_value' => 'pass',
		'local_value' => 'pass',
		'access' => 7,
		),
	'mbstring.http_output' => array(
		'global_value' => 'pass',
		'local_value' => 'pass',
		'access' => 7,
		),
	'mbstring.http_output_conv_mimetypes' => array(
		'global_value' => '^(text/|application/xhtml\\+xml)',
		'local_value' => '^(text/|application/xhtml\\+xml)',
		'access' => 7,
		),
	'mbstring.internal_encoding' => array(
		'global_value' => NULL,
		'local_value' => NULL,
		'access' => 7,
		),
	'mbstring.language' => array(
		'global_value' => 'neutral',
		'local_value' => 'neutral',
		'access' => 7,
		),
	'mbstring.script_encoding' => array(
		'global_value' => NULL,
		'local_value' => NULL,
		'access' => 7,
		),
	'mbstring.strict_detection' => array(
		'global_value' => '0',
		'local_value' => '0',
		'access' => 7,
		),
	'mbstring.substitute_character' => array(
		'global_value' => NULL,
		'local_value' => NULL,
		'access' => 7,
		),
	'mcrypt.algorithms_dir' => array(
		'global_value' => NULL,
		'local_value' => NULL,
		'access' => 7,
		),
	'mcrypt.modes_dir' => array(
		'global_value' => NULL,
		'local_value' => NULL,
		'access' => 7,
		),
	'memory_limit' => array(
		'global_value' => '48M',
		'local_value' => '48M',
		'access' => 7,
		),
	'mysql.allow_local_infile' => array(
		'global_value' => '1',
		'local_value' => '1',
		'access' => 4,
		),
	'mysql.allow_persistent' => array(
		'global_value' => '1',
		'local_value' => '1',
		'access' => 4,
		),
	'mysql.connect_timeout' => array(
		'global_value' => '60',
		'local_value' => '60',
		'access' => 7,
		),
	'mysql.default_host' => array(
		'global_value' => '',
		'local_value' => '',
		'access' => 7,
		),
	'mysql.default_password' => array(
		'global_value' => '',
		'local_value' => '',
		'access' => 7,
		),
	'mysql.default_port' => array(
		'global_value' => '',
		'local_value' => '',
		'access' => 7,
		),
	'mysql.default_socket' => array(
		'global_value' => '/var/lib/mysql/mysql.sock',
		'local_value' => '/var/lib/mysql/mysql.sock',
		'access' => 7,
		),
	'mysql.default_user' => array(
		'global_value' => '',
		'local_value' => '',
		'access' => 7,
		),
	'mysql.max_links' => array(
		'global_value' => '-1',
		'local_value' => '-1',
		'access' => 4,
		),
	'mysql.max_persistent' => array(
		'global_value' => '-1',
		'local_value' => '-1',
		'access' => 4,
		),
	'mysql.trace_mode' => array(
		'global_value' => '',
		'local_value' => '',
		'access' => 7,
		),
	'mysqli.allow_local_infile' => array(
		'global_value' => '1',
		'local_value' => '1',
		'access' => 4,
		),
	'mysqli.allow_persistent' => array(
		'global_value' => '1',
		'local_value' => '1',
		'access' => 4,
		),
	'mysqli.default_host' => array(
		'global_value' => NULL,
		'local_value' => NULL,
		'access' => 7,
		),
	'mysqli.default_port' => array(
		'global_value' => '3306',
		'local_value' => '3306',
		'access' => 7,
		),
	'mysqli.default_pw' => array(
		'global_value' => NULL,
		'local_value' => NULL,
		'access' => 7,
		),
	'mysqli.default_socket' => array(
		'global_value' => '/var/lib/mysql/mysql.sock',
		'local_value' => '/var/lib/mysql/mysql.sock',
		'access' => 7,
		),
	'mysqli.default_user' => array(
		'global_value' => NULL,
		'local_value' => NULL,
		'access' => 7,
		),
	'mysqli.max_links' => array(
		'global_value' => '-1',
		'local_value' => '-1',
		'access' => 4,
		),
	'mysqli.max_persistent' => array(
		'global_value' => '-1',
		'local_value' => '-1',
		'access' => 4,
		),
	'mysqli.reconnect' => array(
		'global_value' => '0',
		'local_value' => '0',
		'access' => 4,
		),
	'open_basedir' => array(
		'global_value' => NULL,
		'local_value' => NULL,
		'access' => 7,
		),
	'output_buffering' => array(
		'global_value' => '',
		'local_value' => '',
		'access' => 6,
		),
	'output_handler' => array(
		'global_value' => NULL,
		'local_value' => NULL,
		'access' => 6,
		),
	'pcre.backtrack_limit' => array(
		'global_value' => '1000000',
		'local_value' => '1000000',
		'access' => 7,
		),
	'pcre.recursion_limit' => array(
		'global_value' => '100000',
		'local_value' => '100000',
		'access' => 7,
		),
	'pdo_mysql.default_socket' => array(
		'global_value' => '/var/lib/mysql/mysql.sock',
		'local_value' => '/var/lib/mysql/mysql.sock',
		'access' => 4,
		),
	'phpd' => array(
		'global_value' => '1',
		'local_value' => '1',
		'access' => 7,
		),
	'phpd.t' => array(
		'global_value' => '1',
		'local_value' => '1',
		'access' => 7,
		),
	'post_max_size' => array(
		'global_value' => '8M',
		'local_value' => '8M',
		'access' => 6,
		),
	'precision' => array(
		'global_value' => '12',
		'local_value' => '12',
		'access' => 7,
		),
	'realpath_cache_size' => array(
		'global_value' => '16K',
		'local_value' => '16K',
		'access' => 4,
		),
	'realpath_cache_ttl' => array(
		'global_value' => '120',
		'local_value' => '120',
		'access' => 4,
		),
	'register_argc_argv' => array(
		'global_value' => '1',
		'local_value' => '1',
		'access' => 6,
		),
	'register_globals' => array(
		'global_value' => '',
		'local_value' => '',
		'access' => 6,
		),
	'register_long_arrays' => array(
		'global_value' => '1',
		'local_value' => '1',
		'access' => 6,
		),
	'report_memleaks' => array(
		'global_value' => '1',
		'local_value' => '1',
		'access' => 7,
		),
	'report_zend_debug' => array(
		'global_value' => '1',
		'local_value' => '1',
		'access' => 7,
		),
	'request_order' => array(
		'global_value' => NULL,
		'local_value' => NULL,
		'access' => 6,
		),
	'safe_mode' => array(
		'global_value' => '1',
		'local_value' => '1',
		'access' => 4,
		),
	'safe_mode_allowed_env_vars' => array(
		'global_value' => 'PHP_',
		'local_value' => 'PHP_',
		'access' => 4,
		),
	'safe_mode_exec_dir' => array(
		'global_value' => '/usr/local/php/bin',
		'local_value' => '/usr/local/php/bin',
		'access' => 4,
		),
	'safe_mode_gid' => array(
		'global_value' => '0',
		'local_value' => '0',
		'access' => 4,
		),
	'safe_mode_include_dir' => array(
		'global_value' => NULL,
		'local_value' => NULL,
		'access' => 4,
		),
	'safe_mode_protected_env_vars' => array(
		'global_value' => 'LD_LIBRARY_PATH',
		'local_value' => 'LD_LIBRARY_PATH',
		'access' => 4,
		),
	'sendmail_from' => array(
		'global_value' => NULL,
		'local_value' => NULL,
		'access' => 7,
		),
	'sendmail_path' => array(
		'global_value' => '/usr/sbin/sendmail -t -i',
		'local_value' => '/usr/sbin/sendmail -t -i',
		'access' => 4,
		),
	'serialize_precision' => array(
		'global_value' => '100',
		'local_value' => '100',
		'access' => 7,
		),
	'session.auto_start' => array(
		'global_value' => '0',
		'local_value' => '0',
		'access' => 7,
		),
	'session.bug_compat_42' => array(
		'global_value' => '1',
		'local_value' => '1',
		'access' => 7,
		),
	'session.bug_compat_warn' => array(
		'global_value' => '1',
		'local_value' => '1',
		'access' => 7,
		),
	'session.cache_expire' => array(
		'global_value' => '180',
		'local_value' => '180',
		'access' => 7,
		),
	'session.cache_limiter' => array(
		'global_value' => 'nocache',
		'local_value' => 'nocache',
		'access' => 7,
		),
	'session.cookie_domain' => array(
		'global_value' => '',
		'local_value' => '',
		'access' => 7,
		),
	'session.cookie_httponly' => array(
		'global_value' => '',
		'local_value' => '',
		'access' => 7,
		),
	'session.cookie_lifetime' => array(
		'global_value' => '0',
		'local_value' => '0',
		'access' => 7,
		),
	'session.cookie_path' => array(
		'global_value' => '/',
		'local_value' => '/',
		'access' => 7,
		),
	'session.cookie_secure' => array(
		'global_value' => '',
		'local_value' => '',
		'access' => 7,
		),
	'session.entropy_file' => array(
		'global_value' => '',
		'local_value' => '',
		'access' => 7,
		),
	'session.entropy_length' => array(
		'global_value' => '0',
		'local_value' => '0',
		'access' => 7,
		),
	'session.gc_divisor' => array(
		'global_value' => '100',
		'local_value' => '100',
		'access' => 7,
		),
	'session.gc_maxlifetime' => array(
		'global_value' => '1440',
		'local_value' => '1440',
		'access' => 7,
		),
	'session.gc_probability' => array(
		'global_value' => '1',
		'local_value' => '1',
		'access' => 7,
		),
	'session.hash_bits_per_character' => array(
		'global_value' => '4',
		'local_value' => '4',
		'access' => 7,
		),
	'session.hash_function' => array(
		'global_value' => '0',
		'local_value' => '0',
		'access' => 7,
		),
	'session.name' => array(
		'global_value' => 'PHPSESSID',
		'local_value' => 'PHPSESSID',
		'access' => 7,
		),
	'session.referer_check' => array(
		'global_value' => '',
		'local_value' => '',
		'access' => 7,
		),
	'session.save_handler' => array(
		'global_value' => 'files',
		'local_value' => 'files',
		'access' => 7,
		),
	'session.save_path' => array(
		'global_value' => '/tmp',
		'local_value' => '/tmp',
		'access' => 7,
		),
	'session.serialize_handler' => array(
		'global_value' => 'php',
		'local_value' => 'php',
		'access' => 7,
		),
	'session.use_cookies' => array(
		'global_value' => '1',
		'local_value' => '1',
		'access' => 7,
		),
	'session.use_only_cookies' => array(
		'global_value' => '1',
		'local_value' => '1',
		'access' => 7,
		),
	'session.use_trans_sid' => array(
		'global_value' => '0',
		'local_value' => '0',
		'access' => 7,
		),
	'short_open_tag' => array(
		'global_value' => '1',
		'local_value' => '1',
		'access' => 6,
		),
	'SMTP' => array(
		'global_value' => 'localhost',
		'local_value' => 'localhost',
		'access' => 7,
		),
	'smtp_port' => array(
		'global_value' => '25',
		'local_value' => '25',
		'access' => 7,
		),
	'soap.wsdl_cache' => array(
		'global_value' => '1',
		'local_value' => '1',
		'access' => 7,
		),
	'soap.wsdl_cache_dir' => array(
		'global_value' => '/tmp',
		'local_value' => '/tmp',
		'access' => 7,
		),
	'soap.wsdl_cache_enabled' => array(
		'global_value' => '1',
		'local_value' => '1',
		'access' => 7,
		),
	'soap.wsdl_cache_limit' => array(
		'global_value' => '5',
		'local_value' => '5',
		'access' => 7,
		),
	'soap.wsdl_cache_ttl' => array(
		'global_value' => '86400',
		'local_value' => '86400',
		'access' => 7,
		),
	'sql.safe_mode' => array(
		'global_value' => '',
		'local_value' => '',
		'access' => 4,
		),
	'sqlite.assoc_case' => array(
		'global_value' => '0',
		'local_value' => '0',
		'access' => 7,
		),
	'sqlite3.extension_dir' => array(
		'global_value' => NULL,
		'local_value' => NULL,
		'access' => 4,
		),
	'suhosin.apc_bug_workaround' => array(
		'global_value' => '0',
		'local_value' => '0',
		'access' => 4,
		),
	'suhosin.cookie.checkraddr' => array(
		'global_value' => '0',
		'local_value' => '0',
		'access' => 6,
		),
	'suhosin.cookie.cryptdocroot' => array(
		'global_value' => '1',
		'local_value' => '1',
		'access' => 6,
		),
	'suhosin.cookie.cryptkey' => array(
		'global_value' => '',
		'local_value' => '',
		'access' => 6,
		),
	'suhosin.cookie.cryptlist' => array(
		'global_value' => NULL,
		'local_value' => NULL,
		'access' => 6,
		),
	'suhosin.cookie.cryptraddr' => array(
		'global_value' => '0',
		'local_value' => '0',
		'access' => 6,
		),
	'suhosin.cookie.cryptua' => array(
		'global_value' => '1',
		'local_value' => '1',
		'access' => 6,
		),
	'suhosin.cookie.disallow_nul' => array(
		'global_value' => '1',
		'local_value' => '1',
		'access' => 6,
		),
	'suhosin.cookie.disallow_ws' => array(
		'global_value' => '1',
		'local_value' => '1',
		'access' => 6,
		),
	'suhosin.cookie.encrypt' => array(
		'global_value' => '0',
		'local_value' => '0',
		'access' => 6,
		),
	'suhosin.cookie.max_array_depth' => array(
		'global_value' => '50',
		'local_value' => '50',
		'access' => 6,
		),
	'suhosin.cookie.max_array_index_length' => array(
		'global_value' => '64',
		'local_value' => '64',
		'access' => 6,
		),
	'suhosin.cookie.max_name_length' => array(
		'global_value' => '64',
		'local_value' => '64',
		'access' => 6,
		),
	'suhosin.cookie.max_totalname_length' => array(
		'global_value' => '256',
		'local_value' => '256',
		'access' => 6,
		),
	'suhosin.cookie.max_value_length' => array(
		'global_value' => '10000',
		'local_value' => '10000',
		'access' => 6,
		),
	'suhosin.cookie.max_vars' => array(
		'global_value' => '100',
		'local_value' => '100',
		'access' => 6,
		),
	'suhosin.cookie.plainlist' => array(
		'global_value' => NULL,
		'local_value' => NULL,
		'access' => 6,
		),
	'suhosin.coredump' => array(
		'global_value' => '0',
		'local_value' => '0',
		'access' => 4,
		),
	'suhosin.disable.display_errors' => array(
		'global_value' => '0',
		'local_value' => '0',
		'access' => 4,
		),
	'suhosin.executor.allow_symlink' => array(
		'global_value' => '0',
		'local_value' => '0',
		'access' => 6,
		),
	'suhosin.executor.disable_emodifier' => array(
		'global_value' => '0',
		'local_value' => '0',
		'access' => 6,
		),
	'suhosin.executor.disable_eval' => array(
		'global_value' => '0',
		'local_value' => '0',
		'access' => 6,
		),
	'suhosin.executor.eval.blacklist' => array(
		'global_value' => NULL,
		'local_value' => NULL,
		'access' => 6,
		),
	'suhosin.executor.eval.whitelist' => array(
		'global_value' => NULL,
		'local_value' => NULL,
		'access' => 6,
		),
	'suhosin.executor.func.blacklist' => array(
		'global_value' => NULL,
		'local_value' => NULL,
		'access' => 6,
		),
	'suhosin.executor.func.whitelist' => array(
		'global_value' => NULL,
		'local_value' => NULL,
		'access' => 6,
		),
	'suhosin.executor.include.allow_writable_files' => array(
		'global_value' => '1',
		'local_value' => '1',
		'access' => 6,
		),
	'suhosin.executor.include.blacklist' => array(
		'global_value' => NULL,
		'local_value' => NULL,
		'access' => 6,
		),
	'suhosin.executor.include.max_traversal' => array(
		'global_value' => '0',
		'local_value' => '0',
		'access' => 6,
		),
	'suhosin.executor.include.whitelist' => array(
		'global_value' => NULL,
		'local_value' => NULL,
		'access' => 6,
		),
	'suhosin.executor.max_depth' => array(
		'global_value' => '0',
		'local_value' => '0',
		'access' => 6,
		),
	'suhosin.filter.action' => array(
		'global_value' => NULL,
		'local_value' => NULL,
		'access' => 6,
		),
	'suhosin.get.disallow_nul' => array(
		'global_value' => '1',
		'local_value' => '1',
		'access' => 6,
		),
	'suhosin.get.disallow_ws' => array(
		'global_value' => '0',
		'local_value' => '0',
		'access' => 6,
		),
	'suhosin.get.max_array_depth' => array(
		'global_value' => '50',
		'local_value' => '50',
		'access' => 6,
		),
	'suhosin.get.max_array_index_length' => array(
		'global_value' => '64',
		'local_value' => '64',
		'access' => 6,
		),
	'suhosin.get.max_name_length' => array(
		'global_value' => '64',
		'local_value' => '64',
		'access' => 6,
		),
	'suhosin.get.max_totalname_length' => array(
		'global_value' => '256',
		'local_value' => '256',
		'access' => 6,
		),
	'suhosin.get.max_value_length' => array(
		'global_value' => '512',
		'local_value' => '512',
		'access' => 6,
		),
	'suhosin.get.max_vars' => array(
		'global_value' => '100',
		'local_value' => '100',
		'access' => 6,
		),
	'suhosin.log.file' => array(
		'global_value' => '0',
		'local_value' => '0',
		'access' => 6,
		),
	'suhosin.log.file.name' => array(
		'global_value' => NULL,
		'local_value' => NULL,
		'access' => 6,
		),
	'suhosin.log.phpscript' => array(
		'global_value' => '0',
		'local_value' => '0',
		'access' => 6,
		),
	'suhosin.log.phpscript.is_safe' => array(
		'global_value' => '0',
		'local_value' => '0',
		'access' => 6,
		),
	'suhosin.log.phpscript.name' => array(
		'global_value' => NULL,
		'local_value' => NULL,
		'access' => 6,
		),
	'suhosin.log.sapi' => array(
		'global_value' => '0',
		'local_value' => '0',
		'access' => 6,
		),
	'suhosin.log.script' => array(
		'global_value' => '0',
		'local_value' => '0',
		'access' => 6,
		),
	'suhosin.log.script.name' => array(
		'global_value' => NULL,
		'local_value' => NULL,
		'access' => 6,
		),
	'suhosin.log.syslog' => array(
		'global_value' => NULL,
		'local_value' => NULL,
		'access' => 6,
		),
	'suhosin.log.syslog.facility' => array(
		'global_value' => NULL,
		'local_value' => NULL,
		'access' => 6,
		),
	'suhosin.log.syslog.priority' => array(
		'global_value' => NULL,
		'local_value' => NULL,
		'access' => 6,
		),
	'suhosin.log.use-x-forwarded-for' => array(
		'global_value' => '0',
		'local_value' => '0',
		'access' => 6,
		),
	'suhosin.mail.protect' => array(
		'global_value' => '0',
		'local_value' => '0',
		'access' => 6,
		),
	'suhosin.memory_limit' => array(
		'global_value' => '0',
		'local_value' => '0',
		'access' => 6,
		),
	'suhosin.mt_srand.ignore' => array(
		'global_value' => '1',
		'local_value' => '1',
		'access' => 6,
		),
	'suhosin.multiheader' => array(
		'global_value' => '0',
		'local_value' => '0',
		'access' => 6,
		),
	'suhosin.perdir' => array(
		'global_value' => '0',
		'local_value' => '0',
		'access' => 4,
		),
	'suhosin.post.disallow_nul' => array(
		'global_value' => '1',
		'local_value' => '1',
		'access' => 6,
		),
	'suhosin.post.disallow_ws' => array(
		'global_value' => '0',
		'local_value' => '0',
		'access' => 6,
		),
	'suhosin.post.max_array_depth' => array(
		'global_value' => '50',
		'local_value' => '50',
		'access' => 6,
		),
	'suhosin.post.max_array_index_length' => array(
		'global_value' => '64',
		'local_value' => '64',
		'access' => 6,
		),
	'suhosin.post.max_name_length' => array(
		'global_value' => '64',
		'local_value' => '64',
		'access' => 6,
		),
	'suhosin.post.max_totalname_length' => array(
		'global_value' => '256',
		'local_value' => '256',
		'access' => 6,
		),
	'suhosin.post.max_value_length' => array(
		'global_value' => '1000000',
		'local_value' => '1000000',
		'access' => 6,
		),
	'suhosin.post.max_vars' => array(
		'global_value' => '1000',
		'local_value' => '1000',
		'access' => 6,
		),
	'suhosin.protectkey' => array(
		'global_value' => '1',
		'local_value' => '1',
		'access' => 4,
		),
	'suhosin.request.disallow_nul' => array(
		'global_value' => '1',
		'local_value' => '1',
		'access' => 6,
		),
	'suhosin.request.disallow_ws' => array(
		'global_value' => '0',
		'local_value' => '0',
		'access' => 6,
		),
	'suhosin.request.max_array_depth' => array(
		'global_value' => '50',
		'local_value' => '50',
		'access' => 6,
		),
	'suhosin.request.max_array_index_length' => array(
		'global_value' => '64',
		'local_value' => '64',
		'access' => 6,
		),
	'suhosin.request.max_totalname_length' => array(
		'global_value' => '256',
		'local_value' => '256',
		'access' => 6,
		),
	'suhosin.request.max_value_length' => array(
		'global_value' => '1000000',
		'local_value' => '1000000',
		'access' => 6,
		),
	'suhosin.request.max_varname_length' => array(
		'global_value' => '64',
		'local_value' => '64',
		'access' => 6,
		),
	'suhosin.request.max_vars' => array(
		'global_value' => '1000',
		'local_value' => '1000',
		'access' => 6,
		),
	'suhosin.server.encode' => array(
		'global_value' => '1',
		'local_value' => '1',
		'access' => 4,
		),
	'suhosin.server.strip' => array(
		'global_value' => '1',
		'local_value' => '1',
		'access' => 4,
		),
	'suhosin.session.checkraddr' => array(
		'global_value' => '0',
		'local_value' => '0',
		'access' => 6,
		),
	'suhosin.session.cryptdocroot' => array(
		'global_value' => '1',
		'local_value' => '1',
		'access' => 6,
		),
	'suhosin.session.cryptkey' => array(
		'global_value' => '',
		'local_value' => '',
		'access' => 7,
		),
	'suhosin.session.cryptraddr' => array(
		'global_value' => '0',
		'local_value' => '0',
		'access' => 6,
		),
	'suhosin.session.cryptua' => array(
		'global_value' => '0',
		'local_value' => '0',
		'access' => 6,
		),
	'suhosin.session.encrypt' => array(
		'global_value' => '1',
		'local_value' => '1',
		'access' => 6,
		),
	'suhosin.session.max_id_length' => array(
		'global_value' => '128',
		'local_value' => '128',
		'access' => 6,
		),
	'suhosin.simulation' => array(
		'global_value' => '0',
		'local_value' => '0',
		'access' => 6,
		),
	'suhosin.sql.bailout_on_error' => array(
		'global_value' => '0',
		'local_value' => '0',
		'access' => 6,
		),
	'suhosin.sql.comment' => array(
		'global_value' => '0',
		'local_value' => '0',
		'access' => 6,
		),
	'suhosin.sql.multiselect' => array(
		'global_value' => '0',
		'local_value' => '0',
		'access' => 6,
		),
	'suhosin.sql.opencomment' => array(
		'global_value' => '0',
		'local_value' => '0',
		'access' => 6,
		),
	'suhosin.sql.union' => array(
		'global_value' => '0',
		'local_value' => '0',
		'access' => 6,
		),
	'suhosin.sql.user_postfix' => array(
		'global_value' => NULL,
		'local_value' => NULL,
		'access' => 6,
		),
	'suhosin.sql.user_prefix' => array(
		'global_value' => NULL,
		'local_value' => NULL,
		'access' => 6,
		),
	'suhosin.srand.ignore' => array(
		'global_value' => '1',
		'local_value' => '1',
		'access' => 6,
		),
	'suhosin.stealth' => array(
		'global_value' => '1',
		'local_value' => '1',
		'access' => 4,
		),
	'suhosin.upload.disallow_binary' => array(
		'global_value' => '0',
		'local_value' => '0',
		'access' => 6,
		),
	'suhosin.upload.disallow_elf' => array(
		'global_value' => '1',
		'local_value' => '1',
		'access' => 6,
		),
	'suhosin.upload.max_uploads' => array(
		'global_value' => '25',
		'local_value' => '25',
		'access' => 6,
		),
	'suhosin.upload.remove_binary' => array(
		'global_value' => '0',
		'local_value' => '0',
		'access' => 6,
		),
	'suhosin.upload.verification_script' => array(
		'global_value' => NULL,
		'local_value' => NULL,
		'access' => 6,
		),
	'track_errors' => array(
		'global_value' => '',
		'local_value' => '',
		'access' => 7,
		),
	'unserialize_callback_func' => array(
		'global_value' => '',
		'local_value' => '',
		'access' => 7,
		),
	'upload_max_filesize' => array(
		'global_value' => '12M',
		'local_value' => '12M',
		'access' => 6,
		),
	'upload_tmp_dir' => array(
		'global_value' => NULL,
		'local_value' => NULL,
		'access' => 4,
		),
	'url_rewriter.tags' => array(
		'global_value' => 'a=href,area=href,frame=src,input=src,form=,fieldset=',
		'local_value' => 'a=href,area=href,frame=src,input=src,form=,fieldset=',
		'access' => 7,
		),
	'user_agent' => array(
		'global_value' => NULL,
		'local_value' => NULL,
		'access' => 7,
		),
	'user_dir' => array(
		'global_value' => '',
		'local_value' => '',
		'access' => 4,
		),
	'user_ini.cache_ttl' => array(
		'global_value' => '300',
		'local_value' => '300',
		'access' => 4,
		),
	'user_ini.filename' => array(
		'global_value' => '.user.ini',
		'local_value' => '.user.ini',
		'access' => 4,
		),
	'variables_order' => array(
		'global_value' => 'EGPCS',
		'local_value' => 'EGPCS',
		'access' => 6,
		),
	'xmlrpc_error_number' => array(
		'global_value' => '0',
		'local_value' => '0',
		'access' => 7,
		),
	'xmlrpc_errors' => array(
		'global_value' => '0',
		'local_value' => '0',
		'access' => 4,
		),
	'xsl.security_prefs' => array(
		'global_value' => '44',
		'local_value' => '44',
		'access' => 7,
		),
	'y2k_compliance' => array(
		'global_value' => '1',
		'local_value' => '1',
		'access' => 7,
		),
	'zend.enable_gc' => array(
		'global_value' => '1',
		'local_value' => '1',
		'access' => 7,
		),
	'zlib.output_compression' => array(
		'global_value' => '',
		'local_value' => '',
		'access' => 7,
		),
	'zlib.output_compression_level' => array(
		'global_value' => '-1',
		'local_value' => '-1',
		'access' => 7,
		),
	'zlib.output_handler' => array(
		'global_value' => '',
		'local_value' => '',
		'access' => 7,
		),
	);
