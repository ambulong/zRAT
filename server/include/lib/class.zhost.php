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
	
	public function add($sid, $key, $pub_ip, $ip, $username, $hostname, $os) {
		global $table_prefix;
		$sid = trim($sid);
		$key = trim($key);
		$pub_ip = trim($pub_ip);
		$ip = trim($ip);
		$username = trim($username);
		$hostname = trim($hostname);
		$os = trim($os);
		$time = get_time();
		try {
			$sth = $this->dbh->prepare ( "INSERT INTO {$table_prefix}hosts(`sid`,`key`,`pub_ip`,`ip`,`username`,`hostname`,`os`,`time`,`last_time`,`mgmt_time`) VALUES( :sid, :key, :pub_ip, :ip, :username, :hostname, :os, :time, :last_time, :mgmt_time)" );
			$sth->bindParam ( ':sid', $sid);
			$sth->bindParam ( ':key', $key);
			$sth->bindParam ( ':pub_ip',  $pub_ip);
			$sth->bindParam ( ':ip', $ip);
			$sth->bindParam ( ':username', $username);
			$sth->bindParam ( ':hostname', $hostname);
			$sth->bindParam ( ':os', $os);
			$sth->bindParam ( ':time', $time);
			$sth->bindParam ( ':last_time', $time);
			$sth->bindParam ( ':mgmt_time', $time);
			$sth->execute ();
			if (! ($sth->rowCount () > 0)) {
				return FALSE;
			}
			return $this->dbh->lastInsertId ();
		} catch ( PDOExecption $e ) {
			echo "<br>Error: " . $e->getMessage ();
		}
	}
	
	public function isExistSID($sid) {
		global $table_prefix;
		$sid = trim($sid);
		try {
			$sth = $this->dbh->prepare ( "SELECT count(*) FROM {$table_prefix}hosts WHERE `sid` = :sid" );
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
	
	public function updateLabel($str, $id) {
		global $table_prefix;
		$str = trim($str);
		$id = intval ( $id );
		$mgmt_time = get_time();
		if (! $this->isExistID ( $id )) {
			return FALSE;
		}
		try {
			$sth = $this->dbh->prepare ( "UPDATE {$table_prefix}hosts SET `label`= :label, `mgmt_time` = :mgmt_time WHERE `id` = :id" );
			$sth->bindParam ( ':label', $str );
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
	
	public function updateLastTime($id) {
		$last_time = get_time();
		if (! $this->isExistID ( $id )) {
			return FALSE;
		}
		try {
			$sth = $this->dbh->prepare ( "UPDATE {$table_prefix}hosts SET `last_time`= :time WHERE `id` = :id" );
			$sth->bindParam ( ':time', $last_time );
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
	
	public function updateKey($key, $id) {
		$key = trim($key);
		if (! $this->isExistID ( $id )) {
			return FALSE;
		}
		try {
			$sth = $this->dbh->prepare ( "UPDATE {$table_prefix}hosts SET `key`= :key WHERE `id` = :id" );
			$sth->bindParam ( ':key', $key );
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
	
	public function getKey($id) {
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
			return $result["key"];
		} catch ( PDOExecption $e ) {
			echo "<br>Error: " . $e->getMessage ();
		}
	}
	
	public function getID($sid) {
		global $table_prefix;
		$sid = trim($sid);
		if (!$this->isExistSID ( $sid )) {
			return FALSE;
		}
		try {
			$sth = $this->dbh->prepare ( "SELECT * FROM {$table_prefix}hosts WHERE `sid` = :sid " );
			$sth->bindParam ( ':sid', $sid );
			$sth->execute ();
			$result = $sth->fetch ( PDO::FETCH_ASSOC );
			return $result["id"];
		} catch ( PDOExecption $e ) {
			echo "<br>Error: " . $e->getMessage ();
		}
	}
	
	public function getSID($id) {
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
			return $result["sid"];
		} catch ( PDOExecption $e ) {
			echo "<br>Error: " . $e->getMessage ();
		}
	}
	
	public function isOnline($id) {
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
			$last_time = $result["last_time"];
			$timediff = (getTimestamp() - strtotime($last_time))%86400/60;
			if($timediff > 4)	//4分钟
				return FALSE;
			else
				return TRUE;
		} catch ( PDOExecption $e ) {
			echo "<br>Error: " . $e->getMessage ();
		}
	}
}