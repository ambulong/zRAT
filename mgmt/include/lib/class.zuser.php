<?php
/**
 * 用户操作类
 * @author Ambulong
 *
 */
class zUser {
	private $dbh = NULL;
	private $hasher = NULL;
	public function __construct() {
		$this->dbh = $GLOBALS ['z_dbh'];
		$this->hasher = new PasswordHash ( 8, FALSE );
		$this->refreshToken();
	}
	
	
	/**
	 * 校验密码
	 * 
	 * @param string $username       	
	 * @param string $password
	 * @return boolean
	 */
	public function validatePassword($username, $password) {
		$username = strtolower(trim ( $username ));
		$hash = $this->getPassword ( $username );
		//var_dump($hash);
		//var_dump($this->hasher->CheckPassword ( $password, $hash ));
		//var_dump($password);
		if ($this->hasher->CheckPassword ( $password, $hash ))
			return TRUE;
		else
			return FALSE;
	}
	
	
	/**
	 * User是否存在
	 *
	 * @param String $username
	 *        	要查询的用户名
	 * @return boolean
	 */
	public function isExistUser($username) {
		global $table_prefix;
		$username = strtolower(trim($username));
		try {
			$sth = $this->dbh->prepare ( "SELECT count(*) FROM {$table_prefix}users WHERE `username` = :username " );
			$sth->bindParam ( ':username', $username );
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
	
	public function getPassword($username) {
		global $table_prefix;
		$username = strtolower(trim($username));
		if (! $this->isExistUser($username)) {
			return FALSE;
		}
		try {
			$sth = $this->dbh->prepare ( "SELECT * FROM {$table_prefix}users WHERE `username` = :username " );
			$sth->bindParam ( ':username', $username );
			$sth->execute ();
			$result = $sth->fetch ( PDO::FETCH_ASSOC );
			return $result["password"];
		} catch ( PDOExecption $e ) {
			echo "<br>Error: " . $e->getMessage ();
		}
	}
	
	public function updatePassword($username, $password) {
		global $table_prefix;
		$hash = $this->hasher->HashPassword ( $password );
		$username = strtolower(trim($username));
		$mgmt_time = get_time ();
		if (! $this->isExistUser($username)) {
			return FALSE;
		}
		try {
			(new zLog())->add(array(
					"username"	=> $username,
					"action"	=> "update password",
					"user_info"	=> get_user_info()
			));
			$sth = $this->dbh->prepare ( "UPDATE {$table_prefix}users SET `password`= :password, `mgmt_time` = :mgmt_time WHERE `username` = :username" );
			$sth->bindParam ( ':password', $hash );
			$sth->bindParam ( ':mgmt_time', $mgmt_time );
			$sth->bindParam ( ':username', $username );
			$sth->execute ();
			if (! ($sth->rowCount () > 0))
				return FALSE;
			else
				return TRUE;
		} catch ( PDOExecption $e ) {
			echo "<br>Error: " . $e->getMessage ();
		}
	}
	
	public function hash($password) {
		$hash = $this->hasher->HashPassword ( $password );
		return $hash;
	}
	
	/**
	 * 用户登录
	 *
	 * @param String $username 用户名
	 * 
	 * @return string token
	 */
	public function login($username) {
		$token = $this->newSalt(180);
		if($this->isExistToken($username, $token))
			return false;
		$this->newToken($username, $token);
		(new zLog())->add(array(
				"username"	=> $username,
				"action"	=> "login",
				"user_info"	=> get_user_info()
		));
		return $token;
	}
	
	public function isExistToken($username, $token) {
		global $table_prefix;
		$username = strtolower(trim($username));
		try {
			$sth = $this->dbh->prepare ( "SELECT count(*) FROM {$table_prefix}users_token WHERE `username` = :username and token = :token" );
			$sth->bindParam ( ':username', $username );
			$sth->bindParam ( ':token', $token );
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
	
	/**
	 * 校验token
	 *
	 * @param String $username 用户名
	 * 
	 * @param String $token token
	 *
	 * @return string boolean
	 */
	public function validateToken($username, $token) {
		global $table_prefix;
		$username = strtolower(trim($username));
		try {
			$sth = $this->dbh->prepare ( "SELECT count(*) FROM {$table_prefix}users_token WHERE `username` = :username and token = :token and ABS(UNIX_TIMESTAMP(now()) - UNIX_TIMESTAMP(`expired_time`)) > 100" );
			$sth->bindParam ( ':username', $username );
			$sth->bindParam ( ':token', $token );
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
	
	public function newToken($username, $token) {
		global $table_prefix;
		$username = strtolower(trim($username));
		$time = get_time();
		try {
			$sth = $this->dbh->prepare ( "INSERT INTO {$table_prefix}users_token(`username`,`token`,`expired_time`) VALUES( :username, :token, :time)" );
			$sth->bindParam ( ':username', $username);
			$sth->bindParam ( ':token', $token);
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
	
	public function updateToken($username, $token) {
		global $table_prefix;
		$username = strtolower(trim($username));
		$time = get_time();
		try {
			$sth = $this->dbh->prepare ( "UPDATE {$table_prefix}users_token SET `time` = :time WHERE `username` = :username and `token` = :token" );
			$sth->bindParam ( ':username', $username);
			$sth->bindParam ( ':token', $token);
			$sth->bindParam ( ':time', $time);
			$sth->execute ();
			if (! ($sth->rowCount () > 0)) {
				return FALSE;
			}
			return TRUE;
		} catch ( PDOExecption $e ) {
			echo "<br>Error: " . $e->getMessage ();
		}
	}
	
	public function delToken($username, $token) {
		global $table_prefix;
		$username = strtolower(trim($username));
		try {
			$sth = $this->dbh->prepare ( "DELETE FROM {$table_prefix}users_token WHERE `username` = :username and `token` = :token " );
			$sth->bindParam ( ':username', $username);
			$sth->bindParam ( ':token', $token);
			$sth->execute ();
			$row = $sth->rowCount ();
			if ($row > 0) {
				return $row;
			} else {
				return FALSE;
			}
		} catch ( PDOExecption $e ) {
			echo "<br>Error: " . $e->getMessage ();
		}
	}
	
	/**
	 * 清除过期token
	 *
	 * @return string boolean
	 */
	public function refreshToken() {
		global $table_prefix;
		try {
			$sth = $this->dbh->prepare ( "DELETE FROM {$table_prefix}users_token WHERE ABS(UNIX_TIMESTAMP(now()) - UNIX_TIMESTAMP(`expired_time`)) > 100 " );
			$sth->execute ();
			$row = $sth->rowCount ();
			if ($row > 0) {
				return $row;
			} else {
				return FALSE;
			}
		} catch ( PDOExecption $e ) {
			echo "<br>Error: " . $e->getMessage ();
		}
	}
	
	/**
	 * 生成随机字符串
	 *
	 * @param int $length
	 *        	要生成的字符串长度
	 * @return string
	 */
	public function newSalt($length = 8) {
		$chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
		$salt = '';
		for($i = 0; $i < $length; $i ++) {
			$salt .= $chars [mt_rand ( 0, strlen ( $chars ) - 1 )];
		}
		return $salt;
	}
	
	/**
	 * 销毁hash
	 */
	public function __destruct() {
		if (isset ( $this->hasher ))
			unset ( $this->hasher );
	}
}