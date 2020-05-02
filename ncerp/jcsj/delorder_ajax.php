<?php
require("../inc/conn.php");
$ddh = $_GET["ddh"];
if(mysql_query("start transaction;delete from order_zh where ddh='$ddh';delete from order_mxqt_hd where mxid in (select id from order_mxqt where ddh='$ddh');delete from order_mxqt where ddh='$ddh';delete from order_mainqt where ddh='$ddh';commit", $conn))
	echo "1";
else
	echo "0";
