<?php
if (! defined ( "Z_ENTRANCE" )) {
	header ( "HTTP/1.0 404 Not Found" );
	exit ();
}

$hosts = new zHosts ();
$user = new zUser ();

$username = isset ( $_POST ['username'] ) ? trim ( $_POST ['username'] ) : "";
$token = isset ( $_POST ['token'] ) ? trim ( $_POST ['token'] ) : "";
$offset = isset ( $_REQUEST ['offset'] ) ? intval ( $_REQUEST ['offset'] ) : 0;
$rows = isset ( $_REQUEST ['rows'] ) ? intval ( $_REQUEST ['rows'] ) : 13;

if ($username == "" || $token == "") {
	resp ( 0, "" );
}

if (! $user->validateToken ( $username, $token )) {
	resp ( 0, "" );
}

$list = $hosts->getList($offset, $rows);
if(count($list) <= 0)
	resp ( 1, "" );
resp ( 1, $list );