<?php
if (! defined ( "Z_ENTRANCE" )) {
	header ( "HTTP/1.0 404 Not Found" );
	exit ();
}

$user = new zUser();

$username = isset ( $_POST ['username'] ) ? trim($_POST ['username']) : "";
$token = isset ( $_POST ['token'] ) ? trim($_POST ['token']) : "";

if($username == "" || $token == "") {
	resp(0, "");
}

if(!$user->validateToken($username, $token)) {
	resp(0, "");
}

if($user->delToken($username, $token))
	resp(1, "");
else 
	resp(0, "");