<?php
if (! defined ( "Z_ENTRANCE" )) {
	header ( "HTTP/1.0 404 Not Found" );
	exit ();
}

$username = isset ( $_POST ['username'] ) ? trim($_POST ['username']) : "";
$password = isset ( $_POST ['password'] ) ? trim($_POST ['password']) : "";

if($username == "" || $password == "") {
	resp(0, array("token" => ""));
}

$user = new zUser();

if (! $user->validatePassword($username, $password)) {
	
}
