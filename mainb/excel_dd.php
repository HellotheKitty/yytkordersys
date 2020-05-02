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
	按日期：<input onClick="WdatePicker();" maxlength="50" class="Wdate" style="width: 100px;" type="text" name="rq1" id="rq1" value="<? echo $d1;?>" />--<input onClick="WdatePicker();" maxlength="50" class="Wdate" style="width: 100px;" type="text" name="rq2" id="rq2" value="<? echo $d2;?>" />&nbsp;客户名称：<input type="text" name="fkhmc" width="15" value="<?echo $d3=="%"?"":$d3;?>"/>&nbsp;收款方式：<select name="skfs"><option value="">全部</option><option value="预存扣款"<?if($skfs=="预存扣款") echo " selected"?>>预存扣款</option><option value="现金"<?if($skfs=="现金") echo " selected"?>>现金</option><option value="支票"<?if($skfs=="支票") echo " selected"?>>支票</option><option value="POS刷卡" <?if($skfs=="POS刷卡") echo " selected"?>>POS刷卡</option><option value="汇款"<?if($skfs=="汇款") echo " selected"?>>汇款</option></select>
	<input name="bt1" type="submit" value="查 询" /><?if($_POST["bt1"]) {?>　　<input name="bt2" type="submit" value="导 出" /><?}?></form>
<?
if(!$_POST["bt1"] && !$_POST["bt2"]){
	echo "查询的时间为PS下单时间，查找的结果为已结算的订单，这些订单可能未生成配送单或暂未配送。";
	exit;
}
@$dwdm = substr($_SESSION["GDWDM"],0,4);


//	$rs=mysql_query("select order_mainqt.*,count(order_mxqt.id) mxsl,group_concat(order_mxqt.productname) cpms,xs.xm,sksj from b_ry xs,order_mainqt left join order_mxqt on order_mainqt.ddh=order_mxqt.ddh left join order_zh on order_mainqt.ddh=order_zh.ddh and zy<>'订单订金' where order_mainqt.xsbh=xs.bh and order_mainqt.state='订单完成' and ddate>='$d1 00:00:00' and ddate<='$d2 23:59:59' and order_mainqt.khmc like '%{$d3}%' and zzfy='".substr($_SESSION["GDWDM"],0,4)."' group by order_mainqt.ddh order by ddate desc",$conn);
//	$rs=mysql_query("select main.ddh,main.kydh,ry.bh,ry.xm,main.khmc,main.sdate,main.dje+main.kdje je,main.djje,zh.df,zh.xsbh,zh.sksj,main.skbz from order_mainqt main inner join order_zh zh on (main.state='订单完成') and main.zzfy=3301 and zh.ddh=main.ddh inner join b_ry ry on main.xsbh=ry.bh group by main.ddh",$conn);
$rs=mysql_query("select * from order_mainqt where ddate>='$d1 00:00:00' and ddate<='$d2 23:59:59' and order_mainqt.khmc like '%{$d3}%' and state in ('待配送','订单完成') and zzfy='$dwdm'",$conn);
$tdje = 0;
$tdjje = 0;
$tskje = 0;
if($_POST["bt2"]){
header("Content-type:application/vnd.ms-excel;charset=UTF-8"); 
header("Content-Disposition:filename=".iconv("utf-8","gb2312","送货单导出[".$d1."-".$d2."].xls"));
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
<style>
a {
	text-decoration: none;
	color: black;
}
a:hover {
	text-decoration: underline;
	color: blue;
}
</style>
</head>

<body style="font-size:12px">

<span id='xxx' style="display:none"></span>
<table width="100%" border="0" cellspacing="0" cellpadding="0" style="padding:0px;">
  <tbody><tr>
    <td valign="top">
<div style="padding:15px 4px 22px 4px; color:#58595B">
		 	 <div class="bot_line"></div>
	        <div class="page">


<div id="AspNetPager2" style="width:100%;text-align:right;">

	<table cellspacing="0" cellpadding="0" rules="all" border="1" id="gvOrder" style="border-width:0px;width:100%;border-collapse:collapse;border:1px solid #D3D3D3; border-bottom:1px solid #D3D3D3; font-size:12px">
	  <tbody><tr class="td_title" style="height:30px;">
			<th  align="center" scope="col">订单号</th>
            <!--<th  align="center" scope="col">配送单号</th>-->
			<th  align="center" scope="col">下单客服</th>
			<th  align="center" scope="col">客户名称</th>
			<!--<th  align="center" scope="col">开单时间</th>-->
			<th  align="center" scope="col">订单金额</th>
            <th  align="center" scope="col">预付定金</th>
            <th  align="center" scope="col">收款金额</th>
            <th  align="center" scope="col">收款方式</th>
            <th  align="center" scope="col">收款时间</th>
            <th  align="center" scope="col">收款备注</th>
		</tr>
<? for($i=0;$i<mysql_num_rows($rs);$i++){
	
		$skrs = mysql_query("select * from order_zh where ddh='".mysql_result($rs,$i,"ddh")."' $skfstj and zy<>'订单订金' and df>0",$conn);
//		$skrs = mysql_query("select sksj from order_zh where ddh='".mysql_result($rs,$i,"ddh")."' and df>0",$conn);
		if(!$skrs || mysql_num_rows($skrs)<=0) continue;
		$sksj = mysql_result($skrs,0,"sksj");
			if(@!$xsxm[mysql_result($rs,$i,"xsbh")]){
				$kfrs = mysql_query("select xm from b_ry where bh='".mysql_result($rs,$i,"xsbh")."'",$conn);
				$xsxm[mysql_result($rs,$i,"xsbh")] = mysql_result($kfrs,0,"xm");
			}

			?>
		<tr style="height:30px;">
			<td class="td_content" align="center" ><a href="javascript:void(0)" onclick="javascript:window.open('../ncerp/jcsj/NS_new.php?ddh=<? echo mysql_result($rs,$i,"ddh");?>','OrderDetail','height=600px,width=920px,top=100px,left=200px,location=no,menubar=no,resizable=no,scrollbars=yes,status=no,titlebar=no,toolbar=no,directories=no');return false;"  class="a1"><? echo mysql_result($rs,$i,"ddh");?></td></a>
			<!--<td class="td_content" align="center" ><? echo mysql_result($rs,$i,"kydh");?></td>-->
			<td class="td_content" align="center" ><? echo $xsxm[mysql_result($rs,$i,"xsbh")];?></td>
			<td class="td_content" align="center" ><? echo mysql_result($rs,$i,"khmc");?></td>
			<!--<td class="td_content" align="center" ><? echo $sksj;// mysql_result($rs,$i,"sjpssj");?></td>-->
			<td class="td_content" align="center" ><? echo mysql_result($rs,$i,"dje"); $tdje+=mysql_result($rs,$i,"dje");?></td>
			<td class="td_content" align="center" ><? echo mysql_result($rs,$i,"djje"); $tdjje+=mysql_result($rs,$i,"djje");?></td>
			<td class="td_content" align="center" ><? if($skrs and mysql_num_rows($skrs)>0){echo mysql_result($skrs,0,"df");$tskje+=mysql_result($skrs,0,"df");}?></td>
			<td class="td_content" align="center" ><? if($skrs and mysql_num_rows($skrs)>0)echo mysql_result($skrs,0,"xsbh");?></td>
			<td class="td_content" align="center" ><? if($skrs and mysql_num_rows($skrs)>0)echo mysql_result($skrs,0,"sksj");?></td>
			<td class="td_content" align="center" ><? echo mysql_result($rs,$i,"skbz");?></td>
		</tr>

	<? }?>
		<tr style="height:30px;">
			<td class="td_content" align="center" colspan='3'>合计</td>
			<!--<td class="td_content" align="center" ><? //echo mysql_result($rs,$i,"kydh");?></td>-->
			<!--<td class="td_content" align="center" ></td>
			<td class="td_content" align="center" ></td>-->
			<!--<td class="td_content" align="center" ><? echo $sksj;// mysql_result($rs,$i,"sjpssj");?></td>-->
			<td class="td_content" align="center" ><? echo $tdje;//mysql_result($rs,$i,"dje"); $tdje+=mysql_result($rs,$i,"dje");?></td>
			<td class="td_content" align="center" ><? echo $tdjje;//mysql_result($rs,$i,"djje"); $tdjje+=mysql_result($rs,$i,"djje");?></td>
			<td class="td_content" align="center" ><? echo $tskje;//if($skrs and mysql_num_rows($skrs)>0){echo mysql_result($skrs,0,"df");$tskje+=mysql_result($skrs,0,"df");}?></td>
			<td class="td_content" align="center" ></td>
			<td class="td_content" align="center" ></td>
			<td class="td_content" align="center" ></td>
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

