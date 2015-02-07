<?php
/**
 * 主机操作
 * 
 */
class zHost {
	
	private $dbh = NULL;
	
	public function __construct() {
		$this->dbh = $GLOBALS ['z_dbh'];
	}
	
	public function isExistID($id) {
		$id = intval ( $id );
		global $table_prefix;
		try {
			$sth = $this->dbh->prepare ( "SELECT count(*) FROM {$table_prefix}hosts WHERE `id` = :id " );
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
	
	public function getDetail($id) {
		global $table_prefix;
		$id = intval ( $id );
		if (!$this->isExistID ( $id )) {
			return FALSE;
		}
		try {
			$sth = $this->dbh->prepare ( "SELECT * FROM {$table_prefix}hosts WHERE `id` = :id " );
			$sth->bindParam ( ':id', $id );
			$sth->execute ();
			$result = $sth->fetch ( PDO::FETCH_ASSOC );
			return $result;
		} catch ( PDOExecption $e ) {
			echo "<br>Error: " . $e->getMessage ();
		}
	}
	
	public function updateLabel($label, $id) {
		global $table_prefix;
		$label = trim($label);
		$id = intval($id);
		$mgmt_time = get_time ();
		if (! $this->isExistID($id)) {
			return FALSE;
		}
		try {
			$sth = $this->dbh->prepare ( "UPDATE {$table_prefix}hosts SET `label`= :label, `mgmt_time` = :mgmt_time WHERE `id` = :id" );
			$sth->bindParam ( ':label', $label );
			$sth->bindParam ( ':mgmt_time', $mgmt_time );
			$sth->bindParam ( ':id', $id );
			$sth->execute ();
			if (! ($sth->rowCount () > 0))
				return FALSE;
			else {
				$this->delUserToken($username);
				return TRUE;
			}
		} catch ( PDOExecption $e ) {
			echo "<br>Error: " . $e->getMessage ();
		}
	}
}