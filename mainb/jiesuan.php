<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<?php
session_start();
require("../inc/conn.php");

include '../commonfile/calc_area.php';

//$d1=$_POST["rq1"];if ($d1=="") {$d1=date("Y-m-")."01";}

$d1=$_POST["rq1"];if ($d1=="") {$d1=date("Y-m-d");}
$d2=$_POST["rq2"];if ($d2=="") {$d2=date("Y-m-d");}

if ($_POST["bt2"]) {
    header("Content-type:application/vnd.ms-excel;charset=UTF-8");
    header("Content-Disposition:filename=" . iconv("utf-8", "gb2312", "结算统计导出[" . $d1 . "-" . $d2 . "].xls"));
    header("Expires:0");
    header('Pragma:   public');
    header("Cache-control:must-revalidate,post-check=0,pre-check=0");
}

$department = $_POST["department"];
$dpt = "";
if($department <> "所有部门"){
	if($department=="菜谱部")
		$dpt = " and locate('菜谱',order_mainqt.khmc)>0 ";
	else if($department=="商务部")
		$dpt = " and locate('商务',order_mainqt.khmc)>0 ";
	else if($department == "其他")
		$dpt = " and locate('菜谱',order_mainqt.khmc)=0 and locate('商务',order_mainqt.khmc)=0 and locate('影像',order_mainqt.khmc)=0 ";
    else if($department == "影像部")
        $dpt = " and locate('影像',order_mainqt.khmc)>0 ";
    else if($department == '外协部')
        $dpt = " and locate('外协',order_mainqt.khmc)>0 ";
    else if($department == "市场部")
        $dpt = " and locate('市场',order_mainqt.khmc)>0 ";
	else
		$department = "所有部门";
}

if($d1>$d2){$d=$d1;$d1=$d2;$d2=$d;}
$t1=array("订单订金","订单结算");
$t2=array("amount","现金","支票","POS机招行","汇款","预存扣款","sum");
foreach($t1 as $v1)
	foreach($t2 as $v2)
		$arr[$v1][$v2]=0;
$sql1 = "select zy,sum(df),count(1) from order_zh left join order_mainqt on order_zh.ddh=order_mainqt.ddh where sksj>='$d1 00:00:00' and sksj<='$d2 23:59:59' and zy in ('订单订金','订单结算') and df>0 and order_mainqt.zzfy  in $dwdmStr $dpt group by zy";
$rs = mysql_query($sql1, $conn);
//echo $sql1;
while($a=mysql_fetch_array($rs)){
	$arr[$a[0]]["sum"] = $a[1];
	$arr[$a[0]]["amount"] = $a[2];
}
echo "<br>";

$sql2 = "select order_zh.xsbh,sum(df) from order_zh left join order_mainqt on order_zh.ddh=order_mainqt.ddh where sksj>='$d1 00:00:00' and sksj<='$d2 23:59:59' and zy='订单订金' and df>0 and order_mainqt.zzfy in $dwdmStr $dpt group by order_zh.xsbh";
$rsdj = mysql_query($sql2, $conn);
//echo $sql2;
while($b=mysql_fetch_array($rsdj)){
	$arr["订单订金"][$b[0]] = $b[1];
	}
echo "<br>";

$sql3 = "select order_zh.xsbh,sum(df) from order_zh left join order_mainqt on order_zh.ddh=order_mainqt.ddh where sksj>='$d1 00:00:00' and sksj<='$d2 23:59:59' and zy='订单结算' and df>0 and order_mainqt.zzfy in $dwdmStr $dpt group by order_zh.xsbh";
$rsjs = mysql_query($sql3, $conn);
//echo $sql3;
while($c=mysql_fetch_array($rsjs)){
	$arr["订单结算"][$c[0]] = $c[1];
	}
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>无标题文档</title>
<style>
table{
	border-collapse: collapse;
    border: none;
	width: 50%;
}

td,th{
	    border: solid #000 1px;
	    text-align: center;
}
</style>
<script src="../js/WdatePicker.js" type="text/javascript" language="javascript"></script>
</head>

<body>
<form method="post" name="form111" >
    <? if($_POST['bt2']==''){
        ?>

        按日期：<input onClick="WdatePicker();" maxlength="50" class="Wdate" style="cursor:pointer;" type="text" name="rq1" id="rq1" value="<? echo $d1;?>" size="9" readonly />～<input onClick="WdatePicker();" maxlength="50" class="Wdate" style="cursor:pointer;" type="text" name="rq2" id="rq2" value="<? echo $d2;?>" size="9" readonly />&nbsp;
        <select name="department" onchange="form111.submit()">
            <option value="所有部门">所有部门</option>
            <option value="菜谱部" <?if($department=="菜谱部") echo "selected"; ?>>菜谱部</option>
            <option value="商务部" <?if($department=="商务部") echo "selected"; ?>>商务部</option>
            <option value="影像部" <?if($department=="影像部") echo "selected"; ?>>影像部</option>
            <option value="外协部" <?if($department=="外协部") echo "selected"; ?>>外协部</option>
            <option value="市场部" <?if($department=="市场部") echo "selected"; ?>>市场部</option>
<!--            <option value="其他" --><?//if($department=="其他") echo "selected"; ?><!-->其他</option>-->
        </select>

        <? include '../commonfile/calc_options.php'; ?>

        <font size="3" color="red">查询时间为结算扣款时间</font>&nbsp;<input name="bt1" type="submit" value="查 询" />
        &nbsp;&nbsp;<input type="submit" name="bt2" value="导 出"/>

        <?
    } ?>

<br><br>
<table>
	<tr>
	<th colspan="8"><?echo $department." ";echo $d1==$d2?$d1:$d1."~".$d2;?> 收款统计</th>
    </tr>
    <tr>
	<th></th>
        <th>订单数量</th>
    	<th>现金</th>
        <th>支票</th>
        <th>POS机招行</th>
        <th>汇款</th>
        <th>预存扣款</th>
        <th>小计</th>
    </tr>
    <tr>
    	<th>订单订金</th>
        <td><?echo $arr["订单订金"]["amount"];?></td>
    	<td><?echo $arr["订单订金"]["现金"];?></td>
        <td><?echo $arr["订单订金"]["支票"];?></td>
        <td><?echo $arr["订单订金"]["POS机招行"];?></td>
        <td><?echo $arr["订单订金"]["汇款"];?></td>
        <td><?echo $arr["订单订金"]["预存扣款"];?></td>
        <td><?echo $arr["订单订金"]["sum"];?></td>
    </tr>
    <tr>
    	<th>订单结算</th>
        <td><?echo $arr["订单结算"]["amount"];?></td>
    	<td><?echo $arr["订单结算"]["现金"];?></td>
        <td><?echo $arr["订单结算"]["支票"];?></td>
        <td><?echo $arr["订单结算"]["POS机招行"];?></td>
        <td><?echo $arr["订单结算"]["汇款"];?></td>
        <td><?echo $arr["订单结算"]["预存扣款"];?></td>
        <td><?echo $arr["订单结算"]["sum"];?></td>
    </tr>
    <tr>
    	<th>合计</th>
        <td><?echo $arr["订单结算"]["amount"]+$arr["订单订金"]["amount"];?></td>
    	<td><?echo $arr["订单结算"]["现金"]+$arr["订单订金"]["现金"];?></td>
        <td><?echo $arr["订单结算"]["支票"]+$arr["订单订金"]["支票"];?></td>
        <td><?echo $arr["订单结算"]["POS机招行"]+$arr["订单订金"]["POS机招行"];?></td>
        <td><?echo $arr["订单结算"]["汇款"]+$arr["订单订金"]["汇款"];?></td>
        <td><?echo $arr["订单结算"]["预存扣款"]+$arr["订单订金"]["预存扣款"];?></td>
        <td><?echo $arr["订单结算"]["sum"]+$arr["订单订金"]["sum"];?></td>
    </tr>
<?
?>
</table>
</body>
</html>
