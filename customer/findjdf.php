<?php
require("../inc/conn.php");
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=2.0, minimum-scale=1.0, user-scalable=yes">  
    <title>易卡工坊--查找打印文件</title>
</head>
<style>
	#navigation {
		height: 50px;
		width: 100%;
		text-align: center;
		background: #2aa439;
	}
	#navigation a.back {
		position: absolute;
		top: 15px;
		left: 15px;
		width: 50px;
		background: none;
		color: #fff;
		font-size: 16px;
	}
	h3 {
		width: 180px;
		height: 50px;
		line-height: 50px;
		margin: 0 auto;
		color: #fff;
		font-size: 20px;
	}
	
</style>

<body style="overflow-x:hidden;overflow-y:auto">
<div id="navigation">
	
  <h3>查找打印文件</h3>
</div>
<div style="margin-left:10px; margin-top:30px;"> 
  <p>输入拼版号：</p>
  <form name="form1" method="post" action="">
   
    <input type="text" name="pbh" id="pbh" style="width:200px;height:35px; font-size:18px">
    <input type="submit" name="find" id="find" value="查找" style="width:80px;height:35px; font-size:18px">
    <br>输入几位拼版号，点查找。
  </form>
  <br><br>
 <?
 if ($_POST["pbh"]<>"") {
 	$rs=mysql_query("select ddh,file1,jdf1 from order_mxqt where instr(file1,'".$_POST["pbh"]."')>0 order by ddh desc limit 10");

	for ($i=0;$i<mysql_num_rows($rs);$i++) {
		echo "印艺订单号：",mysql_result($rs,$i,"ddh"),"<br>","文件名：<font color=red>",mysql_result($rs,$i,"jdf1"),"</font><br>","拼版号：",substr(mysql_result($rs,$i,"file1"),strpos(mysql_result($rs,$i,"file1"),'Pok1')+5),"<hr>";
	}
 }
 ?>
</div>


</body>
</html>