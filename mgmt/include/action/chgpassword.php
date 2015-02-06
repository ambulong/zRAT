<?php
if (! defined ( "Z_ENTRANCE" )) {
	header ( "HTTP/1.0 404 Not Found" );
	exit ();
}

$user = new zUser();

$username = isset ( $_POST ['username'] ) ? trim($_POST ['username']) : "";
$password = isset ( $_POST ['password'] ) ? trim($_POST ['password']) : "";
$newpassword = isset ( $_POST ['newpassword'] ) ? trim($_POST ['newpassword']) : "";
$token = isset ( $_POST ['token'] ) ? trim($_POST ['token']) : "";

if($username == "" || $password == "" || $newpassword == "" || $token == "") {
	resp(0, array("token" => ""));
}

if (!$user->validatePassword($username, $password) || !$user->validateToken($username, $token)) {
	resp(0, array("token" => ""));
}

if($user->updatePassword($username, $newpassword))
	resp(1, array("token" => ""));
else 
	resp(0, array("token" => ""));