<?php
/**
 * 主机列表操作
 * 
 */
class zHosts {
	
	private $dbh = NULL;
	
	public function __construct() {
		$this->dbh = $GLOBALS ['z_dbh'];
	}
	
	public function getList($offset, $rows) {
		global $table_prefix;
		$offset = intval ( $offset );
		$rows = intval($rows);
		try {
			$sth = $this->dbh->prepare ( "SELECT * FROM {$table_prefix}hosts limit {$offset},{$rows} " );
			$sth->execute ();
			$result = $sth->fetch ( PDO::FETCH_ASSOC );
			return $result;
		} catch ( PDOExecption $e ) {
			echo "<br>Error: " . $e->getMessage ();
		}
	}
	}