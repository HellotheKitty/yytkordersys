<?php
session_start();
$mysql_server_name='rm-2ze5r2a62bn7e4769.mysql.rds.aliyuncs.com';
$mysql_username='yinyitiankong';
$mysql_password='YINyitiankong2007';
$mysql_database='ordersys';
$conn=mysql_connect($mysql_server_name,$mysql_username,$mysql_password,$mysql_database);
mysql_select_db($mysql_database, $conn);   
mysql_query("SET NAMES UTF8"); 

//$localftp="http://192.168.1.102/skyprint.cn/fileupload";
if ($_SESSION["GSSDQ"]=="杭州")
	$localftp="http://192.168.1.71:88/skyserver";
elseif ($_SESSION["GSSDQ"]=="上海")
	$localftp="http://192.168.1.71:88/skyserver";
else
	$localftp="http://59.110.17.13/ordersys/fileupload";
 
function dw0($dw) {
    if (substr($dw,1,5)=="00000") return substr($dw,0,1);
    elseif (substr($dw,2,4)=="0000") return substr($dw,0,2);
    elseif (substr($dw,4,2)=="00") return substr($dw,0,4);
    else return $dw;
}    

function base_encode($str) {  
        $src  = array("/","+","=");  
        $dist = array("_a","_b","_c");  
        $old  = base64_encode($str);  
        $new  = str_replace($src,$dist,$old);  
        return $new;  
}  
function base_decode($str) {  
       $src = array("_a","_b","_c");  
       $dist  = array("/","+","=");  
       $old  = str_replace($src,$dist,$str);  
       $new = base64_decode($old);  
       return $new;  
}

function beginTransaction() {
    mysql_query("SET AUTOCOMMIT=0");
}

function transaction($execs) {

    $retval = 1;

    foreach ($execs as $result) {
        if ( ! $result) $retval = 0;
    }

    if ($retval == 0) {

        mysql_query("ROLLBACK");
        $sign = false;

    } else {

        mysql_query("COMMIT");
        $sign = true;
    }

    mysql_query("END");
    mysql_query("SET AUTOCOMMIT=1");

    return $sign;
}
?>
