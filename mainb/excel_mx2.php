<?php
session_start();
$dwdm = substr($_SESSION["GDWDM"],0,4);
require("../inc/conn.php");

	@$d1 = $_POST["starttime"];
	@$d2 = $_POST["endtime"];
	@$khmc = $_POST["khmc"];
	if($d1 == "")
		$d1 = date("Y-m-",time())."1";
	if($d2 == "")
		$d2 = date("Y-m-d",time());
	$kh = "";
	if($khmc != "")
		$kh = " and order_mainqt.khmc like '%$khmc%' ";
//$sql = "select order_mainqt.ddh,order_mainqt.khmc,order_zh.sksj,group_concat(order_mxqt.pname),group_concat(order_mxqt.n1,'^',order_mxqt.file1,'^',order_mxqt.machine1,'^',m1.MaterialName,'^',m1.Specs,'^',order_mxqt.dsm1,'^',order_mxqt.jldw1,'^',order_mxqt.sl1*order_mxqt.pnum1,'^',order_mxqt.jg1,',',order_mxqt.n2,'^',order_mxqt.file2,'^',order_mxqt.machine2,'^',m2.MaterialName,'^',m2.Specs,'^',order_mxqt.dsm2,'^',order_mxqt.jldw2,'^',order_mxqt.sl2*order_mxqt.pnum2,'^',order_mxqt.jg2),group_concat(order_mxqt_hd.jgfs,'^',order_mxqt_hd.jldw,'^',order_mxqt_hd.sl,'^',order_mxqt_hd.jg),order_zh.xsbh from order_mainqt left join order_mxqt on order_mainqt.ddh=order_mxqt.ddh left join order_mxqt_hd on mxid in (select id from order_mxqt where order_mxqt.ddh=order_mainqt.ddh) left join order_zh on order_mainqt.ddh=order_zh.ddh left join material m1 on order_mxqt.paper1=m1.id left join material m2 on order_mxqt.paper2=m2.id where order_mainqt.ddate>='$d1 00:00:00' and order_mainqt.ddate<='$d2 23:59:59' $kh and order_mainqt.zzfy='$dwdm' and order_zh.df>0 group by order_mainqt.ddh";
//$sql = "select order_mainqt.ddh,order_mainqt.khmc,order_zh.sksj,group_concat(order_mxqt.pname),group_concat(order_mxqt.n1,'^',order_mxqt.file1,'^',order_mxqt.machine1,'^',m1.MaterialName,'^',m1.Specs,'^',order_mxqt.dsm1,'^',order_mxqt.jldw1,'^',order_mxqt.sl1*order_mxqt.pnum1,'^',order_mxqt.jg1,',',order_mxqt.n2,'^',order_mxqt.file2,'^',order_mxqt.machine2,'^',m2.MaterialName,'^',m2.Specs,'^',order_mxqt.dsm2,'^',order_mxqt.jldw2,'^',order_mxqt.sl2*order_mxqt.pnum2,'^',order_mxqt.jg2),group_concat(order_mxqt_hd.jgfs,'^',order_mxqt_hd.jldw,'^',order_mxqt_hd.sl,'^',order_mxqt_hd.jg),order_zh.xsbh from order_mainqt left join order_mxqt on order_mainqt.ddh=order_mxqt.ddh left join order_mxqt_hd on order_mxqt_hd.mxid=order_mxqt.id left join order_zh on order_mainqt.ddh=order_zh.ddh left join material m1 on order_mxqt.paper1=m1.id left join material m2 on order_mxqt.paper2=m2.id where order_mainqt.ddh=15120200578 and order_zh.df>0 group by order_mainqt.ddh";

//$sql = "select order_mainqt.ddh,order_mainqt.khmc,order_zh.sksj,group_concat(order_mxqt.pname),group_concat(order_mxqt.n1,'^',order_mxqt.file1,'^',order_mxqt.machine1,'^',m1.MaterialName,'^',m1.Specs,'^',order_mxqt.dsm1,'^',order_mxqt.jldw1,'^',order_mxqt.sl1*order_mxqt.pnum1,'^',order_mxqt.jg1,',',order_mxqt.n2,'^',order_mxqt.file2,'^',order_mxqt.machine2,'^',m2.MaterialName,'^',m2.Specs,'^',order_mxqt.dsm2,'^',order_mxqt.jldw2,'^',order_mxqt.sl2*order_mxqt.pnum2,'^',order_mxqt.jg2),group_concat(order_mxqt_hd.jgfs,'^',order_mxqt_hd.jldw,'^',order_mxqt_hd.sl,'^',order_mxqt_hd.jg),order_zh.xsbh from order_mainqt left join order_mxqt on order_mainqt.ddh=order_mxqt.ddh left join order_mxqt_hd on order_mxqt_hd.ddhao=order_mainqt.ddh left join order_zh on order_mainqt.ddh=order_zh.ddh left join material m1 on order_mxqt.paper1=m1.id left join material m2 on order_mxqt.paper2=m2.id where order_mainqt.ddh=20151203291 and ddate>='2015-12-23 00:00:00' and order_mainqt.ddate<='2015-12-23 23:59:59' and order_mainqt.zzfy='3301' and order_zh.df>0 group by order_mainqt.ddh";
$sql = "select order_mainqt.ddh,order_mainqt.khmc,order_zh.sksj,group_concat(order_mxqt.pname),group_concat(order_mxqt.n1,'^',order_mxqt.file1,'^',order_mxqt.machine1,'^',m1.MaterialName,'^',m1.Specs,'^',order_mxqt.dsm1,'^',order_mxqt.jldw1,'^',order_mxqt.sl1*order_mxqt.pnum1,'^',order_mxqt.jg1,',',order_mxqt.n2,'^',order_mxqt.file2,'^',order_mxqt.machine2,'^',m2.MaterialName,'^',m2.Specs,'^',order_mxqt.dsm2,'^',order_mxqt.jldw2,'^',order_mxqt.sl2*order_mxqt.pnum2,'^',order_mxqt.jg2),group_concat(order_mxqt_hd.jgfs,'^',order_mxqt_hd.jldw,'^',order_mxqt_hd.sl,'^',order_mxqt_hd.jg),order_zh.xsbh from order_mainqt left join order_mxqt on order_mainqt.ddh=order_mxqt.ddh left join order_mxqt_hd on order_mxqt_hd.ddhao=order_mainqt.ddh left join order_zh on order_mainqt.ddh=order_zh.ddh left join material m1 on order_mxqt.paper1=m1.id left join material m2 on order_mxqt.paper2=m2.id where order_mainqt.ddh=20151203291 and ddate>='2015-12-23 00:00:00' and order_mainqt.ddate<='2015-12-23 23:59:59' and order_mainqt.zzfy='3301' and order_zh.df>0";

$rs = mysql_query($sql,$conn);
$row = mysql_fetch_array($rs,MYSQL_NUM);
dump($row);
echo "<br>";
$arr = explode(",", $row[4]);
dump($arr);


$sql2 = "select order_mainqt.ddh,order_mainqt.khmc,order_zh.sksj,group_concat(order_mxqt.pname),group_concat(order_mxqt.n1,'^',order_mxqt.file1,'^',order_mxqt.machine1,'^',m1.MaterialName,'^',m1.Specs,'^',order_mxqt.dsm1,'^',order_mxqt.jldw1,'^',order_mxqt.sl1*order_mxqt.pnum1,'^',order_mxqt.jg1,',',order_mxqt.n2,'^',order_mxqt.file2,'^',order_mxqt.machine2,'^',m2.MaterialName,'^',m2.Specs,'^',order_mxqt.dsm2,'^',order_mxqt.jldw2,'^',order_mxqt.sl2*order_mxqt.pnum2,'^',order_mxqt.jg2),group_concat(order_mxqt_hd.jgfs,'^',order_mxqt_hd.jldw,'^',order_mxqt_hd.sl,'^',order_mxqt_hd.jg),order_zh.xsbh from order_mainqt left join order_mxqt on order_mainqt.ddh=order_mxqt.ddh left join order_mxqt_hd on order_mxqt_hd.ddhao=order_mainqt.ddh left join order_zh on order_mainqt.ddh=order_zh.ddh left join material m1 on order_mxqt.paper1=m1.id left join material m2 on order_mxqt.paper2=m2.id where order_mainqt.ddh=20151203291 and order_zh.df>0";

$rs2 = mysql_query($sql2, $conn);
$row2 = mysql_fetch_array($rs2);
dump($row2);
function dump($arr) {
	echo "<br>****************************************<br>";
	foreach($arr as $key => $val) {
		echo $key." => ".$val."<br>";
	}
	echo "<br>****************************************<br>";
}
//exit;
?>
<html>
<head>
<script src="../js/WdatePicker.js" type="text/javascript" language="javascript"></script>
<style>
table {
	border-collapse:collapse;
	border:none;
	text-align:center;
}
th,td {
	border:solid 1px;
//	width:100px;
//	height:30px;
}
</style>
</head>
<body>
<form method="post">
下单时间范围：<input type="text" onclick="WdatePicker()" class="Wdate" name="starttime" value="<?echo $d1?>" style="cursor:hand" size="9" readonly />～<input type="text" onclick="WdatePicker()" class="Wdate" name="endtime" value="<?echo $d2?>" style="cursor:hand" size="9" readonly />　客户名称：<input type="text" name="khmc" value="<?echo $khmc?>" />　　<input type="submit" name="btn1" value="查询" />　　<?if(@$_POST["btn1"]){?><input type="submit" name="btn2" value="导出" /><?}?>
</form>
<table>
	<tr>
		<th  align="center" scope="col">订单号</th>
		<th  align="center" scope="col">客户名称</th>
		<th  align="center" scope="col">开单时间</th>
		<th  align="center" scope="col">后加工方式</th>
		<th  align="center" scope="col">印件名称</th>
		<th  align="center" scope="col">构件</th>
		<th  align="center" scope="col">文件名</th>
		<th  align="center" scope="col">机器及颜色</th>
		<th  align="center" scope="col">纸张</th>
		<th  align="center" scope="col">规格</th>
		<th  align="center" scope="col">单双</th>
		<th  align="center" scope="col">单位</th>
		<th  align="center" scope="col">数量</th>
		<th  align="center" scope="col">单价</th>
		<th  align="center" scope="col">小计金额</th>
	</tr>
<?
$total = 0;
while($row = mysql_fetch_array($rs)){
	$totalOrder = 0;
	$pnames = explode(',',$row[3]);
	foreach($pnames as $pname){	?>
	<tr>
		<td  align="center" scope="col"><?echo $row[0]?></td>
		<td  align="center" scope="col"><?echo $row[1]?></td>
		<td  align="center" scope="col"><?echo $row[2]?></td>
		<td  align="center" scope="col"></td>
		<td  align="center" scope="col"><?echo $pname?></td>
		<td  align="center" scope="col"></td>
		<td  align="center" scope="col"></td>
		<td  align="center" scope="col"></td>
		<td  align="center" scope="col"></td>
		<td  align="center" scope="col"></td>
		<td  align="center" scope="col"></td>
		<td  align="center" scope="col"></td>
		<td  align="center" scope="col"></td>
		<td  align="center" scope="col"></td>
		<td  align="center" scope="col"></td>
	</tr>

<?}
	$components = explode(',',$row[4]);
//	$components = array_unique($components);
	foreach($components as $key => $val)
		if($val[0] == '^')
			unset($components[$key]);
	if(isset($para)) unset($para);
	foreach($components as $component){
		$para = explode('^',$component);
		if($para[0] == "") continue;
?>
	<tr>
		<td  align="center" scope="col"></td>
		<td  align="center" scope="col"></td>
		<td  align="center" scope="col"></td>
		<td  align="center" scope="col"></td>
		<td  align="center" scope="col"></td>
		<td  align="center" scope="col"><?echo $para[0]?></td>
		<td  align="center" scope="col"><?echo $para[1]?></td>
		<td  align="center" scope="col"><?echo $para[2]?></td>
		<td  align="center" scope="col"><?echo $para[3]?></td>
		<td  align="center" scope="col"><?echo $para[4]?></td>
		<td  align="center" scope="col"><?echo $para[5]?></td>
		<td  align="center" scope="col"><?echo $para[6]?></td>
		<td  align="center" scope="col"><?echo $para[7]?></td>
		<td  align="center" scope="col"><?echo $para[8]?></td>
		<td  align="center" scope="col"></td>
	</tr>
<?
		$totalOrder += $para[7]*$para[8];
	}
	$afterprocesses = explode(',',$row[5]);
	if(isset($para)) unset($para);
	foreach($afterprocesses as $afterprocess){
		$para = explode('^',$afterprocess);
		if($para[0] == "") continue;
?>
	<tr>
		<td  align="center" scope="col"></td>
		<td  align="center" scope="col"></td>
		<td  align="center" scope="col"></td>
		<td  align="center" scope="col"><?echo $para[0]?></td>
		<td  align="center" scope="col"></td>
		<td  align="center" scope="col"></td>
		<td  align="center" scope="col"></td>
		<td  align="center" scope="col"></td>
		<td  align="center" scope="col"></td>
		<td  align="center" scope="col"></td>
		<td  align="center" scope="col"></td>
		<td  align="center" scope="col"><?echo $para[1]?></td>
		<td  align="center" scope="col"><?echo $para[2]?></td>
		<td  align="center" scope="col"><?echo $para[3]?></td>
		<td  align="center" scope="col"></td>
	</tr>
<?
		$totalOrder += $para[2]*$para[3];	
	}
?>
	<tr>
		<td  align="center" scope="col"></td>
		<td  align="center" scope="col"></td>
		<td  align="center" scope="col"></td>
		<td  align="center" scope="col"></td>
		<td  align="center" scope="col"></td>
		<td  align="center" scope="col"></td>
		<td  align="center" scope="col"></td>
		<td  align="center" scope="col">小计</td>
		<td  align="center" scope="col"></td>
		<td  align="center" scope="col"></td>
		<td  align="center" scope="col"></td>
		<td  align="center" scope="col"></td>
		<td  align="center" scope="col"></td>
		<td  align="center" scope="col"></td>
		<td  align="center" scope="col"><?echo $totalOrder?></td>
	</tr>
<?
		$total += $totalOrder;
}
?>
	<tr>
		<td  align="center" scope="col"></td>
		<td  align="center" scope="col"></td>
		<td  align="center" scope="col"></td>
		<td  align="center" scope="col"></td>
		<td  align="center" scope="col"></td>
		<td  align="center" scope="col"></td>
		<td  align="center" scope="col"></td>
		<td  align="center" scope="col">合计</td>
		<td  align="center" scope="col"></td>
		<td  align="center" scope="col"></td>
		<td  align="center" scope="col"></td>
		<td  align="center" scope="col"></td>
		<td  align="center" scope="col"></td>
		<td  align="center" scope="col"></td>
		<td  align="center" scope="col"><?echo $total?></td>
	</tr>
</table>
</body>
</html>
