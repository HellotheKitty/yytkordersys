<?php
//$mysql_server_name='rds8qr68017857z3yn2o.mysql.rds.aliyuncs.com';

$conn = @ mysql_connect("rm-2ze5r2a62bn7e4769.mysql.rds.aliyuncs.com", "yinyitiankong", "YINyitiankong2007");    //数据库账号密码
mysql_select_db("ordersys", $conn);     //数据库名
mysql_query("set names 'utf8'"); //使用utf中文编码;
?>
