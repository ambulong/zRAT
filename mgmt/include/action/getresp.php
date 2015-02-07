<?php
if (! defined ( "Z_ENTRANCE" )) {
	header ( "HTTP/1.0 404 Not Found" );
	exit ();
}

$commands = new zCommand();
$user = new zUser ();

$username = isset ( $_POST ['username'] ) ? trim ( $_POST ['username'] ) : "";
$token = isset ( $_POST ['token'] ) ? trim ( $_POST ['token'] ) : "";
$cid = isset ( $_REQUEST ['cid'] ) ? intval ( $_REQUEST ['cid'] ) : 0;

if ($username == "" || $token == "") {
	resp ( 0, "" );
}

if (! $user->validateToken ( $username, $token )) {
	resp ( 0, "" );
}

$resp = $commands->getResp($cid);
if($resp)
	resp ( 1, $resp );
else 
	resp ( 0, "" );