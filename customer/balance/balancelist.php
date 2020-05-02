<?php
session_start();
//$_SESSION["CUSTOMER"] = "OK";
if($_SESSION["CUSTOMER"]<>"OK")
	die("请登录！");
require("../../inc/conn.php");
//while($row = mysql_fetch_array($rs,MYSQL_ASSOC)){
//	print_r($row);
//	echo "<br><br>";
//}

$tj = " khmc = '".$_SESSION["KHMC"]."' and (df<>0 or jf<>0) ";
$btime = date("Y-m-",time())."01";
$etime = date("Y-m-d",time());

$btime = $_POST["begintime"]==""?$btime:$_POST["begintime"];
$etime = $_POST["endtime"]==""?$etime:$_POST["endtime"];
if($btime <> "") $tj .= " and fssj >= '$btime 00:00:00'";
if($etime <> "") $tj .= " and fssj <= '$etime 23:59:59'";


$result = mysql_query("select count(1) from order_zh where $tj", $conn);
$total = mysql_result($result, 0, 0);
$pages = ceil($total/20);

$lpage = $_POST["nowpage"]==""?1:(int)$_POST["nowpage"];
if($lpage<1 || $lpage >$pages)
	$lpage = 1;
$page = $_POST["gopage"]==""?1:(int)$_POST["gopage"];
if($page<1 || $page>$pages)
	$page = $lpage;
	
$index = ($page - 1) * 20 ;
$limit = 20;

if($_POST["btn"]<>""){
	$index = 0;
	$page = 1;
}

$rs = mysql_query("select * from order_zh where $tj order by id desc limit $index,$limit", $conn);

// exit;
?>
<html>
<head>
<script src="../../js/WdatePicker.js" type="text/javascript" language="javascript"></script>
<style>
table {
	border-collapse: collapse;
	border: none;
	width: 60%;
	text-align: center;
}
th,td {
	border: solid 1px;
	font-size: 14px;
	height: 30px;
}
a:link,a:visited {
	text-decoration: none;
	color: black;
}
a:hover {
	text-decoration: underline;
	color: red;
	cursor: pointer;
}
</style>
</head>

<body>
<form method="post" name="form11" id="form11">
查询日期：<input type="text" name="begintime" placeholder="起始时间" onclick="WdatePicker()" class="Wdate" style="cursor:hand" size="9" value="<?echo $btime?>" readonly />～<input type="text" name="endtime" placeholder="结束时间" onclick="WdatePicker()" class="Wdate" style="cursor:hand" size="9" value="<?echo $etime?>" readonly />　　<input type="submit" name="btn" value="查询" />

<input type="hidden" name="nowpage" id="nowpage" value="<?echo $page?>" />
<input type="hidden" name="gopage" id="gopage" value="<?echo $page?>" />
<input type="hidden" name="pages" id="pages" value="<?echo $pages?>" />
</form>
<div>
	<span>账户余额:<? /*$resye = mysql_query("select ifnull(sum((ifnull(`jf`, 0) - ifnull(`df`, 0))),0) AS `ye` from order_zh where khmc = '".$_SESSION["KHMC"]."'",$conn);
		echo mysql_result($resye,0,'ye');*/
		$khmc = $_SESSION['KHMC'];
		$sql_czxf = "SELECT ifnull(sum((ifnull(`order_zh`.`jf`, 0) - ifnull(`order_zh`.`df`, 0))),0) AS `czxf` FROM order_zh WHERE fssj > IFNULL((SELECT sdate FROM kh_ye WHERE depart = '$khmc' LIMIT 1),'2015-01-01') AND khmc = '$khmc' GROUP BY khmc ";

 		$sql_ye = "select ye from kh_ye where depart = '$khmc'";

		$resye1 = mysql_query($sql_czxf,$conn);
		$resye2 = mysql_query($sql_ye,$conn);
		if(mysql_num_rows($resye1) >0){

			$res_czxf = mysql_result($resye1 ,0,'czxf');

		}else{
			$res_czxf = 0;
		}

		if(mysql_num_rows($resye2)>0){
			$res_ye = mysql_result($resye2 ,0,'ye');

		}else{
			$res_ye=0;
		}

		$yue = round(floatval($res_czxf) + floatval($res_ye) , 2);
		echo $yue . '元';

		?>
    </span>

</div>
<table>
	<tr>
		<th>摘要</th>
		<th>金额</th>
		<th>方式</th>
		<th>日期</th>
		<th>订单号</th>
	</tr>
<? while($row = mysql_fetch_array($rs,MYSQL_ASSOC)) {	?>
	<tr>
		<td><? echo $row["zy"] ?></td>
		<td><? echo $row["jf"]!=0?$row["jf"]:$row["df"] ?></td>
		<td><? echo $row["xsbh"] ?></td>
		<td><? echo $row["fssj"] ?></td>
		<td><a href="#" onclick="javascript:window.open('../order/orderdetail.php?ddh=<?echo $row["ddh"]."&auth=".md5($_SESSION["KHID"]."-".$_SESSION["AUTHCODE"]."-".$row["ddh"]."-"."noah");?>','detail','height=600px,width=920px,top=100px,left=200px,location=no,menubar=no,resizable=no,scrollbars=yes,status=no,titlebar=no,toolbar=no,directories=no')"><? echo $row["ddh"]>9999?$row["ddh"]:"" ?></a></td>
	</tr>
<? } ?>
</table><br>
<div style="float:left;margin:5px 5px 5px auto"><a onclick="go(1)">首页</a>　<a onclick="go(<? if($page>1) echo ($page-1); else echo $page;?>)">＜上一页</a>　<a onclick="go(<?if($page<$pages) echo ($page+1); else echo $page?>)">下一页＞</a>　<a onclick="go(<?echo $pages?>)">尾页</a>　当前第<?echo $page?>页，共<?echo $pages?>页　<input type="text" name="jump" id="jump" value="<?echo $page?>" style="text-align:center" size="1" /> <input type="button" onclick="javascript:go(document.getElementById('jump').value)" value="ＧＯ" style="cursor:hand;" /></div>
<script>
function go(n)
{
	reg = /^[1-9][0-9]*$/;
	if(!reg.test(n)){
		alert("页数必须为正整数");
		return;
	}
	pages = document.getElementById("pages").value;
	if((n-pages)>0){
		alert("没有这么多页");
		return;
	}
	nowpage = document.getElementById("nowpage").value;
	if(nowpage == n)
		return;
	document.getElementById("gopage").value = n;
	document.getElementById("form11").submit();	
}
</script>
</body>
</html>
