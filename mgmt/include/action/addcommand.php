<?php
if (! defined ( "Z_ENTRANCE" )) {
	header ( "HTTP/1.0 404 Not Found" );
	exit ();
}

$commands = new zCommand();
$user = new zUser ();

$username = isset ( $_POST ['username'] ) ? trim ( $_POST ['username'] ) : "";
$token = isset ( $_POST ['token'] ) ? trim ( $_POST ['token'] ) : "";
$hid = isset ( $_REQUEST ['hid'] ) ? intval ( $_REQUEST ['hid'] ) : 0;
$command = isset ( $_POST ['command'] ) ? trim ( $_POST ['command'] ) : "";
$data = isset ( $_POST ['data'] ) ? trim ( $_POST ['data'] ) : "";
$timestamp = isset ( $_POST ['timestamp'] ) ? trim ( $_POST ['timestamp'] ) : "";

if ($username == "" || $token == "") {
	resp ( 0, "" );
}

if (! $user->validateToken ( $username, $token )) {
	resp ( 0, "" );
}

if(is_json($data))
	$data = json_decode($data, true);

if($commands->add($hid, $command, $data, $timestamp))
	resp ( 1, "" );
else 
	resp ( 0, "" );