<?php
if (! defined ( "Z_ENTRANCE" )) {
	header ( "HTTP/1.0 404 Not Found" );
	exit ();
}

require_once (Z_ABSPATH . Z_INC . 'envchk.php');
require_once (Z_ABSPATH . Z_INC . 'functions.php');
require_once (Z_ABSPATH . Z_INC . 'autoload.php');
require_once (Z_ABSPATH . 'settings.php');
z_debug_mode ();
z_check_php_mysql ();
date_default_timezone_set ( Z_TIMEZONE );