<?php
/**
 * Check the required php version and the MySQL extension
 * 
 * @access private
 */
function z_check_php_mysql() {
	$required_php_version = "5.3.9";
	$php_version = phpversion ();
	if (version_compare ( $required_php_version, $php_version ) > 0) {
		header ( 'Content-Type: text/plain; charset=utf-8' );
		die ( "Your server is running PHP version {$php_version} but zRAT requires at least {$required_php_version}." );
	}
	
	if (! extension_loaded ( 'PDO' )) {
		header ( 'Content-Type: text/plain; charset=utf-8' );
		die ( "Your PHP installation appears to be missing the MySQL extension which is required by zRAT." );
	}
}
/**
 * Set PHP error reporting based on debug settings.
 *
 * @access private
 */
function z_debug_mode() {
	if (Z_DEBUG) {
		error_reporting ( E_ALL );
	} else {
		error_reporting ( E_CORE_ERROR | E_CORE_WARNING | E_COMPILE_ERROR | E_ERROR | E_WARNING | E_PARSE | E_USER_ERROR | E_USER_WARNING | E_RECOVERABLE_ERROR );
	}
}