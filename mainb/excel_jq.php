<? 
session_start();
require("../inc/conn.php");

if ($_SESSION["OK"]<>"OK") { 
echo "<script language=JavaScript>{window.location.href='error.php';}</script>";
exit; }

	@$d1=$_POST["rq1"];if ($d1=="") {$d1=date("Y-m-")."01";$ss="";$tss="全部信息";}
	@$d2=$_POST["rq2"];if ($d2=="") {$d2=date("Y-m-d");}
	@$d3=$_POST["fkhmc"];if ($d3=="") {$d3="%";}
	$skfs=$_POST["skfs"];if($skfs!="") $skfstj = " and xsbh='$skfs' ";else $skfstj="";?>
<script src="../js/WdatePicker.js" type="text/javascript" language="javascript"></script>
<form method="post" >
	按下单日期：<input onClick="WdatePicker();" maxlength="50" class="Wdate" style="width: 100px;" type="text" name="rq1" id="rq1" value="<? echo $d1;?>" />--<input onClick="WdatePicker();" maxlength="50" class="Wdate" style="width: 100px;" type="text" name="rq2" id="rq2" value="<? echo $d2;?>" />&nbsp;客户名称：<input type="text" name="fkhmc" width="15" value="<?echo $d3=="%"?"":$d3;?>"/>&nbsp;
	<input name="bt1" type="submit" value="查 询" />　　<input name="bt2" type="submit" value="导 出" /></form>
<?
/*
if(!$_POST["bt1"] && !$_POST["bt2"]){
	exit;
}
 */
@$dwdm = substr($_SESSION["GDWDM"],0,4);

$rs = mysql_query("select * from order_mxqt where ddh in (select order_mainqt.ddh from order_mainqt left join order_zh on order_mainqt.ddh=order_zh.ddh where order_zh.df>0 and order_mainqt.ddate>='$d1 00:00:00' and order_mainqt.ddate<='$d2 23:59:59' and order_mainqt.khmc like '%{$d3}%' and order_mainqt.state in ('待配送','订单完成') and order_mainqt.zzfy='$dwdm')",$conn);

$total = 0;
$total0 = 0;

while($a=mysql_fetch_assoc($rs)){
	if(isset($jq[$a["machine1"]])){
		$jq[$a["machine1"]]+=$a["pnum1"]*$a["sl1"];
		$fee[$a["machine1"]]+=$a["pnum1"]*$a["sl1"]*$a["jg1"];
	}else{
		$jq[$a["machine1"]]=$a["pnum1"]*$a["sl1"];
		$fee[$a["machine1"]]=$a["pnum1"]*$a["sl1"]*$a["jg1"];
	}

	if($a["n2"]<>""){
		if(isset($jq[$a["machine2"]])){
			$jq[$a["machine2"]]+=$a["pnum2"]*$a["sl2"];
			$fee[$a["machine2"]]+=$a["pnum2"]*$a["sl2"]*$a["jg2"];
		}else{
			$jq[$a["machine2"]]=$a["pnum2"]*$a["sl2"];
			$fee[$a["machine2"]]=$a["pnum2"]*$a["sl2"]*$a["jg2"];
		}		
	}
}

if($_POST["bt2"]){
header("Content-type:application/vnd.ms-excel;charset=UTF-8"); 
header("Content-Disposition:filename=".iconv("utf-8","gb2312","机器打印统计[".$d1."-".$d2."].xls"));
header("Expires:0");
header('Pragma:   public'   );
header("Cache-control:must-revalidate,post-check=0,pre-check=0");
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>名片工坊-业务管理</title>
	<script src="../js/WdatePicker.js" type="text/javascript" language="javascript"></script>

</head>

<body style="font-size:12px">

<span id='xxx' style="display:none"></span>
<table width="30%" border="0" cellspacing="0" cellpadding="0" style="padding:0px;">
  <tbody><tr>
    <td valign="top">
<div style="padding:15px 4px 22px 4px; color:#58595B">
		 	 <div class="bot_line"></div>
	        <div class="page">


<div id="AspNetPager2" style="width:100%;text-align:right;">

	<table cellspacing="0" cellpadding="0" rules="all" border="1" id="gvOrder" style="border-width:0px;width:100%;border-collapse:collapse;border:1px solid #D3D3D3; border-bottom:1px solid #D3D3D3; font-size:12px">
	  <tbody><tr class="td_title" style="height:30px;">
			<th  align="center" scope="col">机器</th>
			<th  align="center" scope="col">打印数量</th>
			<th  align="center" scope="col">打印金额</th>
		</tr>
<?if(isset($jq))foreach($jq as $key => $val) {?>
	
		<tr style="height:30px;">
			<td class="td_content" align="center" ><? echo $key?></td>
			<td class="td_content" align="center" ><? echo $val; $total0 += $val;?></td>
			<td class="td_content" align="center" ><? echo $fee[$key]; $total += $fee[$key];?></td>
		</tr>

	<? }?>
		<tr style="height:30px;">
			<td class="td_content" align="center" >合计</td>
			<td class="td_content" align="center" ><? echo $total0?></td>
			<td class="td_content" align="center" ><? echo $total;?></td>
		</tr>
		
	</tbody></table>
</div>
                  
<br>
		
</div>
	  
 </td>
  </tr>
</tbody></table>
    
</form>

</body></html>

