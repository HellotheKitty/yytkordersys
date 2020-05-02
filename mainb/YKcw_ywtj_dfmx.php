<? 
session_start();
require("inc/conn.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    
    <title>名片工坊-销售明细</title>
    <link href="./css/CITICcss.css" rel="stylesheet" type="text/css">
<base target="_self" />
</head>

<body style="overflow-x:hidden;overflow-y:auto">
<Div style="width:600px; margin:0px auto;"><Div id=Calendar scrolling="no" style="border:0px solid #EEEEEE ;position: absolute; margin-top:150px; margin-left: 5px; width: 150; height: 137; z-index: 200; filter :\'progid:DXImageTransform.Microsoft.Shadow(direction=135,color=#AAAAAA,strength=4)\' ;display: none"></Div></Div>
<form name="form1" method="post" action="" id="form1">

<div></div>

<div class="main_box">

<table width="90%" border="0" cellspacing="0" cellpadding="0" style="padding:0px;">
  <tbody><tr>
    <td valign="top">   
<div style="margin-left:20px"><br>销售明细（<? echo $_GET["d1"],"到",$_GET["d2"]?>）
	<table cellspacing="0" cellpadding="0" rules="all" border="1" id="gvOrder" style="border-width:0px;width:100%;border-collapse:collapse;border:1px solid #D3D3D3; border-bottom:0px;">
		<tbody><tr class="td_title" style="height:30px;">
			<th width="30%" align="center" scope="col">用户</th>
			<th width="14%" align="center" scope="col">订单金额</th>
			<th width="25%" align="center" scope="col">摘要</th>
			<th width="17%" align="center" scope="col">时间</th>
			<th width="14%" align="center" scope="col">备注</th>
		</tr>
    <? $sr=0;
	
	$d1=$_GET["d1"];
	$d2=$_GET["d2"];
	$rs=mysql_query("select user_zh.*,depart from user_zh,base_user b,ry_xs where b.xsbh=ry_xs.xsbh and user_zh.zh=b.zh and fssj>='$d1 00:00:00' and fssj<'$d2 00:00:00' and ssdq='".$_GET["dq"]."' and df<>0",$conn);
	for ($i=0;$i<mysql_num_rows($rs);$i++) {
 ?>
        <tr>
			<td align="left" class="td_content" ><? echo mysql_result($rs,$i,1),"-",mysql_result($rs,$i,"depart");?></td>
            <td align="right" class="td_content" ><? echo mysql_result($rs,$i,3);?></td>
            <td align="right" class="td_content" ><? echo mysql_result($rs,$i,4);?></td>
            <td class="td_content" align="right" ><? echo mysql_result($rs,$i,5);?></td>
            <td class="td_content" align="right" ><? echo mysql_result($rs,$i,7);?></td>
		</tr>
        <? $sr+=mysql_result($rs,$i,3);
		} ?>
		 <tr>
			<td align="center" class="td_content" ><strong>合计</strong></td>
            <td align="right" class="td_content" ><? echo $sr;?></td>
            <td align="right" class="td_content" ></td>
            <td class="td_content" align="right" ></td>
            <td class="td_content" align="right" ></td>
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
