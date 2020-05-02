<?php
$mysql_server_name='rm-2ze5r2a62bn7e4769.mysql.rds.aliyuncs.com';
//$mysql_server_name='rds8qr68017857z3yn2opublic.mysql.rds.aliyuncs.com';
$mysql_username='yinyitiankong';
$mysql_password='YINyitiankong2007';
$mysql_database='ordersys';
$conn=mysql_connect($mysql_server_name,$mysql_username,$mysql_password,$mysql_database);
mysql_select_db($mysql_database, $conn);   
mysql_query("SET NAMES UTF8"); 
?>
