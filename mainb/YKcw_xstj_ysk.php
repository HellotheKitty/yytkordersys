<? 
session_start();
require("inc/conn.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    
    <title>名片工坊-收款明细</title>
    <link href="./css/CITICcss.css" rel="stylesheet" type="text/css">
<base target="_self" />
</head>

<body style="overflow-x:hidden;overflow-y:auto">

<form name="form1" method="post" action="" id="form1">

<div></div>

<div>

<table width="95%" border="0" cellspacing="0" cellpadding="0" style="padding:0px;">
  <tbody><tr>
    <td valign="top">   
<div style="margin-left:20px"><br><? $rs=mysql_query("select xm from ry_xs where xsbh='".$_GET["xsbh"]."'",$conn);echo mysql_result($rs,0,0);?>'s 应收款明细（<? echo "截止：",$_GET["d2"]?>）
	<table cellspacing="0" cellpadding="0" rules="all" border="1" id="gvOrder" style="border-width:0px;width:100%;border-collapse:collapse;border:1px solid #D3D3D3; border-bottom:0px;">
		<tbody><tr class="td_title" style="height:30px;">
			<th width="40%" align="center" scope="col">用户</th>
			<th width="12%" align="center" scope="col">订单金额</th>
			<th width="12%" align="center" scope="col">收款合计</th>
			<th width="12%" align="center" scope="col">应收款</th>
			<th width="15%" align="center" scope="col">最近变动</th>
			<th width="7%" align="center" scope="col">收款方式</th>
		</tr>
    <? $sr=0;
	$d1=$_GET["d1"];
	$d2=$_GET["d2"];
	$rs=mysql_query("select b.zh,b.depart,b.xm,b.mobile,sum(jf),sum(df),b.payfs,max(fssj) from user_zh,base_user b where user_zh.zh=b.zh and fssj<='$d2 23:59:59' and xsbh='".$_GET["xsbh"]."' group by b.zh order by sum(df)-sum(jf) desc",$conn);
	for ($i=0;$i<mysql_num_rows($rs);$i++) {
 ?>
        <tr onMouseOver="this.style.backgroundColor='#FFD'" onMouseOut="this.style.backgroundColor='white'">
			<td ><a href='javascript:window.open("Userzh_mx.php?userzh=<? echo base_encode(mysql_result($rs,$i,0));?>",window,"width=800,height=485,top=100,left=100,toolbar=no,menubar=no,scrollbars=no, resizable=yes,location=no, status=no");'><? echo mysql_result($rs,$i,0),"-",mysql_result($rs,$i,"depart"),"[",mysql_result($rs,$i,"xm"),mysql_result($rs,$i,"mobile"),"]";?></a></td>
            <td align="right" class="td_content" ><? echo mysql_result($rs,$i,5);?></td>
            <td align="right" class="td_content" ><? echo mysql_result($rs,$i,4);?></td>
            <td class="td_content" align="right" title="" ><? echo mysql_result($rs,$i,5)-mysql_result($rs,$i,4)>0?"<font color=red>".(mysql_result($rs,$i,5)-mysql_result($rs,$i,4))."</font>":mysql_result($rs,$i,5)-mysql_result($rs,$i,4);?></td>
            <td class="td_content" align="right" ><? echo mysql_result($rs,$i,7);?></td>
            <td class="td_content" align="right" ><? echo mysql_result($rs,$i,6);?></td>
		</tr>
        <? $sr+=mysql_result($rs,$i,5)-mysql_result($rs,$i,4)>0?mysql_result($rs,$i,5)-mysql_result($rs,$i,4):0;
		} ?>
		 <tr>
			<td align="center" class="td_content" ><strong>合计</strong></td>
            <td align="right" class="td_content" >&nbsp;</td>
            <td align="right" class="td_content" >&nbsp;</td>
            <td class="td_content" align="right" ><? echo $sr;?></td>
            <td class="td_content" align="right" >&nbsp;</td>
            <td class="td_content" align="right" >&nbsp;</td>
		</tr>
	</tbody></table>
   
<br><br>
</div>

 </td>
  </tr>
</tbody></table>
    
</div>

</div>
<p>&nbsp;</p>
</form>

</body></html>
