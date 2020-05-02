<? header("P3P:CP=CAO PSA OUR");
session_start();
require("../inc/conn.php");
if ($_GET["uu"]<>"") {
	if ($_GET["cks"]==md5("hzyk".$_GET["uu"]."winner")) {  //验证通过
			$_SESSION["OK"]="OK";
			$_SESSION["SSUSER"]=$_GET["uu"];
			$_SESSION["USERBH"]=$_GET["uu"];
			if ($_GET["lx"]=="sd") $_SESSION["FBSD"]=1; else $_SESSION["FBSD"]=0;
	}
}
if ($_GET["xsbh"]<>"") {$_SESSION["USERBH"]=$_GET["xsbh"];}
if ($_SESSION["OK"]<>"OK") { 
echo "<script language=JavaScript>{window.location.href='error.php';}</script>";
exit; }?>

<? 

$rs1=mysql_query("select xsbh,xm from yikab.ry_xs order by ssdq,xsbh",$conn);
if ($_GET["fddh"]<>"") $fddh=$_GET["fddh"]; else $fddh="";

if ($_SESSION["FBSD"]==1) //生产
	$rs=mysql_query("select order_mainqt.*,count(order_mxqt.id) mxsl,group_concat(order_mxqt.cpms) cpms,xs.xm from yikab.ry_xs xs,order_mainqt left join order_mxqt on order_mainqt.ddh=order_mxqt.ddh where  order_mainqt.xsbh=xs.xsbh and (order_mainqt.ddh like '%{$fddh}%' or order_mainqt.khmc like '%{$fddh}%') and (state='待生产' or state='进入生产' or state='订单完成' or state='退回') and (scjd like '".$_SESSION["SSUSER"]."' or scjd='' or scjd is null) group by order_mainqt.ddh order by instr('待生产,进入生产,订单完成,退回',state),sdate desc",$conn);
else 
	$rs=mysql_query("select order_mainqt.*,count(order_mxqt.id) mxsl,group_concat(order_mxqt.cpms) cpms,xs.xm from yikab.ry_xs xs,order_mainqt left join order_mxqt on order_mainqt.ddh=order_mxqt.ddh where order_mainqt.xsbh=xs.xsbh and (order_mainqt.ddh like '%{$fddh}%' or order_mainqt.khmc like '%{$fddh}%') and xs.xsbh='".$_SESSION["USERBH"]."' group by order_mainqt.ddh order by ddate desc",$conn);

															
//分页
if ($tj<>"" or $fddh<>"") {$page_num=mysql_num_rows($rs)+1;} else {$page_num=15;}     //每页行数
$page_no=$_GET["pno"];     //当前页
if ($page_no=="") {$page_no=1;}
$page_f=$page_num*($page_no -1);   //开始行
$page_e=$page_f+$page_num;			//结束行
if($page_e>mysql_num_rows($rs)) {$page_e=mysql_num_rows($rs);}
$page_t=ceil(mysql_num_rows($rs) / $page_num);  //总页数
//分页
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    
    <title>名片工坊-业务管理</title>
    <!--/*<link href="mycss.css" rel="stylesheet" type="text/css">*/-->
    
</head>
<script language="JavaScript">
<!--
function suredo(src,q)
    {
      var ret;
      ret = confirm(q);
      if(ret!=false) window.location=src;
    }
//-->
</script>    

<body style="font-size:12px">
<form name="form1" method="post" action="" id="form1">
<? if ($_SESSION["FBSD"]!=1) {?>
选择销售：
<select name="skh" <? if ($_SESSION["USERBH"]=="qd0000") {?>onchange="javascript:window.location.href='MYOrderShowqt.php?xsbh='+document.form1.skh.options[document.form1.skh.selectedIndex].value;"<? }?>>
<? for ($i=0;$i<mysql_num_rows($rs1);$i++) {?>
  <option value="<? echo mysql_result($rs1,$i,0);?>" <? if (mysql_result($rs1,$i,0)==$_SESSION["USERBH"]) echo "selected";?> ><? echo mysql_result($rs1,$i,0),"--",mysql_result($rs1,$i,1);?></option>
<? } 
?>
</select> <a href="#" class="nav" onClick="javascript:window.open('jcsj/YSXMqt_dj.php?lx=1&xsbh='+encodeURI(document.form1.skh.options[document.form1.skh.selectedIndex].value), 'HT_dhdj', 'toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=yes,copyhistory=yes,width=600,height=360,left=300,top=100')">新建订单</a>（只有经过设定的<a href='MYOrderqtmxs.php?lx=show'>固定产品</a>[联络孙家佳加入]可以直接新建订单，其他产品请做询价单）
<? } else echo $_SESSION["SSUSER"];?>
<table width="100%" border="0" cellspacing="0" cellpadding="0" style="padding:0px;">
  <tbody><tr>
    <td valign="top">
<div style="padding:15px 62px 22px 55px; color:#58595B">
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
			<th  align="center" scope="col">销售编号</th>
            <th  align="center" scope="col">订单编号</th>
			<th  align="center" scope="col">客户名称</th>
			<th  align="center" scope="col">订购时间</th>
            <th  align="center" scope="col">订单金额</th>
            <th  align="center" scope="col">配送要求</th>
            <th  align="center" scope="col">生产地</th>
            <th  align="center" scope="col">订单状态</th>
            <th  align="center" scope="col">操作</th>
		</tr>
        <? for($i=$page_f;$i<$page_e;$i++){  ?>
        <tr class="td_title" style="height:30px;" onMouseOver="this.style.backgroundColor='#FFD'" onMouseOut="this.style.backgroundColor='white'">
			<td class="td_content" align="center" ><? echo mysql_result($rs,$i,"xsbh"),'-',mysql_result($rs,$i,"xm");?></td>
            <td align="center" class="td_content" ><? echo mysql_result($rs,$i,"ddh");?><br>
			<? echo "[明细：",mysql_result($rs,$i,"mxsl"),"]";?>
               <a href="javascript:void(0)" onclick="javascript:window.open('jcsj/YSXMqt_show.php?ddh=<? echo mysql_result($rs,$i,"ddh");?>','OrderDetail','height=600px,width=920px,top=100px,left=200px,location=no,menubar=no,resizable=no,scrollbars=yes,status=no,titlebar=no,toolbar=no,directories=no');return false;"  class="a1">查看订单详情</a>
               </td>
            <td align="center" class="td_content" ><span class="td_content" ><? echo mysql_result($rs,$i,"khmc");?><br><font color="#FFCCCC"><? echo "[",mysql_result($rs,$i,"cpms"),"]";?></font></span></td>
            <td class="td_content" align="center" ><? echo mysql_result($rs,$i,"ddate");?></td>
            <td class="td_content" align="center" ><span style="color:Red;"><? echo mysql_result($rs,$i,"dje");?></span>元</td>
            <td align="center" class="td_content" ><? echo mysql_result($rs,$i,"psfs");?></td>
            <td align="center" class="td_content" ><? echo mysql_result($rs,$i,"scjd");?></td>
            <td class="td_content" align="center" ><? echo mysql_result($rs,$i,"state");?></td>
            <td class="td_content" align="center" >
			<? if (mysql_result($rs,$i,"state")=="新建订单") {
				if (mysql_result($rs,$i,"xjdid")==0)
				echo "<a href='#' class='nav' onClick='javascript:window.open(\"jcsj/YSXMqt_mxdjs.php?ddh=".mysql_result($rs,$i,"ddh")."\", \"HT_dhdj\", \"toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=yes,copyhistory=yes,width=650,height=410,left=300,top=100\")'>增加明细</a>&nbsp;&nbsp;";
				echo "<a href='#' onClick=\"".(mysql_result($rs,$i,"khmc")!=""?(mysql_result($rs,$i,"mxsl")>0?"javascript:suredo('jcsj/YSXMqt_del.php?BH2=".mysql_result($rs,$i,"ddh")."','提交生产后不能修改，确定提交生产?')":"javascript:alert('明细数量为0，不能提交，请增加明细。')"):"javascript:alert('客户名称没有输入，不能提交，请先选择快递。')")."\">提交生产</a>&nbsp;&nbsp;<a href='#' onClick=\"javascript:suredo('jcsj/YSXMqt_del.php?BH=".mysql_result($rs,$i,"ddh")."','确定删除?')\">删除</a>";
			}
			  if ($_SESSION["FBSD"]=="1" ) {
				  if (mysql_result($rs,$i,"state")=='待生产') {
						echo "<a href='jcsj/YSXMqt_del.php?did=".mysql_result($rs,$i,"id")."'>确定生产</a>&nbsp;&nbsp;&nbsp;&nbsp;";
						echo "<input type=button onclick='javascript:window.open(\"jcsj/YSXMqt_tuihui.php?didth=".mysql_result($rs,$i,"id")."\", \"HT_dhdj\", \"toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=yes,copyhistory=yes,width=650,height=340,left=300,top=100\");' value='退回'>";
				  } elseif (mysql_result($rs,$i,"state")!='退回') {
					  echo "<a href='#' class='nav' onClick='javascript:window.open(\"jcsj/YSXMqt_zx.php?ddh=".mysql_result($rs,$i,"ddh")."\", \"HT_dhdj\", \"toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=yes,copyhistory=yes,width=950,height=540,left=300,top=100\")'>生产配送情况</a>";
					  if (mysql_result($rs,$i,"sjpsfs")!='') echo "[",mysql_result($rs,$i,"sjpsfs"),"]";
				  }
				}
			?></td>
		</tr>
        <? }?>
	</tbody></table>
</div>
                  
按订单号或客户名称查找：<span style="width:40%;">
<input name="fddh" type="text" id="fddh" size="15" />
<input name="find" type="button" value="查找" onclick="javascript:window.location.href='?gp=<? echo $_GET["gp"]?>&fddh='+form1.fddh.value;" />
</span><br>
		
		  <div class="page1">


<DIV STYLE="width:87%; float:right;" align="right"><A <? if ($page_t>1 and $page_no>1) {echo "href=".$_SERVER["PHP_SELF"]."?pno=1&gp=".$_GET["gp"];} else {echo "disabled style='color:gray;'";};?>>首页</A>　<A <? if ($page_t>1 and $page_no>1) {echo "href=".$_SERVER["PHP_SELF"]."?pno=".($page_no-1)."&gp=".$_GET["gp"];} else {echo "disabled style='color:gray;'";};?>>上一页</A>　<A <? if ($page_t>1 and $page_no<$page_t) {echo "href=".$_SERVER["PHP_SELF"]."?pno=".($page_no+1)."&gp=".$_GET["gp"];} else {echo "disabled style='color:gray;'";};?>>下一页</A>　<A <? if ($page_t>1 and $page_no<>$page_t) {echo "href=".$_SERVER["PHP_SELF"]."?pno=".$page_t."&gp=".$_GET["gp"];} else {echo "disabled style='color:gray;'";};?>>尾页</A>　
    <INPUT name="pno" onKeyDown="" value="<? echo $page_no?>" size="3">
    <INPUT name="ZKPager1" type="button" class="menubutton" value="转到" onClick="javascript:window.location.href='<? echo $_SERVER["PHP_SELF"]?>?pno='+document.actForm.pno.value+'&gp=<? echo $_GET["gp"] ?>'">　
    第<? echo $page_no."/".$page_t?>页&nbsp;&nbsp;&nbsp;&nbsp;</DIV>



		  </div>
		  </div>
	  
 </td>
  </tr>
</tbody></table>
    
</form>

</body></html>