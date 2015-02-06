<?php
if (! defined ( "Z_ENTRANCE" )) {
	header ( "HTTP/1.0 404 Not Found" );
	exit ();
}

/**
 * Absolute path to zRAT directory
 */
define ( 'Z_ABSPATH', dirname ( __FILE__ ) . '/' );

define ( 'Z_INC', 'include/' );

/**
 * Require the configure file
*/
require_once (Z_ABSPATH . 'config.php');

/**
 * Loads the environment and template
*/
require_once (Z_ABSPATH . 'load.php');
