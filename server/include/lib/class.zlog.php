<?php
class zLog {

	private $dbh = NULL;

	public function __construct() {
		$this->dbh = $GLOBALS ['z_dbh'];
	}


	public function add($data) {
		global $table_prefix;
		$data = json_encode($data);
		$time = get_time();
		try {
			$sth = $this->dbh->prepare ( "INSERT INTO {$table_prefix}logs(`data`,`time`) VALUES( :data, :time)" );
			$sth->bindParam ( ':data', $data);
			$sth->bindParam ( ':time', $time);
			$sth->execute ();
			if (! ($sth->rowCount () > 0)) {
				return FALSE;
			}
			return $this->dbh->lastInsertId ();
		} catch ( PDOExecption $e ) {
			echo "<br>Error: " . $e->getMessage ();
		}
	}
	
}