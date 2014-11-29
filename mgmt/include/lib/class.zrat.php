<?php
/**
 * 初始化程序类
 * @author Ambulong
 *
 */
class zRAT {
	private $DBH = NULL; // OPD数据连接句柄
	public function __construct() {
	}
	/**
	 * Initialize the environment and template.
	 */
	public function init() {
		$this->initPDO ();
		$this->initRouter ();
	}
	
	/**
	 * Connect to MySQL server and set the environment.
	 */
	public function initPDO() {
		try {
			$this->DBH = new zPDO ( "mysql:host=" . Z_DB_HOST . ";dbname=" . Z_DB_NAME . ";charset=" . Z_DB_CHARSET, Z_DB_USER, Z_DB_PASSWORD, array (
					PDO::ATTR_PERSISTENT => true 
			) );
			$this->DBH->setAttribute ( PDO::ATTR_EMULATE_PREPARES, false );
			$this->DBH->setAttribute ( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
			$this->DBH->setAttribute ( PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING );
			$GLOBALS ["z_dbh"] = $this->DBH;
		} catch ( Exception $e ) {
			header ( 'Content-Type: text/plain; charset=utf-8' );
			die ( "Error!: " . $e->getMessage () );
		}
	}
	
	/**
	 * Initialize the router.
	 */
	public function initRouter() {
		$router = new zRouter ();
		$router->init ();
	}
}