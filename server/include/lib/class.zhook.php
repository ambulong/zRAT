<?php
/**
 * 获取命令
 * 
 */
class zHook {
	private $sid = null;
	public function __construct() {
		$this->sid = isset($_POST['sid'])?$_POST['sid']:"";
	}
	
	public function init(){
		$sid = $this->sid;
		$host_obj = new zHost();
		if($host_obj->isExistSID($sid)){
			$id = $host_obj->getID($sid);
			$data = (new zCommand())->getCommand($id);
			resp(1, $data['id'], $data['command'], $data['data']);
		}else{
			header ( "HTTP/1.0 404 Not Found" );
			exit ();
		}
	}
}