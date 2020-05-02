<?php
require("../../inc/conn.php");
@$d1=$_POST["rq1"];if ($d1=="") {$d1=date("Y-m-")."01";$ss="";$tss="全部信息";}
@$d2=$_POST["rq2"];if ($d2=="") {$d2=date("Y-m-d");}
@$d3=$_POST["fkhmc"];if ($d3=="") {$d3="%";}

$sql = "select base_kh.id,order_zh.khmc,sum(jf) from order_zh,base_kh where jf>0 and zy<>'订单结算' and zy<>'订单定金' and locate('预存赠送',zy)=0 and locate('预存赠送',order_zh.xsbh)=0 and order_zh.khmc=base_kh.khmc and base_kh.gdzk=3301 and order_zh.fssj>='$d1 00:00:00' and order_zh.fssj<='$d2 23:59:59' and order_zh.khmc like '$d3' group by khmc";
$rs = mysql_query($sql, $conn);


if($_POST["btn2"]){
    header("Content-type:application/vnd.ms-excel;charset=UTF-8");
    header("Content-Disposition:filename=".iconv("utf-8","gb2312","客户累计充值记录[".$d1."-".$d2."].xls"));
    header("Expires:0");
    header('Pragma:   public'   );
    header("Cache-control:must-revalidate,post-check=0,pre-check=0");
}


?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <style>
        table {
            border-collapse: collapse;
            border: none;
        }
        th, td {
            border: 1px solid;
        }
        a {
            text-decoration: none;
            color: #000000;
        }
    </style>
    <script src="../../js/WdatePicker.js" type="text/javascript"></script>
</head>

<form action="" method="post">按日期：<input onClick="WdatePicker();" maxlength="50" class="Wdate" style="cursor:pointer;" type="text" name="rq1" id="rq1" value="<? echo $d1;?>" size="9" readonly />～<input onClick="WdatePicker();" maxlength="50" class="Wdate" style="cursor:pointer;" type="text" name="rq2" id="rq2" value="<? echo $d2;?>" size="9" readonly />&nbsp;客户名称：<input type="text" name="fkhmc" width="15" value="<?echo $d3=="%"?"":$d3;?>"/>&nbsp;&nbsp;<input type="submit" name="btn1" value="查询" />&nbsp;&nbsp;<input type="submit" name="btn2" value="导出" /></form>
<body><!--
<form action="" method="post">按日期：<input onClick="WdatePicker();" maxlength="50" class="Wdate" style="cursor:pointer;" type="text" name="rq1" id="rq1" value="<? echo $d1;?>" size="9" readonly />～<input onClick="WdatePicker();" maxlength="50" class="Wdate" style="cursor:pointer;" type="text" name="rq2" id="rq2" value="<? echo $d2;?>" size="9" readonly />&nbsp;客户名称：<input type="text" name="fkhmc" width="15" value="<?echo $d3=="%"?"":$d3;?>"/>&nbsp;&nbsp;<input type="submit" name="btn1" value="查询" />&nbsp;&nbsp;<input type="submit" name="btn2" value="导出" /></form>-->
<br>
<tbody>
<table>
    <tr>
        <th>客户名称</th>
        <th>累计预存</th>
    </tr>
    <?$total = 0; while($row = mysql_fetch_array($rs)){	?>
        <tr>
            <td><a href="javascript:void(0)" onclick='javascript:window.open("KH_czxq.php?khmc=<?echo $row[1]?>&d1=<?echo $d1?>&d2=<?echo $d2?>", "HT_dhdj", "toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes,copyhistory=yes,width=1000,height=350,left=300,top=100")'><?echo $row[1]?></a></td>
            <td><?echo $row[2]; $total+=$row[2]?></td>
            <!--<td><?echo $row[3];?></td>
		<td><?echo $row[4];?></td>-->
        </tr>
    <? } ?>
    <tr>
        <td>合计</td>
        <td><?echo $total?></td>
    </tr>
</table>
</tbody>
</body>
</html>
