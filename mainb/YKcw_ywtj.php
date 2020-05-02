<? 
session_start();
require("inc/conn.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>名片工坊-业务统计</title>
    <link href="./css/CITICcss.css" rel="stylesheet" type="text/css">
	<script src="jsp/WdatePicker.js" type="text/javascript" language="javascript"></script>
<base target="_self" />
</head>

<body style="overflow-x:hidden;overflow-y:auto">
<Div style="width:700px; margin:0px auto;"><Div id=Calendar scrolling="no" style="border:0px solid #EEEEEE ;position: absolute; margin-top:150px; margin-left: 5px; width: 150; height: 137; z-index: 200; filter :\'progid:DXImageTransform.Microsoft.Shadow(direction=135,color=#AAAAAA,strength=4)\' ;display: none"></Div></Div>
<form name="form1" method="post" action="" id="form1">

<div></div>

<div class="main_box">

<table width="100%" border="0" cellspacing="0" cellpadding="0" style="padding:0px;">
  <tbody><tr>
    <td valign="top">   
<div style="margin-left:20px">
    
    B类系统： 客户总数：<? $rs=mysql_query("select count(id) from base_user where qx>0");echo mysql_result($rs,0,0);?> 制作名片人员总数：<? $rs=mysql_query("select count(id) from temp_ry");echo mysql_result($rs,0,0);?>
<br><br>
<? if ($_GET["dq"]<>"") $ssdq=base_decode($_GET["dq"]); else $ssdq="杭州"; ?>
<span style="width:100%;text-align:left;">
<select name="dq" onchange="document.getElementById('ww').innerHTML='请稍候...';window.location.href='YKcw_ywtj.php?dq='+this.options[this.selectedIndex].value">
<option value="<? echo base_encode("%");?>">全部</option>
<? $rs00=mysql_query("select distinct ssdq from ry_xs order by ssdq",$conn);
for ($kk=0;$kk<mysql_num_rows($rs00);$kk++) {
	$dq=mysql_result($rs00,$kk,0);
	if ($ssdq==$dq)
		echo "<option value='".base_encode($dq)."' selected>$dq</option>";
	else
		echo "<option value='".base_encode($dq)."'>$dq</option>";
}?>
</select>
</span>情况 <span id="ww" style="color:#F00"></span> 
	<table cellspacing="0" cellpadding="0" rules="all" border="1" id="gvOrder" style="border-width:0px;width:100%;border-collapse:collapse;border:1px solid #D3D3D3; border-bottom:0px;">
		<tbody><tr class="td_title" style="height:30px;">
			<th align="center" scope="col">月度</th>
			<th align="center" scope="col">当月名片销售</th>
			<th align="center" scope="col">当月名片收款</th>
			<th align="center" scope="col">新增用户数</th>
			<th align="center" scope="col">印名片人次</th>
			<th align="center" scope="col">当月非标销售</th>
			<th align="center" scope="col">当月非标收款</th>
		</tr>
    <? $sr=0;$xz=0;$rss=0;$jf=0;$qxs=0;$qsk=0;
	$sd1="";$sd2="";$sd3="";$sd4="";
	$cm=date("m");
	for ($m=1;$m<13;$m++) {
	if ($m>$cm) $d1=(date("Y")-1)."-".$m."-01"; else $d1=date("Y-").$m."-01";
	if ($m==12) $d2=(date("Y")+1)."-01-01"; else $d2=date("Y-").($m+1)."-01";
	if ($m>$cm) $d2=(date("Y")-1)."-".($m+1)."-01";
	$yd=$m>$cm?((date("Y")-1).".".$m):(date("Y.").$m);
	$rst=mysql_query("select * from cw_t_ywtj where yd='".$yd."' and dq='$ssdq'",$conn);
	if (mysql_num_rows($rst)==0 or $m==$cm or $_GET["refresh"]=="1") {  //没有保存,当月
	$rs=mysql_query("select sum(df),sum(jf) from user_zh,base_user b,ry_xs where b.xsbh=ry_xs.xsbh and user_zh.zh=b.zh and fssj>='$d1 00:00:00' and fssj<'$d2 00:00:00' and ssdq like '$ssdq' ",$conn);
	$rs2=mysql_query("select count(1) from base_user,ry_xs where base_user.xsbh=ry_xs.xsbh and zctime>='$d1 00:00:00' and zctime<'$d2 00:00:00' and ssdq like '$ssdq' and qx>0",$conn);
	$rs3=mysql_query("select count(1) from order_main,order_mx,base_user,ry_xs where base_user.xsbh=ry_xs.xsbh and order_main.user=base_user.zh and order_main.ddh=order_mx.ddh and ddate>='$d1 00:00:00' and ddate<'$d2 00:00:00' and ssdq like '$ssdq' and left(order_main.state,4)<>'订单取消' and order_main.state<>'待审核' and order_main.state<>'不生产' and order_main.state<>'待付款'",$conn); 
	$rs41=mysql_query("select sum(dje+ifnull(kdje,0)) from nc_erp.order_mainqt m,ry_xs where m.xsbh=ry_xs.xsbh and ddate>='$d1 00:00:00' and ddate<'$d2 00:00:00' and ssdq like '$ssdq' and (state='已收款' or state='待生产' or state='进入生产' or state='订单完成')",$conn);
	$rs42=mysql_query("select sum(dje+ifnull(kdje,0)) from nc_erp.order_mainqt m,ry_xs where m.xsbh=ry_xs.xsbh and ddate>='$d1 00:00:00' and ddate<'$d2 00:00:00' and ssdq like '$ssdq' and (state='已收款')",$conn);?>
        <tr>
			<td align="center" class="td_content" ><? if ($m>date("m")) echo "<font color='#FF0000'>",date("Y")-1,".",$m,"</font>";else echo date("Y."),$m;?></td>
            <td align="right" class="td_content" ><a href="javascript:void(0)" onClick="javascript:window.open('YKcw_ywtj_dfmx.php?d1=<? echo $d1;?>&d2=<? echo $d2;?>&dq=<? echo urlencode($ssdq)?>','Orderbj','height=600px,width=720px,top=150px,left=250px,location=no,menubar=no,resizable=no,scrollbars=yes,status=no,titlebar=no,toolbar=no,directories=no');return false;" class="a1"><? echo mysql_result($rs,0,0);$sd1=$sd1.(mysql_result($rs,0,0)==""?0:mysql_result($rs,0,0)).",";?></a></td>
            <td align="right" class="td_content" ><a href="javascript:void(0)" onClick="javascript:window.open('YKcw_ywtj_jfmx.php?d1=<? echo $d1;?>&d2=<? echo $d2;?>&dq=<? echo urlencode($ssdq)?>','Orderbj','height=600px,width=720px,top=150px,left=250px,location=no,menubar=no,resizable=no,scrollbars=yes,status=no,titlebar=no,toolbar=no,directories=no');return false;" class="a1"><? echo mysql_result($rs,0,1);$sd2=$sd2.(mysql_result($rs,0,1)==""?0:mysql_result($rs,0,1)).",";?></a></td>
            <td class="td_content" align="right" ><a href="javascript:void(0)" onClick="javascript:window.open('YKcw_ywtj_newzh.php?d1=<? echo $d1;?>&d2=<? echo $d2;?>&dq=<? echo urlencode($ssdq)?>','Orderbj5','height=600px,width=720px,top=150px,left=250px,location=no,menubar=no,resizable=no,scrollbars=yes,status=no,titlebar=no,toolbar=no,directories=no');return false;" class="a1"><? echo mysql_result($rs2,0,0);$sd4=$sd4.(mysql_result($rs2,0,0)==""?0:mysql_result($rs2,0,0)).",";?></a></td>
            <td class="td_content" align="right" ><? echo mysql_result($rs3,0,0);?></td>
            <td class="td_content" align="right" ><a href="javascript:void(0)" onClick="javascript:window.open('YKcw_ywtj_qtmx.php?d1=<? echo $d1;?>&d2=<? echo $d2;?>&dq=<? echo urlencode($ssdq)?>','Orderbj3','height=600px,width=720px,top=150px,left=250px,location=no,menubar=no,resizable=no,scrollbars=yes,status=no,titlebar=no,toolbar=no,directories=no');return false;" class="a1"><? echo mysql_result($rs41,0,0);$sd3=$sd3.(mysql_result($rs41,0,0)==""?0:mysql_result($rs41,0,0)).",";?></a></td>
            <td class="td_content" align="right" ><? echo mysql_result($rs42,0,0);?></td>
		</tr>
        <? $sr+=mysql_result($rs,0,0);$jf+=mysql_result($rs,0,1);$xz+=mysql_result($rs2,0,0);$rss+=mysql_result($rs3,0,0);$qxs+=mysql_result($rs41,0,0);$qsk+=mysql_result($rs42,0,0);
		mysql_query("delete from cw_t_ywtj where yd='".$yd."' and dq='$ssdq'",$conn);
		mysql_query("insert into cw_t_ywtj (yd,dymp,dympsk,xzyh,ymprc,dyfb,dyfbsk,dq) values ('$yd','".mysql_result($rs,0,0)."','".mysql_result($rs,0,1)."','".mysql_result($rs2,0,0)."','".mysql_result($rs3,0,0)."','".mysql_result($rs41,0,0)."','".mysql_result($rs42,0,0)."','$ssdq')",$conn);
		 
	} else {  //从临时表取数据?>
        <tr>
			<td align="center" class="td_content" ><? if ($m>date("m")) echo "<font color='#FF0000'>",date("Y")-1,".",$m,"</font>";else echo date("Y."),$m;?></td>
            <td align="right" class="td_content" ><a href="javascript:void(0)" onClick="javascript:window.open('YKcw_ywtj_dfmx.php?d1=<? echo $d1;?>&d2=<? echo $d2;?>&dq=<? echo urlencode($ssdq)?>','Orderbj','height=600px,width=720px,top=150px,left=250px,location=no,menubar=no,resizable=no,scrollbars=yes,status=no,titlebar=no,toolbar=no,directories=no');return false;" class="a1"><? echo mysql_result($rst,0,1);$sd1=$sd1.(mysql_result($rst,0,1)==""?0:mysql_result($rst,0,1)).",";?></a></td>
            <td align="right" class="td_content" ><a href="javascript:void(0)" onClick="javascript:window.open('YKcw_ywtj_jfmx.php?d1=<? echo $d1;?>&d2=<? echo $d2;?>&dq=<? echo urlencode($ssdq)?>','Orderbj','height=600px,width=720px,top=150px,left=250px,location=no,menubar=no,resizable=no,scrollbars=yes,status=no,titlebar=no,toolbar=no,directories=no');return false;" class="a1"><? echo mysql_result($rst,0,2);$sd2=$sd2.(mysql_result($rst,0,2)==""?0:mysql_result($rst,0,2)).",";?></a></td>
            <td class="td_content" align="right" ><a href="javascript:void(0)" onClick="javascript:window.open('YKcw_ywtj_newzh.php?d1=<? echo $d1;?>&d2=<? echo $d2;?>&dq=<? echo urlencode($ssdq)?>','Orderbj5','height=600px,width=720px,top=150px,left=250px,location=no,menubar=no,resizable=no,scrollbars=yes,status=no,titlebar=no,toolbar=no,directories=no');return false;" class="a1"><? echo mysql_result($rst,0,3);$sd4=$sd4.(mysql_result($rst,0,3)==""?0:mysql_result($rst,0,3)).",";?></a></td>
            <td class="td_content" align="right" ><? echo mysql_result($rst,0,4);?></td>
            <td class="td_content" align="right" ><a href="javascript:void(0)" onClick="javascript:window.open('YKcw_ywtj_qtmx.php?d1=<? echo $d1;?>&d2=<? echo $d2;?>&dq=<? echo urlencode($ssdq)?>','Orderbj3','height=600px,width=720px,top=150px,left=250px,location=no,menubar=no,resizable=no,scrollbars=yes,status=no,titlebar=no,toolbar=no,directories=no');return false;" class="a1"><? echo mysql_result($rst,0,5);$sd3=$sd3.(mysql_result($rst,0,5)==""?0:mysql_result($rst,0,5)).",";?></a></td>
            <td class="td_content" align="right" ><? echo mysql_result($rst,0,6);?></td>
		</tr>
        
        <? $sr+=mysql_result($rst,0,1);$jf+=mysql_result($rst,0,2);$xz+=mysql_result($rst,0,3);$rss+=mysql_result($rst,0,4);$qxs+=mysql_result($rst,0,5);$qsk+=mysql_result($rst,0,6); }
	}?>
		 <tr>
			<td align="center" class="td_content" ><strong>合计</strong></td>
            <td align="right" class="td_content" ><? echo $sr;?></td>
            <td align="right" class="td_content" ><? echo $jf;?></td>
            <td class="td_content" align="right" ><? echo $xz;?></td>
            <td class="td_content" align="right" ><? echo $rss;?></td>
            <td align="right" class="td_content" ><? echo $qxs;?></td>
            <td align="right" class="td_content" ><? echo $qsk;?></td>
		</tr>
	</tbody></table>

</div>

 </td>
  </tr>
</tbody></table>
    
</div>

</div>
<p><a href="javascript:void(0)" onClick="javascript:window.open('YKchart.php?sd1=<? echo substr($sd1,0,-1);?>&sd1s=<? echo urlencode('当月名片销售')?>&sd2=<? echo substr($sd2,0,-1);?>&sd2s=<? echo urlencode('当月名片收款')?>&sd3=<? echo substr($sd3,0,-1);?>&sd3s=<? echo urlencode('当月其他销售')?>','Orderbj3','height=600px,width=720px,top=150px,left=250px,location=no,menubar=no,resizable=no,scrollbars=yes,status=no,titlebar=no,toolbar=no,directories=no');return false;" class="a1">销售曲线图</a>　　<a href="javascript:void(0)" onClick="javascript:window.open('YKchart.php?sd1=<? echo substr($sd4,0,-1);?>&sd1s=<? echo urlencode('新增用户')?>','Orderbj3','height=600px,width=720px,top=150px,left=250px,location=no,menubar=no,resizable=no,scrollbars=yes,status=no,titlebar=no,toolbar=no,directories=no');return false;" class="a1">新增用户曲线图</a>
</p>
</form>

</body></html>
