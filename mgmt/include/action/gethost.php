<?php
if (! defined ( "Z_ENTRANCE" )) {
	header ( "HTTP/1.0 404 Not Found" );
	exit ();
}

$host = new zHost ();
$user = new zUser ();

$username = isset ( $_POST ['username'] ) ? trim ( $_POST ['username'] ) : "";
$token = isset ( $_POST ['token'] ) ? trim ( $_POST ['token'] ) : "";
$id = isset ( $_REQUEST ['id'] ) ? intval ( $_REQUEST ['id'] ) : 0;

if ($username == "" || $token == "" || $id == 0) {
	resp ( 0, "" );
}

if (! $user->validateToken ( $username, $token )) {
	resp ( 0, "" );
}

$detail = $host->getDetail($id);
if(!$detail)
	resp ( 0, "" );
resp ( 1, $detail );