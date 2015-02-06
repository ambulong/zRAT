<?php
/**
 * 保存执行结果
 * 
 */
class zResp {
	private $sid = null;
	public function __construct() {
		$this->sid = isset($_POST['sid'])?$_POST['sid']:"";
	}
	
	public function init(){
		$sid = $this->sid;
		$host_obj = new zHost();
		if($host_obj->isExistSID($sid)){
			$command_obj = new zCommand();
			$cid = isset($_POST['id'])?$_POST['id']:0;	//command's sid
			$ciid = $command_obj->getID($cid);	//command's id
			if(!$command_obj->isExistSID($cid)){
				exit();
			}
			$status = isset($_POST['status'])?$_POST['status']:"";
			$data = isset($_POST['data'])?$_POST['data']:"";
			$timestamp = isset($_POST['timestamp'])?$_POST['timestamp']:"";
			
			$this->add($ciid, array(
				"status"	=> "{$status}",
				"data"	=> "{$data}",
				"timestamp"	=> "{$timestamp}"
			));
		}else{
			header ( "HTTP/1.0 404 Not Found" );
			exit ();
		}
	}
	
	public function add($cid, $data){
		global $table_prefix;
		$cid = intval($cid);
		$data = json_encode(trim($data));
		$time = get_time();
		try {
			$sth = $this->dbh->prepare ( "INSERT INTO {$table_prefix}resps(`cid`,`data`,`time`) VALUES( :cid, :data, :time)" );
			$sth->bindParam ( ':cid', $cid);
			$sth->bindParam ( ':data', $data);
			$sth->bindParam ( ':time',  $time);
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