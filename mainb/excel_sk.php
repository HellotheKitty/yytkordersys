<? 
session_start();
require("../inc/conn.php");

if ($_SESSION["OK"]<>"OK") { 
echo "<script language=JavaScript>{window.location.href='error.php';}</script>";
exit; }

	@$d1=$_POST["rq1"];if ($d1=="") {$d1=date("Y-m-")."01";$ss="";$tss="全部信息";}
	@$d2=$_POST["rq2"];if ($d2=="") {$d2=date("Y-m-d");}
	@$d3=$_POST["fkhmc"];if ($d3=="") {$d3="%";}
	$skfs=$_POST["skfs"];if($skfs!="") $skfstj = " and z.xsbh='$skfs' ";else $skfstj="";
	$type = "sk";
	if($_POST["ps"])
		$type = "ps";
	if($type == "sk"){
		$sjtj = "z.fssj";
	} else {
		$sjtj = "ddate";
	}
?>

<script src="../js/WdatePicker.js" type="text/javascript" language="javascript"></script>
<form method="post" >
	根据<select name="datetype"><option value="sk">收款时间</option><option value="ps" <?if($type=="ps") echo "selected"?>>PS下单时间</option></select>查询：<input onClick="WdatePicker();" maxlength="50" class="Wdate" style="cursor:pointer;" type="text" name="rq1" id="rq1" value="<? echo $d1;?>" size="9" readonly />～<input onClick="WdatePicker();" maxlength="50" class="Wdate" style="cursor:pointer;" type="text" name="rq2" id="rq2" value="<? echo $d2;?>" size="9" readonly />&nbsp;客户名称：<input type="text" name="fkhmc" width="15" value="<?echo $d3=="%"?"":$d3;?>"/>&nbsp;收款方式：<select name="skfs"><option value="">全部</option><option value="预存扣款"<?if($skfs=="预存扣款") echo " selected"?>>预存扣款</option><option value="现金"<?if($skfs=="现金") echo " selected"?>>现金</option><option value="支票"<?if($skfs=="支票") echo " selected"?>>支票</option><option value="POS刷卡" <?if($skfs=="POS刷卡") echo " selected"?>>POS刷卡</option><option value="汇款"<?if($skfs=="汇款") echo " selected"?>>汇款</option></select>
	<input type="checkbox" name="onlydiff" id="onlydiff" <?if($_POST["onlydiff"]=="on") echo "checked"?>><label for="onlydiff">只查看结算与订单金额不同的订单</label>　　<input name="bt1" type="submit" value="查 询" /><?if($_POST["bt1"]) {?>　　<input name="bt2" type="submit" value="导 出" /><?}?></form>
<?
if(!$_POST["bt1"] && !$_POST["bt2"]){
	echo "查询的时间可选<B>收款时间</B>(默认)或<B>PS下单时间</B>，查找的结果为已结算的订单，这些订单可能未生成配送单或暂未配送。";
	exit;
}
@$dwdm = substr($_SESSION["GDWDM"],0,4);


$rs = mysql_query("select m.ddh ddh,m.khmc khmc,m.dje dje,m.djje djje,z.df df,z.xsbh skfs,z.sksj sksj,m.skbz skbz from order_mainqt m left join order_zh z on m.ddh=z.ddh where $sjtj>='$d1 00:00:00' and $sjtj<='$d2 23:59:59' and m.khmc like '%{$d3}%' and state in ('待配送','订单完成') and zzfy='$dwdm' and z.zy='订单结算' and z.df>0 $skfstj",$conn);

if($_POST["bt2"]){
header("Content-type:application/vnd.ms-excel;charset=UTF-8"); 
header("Content-Disposition:filename=".iconv("utf-8","gb2312","收款单导出[".$d1."-".$d2."].xls"));
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
	<script src="../js/jquery-1.8.3.min.js" type="text/javascript" language="javascript"></script>

<script>
function del(ddh){
	if(confirm("确认删除？删除后不可恢复！")) {
		if(confirm("再次确认")) {
			$.ajax({
				type: "GET",
				url: "delorder_ajax.php?ddh="+ddh,
				async: true,
				success: function(data) {
					if(data == '1') {
						$("#ddh"+ddh).html(ddh);
						$("#del"+ddh).html("已删除");
					} else {
						alert("delete failed,plz retry.");
					}	
				},
				error: function() {
					alert("delete failed,plz retry.");
				}
			});
		}
	}
}
</script>
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
			<th  align="center" scope="col">客户名称</th>
           		<th  align="center" scope="col">收款时间</th>
           		<th  align="center" scope="col">收款方式</th>
			<th  align="center" scope="col">订单金额</th>
			<th  align="center" scope="col">已结算金额</th>
			<th  align="center" scope="col">待结算金额</th>
			<? if(!$_POST["bt2"]) { ?> <th  align="center" scope="col">操作</th> <? } ?>
		</tr>
<?//$t = mysql_num_rows($rs); for($i=0;$i<$t;$i++)

	$t_dje = 0;
	$t_df = 0;
	$t_left = 0;
	while($row=mysql_fetch_assoc($rs)){
		$left = round($row["dje"]-$row["djje"]-$row["df"], 2);
		if($_POST["onlydiff"] == "on" && $left == 0)
			continue;
			?>
		<tr style="height:30px;">
			<td class="td_content" align="center" id="ddh<?echo $row["ddh"]?>" ><a href="javascript:void(0)" onclick="javascript:window.open('../ncerp/jcsj/orderdetail.php?ddh=<? echo $row["ddh"];?>','OrderDetail','height=600px,width=920px,top=100px,left=200px,location=no,menubar=no,resizable=no,scrollbars=yes,status=no,titlebar=no,toolbar=no,directories=no');return false;"  class="a1"><? echo $row["ddh"];?></td></a>
			<td class="td_content" align="center" ><? echo $row["khmc"];?></td>
			<td class="td_content" align="center" ><? echo $row["sksj"];?></td>
			<td class="td_content" align="center" ><? echo $row["skfs"];?></td>
			<td class="td_content" align="center" ><? echo $left==0?$row["dje"]:"<font color='red'>".$row["dje"]."</font>"; 	$t_dje+=$row["dje"];?></td>
			<td class="td_content" align="center" ><? echo $left==0?$row["df"]:"<font color='red'>".$row["df"]."</font>";	$t_df+=$row["df"];?></td>
			<td class="td_content" align="center" ><? echo $left==0?$left:"<font color='red'>".$left."</font>";	$t_left+=$left;?></td>
			<? if(!$_POST["bt2"]) { ?> <td class="td_content" align="center" id="del<?echo $row["ddh"]?>" ><button onclick="del(<?echo $row["ddh"]?>)">删除</button></td> <? } ?>
		</tr>

	<? }?>
		<tr style="height:30px;">
			<td class="td_content" align="center" colspan='4'>合计</td>
			<td class="td_content" align="center" ><? echo $t_dje;?></td>
			<td class="td_content" align="center" ><? echo $t_df;?></td>
			<td class="td_content" align="center" ><? echo $t_left;?></td>
			<? if(!$_POST["bt2"]) { ?> <td class="td_content" align="center" ></td> <? } ?>
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

