<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<strong><font size="+3">网站正在建设中</font></strong>...
<?php
$mysql_server_name='rm-2ze5r2a62bn7e4769.mysql.rds.aliyuncs.com';
$mysql_username='yinyitiankong';
$mysql_password='YINyitiankong2007';
$mysql_database='ordersys';
$conn=mysql_connect($mysql_server_name,$mysql_username,$mysql_password,$mysql_database);
mysql_select_db($mysql_database, $conn);   
mysql_query("SET NAMES UTF8"); 

?>