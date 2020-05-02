<?php

class dataConnection {

	public $mysqli;
	private $location = 'skyprintdbpublic.mysql.rds.aliyuncs.com:3306';
	private $rootUser = 'yinyitiankong';
	private $rootPassword = 'YINyitiankong2007';

	public function __construct($dbname = 'ordersys') {
        $this->mysqli = new mysqli($this->location, $this->rootUser, $this->rootPassword);
        //$dbname = $dbname != '' ? $dbname : 'yikab';
        $this->mysqli->select_db($dbname);
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

    public function selectOne($sql) {
        $result = $this->mysqli->query($sql) or die(mysqli_connect_errno());
        if ($row = $result->fetch_row()) {
            return (string)$row[0];
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

}
