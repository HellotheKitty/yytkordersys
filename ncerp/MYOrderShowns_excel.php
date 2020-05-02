<? 
session_start();
require("../inc/conn.php");

if ($_SESSION["OK"]<>"OK") { 
echo "<script language=JavaScript>{window.location.href='error.php';}</script>";
exit; }?>

<? 
$dwdm = substr($_SESSION["GDWDM"],0,4);

	$d1=$_GET["rq1"];if ($d1=="") {$d1=date("Y-m-")."01";$ss="";$tss="全部信息";}
	$d2=$_GET["rq2"];if ($d2=="") {$d2=date("Y-m-d");}
	$d3=$_GET["fkhmc"];if ($d3=="") {$d3="%";}
	$rs=mysql_query("select order_mainqt.*,count(order_mxqt.id) mxsl,group_concat(order_mxqt.productname) cpms,xs.xm,sksj from b_ry xs,order_mainqt left join order_mxqt on order_mainqt.ddh=order_mxqt.ddh left join order_zh on order_mainqt.ddh=order_zh.ddh and zy<>'订单订金' where order_mainqt.xsbh=xs.bh and order_mainqt.state='订单完成' and ddate>='$d1 00:00:00' and ddate<='$d2 23:59:59' and order_mainqt.khmc like '%{$d3}%' and zzfy='".substr($_SESSION["GDWDM"],0,4)."' group by order_mainqt.ddh order by ddate desc",$conn);

header("Content-type:application/vnd.ms-excel;charset=UTF-8"); 
header("Content-Disposition:filename=".iconv("utf-8","gb2312","订单导出[".$d1."-".$d2."].xls"));
header("Expires:0");
header('Pragma:   public'   );
header("Cache-control:must-revalidate,post-check=0,pre-check=0");															
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>名片工坊-业务管理</title>
    
    
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
	<table width="100%" border="0" cellpadding="0" cellspacing="0">
		<tbody><tr>
			<td valign="bottom" align="left" nowrap="true" style="width:40%;"></td><td valign="bottom" align="right" nowrap="true" class="" style="width:60%;"></td>
		</tr>
	</tbody></table>
	<table cellspacing="0" cellpadding="0" rules="all" border="1" id="gvOrder" style="border-width:0px;width:100%;border-collapse:collapse;border:1px solid #D3D3D3; border-bottom:1px solid #D3D3D3; font-size:12px">
	  <tbody><tr class="td_title" style="height:30px;">
			<th  align="center" scope="col">客服</th>
            <th  align="center" scope="col">订单编号</th>
			<th  align="center" scope="col">客户名称</th>
			<th  align="center" scope="col">订购时间</th>
			<th  align="center" scope="col">要求完成</th>
            <th  align="center" scope="col">订单金额</th>
            <th  align="center" scope="col">预付定金</th>
            <th  align="center" scope="col">配送金额</th>
            <th  align="center" scope="col">配送要求</th>
            <th  align="center" scope="col">生产地</th>
            <th  align="center" scope="col">订单状态</th>
            <th  align="center" scope="col">操作</th>
		</tr>
        <? for($i=0;$i<mysql_num_rows($rs);$i++){  ?>
        <tr class="td_title" style="height:30px;" >
			<td class="td_content" align="left" ><? echo mysql_result($rs,$i,"xsbh"),'-',mysql_result($rs,$i,"xm");?></td>
            <td align="center" class="td_content" style="width:100px"><? echo mysql_result($rs,$i,"ddh");?><br>
			<? echo "[明细：",mysql_result($rs,$i,"mxsl"),"]";
			?>
             
               </td>
            <td align="center" class="td_content" ><span class="td_content" ><? echo mysql_result($rs,$i,"khmc");?><br><font color="#FFCCCC"><? echo "[",mysql_result($rs,$i,"cpms"),"]";?></font></span></td>
            <td align="center" class="td_content"  style="width:80px"><? echo mysql_result($rs,$i,"ddate");?></td>
            <td align="center" class="td_content"  style="width:80px"><? echo mysql_result($rs,$i,"yqwctime");?></td>
            <td align="center" class="td_content" ><span style="color:Red;"><? echo mysql_result($rs,$i,"dje");?></span>元</td>
            <td align="center" class="td_content" ><span style="color:Red;"><? echo mysql_result($rs,$i,"djje");?></span>元</td>
            <td align="center" class="td_content" ><span style="color:Red;"><? echo mysql_result($rs,$i,"kdje");?></span>元</td>
            <td align="center" class="td_content" ><? echo mysql_result($rs,$i,"psfs");?></td>
            <td class="td_content" align="center" ><? echo mysql_result($rs,$i,"scjd");?></td>
            <td class="td_content" align="center" ><? echo mysql_result($rs,$i,"state");
			if (mysql_result($rs,$i,"sksj")<>'') echo "<br>[已收款：",mysql_result($rs,$i,"sksj"),"]";?></td>
            <td class="td_content" align="center" >
			</td>
		</tr>
        <? }?>
	</tbody></table>
</div>
                  
<br>
		
		  </div>
	  
 </td>
  </tr>
</tbody></table>
    
</form>

</body></html>

