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
	按下单日期：<input onClick="WdatePicker();" maxlength="50" class="Wdate" style="width: 100px;" type="text" name="rq1" id="rq1" value="<? echo $d1;?>" />--<input onClick="WdatePicker();" maxlength="50" class="Wdate" style="width: 100px;" type="text" name="rq2" id="rq2" value="<? echo $d2;?>" />&nbsp;客户名称：<input type="text" name="fkhmc" width="15" value="<?echo $d3=="%"?"":$d3;?>"/>&nbsp
	<input name="bt1" type="submit" value="查 询" />　　<input name="bt2" type="submit" value="导 出" /></form>
<?
/*
if(!$_POST["bt1"] && !$_POST["bt2"]){
	exit;
}
 */
@$dwdm = substr($_SESSION["GDWDM"],0,4);

$rs = mysql_query("select jgfs,sum(sl) sl,sum(sl*jg) zje from order_mxqt_hd where mxid in (select id from order_mxqt where ddh in (select order_mainqt.ddh from order_mainqt left join order_zh on order_mainqt.ddh=order_zh.ddh where df>0 and ddate>='$d1 00:00:00' and ddate<='$d2 23:59:59' and order_mainqt.khmc like '%{$d3}%' and state in ('待配送','订单完成') and zzfy='$dwdm')) group by jgfs",$conn);
if($_POST["bt2"]){
header("Content-type:application/vnd.ms-excel;charset=UTF-8"); 
header("Content-Disposition:filename=".iconv("utf-8","gb2312","后加工统计[".$d1."-".$d2."].xls"));
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
			<th  align="center" scope="col">后加工方式</th>
			<th  align="center" scope="col">数量</th>
			<th  align="center" scope="col">总金额</th>
		</tr>
<? 

$total = 0;
$total0 = 0;

while($a=mysql_fetch_array($rs)){if($a["jgfs"]==-1) continue;?>
	
		<tr style="height:30px;">
			<td class="td_content" align="center" ><? echo $a["jgfs"];?></td>
			<td class="td_content" align="center" ><? echo $a["sl"]; $total0 += $a["sl"];?></td>
			<td class="td_content" align="center" ><? echo $a["zje"]; $total += $a["zje"];?></td>
		</tr>

	<? }?>
		<tr style="height:30px;">
			<td class="td_content" align="center" >合计</td>
			<td class="td_content" align="center" ><? echo $total0;?></td>
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

