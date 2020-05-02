<?php

class dataConnection {

	public $mysqli;
	private $location = 'yikayin377.mysql.rds.aliyuncs.com:3386';
	private $rootUser = 'yikamuser';
	private $rootPassword = 'Yikayin88382383';

	public function __construct() {
		$this->mysqli = new mysqli($this->location, $this->rootUser, $this->rootPassword);
		$this->mysqli->select_db('yikab');
		if (mysqli_connect_errno()) {
			return mysqli_connect_error();
		}
		$this->mysqli->query('SET NAMES UTF8');
	}

	/**
	 * select
	 * @param String $sql (Required)
	 * @return ResponseCore
	 */
	public function SQLSelect($sql) {
		$qy = $this->mysqli->query($sql) or die(mysqli_connect_errno());
		return $qy;
	}

	/**
	 * select
	 * @param String $sql (Required)
	 * @return ResponseCore
	 */
	public function selectOne($sql) {
		$result = $this->mysqli->query($sql) or die(mysqli_connect_errno());
		if ($row = $result->fetch_row()) {
			return $row[0];	
		}else {
			return false;
		}
	}


	/**
	 * insert, update, delete
	 * @param String $sql (Required)
	 * @return Integer
	 */
	public function SQLIUD($sql) {
		$this->mysqli->query('SET AUTOCOMMIT=0');
		$op = $this->mysqli->query($sql);
		if ($op) {
			$sign = 1;
		}else {
			$sign = -1;
		}
		// if (!$op) {
		// 	$sign = -1;
		// }else {
		// 	if ($this->mysqli->affected_rows > 0) {
		// 		$sign = 1;
		// 	}else {
		// 		$sign = 0;
		// 	}
		// }
		// return $sign;
		return $sign;
	}

	/**
	 * insert with id returning
	 * @param String $sql (Required)
	 * @return Array (Integer)
	 */
	public function SQLInsert($sql) {
		$callBack = self::SQLIUD($sql);
		$lastInsertId = $this->mysqli->insert_id;
		$data = array(
			'sign' => $callBack,
			'lastInsertId' => $lastInsertId
		);
		return $data;
	}

	/**
	 * transaction processing module
	 * @param Array or Integer $sqls (Required)
	 * @return Integer
	 */
	public function TAP($sqls) {
		if (is_array($sqls)) {
			$count = 0;
			foreach ($sqls as $value) {
				if ($value <= -1) {
					$count += 1;
				}
			}
			if ($count == 0) {
				$this->mysqli->query('COMMIT');
				$call = 1;
			}else {
				$this->mysqli->query('ROLLBACK');
				$call = 0;
			}
		}else {
			if ($sqls <= -1) {
				$this->mysqli->query('ROLLBACK');
				$call = 0;
			}else {
				$this->mysqli->query('COMMIT');
				$call = 1;
			}
		}
		$this->mysqli->query('END');
		$this->mysqli->query('SET AUTOCOMMIT=1');
		return $call;
	}

	/**
	 * close database connection
	 */
	public function SQLClosed() {
		if (!empty($this->mysqli)) {
			$this->mysqli->close();
		}
	}

	public function getNonceStr()
    {
        $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        $noceStr = "";
        for ($i = 0; $i < 32; $i++) {
            $noceStr .= $chars[ mt_rand(0, strlen($chars) - 1) ];
        }
        //$oldNonceStr = $noceStr;
        return $noceStr;
    }

}


class encryption {

	private $src;
	private $dist;
 	private $newStr;

	public function encrypt($str) {
        $src  = array('a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z','0','1','2','3','4','5','6','7','8','9');
        $dist = array('Q','R','S','(','T','U','V','X','W','I','$','Y','Z','A','K','B','@','C','D',')','F','G','H',']','^','[','*','P','~','J','L','M','N','O','E','_');
        $newStr  = str_replace($src,$dist,$str);  
        return $newStr;  
	}  

	public function decrypt($str) {
       	$src  = array('Q','R','S','(','T','U','V','X','W','I','$','Y','Z','A','K','B','@','C','D',')','F','G','H',']','^','[','*','P','~','J','L','M','N','O','E','_');
       	$dist = array('a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z','0','1','2','3','4','5','6','7','8','9');
       	$newStr  = str_replace($src,$dist,$str); 
       	return $newStr;  
	}

	public function getNonceStr() {
		$chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
		$noceStr = "";
		for ($i = 0; $i < 32; $i++) {
			$noceStr .= $chars[ mt_rand(0, strlen($chars) - 1) ];
		}
		$oldNonceStr = $noceStr;
		return $noceStr;
	}

	public function trimAll($str) {
		$newStr = trim($str);
		$newStr = ltrim($newStr);
		return $newStr;
	}

}