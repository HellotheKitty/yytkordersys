<?php
/**
 * Created by PhpStorm.
 * User: GyCCo
 * Date: 8/21/15
 * Time: 12:27 PM
 */



class DB {

    //protected static $_instance = null;
    protected $dsn;
    protected $dbh;
    protected $host = 'skyprintdbpublic.mysql.rds.aliyuncs.com:3306';
    protected $user = 'yinyitiankong';
    protected $password = 'YINyitiankong2007';
    protected $CHARSET = 'UTF8';

    /**
     * 构造
     *
     * @return DB
     */
    public function __construct($dbName = 'ordersys')
    {
        if ($this->dbh) return $this->dbh;

        try {
            $this->dsn = 'mysql:host='.$this->host.';dbname='.$dbName;
            $this->dbh = new PDO($this->dsn, $this->user, $this->password);
            $this->dbh->exec('SET character_set_connection='.$this->CHARSET.', character_set_results='.$this->CHARSET.', character_set_client=binary');
            $this->dbh->setAttribute(PDO::ATTR_AUTOCOMMIT, 0);
            $this->dbh->setAttribute(PDO::ATTR_PERSISTENT, false);
            $this->beginTransaction();
        } catch (PDOException $e) {
            $this->outputError($e->getMessage());
        }
    }

    /**
     * 防止克隆
     *
     */
    private function __clone() {}

    /**
     * Singleton instance
     *
     * @return Object
     */
//    public static function init($dbName = 'yikab')
//    {
//        if (self::$_instance === null) {
//            self::$_instance = new self($dbName);
//        }
//        return self::$_instance;
//    }

    /**
     * Query 查询
     *
     * @param String $strSql SQL语句
     * @param String $queryMode 查询方式(All or Row)
     * @param Boolean $debug
     * @return Array
     */
    public function select($strSql, $queryMode = 'All', $debug = false)
    {
        if ($debug === true) $this->debug($strSql);
        $recordset = $this->dbh->query($strSql);
        $this->getPDOError();
        if ($recordset) {
            $recordset->setFetchMode(PDO::FETCH_ASSOC);
            if ($queryMode == 'All') {
                $result = $recordset->fetchAll();
            } elseif ($queryMode == 'Row') {
                $result = $recordset->fetch();
            } else {
                $result = $recordset->fetchObject();
            }
        } else {
            $result = null;
        }
        return $result;
    }

    public function selectOne($strSql, $queryMode = 'All', $debug = false)
    {
        if ($debug === true) $this->debug($strSql);
        $recordset = $this->dbh->query($strSql);
        $this->getPDOError();
        if ($recordset) {
            $recordset->setFetchMode(PDO::FETCH_ASSOC);
            if ($queryMode == 'All') {
                $result = $recordset->fetchAll();
            } elseif ($queryMode == 'Row') {
                $result = $recordset->fetch();
            } else {
                $result = $recordset->fetchObject();
            }
        } else {
            $result = null;
        }
        return $result[0];
    }


    /**
     * Update 更新
     *
     * @param String $table 表名
     * @param Array $arrayDataValue 字段与值
     * @param String $where 条件
     * @param Boolean $debug
     * @return Int
     */
    public function update($table, $arrayDataValue, $where = '', $debug = false)
    {
        $this->checkFields($table, $arrayDataValue);
        if ($where) {
            $strSql = '';
            foreach ($arrayDataValue as $key => $value) {
                $strSql .= ", `$key`='$value'";
            }
            $strSql = substr($strSql, 1);
            $strSql = "UPDATE `$table` SET $strSql WHERE $where";
        } else {
            $strSql = "REPLACE INTO `$table` (`".implode('`,`', array_keys($arrayDataValue))."`) VALUES ('".implode("','", $arrayDataValue)."')";
        }
        $strSql = str_replace("'now()'", 'now()', $strSql);
        if ($debug === true) $this->debug($strSql);
        $result = $this->dbh->exec($strSql);
        //Log::DEBUG($result);
        $this->getPDOError();
        return $result;
    }

    /**
     * Insert 插入
     *
     * @param String $table 表名
     * @param Array $arrayDataValue 字段与值
     * @param Boolean $debug
     * @return Int
     */
    public function insert($table, $arrayDataValue, $debug = false)
    {
        $this->checkFields($table, $arrayDataValue);
        $strSql = "INSERT INTO `$table` (`".implode('`,`', array_keys($arrayDataValue))."`) VALUES ('".implode("','", $arrayDataValue)."')";
        $strSql = str_replace("'now()'", 'now()', $strSql);
        if ($debug === true) $this->debug($strSql);
        $result = $this->dbh->exec($strSql);
        $this->getPDOError();
        return $result;
    }

    public function lastInsertId() {
        return $this->dbh->lastInsertId();
    }

//    public function insertWithIdBack($table, $arrayDataValue, $debug = false)
//    {
//        $this->checkFields($table, $arrayDataValue);
//        $strSql = "INSERT INTO `$table` (`".implode('`,`', array_keys($arrayDataValue))."`) VALUES ('".implode("','", $arrayDataValue)."')";
//        if ($debug === true) $this->debug($strSql);
//        $result['sign'] = $this->dbh->exec($strSql);
//        $result['lastInsertId'] = $this->dbh->lastInsertId();
//        $this->getPDOError();
//        return $result;
//    }

    /**
     * Replace 覆盖方式插入
     *
     * @param String $table 表名
     * @param Array $arrayDataValue 字段与值
     * @param Boolean $debug
     * @return Int
     */
    public function replace($table, $arrayDataValue, $debug = false)
    {
        $this->checkFields($table, $arrayDataValue);
        $strSql = "REPLACE INTO `$table`(`".implode('`,`', array_keys($arrayDataValue))."`) VALUES ('".implode("','", $arrayDataValue)."')";
        if ($debug === true) $this->debug($strSql);
        $result = $this->dbh->exec($strSql);
        $this->getPDOError();
        return $result;
    }

    /**
     * Delete 删除
     *
     * @param String $table 表名
     * @param String $where 条件
     * @param Boolean $debug
     * @return Int
     */
    public function delete($table, $where = '', $debug = false)
    {
        if ($where == '') {
            $this->outputError("'WHERE' is Null");
        } else {
            $strSql = "DELETE FROM `$table` WHERE $where";
            if ($debug === true) $this->debug($strSql);
            $result = $this->dbh->exec($strSql);
            $this->getPDOError();
            return $result;
        }
    }

    /**
     * execSql 执行SQL语句
     *
     * @param String $strSql
     * @param Boolean $debug
     * @return Int
     */
    public function execSql($strSql, $debug = false)
    {
        if ($debug === true) $this->debug($strSql);
        $result = $this->dbh->exec($strSql);
        $this->getPDOError();
        return $result;
    }

    /**
     * 获取字段最大值
     *
     * @param string $table 表名
     * @param string $field_name 字段名
     * @param string $where 条件
     */
    public function getMaxValue($table, $field_name, $where = '', $debug = false)
    {
        $strSql = "SELECT MAX(".$field_name.") AS MAX_VALUE FROM $table";
        if ($where != '') $strSql .= " WHERE $where";
        if ($debug === true) $this->debug($strSql);
        $arrTemp = $this->select($strSql, 'Row');
        $maxValue = $arrTemp["MAX_VALUE"];
        if ($maxValue == "" || $maxValue == null) {
            $maxValue = 0;
        }
        return $maxValue;
    }

    /**
     * 获取指定列的数量
     *
     * @param string $table
     * @param string $field_name
     * @param string $where
     * @param bool $debug
     * @return int
     */
    public function getCount($table, $field_name, $where = '', $debug = false)
    {
        $strSql = "SELECT COUNT($field_name) AS NUM FROM $table";
        if ($where != '') $strSql .= " WHERE $where";
        if ($debug === true) $this->debug($strSql);
        $arrTemp = $this->select($strSql, 'Row');
        return $arrTemp['NUM'];
    }

    /**
     * 获取表引擎
     *
     * @param String $dbName 库名
     * @param String $tableName 表名
     * @param Boolean $debug
     * @return String
     */
    public function getTableEngine($dbName, $tableName)
    {
        $strSql = "SHOW TABLE STATUS FROM $dbName WHERE Name='".$tableName."'";
        $arrayTableInfo = $this->select($strSql);
        $this->getPDOError();
        return $arrayTableInfo[0]['Engine'];
    }

    /**
     * beginTransaction 事务开始
     */
    private function beginTransaction()
    {
        $this->dbh->beginTransaction();
    }

    /**
     * commit 事务提交
     */
    private function commit()
    {
        $this->dbh->commit();
    }

    /**
     * rollback 事务回滚
     */
    private function rollback()
    {
        $this->dbh->rollback();
    }

    /**
     * transaction 通过事务处理多条SQL语句
     * 调用前需通过getTableEngine判断表引擎是否支持事务
     *
     * @param array $arraySql
     * @return Boolean
     */
    public function execTransaction($arraySql)
    {
        $retval = 1;
        //$this->beginTransaction();
        if (is_array($arraySql)) {
            foreach ($arraySql as $strSql) {
                if ($this->execSql($strSql) === false) $retval = 0;
            }
        } else {
            if ($this->execSql($arraySql) === false) $retval = 0;
        }
        if ($retval == 0) {
            $this->rollback();
            //$this->dbh->setAttribute(PDO::ATTR_AUTOCOMMIT, 1);
            return false;
        } else {
            $this->commit();
            //$this->dbh->setAttribute(PDO::ATTR_AUTOCOMMIT, 1);
            return true;
        }
    }

    public function transaction($arrayResult)
    {
        $retval = 1;
        //$this->beginTransaction();
        if (is_array($arrayResult)) {
            foreach ($arrayResult as $result) {
                if ($result === false) $retval = 0;
            }
        } else {
            if ($arrayResult === false) $retval = 0;
        }
        if ($retval == 0) {
            $this->rollback();
            //$this->dbh->setAttribute(PDO::ATTR_AUTOCOMMIT, 1);
            return false;
        } else {
            $this->commit();
            //$this->dbh->setAttribute(PDO::ATTR_AUTOCOMMIT, 1);
            return true;
        }
    }

    /**
     * checkFields 检查指定字段是否在指定数据表中存在
     *
     * @param String $table
     * @param array $arrayField
     */
    private function checkFields($table, $arrayFields)
    {
        $fields = $this->getFields($table);
        foreach ($arrayFields as $key => $value) {
            if (!in_array($key, $fields)) {
                $this->outputError("Unknown column `$key` in field list.");
            }
        }
    }

    /**
     * getFields 获取指定数据表中的全部字段名
     *
     * @param String $table 表名
     * @return array
     */
    private function getFields($table)
    {
        $fields = array();
        $recordset = $this->dbh->query("SHOW COLUMNS FROM $table");
        $this->getPDOError();
        $recordset->setFetchMode(PDO::FETCH_ASSOC);
        $result = $recordset->fetchAll();
        foreach ($result as $rows) {
            $fields[] = $rows['Field'];
        }
        return $fields;
    }

    /**
     * getPDOError 捕获PDO错误信息
     */
    private function getPDOError()
    {
        if ($this->dbh->errorCode() != '00000') {
            $arrayError = $this->dbh->errorInfo();
            $this->outputError($arrayError[2]);
        }
    }

    /**
     * debug
     *
     * @param mixed $debuginfo
     */
    private function debug($debuginfo)
    {
        var_dump($debuginfo);
        exit();
    }

    /**
     * 输出错误信息
     *
     * @param String $strErrMsg
     */
    private function outputError($strErrMsg)
    {
        throw new Exception('MySQL Error: '.$strErrMsg);
    }

    /**
     * destruct 关闭数据库连接
     */
    public function destruct()
    {
        $this->dbh = null;
    }
}