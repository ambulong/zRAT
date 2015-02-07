<?php
/**
 * 命令操作
 * 
 */
class zCommand {
	private $dbh = NULL;
	public function __construct() {
		$this->dbh = $GLOBALS ['z_dbh'];
	}
	public function add($hid, $command, $data, $timestamp) {
		global $table_prefix;
		$data = json_encode ( $data );
		$hid = intval ( $hid );
		$command = trim ( $command );
		$timestamp = trim ( $timestamp );
		try {
			$sth = $this->dbh->prepare ( "INSERT INTO {$table_prefix}commands(`hid`,data`,`command`,`timestamp`) VALUES(:hid, :data, :command, :timestamp)" );
			$sth->bindParam ( ':hid', $hid );
			$sth->bindParam ( ':data', $data );
			$sth->bindParam ( ':command', $command );
			$sth->bindParam ( ':timestamp', $timestamp );
			$sth->execute ();
			if (! ($sth->rowCount () > 0)) {
				return FALSE;
			}
			return $this->dbh->lastInsertId ();
		} catch ( PDOExecption $e ) {
			echo "<br>Error: " . $e->getMessage ();
		}
	}
	public function getResp($cid) {
		global $table_prefix;
		$cid = intval ( $cid );
		if (! $this->isExistResp ( $cid )) {
			return FALSE;
		}
		try {
			$sth = $this->dbh->prepare ( "SELECT * FROM {$table_prefix}resps WHERE `cid` = :cid " );
			$sth->bindParam ( ':cid', $cid );
			$sth->execute ();
			$result = $sth->fetch ( PDO::FETCH_ASSOC );
			return $result;
		} catch ( PDOExecption $e ) {
			echo "<br>Error: " . $e->getMessage ();
		}
	}
	public function getCommandList($offset = 0, $rows = 13, $hid = 0, $status = null) {
		global $table_prefix;
		$offset = intval ( $offset );
		$rows = intval ( $rows );
		$hid = intval ( $hid );
		
		try {
			if ($hid == 0 && $status == null) {
				$sth = $this->dbh->prepare ( "SELECT * FROM {$table_prefix}commands limit {$offset},{$rows} " );
			} elseif ($hid == 0 && $status != null) {
				$sth = $this->dbh->prepare ( "SELECT * FROM {$table_prefix}commands WHERE `hid` = :hid limit {$offset},{$rows} " );
				$sth->bindParam ( ':hid', $hid );
			} elseif ($hid != 0 && $status == null) {
				$sth = $this->dbh->prepare ( "SELECT * FROM {$table_prefix}commands WHERE `status` = :status limit {$offset},{$rows} " );
				$sth->bindParam ( ':status', $status );
			} else {
				$sth = $this->dbh->prepare ( "SELECT * FROM {$table_prefix}commands WHERE `status` = :status AND `hid` = :hid limit {$offset},{$rows} " );
				$sth->bindParam ( ':status', $status );
				$sth->bindParam ( ':hid', $hid );
			}
			$sth->execute ();
			$result = $sth->fetch ( PDO::FETCH_ASSOC );
			return $result;
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
	public function isExistResp($cid) {
		$cid = intval ( $cid );
		global $table_prefix;
		try {
			$sth = $this->dbh->prepare ( "SELECT count(*) FROM {$table_prefix}resps WHERE `cid` = :cid " );
			$sth->bindParam ( ':cid', $cid );
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
	public function getDetail($id) {
		global $table_prefix;
		$id = intval ( $id );
		if (! $this->isExistID ( $id )) {
			return FALSE;
		}
		try {
			$sth = $this->dbh->prepare ( "SELECT * FROM {$table_prefix}commands WHERE `id` = :id " );
			$sth->bindParam ( ':id', $id );
			$sth->execute ();
			$result = $sth->fetch ( PDO::FETCH_ASSOC );
			return $result;
		} catch ( PDOExecption $e ) {
			echo "<br>Error: " . $e->getMessage ();
		}
	}
}