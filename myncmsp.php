<html >
<head>
<meta HTTP-EQUIV="pragma" CONTENT="no-cache, must-revalidate">
<meta HTTP-EQUIV="Cache-Control" CONTENT="no-cache, must-revalidate">
<meta HTTP-EQUIV="expires" CONTENT="0">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=2.0,user-scalable=yes ">
<title>易卡工坊</title>
<style>
@media screen and (max-device-width:480px) {
img{max-width:96%;height:auto;}
}
#botWrapper {
	width: 100%;
	margin: 5 auto;
}
.but_5{ 
		cursor:pointer; 
		border-radius: 5px; 
		width:90px; 
		height:35px; 
		color: #666; 
		border:2px solid #2aa439; 
		background: none;
		-webkit-appearance: none;
		font-size: 15px;
	}
.but_4{ 
	cursor:pointer; 
	border-radius: 5px; 
	width:90px; 
	height:35px; 
	margin: 10px auto;
	color: #fff; 
	border:1px solid #2aa439; 
	background: #2aa439;
	-webkit-appearance: none;
	font-size: 15px;
}
#navigation {
	height: 50px;
	width: 100%;
	text-align: center;
	background: #2aa439;
}
#navigation a {
	position: absolute;
	top: 11px;
	right: 15px;
	height: 28px;
	line-height: 28px;
	text-decoration: none;
	font-size: 13px;
	width: 80px;
	display: block;
	background: #fff;
	color: #444;
	border-radius: 5px;
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
.modify {
	margin: 5px 0px 5px 15px;
	text-align: left;
}
.modifyInput {
	width: 96%;
}
#mFooter {
	margin-top: 30px;
	text-align: center;
	height: 90px;
	width: 100%;
	padding-top: 5px;
	background: #eaeaea;
}
</style>
</head>
<? session_start();
require("inc/conn.php");
?>

<body topmargin="0" leftmargin="0">
<div id="navigation">
	
	<h3>名片印刷审批</h3>
    <a href="http://m.yikayin.com">易卡工坊</a>
</div>
<? 
if ($_GET["lx"]=="ok") {
	mysql_query("update nc_print set spjg='同意：".$_GET["yj"]."',sptime=now() where id=".$_GET["id"],$conn);	
	echo "<div style='text-align:center;margin-top:50px;'>审批同意！<br>";
	echo '<br><input class="but_5" type="button" value="关闭窗口" onclick="window.close();" />';
	exit;
}
if ($_GET["lx"]=="no") {
	mysql_query("update nc_print set spjg='不同意：".$_GET["yj"]."',sptime=now() where id=".$_GET["id"],$conn);	
	echo "<div style='text-align:center;margin-top:50px;'>审批不同意！<br>";
	echo '<br><input class="but_5" type="button" value="关闭窗口" onclick="window.close();" />';
	exit;
}


$id=($_GET["QSD"]-37)/17;
$rs=mysql_query("select * from nc_print where id=$id",$conn);
$row=mysql_fetch_row($rs)
?>
<div style="text-align:center">
    <form name="form1" method="post" action="">
<div id="botWrapper">
  <div class="modify">
		
		申请人：<? echo $row[1]?><br>
		姓名：<? echo $row[2]?><br>
		部门：<? echo $row[3];?><br>
		职务：<? echo $row[4];?><br>
		申请数量：<? echo $row[5];?>盒<br>
		申请日期：<? echo $row[6];?><br>
		审批意见：<input name="spjg" type="text" size="15" value="<? echo $row[7];?>"><br>
        <? if ($row[8]=="") {?>
        <input type="button" class="but_4" value="同意" onClick="window.location.href='myncmsp.php?lx=ok&id=<? echo $row[0]?>&yj='+form1.spjg.value;"> <input type="button" class="but_4" value="不同意" onClick="window.location.href='myncmsp.php?lx=no&id=<? echo $row[0]?>&yj='+form1.spjg.value;">
        <? } else echo "<br>已经审批!",$row[8];?>
		<br>
	</div>
</div>
    </form>
</div>
<div id="mFooter">
	<p>您有相关任何问题，请致电：<a href="tel:4008229377">4008-229-377</a>。</p>
</div>
</body>
</html>
