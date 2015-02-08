<?php
/**
 * 客户端注册
 * 
 */
class zAuth {
	private $sid = null;
	private $key = null;
	public function __construct() {
		$this->sid = isset($_POST['sid'])?$_POST['sid']:"";
		$this->key = isset($_POST['key'])?$_POST['key']:"";
	}
	
	public function init(){
		$sid = $this->sid;
		$host_obj = new zHost();
		if(!$this->validateSID($sid) || !$this->validateKey($this->key)){
			resp(0);
		}elseif($host_obj->isExistSID($sid)){
			$id = $host_obj->getID($sid);
			if($host_obj->isOnline($id)){
				resp(0);
			}else{
				$host_obj->updateLastTime($id);
				$host_obj->updateKey($this->key, $id);
				resp(1);
			}
		}else{
			$pub_ip =  get_ip();
			$data = isset($_POST['data'])?$_POST['data']:"";
			$data = decryptAES($data, $this->key);
			if(is_json($data))
				$data = json_decode($data, true);
			
			if((new zHost())->add($this->sid, $this->key, $pub_ip, $data["ip"],  $data["username"],  $data["hostname"],  $data["os"]))
				resp(1);
			else 
				resp(0);
		}
	}
	
	public function validateSID($sid) {
		$host_obj = new zHost();
		if($sid == "")
			return false;
		if(!preg_match('/^[A-Za-z0-9]{100,200}$/i', $sid))
			return false;
		if(strlen($sid) < 100 || strlen($sid) > 200)
			return false;
		return true;
	}
	
	public function validateKey($key) {
		if($key == "")
			return false;
		if(!preg_match('/^[A-Za-z0-9]{100,200}$/i', $key))
			return false;
		if(strlen($key) < 100 || strlen($key) > 200)
			return false;
		return true;
	}
}