<?php
/**
 * Is session started
 * @return bool
 */
function is_session_started() {
	if (php_sapi_name () !== 'cli') {
		if (version_compare ( phpversion (), '5.4.0', '>=' )) {
			return session_status () === PHP_SESSION_ACTIVE ? TRUE : FALSE;
		} else {
			return session_id () === '' ? FALSE : TRUE;
		}
	}
	return FALSE;
}
/**
 * Get IP adress
 *
 * @return String
 */
function get_ip() {
	return isset ($_SERVER ['REMOTE_ADDR'])?$_SERVER ['REMOTE_ADDR']:"";
}
/**
 * Get current time
 *
 * @return String
 */
function get_time() {
	return date ( 'Y-m-d H:i:s' );
}
/**
 * Get date
 *
 * @return String
 */
function get_date() {
	return date ( 'Y-m-d' );
}
/**
 * Is str in formats like json
 *
 * @param String $string
 * @return bool
 */
function is_json($string) {
	json_decode ( $string, true );
	return (json_last_error () == JSON_ERROR_NONE);
}
/**
 * Htmlspecialchars
 *
 * @param String $string
 * @return array or string
 */
function esc_html($string) {
	if (is_array ( $string )) {
		foreach ( $string as $key => $val ) {
			$string [$key] = func_htmlhtmlspecialchars ( $val );
		}
	} else {
		$string = htmlspecialchars ( $string );
	}
	return $string;
}
/**
 * Get current page URL
 *
 * @return string
 */
function get_url() {
	$ssl = (! empty ( $_SERVER ['HTTPS'] ) && $_SERVER ['HTTPS'] == 'on') ? true : false;
	$sp = strtolower ( $_SERVER ['SERVER_PROTOCOL'] );
	$protocol = substr ( $sp, 0, strpos ( $sp, '/' ) ) . (($ssl) ? 's' : '');
	$port = $_SERVER ['SERVER_PORT'];
	$port = ((! $ssl && $port == '80') || ($ssl && $port == '443')) ? '' : ':' . $port;
	$host = isset ( $_SERVER ['HTTP_X_FORWARDED_HOST'] ) ? $_SERVER ['HTTP_X_FORWARDED_HOST'] : isset ( $_SERVER ['HTTP_HOST'] ) ? $_SERVER ['HTTP_HOST'] : $_SERVER ['SERVER_NAME'];
	return $protocol . '://' . $host . $port . $_SERVER ['REQUEST_URI'];
}
/**
 * 获取域名
 *
 * @param unknown $referer
 * @return unknown
 */
function get_url_domain($referer) {
	preg_match ( "/^(http:\/\/)?([^\/]+)/i", $referer, $matches );
	$domain = isset ( $matches [2] ) ? $matches [2] : "unknow";
	return $domain;
}
/**
 * URL跳转
 *
 * @param unknown $url
 */
function gotourl($url) {
	header("Location: {$url}");
}
/**
 * 获取浏览用户信息，HTTP头，IP等
 */
function get_user_info() {
	return array(
			"IP" => get_ip (),
			"TIME" => get_time(),
			"HTTP_ACCEPT"	=>	isset ( $_SERVER ["HTTP_ACCEPT"] ) ? $_SERVER ["HTTP_ACCEPT"] : "",
			"HTTP_REFERER"	=>	isset ( $_SERVER ["HTTP_REFERER"] ) ? $_SERVER ["HTTP_REFERER"] : "",
			"HTTP_USER_AGENT"	=>	isset ( $_SERVER ["HTTP_USER_AGENT"] ) ? $_SERVER ["HTTP_USER_AGENT"] : ""
	);
}
function get_salt($length = 8) {
	$chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
	$salt = '';
	for($i = 0; $i < $length; $i ++) {
		$salt .= $chars [mt_rand ( 0, strlen ( $chars ) - 1 )];
	}
	return $salt;
}

function resp($status, $id = "", $command = "", $data = ""){
	header ( 'Content-Type: text/json; charset=utf-8' );
	$resp = array(
			"status" => $status,
			"id"	=> $id,
			"command"	=> "{$command}",
			"data"	=> "{$data}"
	);
	echo json_encode($resp);
}

function getTimestamp() {
	return time();
}

function encryptAES($data, $key) {
	
}

function decryptAES($data, $key) {
	return $data;
}