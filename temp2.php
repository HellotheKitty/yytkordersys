<?php
require("inc/conn.php");
//echo mysql_result(mysql_query("select state from order_mainqt where ddh='20160301254'", $conn), 0, 'state');
header("Content-Type:text/html;charset=utf-8");
if($_POST){
	$ddh = $_POST["ddh"];
	$state = mysql_result(mysql_query("select state from order_mainqt where ddh='$ddh'", $conn), 0, 'state');
	if($state == '订单完成')
		echo "该订单为已完成状态，重新收款后，需要重新点一下 配送完成<br>";
	else
		echo "<br>";
	mysql_query("delete from order_zh where ddh='$ddh'", $conn);
	mysql_query("update order_mainqt set state='待结算',sjpsfs=NULL where ddh='$ddh'", $conn);
	echo "success";
} else {
?>
<p>已结算订单改为待结算状态</p>
<form method="post">
订单号：<input type="text" name="ddh" />
<br>
<input type="submit" value="确定" />
</form>
<? } ?>
