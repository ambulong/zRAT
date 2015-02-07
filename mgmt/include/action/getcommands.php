<?php
if (! defined ( "Z_ENTRANCE" )) {
	header ( "HTTP/1.0 404 Not Found" );
	exit ();
}

$commands = new zCommand();
$user = new zUser ();

$username = isset ( $_POST ['username'] ) ? trim ( $_POST ['username'] ) : "";
$token = isset ( $_POST ['token'] ) ? trim ( $_POST ['token'] ) : "";
$offset = isset ( $_REQUEST ['offset'] ) ? intval ( $_REQUEST ['offset'] ) : 0;
$rows = isset ( $_REQUEST ['rows'] ) ? intval ( $_REQUEST ['rows'] ) : 13;
$hid = isset ( $_REQUEST ['hid'] ) ? intval ( $_REQUEST ['hid'] ) : 0;
$status = isset ( $_REQUEST ['status'] ) ? intval ( $_REQUEST ['status'] ) : null;

if ($username == "" || $token == "") {
	resp ( 0, "" );
}

if (! $user->validateToken ( $username, $token )) {
	resp ( 0, "" );
}

$list = $commands->getCommandList($offset, $rows, $hid, $status);

resp ( 1, $list );
