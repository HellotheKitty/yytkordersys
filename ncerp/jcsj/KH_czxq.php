<?php
require("../../inc/conn.php");
$khmc = $_GET["khmc"];
$tj = "khmc='$khmc' and jf>0 and zy<>'订单结算' and zy<>'订单定金' ";
$d1 = $_GET["d1"]; if($d1 <> "") $tj .= 'and fssj>="'.$d1.' 00:00:00" ';
$d2 = $_GET["d2"]; if($d2 <> "") $tj .= 'and fssj<="'.$d2.' 23:59:59"';
// $sql = "select base_kh.id,order_zh.khmc,sum(jf),group_concat(zy),group_concat(order_zh.xsbh) from order_zh,base_kh where jf>0 and zy<>'订单结算' and zy<>'订单定金' and locate('预存赠送',zy)=0 and locate('预存赠送',order_zh.xsbh)=0 and order_zh.khmc=base_kh.khmc and base_kh.gdzk=3301 group by khmc";

$sql = "select * from order_zh where $tj";

header("charset=utf-8");
$rs = mysql_query($sql, $conn);?>
<html>
<head>    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">

	<title>预存记录详情</title>
<style>
table {
	border-collapse: collapse;
	border: none;
}
th, td {
	border: 1px solid;
	padding-left: 5px;
	padding-right: 5px;
	height: 28px;
}
</style>
</head>
<table>
	<tr>
		<th width="150px">客户</th>
		<th colspan="4"><?echo $khmc?></th>
	</tr>
	<tr>
		<th>类型-方式</th>
		<th width="100px">金额</th>
		<th>时间</th>
		<th>摘要</th>
		<th>备注</th>
	</tr>

<?
$zs = 0;
$yc = 0;
while($row = mysql_fetch_array($rs)){
	?>
	<tr>
		<td style="text-align:center;"><?echo $row["xsbh"]?></td>
		<td><?echo $row["jf"]; if(strpos("0".$row["xsbh"].$row["zy"], '预存赠送')){$zs += $row["jf"];}else{$yc += $row["jf"];}?></td>
		<td><?echo $row["fssj"]?></td>
		<td><?echo $row["zy"]?></td>
		<td><?echo $row["memo"]?></td>
	</tr>
<? } ?>
	<tr>
		<th rowspan="2">合计</th>
		<th colspan="2">预存</th>
		<td colspan="2"><?echo $yc?></td>
	</tr>
	<tr>
		<th colspan="2">预存赠送</th>
		<td colspan="2"><?echo $zs?></td>
	</tr>
</table>
