<?php
/**
 * 命令相关操作
 * 
 */
class zCommand {
	private $dbh = NULL;
	public function __construct() {
		$this->dbh = $GLOBALS ['z_dbh'];
	}
	
	/*
	 * 获取未执行命令列队里的第一条
	 */
	public function getCommand($hid) {
		global $table_prefix;
		$id = intval ( $hid );
		try {
			$sth = $this->dbh->prepare ( "SELECT * FROM {$table_prefix}commands WHERE `hid` = :hid AND `status` = 0 LIMIT 1" );
			$sth->bindParam ( ':hid', $id );
			$sth->execute ();
			$result = $sth->fetch ( PDO::FETCH_ASSOC );
			if (count ( $result ) <= 0) {
				$result = array ();
			}
			return $result;
		} catch ( PDOExecption $e ) {
			echo "<br>Error: " . $e->getMessage ();
		}
	}
	public function updateCommand($value, $id) {
		global $table_prefix;
		$value = intval ( $value );
		$id = intval ( $id );
		$mgmt_time = get_time ();
		if (! $this->isExistID ( $id )) {
			return FALSE;
		}
		try {
			$sth = $this->dbh->prepare ( "UPDATE {$table_prefix}commands SET `status`= :status, `mgmt_time` = :mgmt_time WHERE `id` = :id" );
			$sth->bindParam ( ':status', $value );
			$sth->bindParam ( ':mgmt_time', $mgmt_time );
			$sth->bindParam ( ':id', $id );
			$sth->execute ();
			if (! ($sth->rowCount () > 0))
				return FALSE;
			else
				return TRUE;
		} catch ( PDOExecption $e ) {
			echo "<br>Error: " . $e->getMessage ();
		}
	}
	public function timeoutCommands() {
		global $table_prefix;
		try {
			$sth = $this->dbh->prepare ( "UPDATE {$table_prefix}commands SET `status`= -1 WHERE ABS(UNIX_TIMESTAMP(now()) - UNIX_TIMESTAMP(`lastestRequest`)) > 240" );
			$sth->execute ();
			if (! ($sth->rowCount () > 0))
				return FALSE;
			else
				return TRUE;
		} catch ( PDOExecption $e ) {
			echo "<br>Error: " . $e->getMessage ();
		}
	}
	public function isExistID($id) {
		$id = intval ( $id );
		global $table_prefix;
		try {
			$sth = $this->dbh->prepare ( "SELECT count(*) FROM {$table_prefix}commands WHERE `id` = :id " );
			$sth->bindParam ( ':id', $id );
			$sth->execute ();
			$row = $sth->fetch ();
			if ($row [0] > 0) {
				return TRUE;
			} else {
				return FALSE;
			}
		} catch ( PDOExecption $e ) {
			echo "<br>Error: " . $e->getMessage ();
		}
	}
	public function isExistSID($sid) {
		global $table_prefix;
		$sid = trim ( $sid );
		try {
			$sth = $this->dbh->prepare ( "SELECT count(*) FROM {$table_prefix}commands WHERE `sid` = :sid" );
			$sth->bindParam ( ':sid', $sid );
			$sth->execute ();
			$row = $sth->fetch ();
			if ($row [0] > 0) {
				return TRUE;
			} else {
				return FALSE;
			}
		} catch ( PDOExecption $e ) {
			echo "<br>Error: " . $e->getMessage ();
		}
	}
	public function getID($sid) {
		global $table_prefix;
		$sid = trim ( $sid );
		if (! $this->isExistSID ( $sid )) {
			return FALSE;
		}
		try {
			$sth = $this->dbh->prepare ( "SELECT * FROM {$table_prefix}commands WHERE `sid` = :sid " );
			$sth->bindParam ( ':sid', $sid );
			$sth->execute ();
			$result = $sth->fetch ( PDO::FETCH_ASSOC );
			return $result ["id"];
		} catch ( PDOExecption $e ) {
			echo "<br>Error: " . $e->getMessage ();
		}
	}
}