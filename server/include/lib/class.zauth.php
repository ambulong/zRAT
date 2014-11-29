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
		if(!$this->validateSID($this->sid) || !$this->validateKey($this->key)){
			resp(0);
		}else{
			$pub_ip =  get_ip();
			$data = isset($_POST['data'])?$_POST['data']:"";
			$data = decryptAES($data, $this->key);
			$data = json_decode($data, true);
			
			if((new zHost())->add($this->sid, $this->key, $pub_ip, $data["ip"],  $data["username"],  $data["hostname"],  $data["os"]))
				resp(1);
			else 
				resp(0);
		}
	}
	
	public function validateSID($sid) {
		if($sid == "")
			return false;
		if(!preg_match('/^[A-Za-z0-9]{100,200}$/i', $sid))
			return false;
		if(strlen($sid) < 100 || strlen($sid) > 200)
			return false;
		if((new zHost())->isExistSID($sid))
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