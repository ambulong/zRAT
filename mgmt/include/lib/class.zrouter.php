<?php
/**
 * 程序路由处理类
 * @author ambulong
 *
 */
class zRouter {
	private $FILE = "";
	private $MODULE = array (
			"login",
			"logout",
			"chgpassword",
			"gethosts",
			"gethost",
			"updatehost",
			"getcommands",
			"addcommands",
			"getresp" 
	);
	
	/**
	 * 获取并简单处理URL
	 */
	public function __construct() {
		$this->FILE = isset($_GET['action'])?$_GET['action']:"";
	}
	
	/**
	 * 初始化路由
	 */
	public function init() {
		if (!in_array ( $this->FILE, $this->MODULE )) {
			header ( "HTTP/1.0 404 Not Found" );
			exit ();
		}
		
		$filename = Z_ABSPATH . Z_INC . "action/" . $this->FILE . ".php";
		
		if (is_readable ( $filename )) {
			require_once ($filename);
		} else {
			header ( 'Content-Type: text/plain; charset=utf-8' );
			die ( "ERROR: File  ./" . Z_INC . "action/" . $this->FILE . ".php" . " is unreadable." );
		}
	}
}